<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable([
    'title',
    'slug',
    'system_key',
    'template',
    'content',
    'settings',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'og_image',
    'status',
    'is_system',
])]
class Page extends Model
{
    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_system' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get a nested setting with dot-notation support and fallback.
     */
    public function setting(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings ?? [], $key, $default);
    }

    /**
     * Resolved absolute public URL for this page based on its permalink.
     */
    public function permalinkUrl(): string
    {
        return url($this->slug);
    }

    /**
     * Find a page by system key with optional in-memory caching.
     */
    public static function byKey(string $key): ?self
    {
        return self::where('system_key', $key)->first();
    }

    /**
     * Find a page by slug or system key.
     */
    public static function findBySlugOrKey(string $identifier): ?self
    {
        return self::where('slug', $identifier)
            ->orWhere('system_key', $identifier)
            ->first();
    }

    /**
     * Resolve the public absolute URL for a system page by its key, falling back to a default.
     */
    public static function url(string $key, ?string $fallback = null): string
    {
        $page = self::byKey($key);

        if ($page && ! empty($page->slug)) {
            return url($page->slug);
        }

        return $fallback ? url($fallback) : url($key);
    }

    /**
     * Clear page caches when updated or deleted.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('pages.system_keys');
        });

        static::deleted(function () {
            Cache::forget('pages.system_keys');
        });
    }
}
