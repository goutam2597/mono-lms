<?php

namespace App\Services;

use App\Models\Setting;
use App\Services\Sms\GatewaySmsProvider;
use App\Services\Sms\SmsProvider;
use App\Services\Sms\TwilioSmsProvider;
use Illuminate\Support\Facades\Log;

/**
 * Master SMS entry point: resolves active transport (Twilio vs Gateway)
 * and normalizes recipient phone numbers.
 */
class SmsService
{
    public const SETTING_ENABLED = 'sms_enabled';

    public const SETTING_OTP_ENABLED = 'sms_otp_enabled';

    public const SETTING_NOTIFICATION_ENABLED = 'sms_notification_enabled';

    public const SETTING_SID = 'twilio_sid';

    public const SETTING_TOKEN = 'twilio_token';

    public const SETTING_FROM = 'twilio_from';

    public const SETTING_PROVIDER = 'sms_provider';

    public const PROVIDER_TWILIO = 'twilio';

    public const PROVIDER_GATEWAY = 'gateway';

    /** Master switch for all SMS. */
    public function enabled(): bool
    {
        return Setting::enabled(self::SETTING_ENABLED);
    }

    /** Whether SMS may carry OTP codes. */
    public function otpEnabled(): bool
    {
        return $this->enabled() && Setting::enabled(self::SETTING_OTP_ENABLED);
    }

    /** Whether SMS may carry notifications (course alerts, enrollments). */
    public function notificationEnabled(): bool
    {
        return $this->enabled() && Setting::enabled(self::SETTING_NOTIFICATION_ENABLED);
    }

    /** Which transport the administrator selected (twilio or gateway). */
    public function providerName(): string
    {
        $value = (string) Setting::get(self::SETTING_PROVIDER, self::PROVIDER_TWILIO);

        return $value === self::PROVIDER_GATEWAY ? self::PROVIDER_GATEWAY : self::PROVIDER_TWILIO;
    }

    /** The active transport instance. */
    public function provider(): SmsProvider
    {
        if ($this->providerName() === self::PROVIDER_GATEWAY) {
            return app(GatewaySmsProvider::class);
        }

        return app(TwilioSmsProvider::class);
    }

    /** Whether the selected transport is configured and ready. */
    public function configured(): bool
    {
        return $this->provider()->configured();
    }

    /**
     * Send an SMS. Returns boolean without throwing so callers are never blocked.
     */
    public function send(string $to, string $body, string $kind = 'other'): bool
    {
        if (! $this->enabled() || ! $this->configured()) {
            return false;
        }

        $to = self::normalizePhone($to);

        if ($to === null || trim($body) === '') {
            return false;
        }

        $result = $this->provider()->send($to, $body, $kind);

        if (! ($result['ok'] ?? false)) {
            Log::warning('SMS send failed to '.$to.': '.($result['error'] ?? 'unknown error'), [
                'provider' => $this->providerName(),
                'kind' => $kind,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Normalize a phone number to E.164.
     */
    public static function normalizePhone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $service = app(PhoneNumberService::class);

        return $service->normalize($phone) ?? $service->legacyNormalize($phone);
    }
}
