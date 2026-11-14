{{--
    Storefront cookie-consent banner, rendered on the storefront and auth
    layouts when enabled in Admin → Settings → Cookie Consent. Records the
    visitor's choice in a 180-day `cookie_consent` cookie (+ localStorage
    mirror) so it shows once until a decision is made, and never on the
    admin/instructor/student dashboard layouts.

    The accept button uses the platform palette (--theme-primary /
    --theme-primary-hover) with a neutral near-black fallback, while the panel
    stays neutral so copy is readable on every theme. Self-contained: no
    dependencies beyond the Tailwind utilities. Three admin-pickable styles:
    bar, card, modal. (Ported from the mono_market cookie-consent partial.)
--}}
@php
    $cookieEnabled = \App\Models\Setting::get('cookie_consent_enabled', '0') === '1';
@endphp
@if ($cookieEnabled)
    @php
        $cLayout = \App\Models\Setting::get('cookie_consent_layout', 'bar');
        $cLayout = in_array($cLayout, ['bar', 'card', 'modal'], true) ? $cLayout : 'bar';
        $cShowDecline = \App\Models\Setting::get('cookie_consent_show_decline', '1') === '1';
        $cTitle     = \App\Models\Setting::get('cookie_consent_title') ?: __('app.cookie.title');
        $cMessage   = \App\Models\Setting::get('cookie_consent_message') ?: __('app.cookie.message');
        $cAccept    = \App\Models\Setting::get('cookie_consent_accept_text') ?: __('app.cookie.accept');
        $cDecline   = \App\Models\Setting::get('cookie_consent_decline_text') ?: __('app.cookie.decline');
        $cPolicyTxt = \App\Models\Setting::get('cookie_consent_policy_text') ?: __('app.cookie.learn_more');
        $cPolicyUrl = trim((string) \App\Models\Setting::get('cookie_consent_policy_url', ''));
        $isBar = $cLayout === 'bar';
        $wrap = match ($cLayout) {
            'card'  => 'fixed bottom-4 left-4 right-4 z-[115] sm:right-auto sm:max-w-sm',
            'modal' => 'fixed inset-0 z-[115] flex items-end justify-center p-4 sm:items-center',
            default => 'fixed inset-x-0 bottom-0 z-[115]',
        };
        $panel = match ($cLayout) {
            'card'  => 'rounded-2xl border border-neutral-200 bg-white p-5 shadow-[0_24px_60px_-20px_rgba(0,0,0,0.45)]',
            'modal' => 'relative w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.6)]',
            default => 'cookie-bar-panel border-t border-neutral-200/80 bg-white/95 backdrop-blur-md',
        };
        $btnStretch = $isBar ? 'sm:flex-none' : 'flex-1';
    @endphp

    <style>
        #cookie-consent .cookie-panel { animation: cookie-rise .42s cubic-bezier(.22,1.15,.36,1); }
        #cookie-consent .cookie-backdrop { animation: cookie-fade .25s ease-out; }
        #cookie-consent .cookie-bar-panel { box-shadow: 0 -14px 40px rgba(0,0,0,.10); }
        #cookie-consent .cookie-bar-panel::before {
            content: ""; position: absolute; inset: 0 0 auto 0; height: 3px;
            background: linear-gradient(90deg, var(--theme-primary,#111827) 0%, color-mix(in srgb, var(--theme-primary,#111827) 45%, #ffffff) 100%);
        }
        #cookie-consent .cookie-bar-copy {
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        @media (min-width: 640px) { #cookie-consent .cookie-bar-copy { display: block; -webkit-line-clamp: unset; } }
        @keyframes cookie-rise { from { opacity: 0; transform: translateY(24px); } }
        @keyframes cookie-fade { from { opacity: 0; } }
    </style>

    <div id="cookie-consent" class="{{ $wrap }}" style="display:none"
         role="{{ $cLayout === 'modal' ? 'dialog' : 'region' }}" @if ($cLayout === 'modal') aria-modal="false" @endif
         aria-label="{{ __('app.cookie.aria_label') }}">

        @if ($cLayout === 'modal')
            <div class="cookie-backdrop absolute inset-0 bg-neutral-950/50 backdrop-blur-[2px]"></div>
        @endif

        <div class="cookie-panel relative {{ $panel }} {{ $isBar ? 'px-4 py-4 sm:px-8 sm:py-6' : '' }}">
            @if ($isBar)
                <div class="mx-auto flex w-full max-w-6xl flex-col gap-3.5 sm:flex-row sm:items-center sm:gap-8">

                    {{-- Icon + copy --}}
                    <div class="flex min-w-0 flex-1 gap-3 sm:gap-4">
                        <span class="grid h-9 w-9 flex-none place-items-center self-start rounded-xl ring-1 ring-inset sm:h-11 sm:w-11 sm:rounded-2xl"
                              style="background:color-mix(in srgb, var(--theme-primary,#111827) 10%, white); color:var(--theme-primary,#111827); box-shadow:0 0 0 1px color-mix(in srgb, var(--theme-primary,#111827) 14%, transparent);"
                              aria-hidden="true">
                            <svg width="18" height="18" class="sm:h-[21px] sm:w-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5Z"/><path d="M8.5 8.5h.01M16 15.5h.01M11 13h.01M14.5 10h.01"/></svg>
                        </span>

                        <div class="min-w-0">
                            <p class="text-[14px] font-semibold tracking-tight text-neutral-900 sm:text-[15px]">{{ $cTitle }}</p>
                            <p class="cookie-bar-copy mt-1 max-w-2xl text-[12.5px] leading-relaxed text-neutral-500 sm:mt-1.5 sm:text-[13px]">
                                {{ $cMessage }}
                                @if ($cPolicyUrl !== '')
                                    <a href="{{ $cPolicyUrl }}" class="ml-1 font-semibold underline underline-offset-2 transition hover:opacity-70" style="color:var(--theme-primary, #111827);">{{ $cPolicyTxt }}</a>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-shrink-0 items-center gap-2.5 sm:gap-3">
                        @if ($cShowDecline)
                            <button type="button" data-cookie-decline
                                    class="{{ $btnStretch }} inline-flex flex-1 items-center justify-center rounded-full border border-neutral-300 bg-white px-6 py-2.5 text-[13px] font-semibold text-neutral-700 transition hover:border-neutral-400 hover:bg-neutral-50 active:scale-[0.99] sm:flex-none">
                                {{ $cDecline }}
                            </button>
                        @endif
                        <button type="button" data-cookie-accept
                                class="{{ $btnStretch }} inline-flex flex-1 items-center justify-center rounded-full px-7 py-2.5 text-[13px] font-bold text-white shadow-sm transition active:scale-[0.99] sm:flex-none"
                                style="background:var(--theme-primary, #111827); color:var(--theme-btn-text, #fff); box-shadow:0 6px 16px -6px color-mix(in srgb, var(--theme-primary,#111827) 55%, transparent);"
                                onmouseover="this.style.background='var(--theme-primary-hover, #000)'"
                                onmouseout="this.style.background='var(--theme-primary, #111827)'">
                            {{ $cAccept }}
                        </button>
                    </div>
                </div>
            @else
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="grid h-8 w-8 flex-none place-items-center rounded-full" style="background:color-mix(in srgb, var(--theme-primary, #111827) 12%, white); color:var(--theme-primary, #111827);" aria-hidden="true">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5Z"/><path d="M8.5 8.5h.01M16 15.5h.01M11 13h.01M14.5 10h.01"/></svg>
                        </span>
                        <p class="text-[15px] font-semibold text-neutral-900">{{ $cTitle }}</p>
                    </div>
                    <p class="mt-2 text-[13px] leading-relaxed text-neutral-600">
                        {{ $cMessage }}
                        @if ($cPolicyUrl !== '')
                            <a href="{{ $cPolicyUrl }}" class="font-semibold underline underline-offset-2 hover:opacity-70" style="color:var(--theme-primary, #111827);">{{ $cPolicyTxt }}</a>
                        @endif
                    </p>
                </div>

                <div class="mt-5 flex items-center gap-2.5">
                    @if ($cShowDecline)
                        <button type="button" data-cookie-decline
                                class="{{ $btnStretch }} inline-flex items-center justify-center rounded-full border border-neutral-300 px-6 py-2.5 text-[13px] font-semibold text-neutral-700 transition hover:bg-neutral-100">
                            {{ $cDecline }}
                        </button>
                    @endif
                    <button type="button" data-cookie-accept
                            class="{{ $btnStretch }} inline-flex items-center justify-center rounded-full px-6 py-2.5 text-[13px] font-bold text-white shadow-sm transition active:scale-[0.99]"
                            style="background:var(--theme-primary, #111827); color:var(--theme-btn-text, #fff);"
                            onmouseover="this.style.background='var(--theme-primary-hover, #000)'"
                            onmouseout="this.style.background='var(--theme-primary, #111827)'">
                        {{ $cAccept }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
    (function () {
        var KEY = 'cookie_consent';
        function hasCookie(name) {
            return document.cookie.split('; ').some(function (row) { return row.indexOf(name + '=') === 0; });
        }
        try {
            if (hasCookie(KEY) || localStorage.getItem('mono-' + KEY)) return;
        } catch (e) {}
        var el = document.getElementById('cookie-consent');
        if (!el) return;
        function decide(value) {
            try { document.cookie = KEY + '=' + value + '; path=/; max-age=' + (180 * 24 * 3600) + '; SameSite=Lax'; } catch (e) {}
            try { localStorage.setItem('mono-' + KEY, value); } catch (e) {}
            el.style.display = 'none';
            document.dispatchEvent(new CustomEvent('cookie-consent-change', { detail: { consent: value } }));
        }
        el.querySelectorAll('[data-cookie-accept]').forEach(function (b) { b.addEventListener('click', function () { decide('accepted'); }); });
        el.querySelectorAll('[data-cookie-decline]').forEach(function (b) { b.addEventListener('click', function () { decide('declined'); }); });
        window.setTimeout(function () { el.style.display = ''; }, 600);
    })();
    </script>
@endif
