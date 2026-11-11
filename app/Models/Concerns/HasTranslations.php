<?php

namespace App\Models\Concerns;

use App\Models\Language;

/**
 * Per-language content with fallback.
 *
 * Translatable fields are stored as a JSON map in "{field}_translations"
 * ({locale: value}); the base column keeps the default-language content.
 * Resolution order: requested/current locale → default language → base column.
 * Blank values (empty string, whitespace, empty HTML) never shadow a fallback.
 */
trait HasTranslations
{
    public function trans(string $field, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $default = Language::defaultCode();
        $map = $this->translationMap($field);

        foreach (array_unique([$locale, $default]) as $code) {
            $value = $map[$code] ?? null;
            if (is_string($value) && trim(strip_tags($value)) !== '') {
                return $value;
            }
        }

        return (string) $this->getAttribute($field);
    }

    /**
     * Translate an array field (e.g. JSON sections). Returns the translated
     * array for the locale, falling back to default, then to the base column.
     */
    public function transArray(string $field, ?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $default = Language::defaultCode();
        $map = $this->translationMap($field);

        foreach (array_unique([$locale, $default]) as $code) {
            $value = $map[$code] ?? null;
            if (is_array($value) && ! empty($value)) {
                return $value;
            }
        }

        $base = $this->getAttribute($field);

        return is_array($base) ? $base : [];
    }

    /** Raw translation map for a field (locale => value); never null. */
    public function translationMap(string $field): array
    {
        $map = $this->getAttribute($field.'_translations');

        return is_array($map) ? $map : [];
    }

    /**
     * Clean and filter translations from form submission.
     * Returns an associative array of [locale => value] with blank entries removed.
     *
     * @param  array<string, array<string, mixed>>  $translations
     * @return array<string, string>
     */
    public static function cleanTranslations(array $translations, string $field): array
    {
        $allowed = Language::active()->pluck('code')->all();
        $out = [];

        foreach ($translations as $code => $fields) {
            if (! in_array($code, $allowed, true) || ! is_array($fields)) {
                continue;
            }
            $value = $fields[$field] ?? null;
            if (is_string($value) && trim(strip_tags($value)) !== '') {
                $out[$code] = $value;
            }
        }

        return $out;
    }
}
