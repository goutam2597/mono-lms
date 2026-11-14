<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SmsGatewayDevice;
use App\Models\SmsInboundMessage;
use App\Models\SmsMessage;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Device-facing API for the Android SMS gateway companion app.
 */
class SmsGatewayController extends Controller
{
    private const CLAIM_BATCH = 10;

    public function status(Request $request): JsonResponse
    {
        $device = $this->device($request);

        return response()->json([
            'success' => true,
            'data' => [
                'device' => [
                    'id' => $device->id,
                    'name' => $device->name,
                    'isActive' => $device->is_active,
                ],
                'pending' => $device->pendingCount(),
                'failed' => $device->failedCount(),
                'sent' => $device->sent_count,
                'serverTime' => now()->toIso8601String(),
            ],
        ]);
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'batteryLevel' => ['nullable', 'integer', 'min:0', 'max:100'],
            'simState' => ['nullable', 'string', 'max:20'],
            'simNumber' => ['nullable', 'string', 'max:40'],
            'carrier' => ['nullable', 'string', 'max:80'],
            'appVersion' => ['nullable', 'string', 'max:40'],
            'fcmToken' => ['nullable', 'string', 'max:512'],
        ]);

        $device = $this->device($request);

        $device->forceFill(array_filter([
            'battery_level' => $data['batteryLevel'] ?? null,
            'sim_state' => $data['simState'] ?? null,
            'sim_number' => SmsService::normalizePhone($data['simNumber'] ?? null),
            'carrier' => $data['carrier'] ?? null,
            'app_version' => $data['appVersion'] ?? null,
            'fcm_token' => $data['fcmToken'] ?? null,
            'last_seen_at' => now(),
        ], fn ($value) => $value !== null))->save();

        return response()->json(['success' => true, 'data' => ['serverTime' => now()->toIso8601String()]]);
    }

    /**
     * Atomically claim pending messages for this device.
     */
    public function claim(Request $request): JsonResponse
    {
        $data = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:25'],
        ]);

        $device = $this->device($request);
        $limit = (int) ($data['limit'] ?? self::CLAIM_BATCH);

        $claimed = DB::transaction(function () use ($device, $limit): array {
            // Reclaim stale messages that were claimed but never reported back
            SmsMessage::where('sms_gateway_device_id', $device->id)
                ->where('status', SmsMessage::STATUS_PROCESSING)
                ->where('claimed_at', '<=', now()->subMinutes(SmsMessage::CLAIM_TIMEOUT_MINUTES))
                ->update([
                    'status' => SmsMessage::STATUS_PENDING,
                    'claimed_at' => null,
                ]);

            $rows = SmsMessage::where('sms_gateway_device_id', $device->id)
                ->where('status', SmsMessage::STATUS_PENDING)
                ->where('available_at', '<=', now())
                ->where('attempts', '<', DB::raw('max_attempts'))
                ->orderBy('id')
                ->limit($limit)
                ->lockForUpdate()
                ->get();

            $ids = $rows->pluck('id');

            if ($ids->isNotEmpty()) {
                SmsMessage::whereIn('id', $ids)->update([
                    'status' => SmsMessage::STATUS_PROCESSING,
                    'claimed_at' => now(),
                    'attempts' => DB::raw('attempts + 1'),
                    'updated_at' => now(),
                ]);
            }

            return $rows->map(fn (SmsMessage $message) => [
                ...$message->toDevicePayload(),
                'attempts' => $message->attempts + 1,
            ])->values()->all();
        });

        return response()->json(['success' => true, 'data' => ['messages' => $claimed]]);
    }

    /**
     * Report delivery outcome from the phone.
     */
    public function report(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:sent,failed'],
            'error' => ['nullable', 'string', 'max:1000'],
        ]);

        $device = $this->device($request);

        $message = SmsMessage::where('public_id', $id)
            ->where('sms_gateway_device_id', $device->id)
            ->first();

        if ($message === null) {
            return $this->fail('Unknown message id.', 'UNKNOWN_MESSAGE', 404);
        }

        if ($message->status !== SmsMessage::STATUS_PROCESSING) {
            return $this->fail('Message was already reported.', 'ALREADY_REPORTED', 409);
        }

        if ($data['status'] === 'sent') {
            $message->forceFill([
                'status' => SmsMessage::STATUS_SENT,
                'sent_at' => now(),
                'claimed_at' => null,
                'error' => null,
            ])->save();

            $device->increment('sent_count');
            $device->forceFill(['last_sms_at' => now()])->save();
        } else {
            $exhausted = $message->attempts >= $message->max_attempts;

            $message->forceFill([
                'status' => $exhausted ? SmsMessage::STATUS_FAILED : SmsMessage::STATUS_PENDING,
                'claimed_at' => null,
                'available_at' => $exhausted ? $message->available_at : $message->nextRetryAt(),
                'error' => $data['error'] ?? 'Device reported failure.',
            ])->save();

            $device->increment('failed_count');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $message->public_id,
                'status' => $message->status,
            ],
        ]);
    }

    /**
     * Forward inbound SMS received by the Android device SIM.
     */
    public function incoming(Request $request): JsonResponse
    {
        $data = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:50'],
            'messages.*.uid' => ['required', 'string', 'max:120'],
            'messages.*.from' => ['required', 'string', 'max:40'],
            'messages.*.body' => ['required', 'string', 'max:4000'],
            'messages.*.receivedAt' => ['nullable', 'string', 'max:40'],
            'messages.*.simNumber' => ['nullable', 'string', 'max:40'],
            'messages.*.subscriptionId' => ['nullable', 'string', 'max:40'],
        ]);

        $device = $this->device($request);
        $inserted = 0;

        foreach ($data['messages'] as $item) {
            $from = SmsService::normalizePhone($item['from']) ?: $item['from'];
            $uid = (string) $item['uid'];

            $exists = SmsInboundMessage::where('sms_gateway_device_id', $device->id)
                ->where('message_uid', $uid)
                ->exists();

            if ($exists) {
                continue;
            }

            SmsInboundMessage::create([
                'sms_gateway_device_id' => $device->id,
                'message_uid' => $uid,
                'from_phone' => $from,
                'body' => $item['body'],
                'sim_number' => SmsService::normalizePhone($item['simNumber'] ?? null),
                'subscription_id' => $item['subscriptionId'] ?? null,
                'received_at' => ! empty($item['receivedAt']) ? now()->parse($item['receivedAt']) : now(),
            ]);

            $inserted++;
        }

        return response()->json(['success' => true, 'data' => ['received' => $inserted]]);
    }

    private function device(Request $request): SmsGatewayDevice
    {
        return $request->attributes->get('sms_gateway_device');
    }

    private function fail(string $message, string $code, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => ['message' => $message, 'code' => $code],
        ], $status);
    }
}
