<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * A registered Android gateway phone.
 * Authentication uses SHA-256 token hashing; plaintext tokens are shown only once at creation.
 */
class SmsGatewayDevice extends Model
{
    protected $fillable = [
        'name',
        'device_id',
        'token_hash',
        'fcm_token',
        'sim_number',
        'sim_state',
        'carrier',
        'app_version',
        'is_active',
        'is_enabled',
        'last_seen_at',
        'battery_level',
        'sent_count',
        'failed_count',
        'last_sms_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_enabled' => 'boolean',
        'last_seen_at' => 'datetime',
        'last_sms_at' => 'datetime',
        'battery_level' => 'integer',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(SmsMessage::class);
    }

    public function inboundMessages(): HasMany
    {
        return $this->hasMany(SmsInboundMessage::class);
    }

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Create a device and return [device, plaintext token] — shown once.
     *
     * @return array{0: SmsGatewayDevice, 1: string}
     */
    public static function register(string $name, array $attributes = []): array
    {
        $token = 'smsgw_'.Str::random(48);

        $device = static::create([
            'name' => $name,
            'token_hash' => self::hashToken($token),
            ...$attributes,
        ]);

        return [$device, $token];
    }

    /**
     * Considered online when seen within the given window.
     */
    public function isOnline(int $minutes = 3): bool
    {
        return $this->is_enabled
            && $this->last_seen_at !== null
            && $this->last_seen_at->gte(now()->subMinutes($minutes));
    }

    public function pendingCount(): int
    {
        return SmsMessage::where('sms_gateway_device_id', $this->id)
            ->whereIn('status', [SmsMessage::STATUS_PENDING, SmsMessage::STATUS_PROCESSING])
            ->count();
    }

    public function failedCount(): int
    {
        return SmsMessage::where('sms_gateway_device_id', $this->id)
            ->where('status', SmsMessage::STATUS_FAILED)
            ->count();
    }

    /**
     * The device that should receive outgoing SMS, if any.
     */
    public static function active(): ?self
    {
        return static::where('is_active', true)
            ->where('is_enabled', true)
            ->orderByDesc('id')
            ->first();
    }
}
