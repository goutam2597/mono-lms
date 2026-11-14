<?php

namespace App\Services\Sms;

use App\Models\SmsGatewayDevice;
use App\Models\SmsMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Queue recovery hygiene for the self-hosted Android SMS gateway.
 */
class SmsGatewayService
{
    public function __construct(private GatewaySmsProvider $provider) {}

    /**
     * Reclaim timed-out claims and re-nudge pending messages.
     *
     * @return array{reclaimed: int, nudged: int}
     */
    public function recover(): array
    {
        $device = SmsGatewayDevice::active();

        if ($device === null) {
            return ['reclaimed' => 0, 'nudged' => 0];
        }

        // Reclaim messages stuck in processing past the timeout
        $reclaimed = SmsMessage::where('sms_gateway_device_id', $device->id)
            ->where('status', SmsMessage::STATUS_PROCESSING)
            ->where('claimed_at', '<=', now()->subMinutes(SmsMessage::CLAIM_TIMEOUT_MINUTES))
            ->update([
                'status' => SmsMessage::STATUS_PENDING,
                'claimed_at' => null,
                'available_at' => now(),
                'updated_at' => now(),
            ]);

        // Park messages that exceeded maximum attempts
        SmsMessage::where('sms_gateway_device_id', $device->id)
            ->where('status', SmsMessage::STATUS_PENDING)
            ->where('available_at', '<=', now())
            ->where('attempts', '>=', DB::raw('max_attempts'))
            ->update([
                'status' => SmsMessage::STATUS_FAILED,
                'error' => 'Maximum delivery attempts reached.',
                'updated_at' => now(),
            ]);

        $pending = SmsMessage::where('sms_gateway_device_id', $device->id)
            ->where('status', SmsMessage::STATUS_PENDING)
            ->where('available_at', '<=', now())
            ->orderBy('id')
            ->limit(20)
            ->get();

        foreach ($pending as $message) {
            $this->provider->wake($device, $message);
        }

        if ($reclaimed > 0 || $pending->isNotEmpty()) {
            Log::info('SMS gateway recovery pass completed', [
                'reclaimed' => $reclaimed,
                'nudged' => $pending->count(),
            ]);
        }

        return ['reclaimed' => $reclaimed, 'nudged' => $pending->count()];
    }
}
