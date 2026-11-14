<?php

namespace App\ValueObjects;

use App\Models\Country;

final readonly class PhoneNumber
{
    public function __construct(
        public ?string $e164,
        public ?Country $country,
        public string $nationalNumber = '',
        public ?string $error = null,
    ) {}

    public function isValid(): bool
    {
        return $this->e164 !== null && $this->error === null;
    }

    /** "+880 1712 345678" — dial code and national part split for readability. */
    public function display(): string
    {
        if (! $this->isValid()) {
            return $this->nationalNumber;
        }

        $dial = $this->country?->phone_code;

        return trim(($dial ? $dial.' ' : '').$this->nationalNumber);
    }

    /** "+880 ••••••8877" — safe for OTP screens and admin tables. */
    public function maskedDisplay(): string
    {
        if (! $this->isValid()) {
            return '';
        }

        $dial = $this->country?->phone_code ?? '';
        $digits = $this->nationalNumber !== '' ? $this->nationalNumber : ltrim((string) $this->e164, '+');
        $visible = min(4, strlen($digits));
        $hidden = max(0, strlen($digits) - $visible);

        return trim($dial !== '' ? $dial.' ' : '').str_repeat('•', $hidden).substr($digits, -$visible);
    }
}
