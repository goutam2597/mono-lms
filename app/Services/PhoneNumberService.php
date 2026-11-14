<?php

namespace App\Services;

use App\Models\Country;
use App\ValueObjects\PhoneNumber;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Throwable;

class PhoneNumberService
{
    /**
     * Convert a user-entered local or E.164 number to the platform canonical
     * form. Enabled countries with a dial code take part in prefix matching
     * (longest code wins), so the Locations settings page stays authoritative.
     *
     * Ported from the marketplace parser with one deliberate difference: an
     * E.164-shaped number whose dial code no enabled country claims is still
     * accepted country-less. SMS delivery only needs the E.164 string, and
     * learners sign up from countries the admin may not have enabled yet;
     * rejecting them would silently break OTP. Bare local input without a
     * resolvable country fails like the marketplace parser does.
     */
    public function parse(?string $value, Country|int|string|null $country = null): PhoneNumber
    {
        $raw = trim((string) $value);
        $explicitCountry = $country !== null && $country !== '';
        $selected = $this->resolveCountry($country);

        // Naming a country that is not phone-enabled (disabled, or missing a
        // dial code) must fail loudly. Falling back to some other country here
        // would silently route a number to the wrong country.
        if ($explicitCountry && ! $selected) {
            return new PhoneNumber(null, null, $raw, __('The phone number country code is not enabled.'));
        }

        if ($raw === '') {
            return new PhoneNumber(null, $selected);
        }

        $clean = $this->clean($raw);
        if ($clean === '') {
            return new PhoneNumber(null, $selected, '', __('Please enter a valid phone number.'));
        }

        if (str_starts_with($clean, '00')) {
            $clean = '+'.substr($clean, 2);
        }

        $countries = $this->countries();

        if (str_starts_with($clean, '+')) {
            $matched = $this->matchCountry($clean);

            if ($matched) {
                $national = substr($clean, strlen((string) $matched->phone_code));

                return $this->fromNational($matched, $national);
            }

            return $this->fromE164($clean);
        }

        if (! $selected) {
            // A single enabled country may claim unqualified local input;
            // otherwise the country must be chosen explicitly.
            if ($countries->count() === 1) {
                $selected = $countries->first();
            } else {
                return new PhoneNumber(null, null, $clean, __('Please select a country code.'));
            }
        }

        return $this->withDial($selected, $clean);
    }

