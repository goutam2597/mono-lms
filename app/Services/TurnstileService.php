<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Cloudflare Turnstile bot protection for the web auth forms (student and
 * instructor login + registration). Replaces the marketplace's Google
 * reCAPTCHA gate with Cloudflare's privacy-first alternative — same shape:
 * settings-driven toggle + credentials, a validation rule that passes
 * untouched while disabled, and a fail-closed siteverify check.
 *
 * Everything is stored as settings so the admin panel can switch it on and
 * off and swap credentials without a deploy; with the toggle off or the keys
 * missing, every check short-circuits to a pass.
 */
class TurnstileService
{
    public const ENABLED_SETTING = 'turnstile_enabled';

    public const SITE_KEY_SETTING = 'turnstile_site_key';

    public const SECRET_KEY_SETTING = 'turnstile_secret_key';

    public const THEME_SETTING = 'turnstile_theme';

    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /** True when the admin turned it on AND supplied both credentials. */
    public function enabled(): bool
    {
        return Setting::get(self::ENABLED_SETTING, '0') === '1'
            && $this->siteKey() !== null
            && $this->secretKey() !== null;
    }

    /** True when the toggle is on but credentials are still missing. */
    public function incomplete(): bool
    {
        return Setting::get(self::ENABLED_SETTING, '0') === '1'
            && ($this->siteKey() === null || $this->secretKey() === null);
    }

    /**
     * Widget theme: light, dark, or auto (follows the visitor's OS preference).
     * Defaults to light so the widget matches the platform's light storefront
     * and auth pages; 'auto' can flip it dark on dark-mode devices.
     */
    public function theme(): string
    {
        $theme = Setting::get(self::THEME_SETTING, 'light');

        return in_array($theme, ['light', 'dark', 'auto'], true) ? $theme : 'light';
    }

    public function siteKey(): ?string
    {
        $key = trim((string) Setting::get(self::SITE_KEY_SETTING, ''));

        return $key !== '' ? $key : null;
    }

    public function secretKey(): ?string
    {
        $key = trim((string) Setting::get(self::SECRET_KEY_SETTING, ''));

        return $key !== '' ? $key : null;
    }

    /**
     * Check a widget response against Cloudflare. When an action is given it
     * must match the one the widget was rendered with, so a token minted on
     * one form cannot be replayed on another. Fails closed: an unreachable
     * Cloudflare or an unexpected payload counts as a failed check — the
     * visitor simply retries.
     */
    public function verify(?string $response, ?string $ip = null, ?string $action = null): bool
    {
        $secret = $this->secretKey();

        if ($secret === null || $response === null || trim($response) === '') {
            return false;
        }

        try {
            $payload = Http::asForm()
                ->timeout(5)
                ->retry(1, 300)
                ->post(self::VERIFY_URL, array_filter([
                    'secret' => $secret,
                    'response' => $response,
                    'remoteip' => $ip,
                ]))
                ->json();
        } catch (Throwable $e) {
            Log::warning('Turnstile siteverify request failed.', ['error' => $e->getMessage()]);

            return false;
        }

        if (! is_array($payload) || ($payload['success'] ?? false) !== true) {
            return false;
        }

        if ($action !== null && isset($payload['action']) && $payload['action'] !== $action) {
            return false;
        }

        return true;
    }
}
