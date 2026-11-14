<?php

namespace App\Services\Sms;

use App\Models\SmsGatewayDevice;
use App\Models\SmsMessage;
use App\Services\FirebaseNotificationService;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

/**
 * Self-hosted transport: queues the message in the database for an Android gateway device
 * and dispatches a high-priority FCM wake signal.
 *
 * FCM only signals that an SMS is waiting (contains no recipient and no body) for security.
 */
class GatewaySmsProvider implements SmsProvider
{
    public function __construct(private FirebaseNotificationService $fcm) {}

    public function name(): string
    {
        return 'gateway';
    }

    public function configured(): bool
    {
        return SmsGatewayDevice::active() !== null;
    }

    public function send(string $to, string $body, string $kind = 'other'): array
    {
        $to = SmsService::normalizePhone($to);
        $device = SmsGatewayDevice::active();

        if ($to === null || trim($body) === '') {
            return ['ok' => false, 'error' => 'Invalid recipient or empty message.'];
        }

        if ($device === null) {
            return ['ok' => false, 'error' => 'No active SMS gateway device configured.'];
        }

        $message = SmsMessage::create([
            'sms_gateway_device_id' => $device->id,
            'to_phone' => $to,
            'body' => $body,
            'kind' => $kind,
            'provider' => 'gateway',
            'status' => SmsMessage::STATUS_PENDING,
        ]);

        $this->wake($device, $message);

        return ['ok' => true, 'id' => (string) $message->public_id];
    }

    /**
     * Wake the device via FCM data-only push without leaking message content.
     */
    public function wake(SmsGatewayDevice $device, ?SmsMessage $message = null): void
    {
        if (blank($device->fcm_token) || ! $this->fcm->isAvailable()) {
            return;
        }

        $result = $this->fcm->sendDataMessage($device->fcm_token, [
            'type' => 'sms_queue_available',
            'message_id' => (string) ($message?->public_id ?? ''),
        ]);

        if ($result === 'unregistered') {
            Log::warning('SMS gateway device FCM token unregistered; clearing token.', ['device_id' => $device->id]);
            $device->forceFill(['fcm_token' => null])->save();
        }
    }
}
