<?php

namespace App\Services;

use App\Models\Setting;

/**
 * Ceilings for AI operations (translate, generate, image).
 */
class AiTimeoutService
{
    public const TRANSLATE = 'translate';

    public const GENERATE = 'generate';

    public const IMAGE = 'image';

    public const KINDS = [self::TRANSLATE, self::GENERATE, self::IMAGE];

    public const DEFAULT_TIMEOUT = 0;

    public const MAX_TIMEOUT = 86400;

    public const UNLIMITED_SERVER_SECONDS = 900;

    private const SERVER_HEADROOM = 30;

    public static function applyServerLimit(string $kind): void
    {
        $seconds = self::seconds($kind);

        if ($seconds <= 0) {
            $seconds = self::UNLIMITED_SERVER_SECONDS;
        }

        set_time_limit($seconds + self::SERVER_HEADROOM);
    }

    public static function settingKey(string $kind): string
    {
        return "ai_{$kind}_timeout";
    }

    public static function seconds(string $kind): int
    {
        if (! in_array($kind, self::KINDS, true)) {
            return self::DEFAULT_TIMEOUT;
        }

        $raw = Setting::get(self::settingKey($kind));

        if ($raw === null || $raw === '') {
            return self::DEFAULT_TIMEOUT;
        }

        return max(0, min(self::MAX_TIMEOUT, (int) $raw));
    }

    public static function limits(): array
    {
        $limits = [];

        foreach (self::KINDS as $kind) {
            $seconds = self::seconds($kind);
            $limits[$kind] = $seconds > 0 ? $seconds : null;
        }

        return $limits;
    }

    public static function store(string $kind, int $seconds): void
    {
        if (! in_array($kind, self::KINDS, true)) {
            return;
        }

        Setting::set(
            self::settingKey($kind),
            (string) max(0, min(self::MAX_TIMEOUT, $seconds))
        );
    }
}
