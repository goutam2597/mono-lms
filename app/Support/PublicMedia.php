<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class PublicMedia
{
    /** Resolve public-disk media, legacy storage URLs, or an external URL. */
    public static function url(?string $value, ?string $fallback = null): string
    {
        $value = trim((string) $value);
        $hasFallback = $fallback !== null && trim($fallback) !== '';

        if ($value === '') {
            if (! $hasFallback) {
                return '';
            }

            return self::isHttpUrl($fallback) ? $fallback : asset(ltrim($fallback, '/'));
        }

        if (self::isHttpUrl($value)) {
            $path = parse_url($value, PHP_URL_PATH);
            $marker = '/storage/';
            $position = is_string($path) ? strpos($path, $marker) : false;

            if ($position !== false) {
                $candidate = self::normalizePath(substr($path, $position + strlen($marker)));
                if ($candidate !== '' && Storage::disk('public')->exists($candidate)) {
                    return Storage::disk('public')->url($candidate);
                }
            }

            return $value;
        }

        $path = self::normalizePath($value);

        if ($path === '') {
            return $hasFallback ? asset(ltrim($fallback, '/')) : '';
        }

        // Files shipped with the app (public/images/...) are served from document root
        if (str_starts_with($path, 'images/') && file_exists(public_path($path))) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        // Default to public storage URL for relative paths
        return Storage::disk('public')->url($path);
    }

    public static function fallback(string $name): string
    {
        return asset('images/'.ltrim($name, '/'));
    }

    private static function normalizePath(string $value): string
    {
        $path = str_replace('\\', '/', trim($value));
        $path = preg_replace('~[?#].*$~', '', $path) ?? $path;
        $path = ltrim($path, '/');

        while (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $segments = array_values(array_filter(explode('/', $path), static fn (string $segment): bool => $segment !== ''));
        if ($segments === [] || in_array('..', $segments, true)) {
            return '';
        }

        return implode('/', $segments);
    }

    private static function isHttpUrl(string $value): bool
    {
        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            return false;
        }

        return in_array(strtolower((string) parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true);
    }
}
