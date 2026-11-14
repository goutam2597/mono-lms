<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Setting;
use App\Models\SmsGatewayDevice;
use App\Models\SmsInboundMessage;
use App\Models\SmsMessage;
use App\Services\OtpService;
use App\Services\PhoneNumberService;
use App\Services\SmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmsSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::set('sms_enabled', '1');
        Setting::set('sms_otp_enabled', '1');
        Setting::set('sms_provider', 'gateway');
    }

    public function test_phone_number_service_normalizes_to_e164(): void
    {
        $service = app(PhoneNumberService::class);

        // International format
        $this->assertEquals('+8801700000000', $service->normalize('+8801700000000'));
        $this->assertEquals('+8801700000000', $service->normalize('+880 1700-000000'));

        // Leading double zero 00880... converted to +880...
        $this->assertEquals('+8801700000000', $service->normalize('008801700000000'));

        // Bare local input with no country context is rejected (the UI
        // x-phone-input always submits the dial-code-prefixed E.164 value)
        $this->assertNull($service->normalize('01700000000'));

        // Masking
        $masked = $service->mask('+8801700000000');
        $this->assertStringStartsWith('+880', $masked);
        $this->assertStringEndsWith('0000', $masked);
        $this->assertStringContainsString('•', $masked);
    }

    public function test_phone_number_service_matches_enabled_country_dial_codes(): void
    {
        PhoneNumberService::clearCache();
        Country::create(['name' => 'Bangladesh', 'code' => 'BD', 'phone_code' => '+880', 'is_active' => true]);
        Country::create(['name' => 'India', 'code' => 'IN', 'phone_code' => '+91', 'is_active' => true]);
        Country::create(['name' => 'Disabled Land', 'code' => 'DL', 'phone_code' => '+999', 'is_active' => false]);

        $service = app(PhoneNumberService::class);

        // E.164 prefixes resolve to the enabled country
        $bd = $service->parse('+8801700000000');
        $this->assertTrue($bd->isValid());
        $this->assertEquals('+8801700000000', $bd->e164);
        $this->assertEquals('BD', $bd->country?->code);
        $this->assertEquals('1700000000', $bd->nationalNumber);

        // Bare national input with a country hint uses that country
        $in = $service->parse('09800000000', 'IN');
        $this->assertTrue($in->isValid());
        $this->assertEquals('+919800000000', $in->e164);
        $this->assertEquals('IN', $in->country?->code);

        // Bare national input without any country context is rejected
        $bare = $service->parse('01700000000');
        $this->assertFalse($bare->isValid());

        // An E.164 whose dial code is not enabled is still accepted country-less
        $unknown = $service->parse('+966500000000');
        $this->assertTrue($unknown->isValid());
        $this->assertEquals('+966500000000', $unknown->e164);
        $this->assertNull($unknown->country);

        // An explicitly named disabled country fails loudly
        $disabled = $service->parse('+999500000000', 'DL');
        $this->assertFalse($disabled->isValid());

        // A pasted dial code as part of a country-hinted bare number is stripped once
        $pasted = $service->parse('8801700000000', 'BD');
        $this->assertTrue($pasted->isValid());
        $this->assertEquals('+8801700000000', $pasted->e164);
    }

    public function test_phone_attributes_for_persists_canonical_columns(): void
    {
        PhoneNumberService::clearCache();
        Country::create(['name' => 'Bangladesh', 'code' => 'BD', 'phone_code' => '+880', 'is_active' => true]);

        $service = app(PhoneNumberService::class);

        $attributes = $service->attributesFor('+880 1700-998877');
        $this->assertEquals('+880 1700-998877', $attributes['phone']);
        $this->assertEquals('+8801700998877', $attributes['phone_e164']);
        $this->assertEquals('BD', $attributes['phone_country']);

        // A blank value clears all three columns
        $blank = $service->attributesFor(null);
        $this->assertNull($blank['phone']);
        $this->assertNull($blank['phone_e164']);
        $this->assertNull($blank['phone_country']);
    }

    public function test_sms_service_routes_to_gateway_when_selected(): void
    {
        [$device, $token] = SmsGatewayDevice::register('Test Gateway Phone');
        $device->update(['is_active' => true, 'is_enabled' => true]);

        $service = app(SmsService::class);
        $this->assertTrue($service->enabled());
        $this->assertTrue($service->configured());
        $this->assertEquals('gateway', $service->providerName());

        $sent = $service->send('+8801712345678', 'Test LMS Code 123456', 'otp');
        $this->assertTrue($sent);

        $this->assertDatabaseHas('sms_messages', [
            'to_phone' => '+8801712345678',
            'body' => 'Test LMS Code 123456',
            'status' => 'pending',
            'kind' => 'otp',
        ]);
    }

    public function test_gateway_device_api_requires_valid_bearer_token(): void
    {
        $response = $this->getJson('/api/v1/sms-gateway/status');
        $response->assertStatus(401);
        $response->assertJson(['success' => false]);

        $badResponse = $this->withHeader('Authorization', 'Bearer invalid_token')
            ->getJson('/api/v1/sms-gateway/status');
        $badResponse->assertStatus(401);
    }

    public function test_gateway_device_heartbeat_and_atomic_claim_flow(): void
    {
        [$device, $token] = SmsGatewayDevice::register('Samsung A14 Gateway');
        $device->update(['is_active' => true, 'is_enabled' => true]);

        // 1. Heartbeat
        $heartbeat = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/sms-gateway/heartbeat', [
                'batteryLevel' => 88,
                'carrier' => 'Grameenphone',
                'simNumber' => '+8801700112233',
                'simState' => 'ready',
                'appVersion' => '1.0.0',
            ]);

        $heartbeat->assertOk();
        $this->assertDatabaseHas('sms_gateway_devices', [
            'id' => $device->id,
            'battery_level' => 88,
            'carrier' => 'Grameenphone',
            'sim_number' => '+8801700112233',
        ]);

        // 2. Queue an SMS
        $msg = SmsMessage::create([
            'sms_gateway_device_id' => $device->id,
            'to_phone' => '+8801700000000',
            'body' => 'Hello from Mono LMS',
            'kind' => 'notice',
            'status' => 'pending',
        ]);

        // 3. Device claims pending messages
        $claim = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/sms-gateway/sms/claim', ['limit' => 5]);

        $claim->assertOk();
        $messages = $claim->json('data.messages');
        $this->assertCount(1, $messages);
        $this->assertEquals($msg->public_id, $messages[0]['id']);

        // Check DB row flipped to processing
        $msg->refresh();
        $this->assertEquals('processing', $msg->status);
        $this->assertNotNull($msg->claimed_at);
        $this->assertEquals(1, $msg->attempts);

        // 4. Report outcome as sent
        $report = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/v1/sms-gateway/sms/{$msg->public_id}/report", [
                'status' => 'sent',
            ]);

        $report->assertOk();
        $msg->refresh();
        $this->assertEquals('sent', $msg->status);
        $this->assertNotNull($msg->sent_at);
        $this->assertNull($msg->claimed_at);

        // Check device sent count incremented
        $device->refresh();
        $this->assertEquals(1, $device->sent_count);
    }

    public function test_gateway_device_inbound_sms_is_forwarded_and_deduplicated(): void
    {
        [$device, $token] = SmsGatewayDevice::register('Incoming Receiver Phone');

        $payload = [
            'messages' => [
                [
                    'uid' => 'msg_uid_1001',
                    'from' => '+8801899887766',
                    'body' => 'Inbound response from student',
                    'simNumber' => '+8801700112233',
                    'receivedAt' => now()->toIso8601String(),
                ],
            ],
        ];

        $res1 = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/sms-gateway/sms/incoming', $payload);

        $res1->assertOk();
        $this->assertEquals(1, $res1->json('data.received'));

        $this->assertDatabaseHas('sms_inbound_messages', [
            'sms_gateway_device_id' => $device->id,
            'message_uid' => 'msg_uid_1001',
            'from_phone' => '+8801899887766',
            'body' => 'Inbound response from student',
        ]);

        // Duplicate incoming message is safely ignored
        $res2 = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/sms-gateway/sms/incoming', $payload);
        $res2->assertOk();
        $this->assertEquals(0, $res2->json('data.received'));
        $this->assertEquals(1, SmsInboundMessage::count());
    }

    public function test_otp_service_issue_verify_and_brute_force_protection(): void
    {
        $otpService = app(OtpService::class);

        // Issue SMS OTP
        [$otpModel, $code] = $otpService->issueFor(OtpService::CHANNEL_SMS, '+8801700998877', OtpService::PURPOSE_SIGNUP);

        $this->assertNotNull($otpModel);
        $this->assertEquals(6, strlen($code));
        $this->assertEquals(OtpService::CHANNEL_SMS, $otpModel->channel);
        $this->assertEquals('+8801700998877', $otpModel->phone);
        $this->assertNotEquals($code, $otpModel->otp); // Hashed, not plaintext

        // Wrong attempt increments attempt counter
        $failVerify = $otpService->verifyFor(OtpService::CHANNEL_SMS, '+8801700998877', OtpService::PURPOSE_SIGNUP, '000000');
        $this->assertNull($failVerify);
        $this->assertEquals(1, $otpModel->fresh()->attempts);

        // Correct code successfully verifies
        $successVerify = $otpService->verifyFor(OtpService::CHANNEL_SMS, '+8801700998877', OtpService::PURPOSE_SIGNUP, $code);
        $this->assertNotNull($successVerify);
        $this->assertTrue($successVerify->verified);
        $this->assertNotNull($successVerify->reset_token);

        // Second verification with same code fails (already verified)
        $secondVerify = $otpService->verifyFor(OtpService::CHANNEL_SMS, '+8801700998877', OtpService::PURPOSE_SIGNUP, $code);
        $this->assertNull($secondVerify);
    }
}
