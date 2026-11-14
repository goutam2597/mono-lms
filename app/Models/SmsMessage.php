<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Outbound SMS message queue for self-hosted Android gateway and log.
 */
class SmsMessage extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    /** A device that never reports back must not hold a message forever. */
    public const CLAIM_TIMEOUT_MINUTES = 5;

    protected $fillable = [
        'public_id',
        'sms_gateway_device_id',
        'to_phone',
        'body',
        'kind',
        'provider',
        'status',
        'attempts',
        'max_attempts',
        'available_at',
        'claimed_at',
        'sent_at',
        'error',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'available_at' => 'datetime',
        'claimed_at' => 'datetime',
        'sent_at' => 'datetime',
        'attempts' => 'integer',
        'max_attempts' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $message): void {
            $message->public_id = $message->public_id ?: (string) Str::uuid();
            $message->available_at = $message->available_at ?: now();
        });
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(SmsGatewayDevice::class, 'sms_gateway_device_id');
    }

    /**
     * Exponential backoff between retries: immediate, +2 min, +10 min, then give up.
     */
    public function nextRetryAt(): Carbon
    {
        return match ($this->attempts) {
            0 => now(),
            1 => now()->addMinutes(2),
            default => now()->addMinutes(10),
        };
    }

    /**
     * How long after claiming before a message is handed back out.
     */
    public function isStale(): bool
    {
        return $this->status === self::STATUS_PROCESSING
            && $this->claimed_at !== null
            && $this->claimed_at->lte(now()->subMinutes(self::CLAIM_TIMEOUT_MINUTES));
    }

    /**
     * Format payload for the Android companion app.
     *
     * @return array<string, mixed>
     */
    public function toDevicePayload(): array
    {
        return [
            'id' => $this->public_id,
            'to' => $this->to_phone,
            'body' => $this->body,
            'kind' => $this->kind,
            'attempts' => $this->attempts,
            'maxAttempts' => $this->max_attempts,
        ];
    }
}
