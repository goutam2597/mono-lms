<x-layouts.dashboard title="Cookie Consent - Mono LMS" active="cookie_settings" userType="admin">

@php
    $cookieOn       = old('cookie_consent_enabled', $settings['cookie_consent_enabled'] ?? '0') === '1';
    $cookieDecline  = old('cookie_consent_show_decline', $settings['cookie_consent_show_decline'] ?? '1') === '1';
    $cookieLayout   = old('cookie_consent_layout', $settings['cookie_consent_layout'] ?? 'bar');
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl font-medium tracking-tight text-neutral-900 dark:text-white">Cookie Consent</h1>
                <span id="cookie-status-badge"
                      class="inline-flex items-center gap-2 border px-3 py-1.5 text-[11px] font-medium uppercase tracking-[0.14em] transition-colors
                             {{ $cookieOn ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300' : 'border-neutral-200 bg-neutral-100 text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $cookieOn ? 'bg-emerald-500' : 'bg-neutral-400' }}"></span>
                    <span id="cookie-status-text">{{ $cookieOn ? __('admin.cookie.status_on') : __('admin.cookie.status_off') }}</span>
                </span>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                {{ __('admin.cookie.sub') }}
            </p>
        </div>
        <button type="submit" form="cookie-form" class="action-btn">
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

    <form method="POST" action="{{ route('admin.settings.cookie.update') }}" id="cookie-form" class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px] items-start">
        @csrf
        @method('PUT')

        {{-- Left column: the settings --}}
        <div class="space-y-6">

            {{-- Enable --}}
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.cookie.status_heading') }}</h2>
                <label class="flex cursor-pointer items-start gap-3 border border-neutral-300 dark:border-neutral-700 p-4 rounded transition-colors has-[:checked]:border-neutral-900 has-[:checked]:bg-neutral-50 dark:has-[:checked]:bg-[#161616] dark:has-[:checked]:border-neutral-500">
                    <input type="hidden" name="cookie_consent_enabled" value="0">
                    <input type="checkbox" id="cookie_consent_enabled" name="cookie_consent_enabled" value="1" class="control mt-0.5" @checked($cookieOn)>
                    <span class="text-sm leading-relaxed text-neutral-700 dark:text-neutral-300">
                        <span class="font-medium">{{ __('admin.cookie.enabled') }}</span><br>
                        <span class="text-[12px] text-neutral-500 dark:text-neutral-400">{{ __('admin.cookie.enabled_hint') }}</span>
                    </span>
                </label>
                <p class="text-[11px] leading-relaxed text-neutral-400 dark:text-neutral-500">{{ __('admin.cookie.saved_note') }}</p>
            </div>

            {{-- Display --}}
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.cookie.display_heading') }}</h2>

                <div>
                    <label for="cookie_consent_layout" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.cookie.layout') }}</label>
                    <select id="cookie_consent_layout" name="cookie_consent_layout"
                            class="text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                        @foreach (['bar' => __('admin.cookie.layout_bar'), 'card' => __('admin.cookie.layout_card'), 'modal' => __('admin.cookie.layout_modal')] as $val => $label)
                            <option value="{{ $val }}" @selected($cookieLayout === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-[11px] text-neutral-400 dark:text-neutral-500">{{ __('admin.cookie.layout_hint') }}</p>
                </div>

                <label class="flex cursor-pointer items-start gap-3 border border-neutral-300 dark:border-neutral-700 p-4 rounded transition-colors has-[:checked]:border-neutral-900 has-[:checked]:bg-neutral-50 dark:has-[:checked]:bg-[#161616] dark:has-[:checked]:border-neutral-500">
                    <input type="hidden" name="cookie_consent_show_decline" value="0">
                    <input type="checkbox" id="cookie_consent_show_decline" name="cookie_consent_show_decline" value="1" class="control mt-0.5" @checked($cookieDecline)>
                    <span class="text-sm leading-relaxed text-neutral-700 dark:text-neutral-300">
                        <span class="font-medium">{{ __('admin.cookie.show_decline') }}</span><br>
                        <span class="text-[12px] text-neutral-500 dark:text-neutral-400">{{ __('admin.cookie.show_decline_hint') }}</span>
                    </span>
                </label>
            </div>

            {{-- Content --}}
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.cookie.content_heading') }}</h2>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('admin.cookie.content_sub') }}</p>

                <div>
                    <div class="mb-1.5 flex items-baseline justify-between">
                        <label for="cookie_consent_title" class="block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.cookie.title') }}</label>
                        <span class="text-[10px] text-neutral-300 dark:text-neutral-600" data-counter-for="cookie_consent_title" data-max="120"></span>
                    </div>
                    <input id="cookie_consent_title" name="cookie_consent_title" type="text" maxlength="120"
                           value="{{ old('cookie_consent_title', $settings['cookie_consent_title'] ?? '') }}"
                           placeholder="{{ __('admin.cookie.title_ph') }}"
                           class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                </div>

                <div>
                    <div class="mb-1.5 flex items-baseline justify-between">
                        <label for="cookie_consent_message" class="block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.cookie.message') }}</label>
                        <span class="text-[10px] text-neutral-300 dark:text-neutral-600" data-counter-for="cookie_consent_message" data-max="600"></span>
                    </div>
                    <textarea id="cookie_consent_message" name="cookie_consent_message" rows="3" maxlength="600"
                              placeholder="{{ __('admin.cookie.message_ph') }}"
                              class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">{{ old('cookie_consent_message', $settings['cookie_consent_message'] ?? '') }}</textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="cookie_consent_accept_text" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.cookie.accept_text') }}</label>
                        <input id="cookie_consent_accept_text" name="cookie_consent_accept_text" type="text" maxlength="60"
                               value="{{ old('cookie_consent_accept_text', $settings['cookie_consent_accept_text'] ?? '') }}"
                               placeholder="{{ __('admin.cookie.accept_ph') }}"
                               class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                    </div>
                    <div>
                        <label for="cookie_consent_decline_text" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.cookie.decline_text') }}</label>
                        <input id="cookie_consent_decline_text" name="cookie_consent_decline_text" type="text" maxlength="60"
                               value="{{ old('cookie_consent_decline_text', $settings['cookie_consent_decline_text'] ?? '') }}"
                               placeholder="{{ __('admin.cookie.decline_ph') }}"
                               class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                    </div>
                    <div>
                        <label for="cookie_consent_policy_text" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.cookie.policy_text') }}</label>
                        <input id="cookie_consent_policy_text" name="cookie_consent_policy_text" type="text" maxlength="60"
                               value="{{ old('cookie_consent_policy_text', $settings['cookie_consent_policy_text'] ?? '') }}"
                               placeholder="{{ __('admin.cookie.policy_ph') }}"
                               class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                    </div>
                    <div>
                        <label for="cookie_consent_policy_url" class="mb-1.5 block text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ __('admin.cookie.policy_url') }}</label>
                        <input id="cookie_consent_policy_url" name="cookie_consent_policy_url" type="text" maxlength="255"
                               value="{{ old('cookie_consent_policy_url', $settings['cookie_consent_policy_url'] ?? '') }}"
                               placeholder="{{ __('admin.cookie.policy_url_ph') }}"
                               class="w-full text-xs p-2.5 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-[#161616] text-neutral-900 dark:text-white rounded focus:border-neutral-900 dark:focus:border-white focus:outline-none">
                        <p class="mt-1.5 text-[11px] text-neutral-400 dark:text-neutral-500">{{ __('admin.cookie.policy_url_hint') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column: live preview --}}
        <div class="xl:sticky xl:top-6 xl:self-start space-y-6">
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#111111] p-6 space-y-4">
                <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-900 dark:text-white border-b border-neutral-100 dark:border-neutral-800 pb-3">{{ __('admin.cookie.preview_heading') }}</h2>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('admin.cookie.preview_hint') }}</p>

                {{-- Mini browser frame --}}
                <div class="overflow-hidden border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-[#0a0a0a] rounded">
                    <div class="flex items-center gap-1.5 border-b border-neutral-200 dark:border-neutral-700 bg-white dark:bg-[#161616] px-3 py-2">
                        <span class="h-2 w-2 rounded-full bg-neutral-300 dark:bg-neutral-600"></span>
                        <span class="h-2 w-2 rounded-full bg-neutral-300 dark:bg-neutral-600"></span>
                        <span class="h-2 w-2 rounded-full bg-neutral-300 dark:bg-neutral-600"></span>
                        <span class="ms-2 truncate rounded bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 text-[9px] text-neutral-400">{{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'mono-lms.test' }}</span>
                    </div>

                    <div id="cookie-preview-stage" class="relative h-72 overflow-hidden bg-white dark:bg-[#111111]">
                        {{-- Faux page content so the banner has something to sit over --}}
                        <div class="space-y-2 p-4 opacity-40" aria-hidden="true">
                            <div class="h-3 w-2/3 rounded bg-neutral-200 dark:bg-neutral-700"></div>
                            <div class="h-2.5 w-full rounded bg-neutral-100 dark:bg-neutral-800"></div>
                            <div class="h-2.5 w-5/6 rounded bg-neutral-100 dark:bg-neutral-800"></div>
                            <div class="grid grid-cols-3 gap-2 pt-2">
                                <div class="h-16 rounded bg-neutral-100 dark:bg-neutral-800"></div>
                                <div class="h-16 rounded bg-neutral-100 dark:bg-neutral-800"></div>
                                <div class="h-16 rounded bg-neutral-100 dark:bg-neutral-800"></div>
                            </div>
                        </div>

                        <div id="cookie-preview-off" class="absolute inset-0 z-10 hidden place-items-center bg-white/90 dark:bg-[#111111]/90 p-6 text-center">
                            <p class="text-[12px] leading-relaxed text-neutral-400 dark:text-neutral-500">{{ __('admin.cookie.preview_disabled') }}</p>
                        </div>

                        {{-- The live banner previews --}}
                        <div id="cookie-preview-modal-wrap" class="hidden absolute inset-0 z-10 items-center justify-center bg-neutral-950/40 p-3">
                            <div id="cookie-preview-modal" class="w-full max-w-[240px] rounded-xl border border-neutral-200 bg-white p-3.5 shadow-lg">
                                <div class="flex items-center gap-2">
                                    <span class="grid h-6 w-6 flex-none place-items-center rounded-full bg-neutral-100" aria-hidden="true">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5Z"/><path d="M8.5 8.5h.01M16 15.5h.01M11 13h.01M14.5 10h.01"/></svg>
                                    </span>
                                    <p data-pv="title" class="truncate text-[12px] font-bold text-neutral-900"></p>
                                </div>
                                <p class="mt-1.5 text-[10.5px] leading-relaxed text-neutral-500"><span data-pv="message"></span><span data-pv="policy" class="font-semibold text-neutral-800"></span></p>
                                <div class="mt-3 flex items-center gap-1.5">
                                    <button type="button" tabindex="-1" data-pv="decline" class="rounded-full border border-neutral-300 bg-white px-3 py-1.5 text-[10.5px] font-semibold text-neutral-700"></button>
                                    <button type="button" tabindex="-1" data-pv="accept" class="flex-1 rounded-full bg-neutral-900 px-3 py-1.5 text-[10.5px] font-bold text-white"></button>
                                </div>
                            </div>
                        </div>

                        <div id="cookie-preview-card" class="hidden absolute bottom-3 left-3 right-3 rounded-xl border border-neutral-200 bg-white p-3.5 shadow-lg">
                            <div class="flex items-center gap-2">
                                <span class="grid h-6 w-6 flex-none place-items-center rounded-full bg-neutral-100" aria-hidden="true">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5Z"/><path d="M8.5 8.5h.01M16 15.5h.01M11 13h.01M14.5 10h.01"/></svg>
                                </span>
                                <p data-pv="title" class="truncate text-[12px] font-bold text-neutral-900"></p>
                            </div>
                            <p class="mt-1.5 text-[10.5px] leading-relaxed text-neutral-500"><span data-pv="message"></span><span data-pv="policy" class="font-semibold text-neutral-800"></span></p>
                            <div class="mt-3 flex items-center gap-1.5">
                                <button type="button" tabindex="-1" data-pv="decline" class="rounded-full border border-neutral-300 bg-white px-3 py-1.5 text-[10.5px] font-semibold text-neutral-700"></button>
                                <button type="button" tabindex="-1" data-pv="accept" class="flex-1 rounded-full bg-neutral-900 px-3 py-1.5 text-[10.5px] font-bold text-white"></button>
                            </div>
                        </div>

                        <div id="cookie-preview-bar" class="hidden absolute inset-x-0 bottom-0 border-t border-neutral-200 bg-white px-3 py-2.5 shadow-[0_-8px_20px_rgba(0,0,0,0.08)]">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-1.5">
                                    <span class="grid h-5 w-5 flex-none place-items-center rounded-full bg-neutral-100" aria-hidden="true">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5Z"/><path d="M8.5 8.5h.01M16 15.5h.01M11 13h.01M14.5 10h.01"/></svg>
                                    </span>
                                    <p data-pv="title" class="truncate text-[11px] font-bold text-neutral-900"></p>
                                </div>
                                <p class="text-[10px] leading-relaxed text-neutral-500"><span data-pv="message"></span><span data-pv="policy" class="font-semibold text-neutral-800"></span></p>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" tabindex="-1" data-pv="decline" class="flex-1 rounded-full border border-neutral-300 bg-white px-2.5 py-1.5 text-[10px] font-semibold text-neutral-700"></button>
                                    <button type="button" tabindex="-1" data-pv="accept" class="flex-1 rounded-full bg-neutral-900 px-2.5 py-1.5 text-[10px] font-bold text-white"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-[11px] text-neutral-400 dark:text-neutral-500">{{ __('admin.cookie.preview_empty') }}</p>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    var DEFAULTS = {
        title:   @json(__('app.cookie.title')),
        message: @json(__('app.cookie.message')),
        accept:  @json(__('app.cookie.accept')),
        decline: @json(__('app.cookie.decline')),
        policy:  @json(__('app.cookie.learn_more'))
    };
    var ON_LABEL  = @json(__('admin.cookie.status_on'));
    var OFF_LABEL = @json(__('admin.cookie.status_off'));

    var fEnabled  = document.getElementById('cookie_consent_enabled');
    var fDecline  = document.getElementById('cookie_consent_show_decline');
    var fLayout   = document.getElementById('cookie_consent_layout');
    var fTitle    = document.getElementById('cookie_consent_title');
    var fMessage  = document.getElementById('cookie_consent_message');
    var fAccept   = document.getElementById('cookie_consent_accept_text');
    var fDeclineT = document.getElementById('cookie_consent_decline_text');
    var fPolicy   = document.getElementById('cookie_consent_policy_text');
    var fPolicyU  = document.getElementById('cookie_consent_policy_url');
    var stage     = document.getElementById('cookie-preview-stage');
    var offEl     = document.getElementById('cookie-preview-off');
    var badge     = document.getElementById('cookie-status-badge');
    var badgeText = document.getElementById('cookie-status-text');
    if (!stage || !fEnabled) return;

    var LAYOUTS = ['bar', 'card', 'modal'];
    function val(input, fallback) { return (input && input.value.trim() !== '') ? input.value.trim() : fallback; }

    function paint() {
        var on = fEnabled.checked;
        var layout = LAYOUTS.indexOf(fLayout && fLayout.value) !== -1 ? fLayout.value : 'bar';
        var showDecline = !!(fDecline && fDecline.checked);

        // Status badge
        badgeText.textContent = on ? ON_LABEL : OFF_LABEL;
        badge.className = 'inline-flex items-center gap-2 border px-3 py-1.5 text-[11px] font-medium uppercase tracking-[0.14em] transition-colors '
            + (on ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300' : 'border-neutral-200 bg-neutral-100 text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400');
        badge.querySelector('span').className = 'h-1.5 w-1.5 rounded-full ' + (on ? 'bg-emerald-500' : 'bg-neutral-400');

        // Off overlay + layout visibility
        offEl.classList.toggle('hidden', on);
        offEl.classList.toggle('grid', !on);
        LAYOUTS.forEach(function (name) {
            var el = document.getElementById('cookie-preview-' + name);
            if (!el) return;
            var show = on && name === layout;
            el.classList.toggle('hidden', !show);
            el.classList.toggle('flex', show && name === 'modal');
            el.classList.toggle('items-center', show && name === 'modal');
            el.classList.toggle('justify-center', show && name === 'modal');
        });

        // Copy
        stage.querySelectorAll('[data-pv="title"]').forEach(function (el) { el.textContent = val(fTitle, DEFAULTS.title); });
        stage.querySelectorAll('[data-pv="message"]').forEach(function (el) { el.textContent = val(fMessage, DEFAULTS.message); });
        stage.querySelectorAll('[data-pv="accept"]').forEach(function (el) { el.textContent = val(fAccept, DEFAULTS.accept); });
        stage.querySelectorAll('[data-pv="decline"]').forEach(function (el) {
            el.textContent = val(fDeclineT, DEFAULTS.decline);
            el.classList.toggle('hidden', !showDecline);
        });
        stage.querySelectorAll('[data-pv="policy"]').forEach(function (el) {
            var hasUrl = fPolicyU && fPolicyU.value.trim() !== '';
            el.textContent = hasUrl ? val(fPolicy, DEFAULTS.policy) : '';
        });
    }

    // Character counters
    document.querySelectorAll('[data-counter-for]').forEach(function (out) {
        var input = document.getElementById(out.getAttribute('data-counter-for'));
        if (!input) return;
        var max = out.getAttribute('data-max');
        var update = function () {
            out.textContent = input.value.length + ' / ' + max;
            out.className = 'text-[10px] ' + (input.value.length >= max ? 'text-amber-600' : 'text-neutral-300');
        };
        input.addEventListener('input', update);
        update();
    });

    [fEnabled, fDecline, fLayout, fTitle, fMessage, fAccept, fDeclineT, fPolicy, fPolicyU].forEach(function (el) {
        if (!el) return;
        el.addEventListener('input', paint);
        el.addEventListener('change', paint);
    });

    paint();
})();
</script>

</x-layouts.dashboard>
