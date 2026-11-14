<x-layouts.dashboard title="Bot Protection - Mono LMS" active="turnstile_settings" userType="admin">

@php
    $tsOn        = old('turnstile_enabled', $settings['turnstile_enabled'] ?? '0') === '1';
    $tsTheme     = old('turnstile_theme', $settings['turnstile_theme'] ?? 'auto');
    // Live / Incomplete / Off — mirrors the marketplace's recaptcha status logic.
    $tsStatus    = $service->enabled() ? 'live' : ($service->incomplete() ? 'incomplete' : 'off');
    $badge = [
        'live' => ['border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300', 'bg-emerald-500', __('admin.turnstile.status_live')],
        'incomplete' => ['border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300', 'bg-amber-500', __('admin.turnstile.status_incomplete')],
        'off' => ['border-neutral-200 bg-neutral-100 text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400', 'bg-neutral-400', __('admin.turnstile.status_off')],
    ][$tsStatus];
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">Bot Protection</h1>
                <span id="turnstile-status-badge" class="inline-flex items-center gap-2 border px-3 py-1.5 text-[11px] font-medium uppercase tracking-[0.14em] {{ $badge[0] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $badge[1] }}"></span>
                    {{ $badge[2] }}
                </span>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                {{ __('admin.turnstile.sub') }}
            </p>
        </div>
        <button type="submit" form="turnstile-form" class="action-btn">
            <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ __('app.common.save_changes') }}
        </button>
    </div>

    @if (session('success'))
        <div class="border border-emerald-300 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-4 text-sm text-emerald-800 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="border border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-950/40 p-4 text-sm text-red-800 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($tsStatus === 'incomplete')
        <div class="border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 p-4 text-sm text-amber-800 dark:text-amber-200">
            {{ __('admin.turnstile.incomplete_note') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.turnstile.update') }}" id="turnstile-form" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Setup guide --}}
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.turnstile.setup_heading') }}</h2>
            <ol class="space-y-2 text-xs text-neutral-600 dark:text-neutral-300 list-decimal list-inside">
                <li>{{ __('admin.turnstile.setup_step1') }}</li>
                <li>{{ __('admin.turnstile.setup_step2') }}</li>
                <li>{{ __('admin.turnstile.setup_step3') }}</li>
            </ol>
            <div class="flex flex-wrap gap-3 pt-1">
                <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank" rel="noopener noreferrer"
                   class="action-btn action-btn-primary h-9 px-4 text-[10px]" style="text-decoration:none">
                    {{ __('admin.turnstile.setup_dashboard') }}
                    <svg class="ml-1.5 h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a href="https://developers.cloudflare.com/turnstile/get-started/" target="_blank" rel="noopener noreferrer"
                   class="action-btn action-btn-ghost h-9 px-4 text-[10px]" style="text-decoration:none">
                    {{ __('admin.turnstile.setup_docs') }}
                    <svg class="ml-1.5 h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>

        {{-- Enable --}}
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.turnstile.status_heading') }}</h2>
            <label class="flex cursor-pointer items-start gap-3 border border-neutral-300 dark:border-neutral-700 p-4 rounded transition-colors has-[:checked]:border-neutral-900 has-[:checked]:bg-neutral-50 dark:has-[:checked]:bg-[#161616] dark:has-[:checked]:border-neutral-500">
                <input type="hidden" name="turnstile_enabled" value="0">
                <input type="checkbox" name="turnstile_enabled" value="1" class="control mt-0.5" @checked($tsOn)>
                <span class="text-sm leading-relaxed text-neutral-700 dark:text-neutral-300">
                    <span class="font-medium">{{ __('admin.turnstile.enabled') }}</span><br>
                    <span class="text-[12px] text-neutral-500 dark:text-neutral-400">{{ __('admin.turnstile.enabled_hint') }}</span>
                </span>
            </label>
        </div>

        {{-- Credentials --}}
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.turnstile.keys_heading') }}</h2>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('admin.turnstile.keys_sub') }}</p>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="turnstile_site_key" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.turnstile.site_key') }}</label>
                    <input id="turnstile_site_key" name="turnstile_site_key" type="text"
                           value="{{ old('turnstile_site_key', $settings['turnstile_site_key'] ?? '') }}"
                           placeholder="0x4AAAAAAA..."
                           class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                </div>
                <div>
                    <label for="turnstile_secret_key" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.turnstile.secret_key') }}</label>
                    <input id="turnstile_secret_key" name="turnstile_secret_key" type="text"
                           value="{{ old('turnstile_secret_key', $settings['turnstile_secret_key'] ?? '') }}"
                           placeholder="0x4AAAAAAA..."
                           class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                </div>
                <div>
                    <label for="turnstile_theme" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.turnstile.theme') }}</label>
                    <select id="turnstile_theme" name="turnstile_theme"
                            class="text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                        @foreach (['auto' => __('admin.turnstile.theme_auto'), 'light' => __('admin.turnstile.theme_light'), 'dark' => __('admin.turnstile.theme_dark')] as $val => $label)
                            <option value="{{ $val }}" @selected($tsTheme === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <p class="text-[11px] leading-relaxed text-neutral-400 dark:text-neutral-500">{{ __('admin.turnstile.keys_help') }}</p>
        </div>

        {{-- Coverage --}}
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
            <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.turnstile.coverage_heading') }}</h2>
            <ul class="space-y-2 text-xs text-neutral-600 dark:text-neutral-300">
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    {{ __('admin.turnstile.coverage_login') }}
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    {{ __('admin.turnstile.coverage_register') }}
                </li>
            </ul>
            <p class="text-[11px] leading-relaxed text-neutral-400 dark:text-neutral-500">{{ __('admin.turnstile.coverage_note') }}</p>
        </div>
    </form>
</div>

<script>
(function () {
    // Keep the status badge in step with the toggle while editing.
    var enabled = document.querySelector('input[name="turnstile_enabled"]');
    var badge = document.getElementById('turnstile-status-badge');
    if (!enabled || !badge) return;

    var LABELS = {
        live: @json(__('admin.turnstile.status_live')),
        incomplete: @json(__('admin.turnstile.status_incomplete')),
        off: @json(__('admin.turnstile.status_off'))
    };
    var keysFilled = {{ $service->siteKey() !== null && $service->secretKey() !== null ? 'true' : 'false' }};

    function status() {
        if (!enabled.checked) return 'off';
        return keysFilled ? 'live' : 'incomplete';
    }

    var STYLES = {
        live: ['border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300', 'bg-emerald-500'],
        incomplete: ['border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300', 'bg-amber-500'],
        off: ['border-neutral-200 bg-neutral-100 text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400', 'bg-neutral-400']
    };

    enabled.addEventListener('change', function () {
        var s = status();
        badge.className = 'inline-flex items-center gap-2 border px-3 py-1.5 text-[11px] font-medium uppercase tracking-[0.14em] ' + STYLES[s][0];
        badge.querySelector('span').className = 'h-1.5 w-1.5 rounded-full ' + STYLES[s][1];
        badge.childNodes[badge.childNodes.length - 1].textContent = LABELS[s];
    });
})();
</script>

</x-layouts.dashboard>
