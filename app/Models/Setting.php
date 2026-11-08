<?php

namespace App\Models;

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
}