    /** Parse an existing stored number without guessing a country for bare legacy input. */
    public function parseStored(?string $value, Country|int|string|null $country = null): PhoneNumber
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return new PhoneNumber(null, $this->resolveCountry($country));
        }

        $clean = $this->clean($raw);
        if (str_starts_with($clean, '00')) {
            $clean = '+'.substr($clean, 2);
        }

        if (str_starts_with($clean, '+')) {
            return $this->parse($clean, $country);
        }

        $resolved = $this->resolveCountry($country);
        if (! $resolved) {
            return new PhoneNumber(null, null, $raw, __('This legacy phone number needs a country selection.'));
        }

        return $this->parse($clean, $resolved);
    }

    public function normalize(?string $value, Country|int|string|null $country = null): ?string
    {
        return $this->parse($value, $country)->e164;
    }

    /**
     * Resolve a number already held by a record.
     *
     * Prefers the stored canonical value, then re-derives one from the raw
     * column. Rows the parser cannot resolve (unparseable) fall back to the
     * old syntactic normalization so SMS delivery that worked before the
     * canonical fields existed keeps working.
     */
    public function resolveStored(?string $raw, ?string $e164 = null, Country|int|string|null $country = null): ?string
    {
        foreach ([$e164, $raw] as $candidate) {
            if (blank($candidate)) {
                continue;
            }

            $phone = $this->parse((string) $candidate, $country);

            if ($phone->isValid()) {
                return $phone->e164;
            }
        }

        return $this->legacyNormalize($raw);
    }

    /**
     * The pre-canonical E.164-shaped sanitizer: strips formatting, turns a
     * leading 00 into +, prefixes a bare +. Kept strictly as a fallback for
     * unresolvable legacy rows and masking.
     */
    public function legacyNormalize(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $phone = preg_replace('/[^\d+]/', '', trim($phone)) ?? '';

        if (str_starts_with($phone, '00')) {
            $phone = '+'.substr($phone, 2);
        }

        if (! str_starts_with($phone, '+')) {
            $phone = '+'.$phone;
        }

        return preg_match('/^\+\d{7,15}$/', $phone) === 1 ? $phone : null;
    }

    /** Mask a number for display: keep the first 4 and last 4 characters. */
    public function mask(?string $phone): string
    {
        $e164 = $this->resolveStored($phone) ?? $this->legacyNormalize($phone);

        if ($e164 === null || $e164 === '') {
            return '';
        }

        $digits = substr($e164, 1);
        $visible = min(4, strlen($digits));

        return substr($e164, 0, 4).str_repeat('•', max(0, strlen($digits) - $visible)).substr($digits, -$visible);
    }

    /** Countries with dial codes, ordered by name — the phone-picker feed. */
    public function activeCountries(): Collection
    {
        return $this->countries()->sortBy('name')->values();
    }

    /** Validation rule: a filled phone value must parse to a canonical E.164. */
    public static function rule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {
            if (blank($value)) {
                return;
            }

            $phone = app(static::class)->parse((string) $value);

            if (! $phone->isValid()) {
                $fail(__('Please enter a valid phone number.'));
            }
        };
    }

    /** Validation rule: a dial code looks like +880 — optional plus, 1-6 digits. */
    public static function dialCodeRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {
            if (blank($value)) {
                return;
            }

            if (preg_match('/^\+?\d{1,6}$/', trim((string) $value)) !== 1) {
                $fail(__('A dial code looks like +880 — optional plus sign and 1-6 digits.'));
            }
        };
    }

    /** Drop the cached phone-enabled country list (call after admin geo edits). */
    public static function clearCache(): void
    {
        Cache::forget('countries.phone_enabled');
    }

    /**
     * Model attributes for a phone-bearing update: the raw value plus the
     * canonical columns. A blank value clears all three.
     *
     * @return array{phone: ?string, phone_e164: ?string, phone_country: ?string}
     */
    public function attributesFor(?string $value): array
    {
        $phone = $this->parse($value);

        return [
            'phone' => $value !== null && trim($value) !== '' ? trim($value) : null,
            'phone_e164' => $phone->e164,
            'phone_country' => $phone->country?->code,
        ];
    }

    public function normalizeDialCode(?string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        return $digits === '' ? null : '+'.$digits;
    }

    /**
     * Example national number used as the phone field placeholder. Admins set
     * it per country in Settings → Locations so the hint matches the numbering
     * shape people in that country recognise; a generated value keeps the
     * field sensible while an admin has not filled one in.
     */
    public function exampleFor(?Country $country): string
    {
        $configured = preg_replace('/\D+/', '', (string) $country?->phone_example) ?? '';

        if ($configured !== '') {
            return $configured;
        }

        $dialLength = strlen(preg_replace('/\D+/', '', (string) $country?->phone_code) ?? '');

        // Leave room for the dial code inside E.164's 15-digit ceiling.
        $length = max(6, min(10, 15 - $dialLength - 1));

        return substr('12345678901234567890', 0, $length);
    }

    /** Placeholder text: prefixed for a chosen country, bare digits otherwise. */
    public function placeholderFor(?Country $country): string
    {
        $example = $this->exampleFor($country);

        return $country?->phone_code ? $country->phone_code.' '.$example : $example;
    }

    /**
     * Dial-code-enabled active countries, dial codes normalized. Cached for
     * ten minutes (cleared with the Locations settings page) and guarded so a
     * missing countries table (fresh install before migrate) never 500s —
     * the same contract Language::active() keeps.
     *
     * @return Collection<int, Country>
     */
    private function countries(): Collection
    {
        try {
            $rows = Cache::remember('countries.phone_enabled', now()->addMinutes(10), function () {
                return Country::active()
                    ->whereNotNull('phone_code')
                    ->where('phone_code', '!=', '')
                    ->orderByDesc('phone_code')
                    ->get()
                    ->map(fn (Country $country): array => [
                        'id' => $country->id,
                        'name' => $country->name,
                        'code' => $country->code,
                        'phone_code' => $this->normalizeDialCode($country->phone_code),
                        'phone_example' => $country->phone_example,
                    ])
                    ->filter(fn (array $country): bool => $country['phone_code'] !== null)
                    ->values()
                    ->toArray();
            });

            return collect($rows)->map(fn (array $row): Country => new Country($row));
        } catch (Throwable) {
            return collect();
        }
    }

    private function resolveCountry(Country|int|string|null $country): ?Country
    {
        if ($country instanceof Country) {
            return $country->is_active && $this->normalizeDialCode($country->phone_code) ? $country : null;
        }

        if ($country === null || $country === '') {
            return null;
        }

        return $this->countries()->first(
            is_numeric($country)
                ? fn (Country $candidate): bool => $candidate->id === (int) $country
                : fn (Country $candidate): bool => $candidate->code === strtoupper((string) $country)
        );
    }

    private function clean(string $value): string
    {
        $value = preg_replace('/[^\d+]/', '', $value) ?? '';
        // Only the initial + is meaningful. Pasted values such as
        // +880+880171… are deliberately preserved as invalid instead of
        // silently dialing an unintended number.
        if (substr_count($value, '+') > 1 || (str_contains($value, '+') && ! str_starts_with($value, '+'))) {
            return '';
        }

        return $value;
    }

    /** Longest-prefix match: countries() is pre-sorted so a dial code that prefixes a longer one loses. */
    private function matchCountry(string $e164): ?Country
    {
        return $this->countries()->first(
            fn (Country $country): bool => str_starts_with($e164, (string) $country->phone_code)
        );
    }

    /** Strip a pasted dial code (without its +) once, then treat the rest as national digits. */
    private function withDial(Country $country, string $clean): PhoneNumber
    {
        $dialDigits = ltrim((string) $country->phone_code, '+');
        if ($dialDigits !== '' && str_starts_with($clean, $dialDigits)) {
            $clean = substr($clean, strlen($dialDigits));
        }

        return $this->fromNational($country, $clean);
    }

    /** Accept a country-less E.164 whose dial code no enabled country claims. */
    private function fromE164(string $clean): PhoneNumber
    {
        $e164 = preg_match('/^\+\d{7,15}$/', $clean) === 1 ? $clean : null;

        return new PhoneNumber($e164, null, ltrim($clean, '+'), $e164 === null ? __('Please enter a valid phone number.') : null);
    }

    private function fromNational(Country $country, string $national): PhoneNumber
    {
        $national = ltrim(preg_replace('/\D+/', '', $national) ?? '', '0');
        if ($national === '') {
            return new PhoneNumber(null, $country, '', __('Please enter your phone number.'));
        }

        $e164 = (string) $country->phone_code.$national;
        if (! preg_match('/^\+\d{7,15}$/', $e164)) {
            return new PhoneNumber(null, $country, $national, __('Please enter a valid phone number.'));
        }

        return new PhoneNumber($e164, $country, $national);
    }
}
