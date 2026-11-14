<?php

namespace App\Services;

use App\Mail\OtpVerificationMail;
use App\Mail\PasswordResetOtpMail;
use App\Models\PasswordOtp;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class OtpService
{
    public const PURPOSE_VERIFY = 'verify';

    public const PURPOSE_SIGNUP = 'signup';

    public const PURPOSE_RESET = 'reset';

    public const CHANNEL_EMAIL = 'email';

    public const CHANNEL_SMS = 'sms';

    public const EXPIRY_MINUTES = 10;

    public const MAX_ATTEMPTS = 5;

    /**
     * Issue an OTP code on any channel (email or sms).
     *
     * @return array{0: PasswordOtp, 1: string}
     */
    public function issueFor(
        string $channel,
        string $identifier,
        string $purpose = self::PURPOSE_VERIFY,
        ?string $name = null,
        string $role = 'student',
    ): array {
        $isPhone = $channel === self::CHANNEL_SMS;

        $identifier = $isPhone
            ? (string) SmsService::normalizePhone($identifier)
            : strtolower(trim($identifier));

        if ($identifier === '') {
            throw new InvalidArgumentException('A valid email address or phone number is required to issue a code.');
        }

        // Invalidate prior codes for this purpose/channel
        $query = PasswordOtp::where('purpose', $purpose)->where('channel', $channel);
        if ($isPhone) {
            $query->where('phone', $identifier);
        } else {
            $query->where('email', $identifier);
        }
        $query->delete();

        $code = (string) random_int(100000, 999999);

        $otp = PasswordOtp::create([
            'email' => $isPhone ? null : $identifier,
            'phone' => $isPhone ? $identifier : null,
            'channel' => $channel,
            'purpose' => $purpose,
            'otp' => Hash::make($code),
            'verified' => false,
            'attempts' => 0,
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        if ($channel === self::CHANNEL_SMS) {
            $this->sendSms($identifier, $code);
        } else {
            $this->sendEmail($identifier, $code, $name ?? 'Learner', $purpose, $role);
        }

        return [$otp, $code];
    }

    /**
     * Verify a submitted code against the stored hash on the given channel.
     */
    public function verifyFor(string $channel, string $identifier, string $purpose, string $code): ?PasswordOtp
    {
        $isPhone = $channel === self::CHANNEL_SMS;

        $identifier = $isPhone
            ? (string) SmsService::normalizePhone($identifier)
            : strtolower(trim($identifier));

        $query = PasswordOtp::where('purpose', $purpose)
            ->where('channel', $channel)
            ->where('verified', false);

        if ($isPhone) {
            $query->where('phone', $identifier);
        } else {
            $query->where('email', $identifier);
        }

        $otp = $query->latest()->first();

        if (! $otp || $otp->isExpired()) {
            return null;
        }

        if ($otp->attempts >= self::MAX_ATTEMPTS) {
            $otp->delete();

            return null;
        }

        if (! Hash::check(trim($code), $otp->otp)) {
            $otp->increment('attempts');

            if ($otp->fresh()->attempts >= self::MAX_ATTEMPTS) {
                $otp->delete();
            }

            return null;
        }

        $otp->forceFill([
            'verified' => true,
            'reset_token' => Str::random(64),
        ])->save();

        return $otp->fresh();
    }

    /**
     * Remove OTP records once registration or password reset is finalized.
     */
    public function clear(string $channel, string $identifier, string $purpose): void
    {
        $query = PasswordOtp::where('purpose', $purpose)->where('channel', $channel);

        if ($channel === self::CHANNEL_SMS) {
            $query->where('phone', SmsService::normalizePhone($identifier));
        } else {
            $query->where('email', strtolower(trim($identifier)));
        }

        $query->delete();
    }

    /**
     * Find and consume a verified OTP by its reset token.
     */
    public function consumeByResetToken(string $token): ?PasswordOtp
    {
        $otp = PasswordOtp::where('reset_token', $token)
            ->where('verified', true)
            ->latest()
            ->first();

        if (! $otp || $otp->isExpired()) {
            return null;
        }

        $otp->delete();

        return $otp;
    }

    /**
     * Deliver code via SMS.
     */
    public function sendSms(string $phone, string $code): void
    {
        try {
            $site = Setting::get('site_name', 'Mono LMS');
            $body = "{$code} is your {$site} verification code. It expires in ".self::EXPIRY_MINUTES.' minutes.';

            app(SmsService::class)->send($phone, $body, 'otp');
        } catch (Throwable $e) {
            Log::warning('Failed to send OTP SMS to '.$phone.': '.$e->getMessage());
        }
    }

    /**
     * Deliver code via Email.
     */
    public function sendEmail(string $email, string $code, string $name, string $purpose, string $role): void
    {
        try {
            if ($purpose === self::PURPOSE_RESET) {
                Mail::to($email)->send(new PasswordResetOtpMail($code, $name, $role));
            } else {
                Mail::to($email)->send(new OtpVerificationMail($code, $name, $role));
            }
        } catch (Throwable $e) {
            Log::warning('Failed to send OTP email to '.$email.': '.$e->getMessage());
        }
    }
}
