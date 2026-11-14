{{--
    Cloudflare Turnstile widget for the web auth forms. Renders nothing
    unless the admin enabled it and filled in both keys on the Bot Protection
    settings page. The widget posts its token as the hidden
    `cf-turnstile-response` input (inserted by Turnstile's implicit
    rendering), which the auth controllers validate through
    App\Rules\ValidTurnstile. Pass an action when rendering:
    <x-turnstile action="login" />
--}}
@props(['action' => 'login'])

@php
    $turnstile = app(\App\Services\TurnstileService::class);
    $turnstileActive = $turnstile->enabled();
@endphp
@if ($turnstileActive)
    <div>
        <div class="cf-turnstile w-full"
             data-sitekey="{{ $turnstile->siteKey() }}"
             data-theme="{{ $turnstile->theme() }}"
             data-size="flexible"
             data-action="{{ $action }}"
             aria-label="{{ __('auth.turnstile.label') }}"></div>
        @error('cf-turnstile-response')
            <p class="mt-2 text-[12px] text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
