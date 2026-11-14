<?php

namespace App\Rules;

use App\Services\TurnstileService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Passes untouched when Turnstile is disabled, so existing flows keep
 * working. When enabled it requires the widget response and verifies it with
 * Cloudflare. The action binds a token to the form that minted it ('login'
 * or 'register').
 */
class ValidTurnstile implements ValidationRule
{
    public function __construct(
        private readonly ?string $action = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $turnstile = app(TurnstileService::class);

        if (! $turnstile->enabled()) {
            return;
        }

        if (! $turnstile->verify(is_string($value) ? $value : null, request()->ip(), $this->action)) {
            $fail((string) __('auth.turnstile.failed'));
        }
    }
}
