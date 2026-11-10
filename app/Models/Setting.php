<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    public static function get(string $key, ?string $default = null): ?string
    {
        // Cache plain values only — a missing settings table (fresh test DB)
        // must never 500 a request.
        try {
            return Cache::remember('settings.'.$key, now()->addHour(), fn () => static::query()
                ->where('key', $key)
                ->value('value') ?? $default);
        } catch (\Throwable) {
            return $default;
        }
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.'.$key);
    }

    public static function enabled(string $key): bool
    {
        return self::get($key) === '1';
    }

    public static function faviconUrl(): string
    {
        $custom = self::get('site_favicon');

        if (! empty($custom)) {
            $url = PublicMedia::url($custom);
            if (! empty($url)) {
                return $url.'?v='.substr(md5($custom), 0, 8);
            }
        }

        return asset('images/favicon.png');
    }

    public static function logoUrl(?string $fallback = null): string
    {
        $custom = self::get('site_logo');
        $fallback ??= asset('images/logo.png');

        if (! empty($custom)) {
            $url = PublicMedia::url($custom);
            if (! empty($url)) {
                return $url;
            }
        }

        return $fallback;
    }

    public static function paymentMethodsUrl(?string $fallback = null): string
    {
        $custom = self::get('footer_payment_methods');
        $fallback ??= asset('images/payment-methods.png');

        if (! empty($custom)) {
            $url = PublicMedia::url($custom);
            if (! empty($url)) {
                return $url;
            }
        }

        return $fallback;
    }

    public static function paymentMethodsWidth(?string $fallback = '340px'): string
    {
        $val = trim((string) self::get('footer_payment_methods_width', $fallback));
        if ($val === '') {
            return $fallback;
        }

        if (is_numeric($val)) {
            return $val.'px';
        }

        return $val;
    }

    public static function isOtpSignupEnabled(): bool
    {
        return self::get('otp_signup_enabled', '0') === '1';
    }
}
