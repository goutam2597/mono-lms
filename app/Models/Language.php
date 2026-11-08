<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable([
    'code', 'name', 'native_name', 'direction', 'flag', 'is_active', 'is_default', 'sort_order',
])]
class Language extends Model
{
    public function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public static function active()
    {
        // Cache plain attributes (not the Eloquent collection) so the
        // database cache store can serialize/unserialize safely. A missing
        // languages table (fresh test DB) must never 500 the request.
        try {
            return static::hydrate(Cache::remember('languages.active', now()->addHour(), fn () => static::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->toArray()));
        } catch (\Throwable) {
            return collect();
        }
    }

    public static function defaultCode(): string
    {
        try {
            return Cache::remember('languages.default', now()->addHour(), fn () => static::query()
                ->where('is_default', true)
                ->value('code') ?? 'en');
        } catch (\Throwable) {
            return 'en';
        }
    }

    public static function clearCache(): void
    {
        Cache::forget('languages.active');
        Cache::forget('languages.default');
    }
}
