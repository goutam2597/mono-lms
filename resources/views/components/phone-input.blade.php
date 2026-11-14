{{--
    Reusable phone input (<x-phone-input>).

    Renders a dial-code aware phone field that always submits the canonical
    E.164 value under the given field name:

      - Countries come from PhoneNumberService::activeCountries() (the
        countries table + Locations settings page stay authoritative; nothing
        is hardcoded here).
      - The country control reuses the platform <x-select> mono-select widget
        (search, keyboard support). The collapsed trigger shows only the dial
        code ("+880"); the panel rows are searchable by country name or code.
      - One active country  => locked dial prefix, no selector.
      - Several countries   => the parsed/old country, else the first
        active one is preselected.
      - The visible national input carries NO name; a hidden input named
        `$name` holds the canonical E.164 value, and `{ $name }_country`
        carries the ISO-2 code of the selected country.
      - Without any active dial-code country the field degrades to a plain
        input so forms keep working (fresh installs before seeding).
      - Client-side checks are UX only; the server stays authoritative.

    Props: name (required), value, country, required, label, hint, id,
           inputClass, triggerClass, wrapperClass, placeholder, autocomplete.
--}}

@props([
    'name' => 'phone',
    'value' => null,
    'country' => null,
    'required' => false,
    'label' => null,
    'hint' => null,
    'id' => null,
    'inputClass' => 'w-full min-w-0 flex-1 bg-white border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-xs text-[#191919] placeholder-gray-400 focus:outline-none focus:border-[#738a6e]',
    'triggerClass' => 'border border-[#e7e7e7] rounded-lg px-3 py-2.5 text-xs bg-white hover:border-[#738a6e] whitespace-nowrap text-[#191919]',
    'wrapperClass' => 'w-full',
    'placeholder' => null,
    'autocomplete' => 'tel',
])

@php
    $phoneService = app(\App\Services\PhoneNumberService::class);
    $activeCountries = $phoneService->activeCountries();
    $baseId = $id ?: preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $name);
    $nationalId = $baseId.'-national';

    // Repopulation: an explicitly passed value wins, otherwise old input so
    // validation redirects restore what the user typed.
    $rawValue = old($name, $value);
    $countryOld = old($name.'_country', $country);

    $parsed = $phoneService->parseStored($rawValue, $countryOld);
    $selectedCountry = $parsed->country;

    if (! $selectedCountry && $countryOld) {
        $selectedCountry = $activeCountries->firstWhere('code', strtoupper((string) $countryOld))
            ?? $activeCountries->firstWhere('id', (int) $countryOld);
    }

    // Fall back to the first active country so the selector never sits on an
    // unusable "no country" state.
    if (! $selectedCountry) {
        $selectedCountry = $activeCountries->first();
    }

    // Visible national part: split E.164 when valid, otherwise preserve the
    // legacy/raw value visibly so nothing the user stored is hidden.
    if ($parsed->isValid()) {
        $nationalValue = $parsed->nationalNumber;
    } elseif (trim((string) $rawValue) !== '') {
        $nationalValue = trim((string) $rawValue);
    } else {
        $nationalValue = '';
    }

    $rawTrimmed = trim((string) $rawValue);
    $canonicalValue = $parsed->e164 ?? ($rawTrimmed !== '' ? $rawTrimmed : '');
    $isSingle = $activeCountries->count() === 1;
    $noCountries = $activeCountries->isEmpty();

    $countriesJson = $activeCountries->map(fn ($c) => [
        'code' => $c->code,
        'dial' => $c->phone_code,
        'placeholder' => $phoneService->placeholderFor($c),
    ])->values()->toJson();
@endphp

<div class="{{ $wrapperClass }}" data-phone-input data-single="{{ $isSingle ? '1' : '0' }}"
     data-countries='{!! e($countriesJson) !!}'>
    @if ($label)
        <label for="{{ $nationalId }}" class="text-xs font-semibold text-[#191919] block mb-1.5">{{ $label }}@if ($required) <span class="text-[#e70d0d]">*</span>@endif</label>
    @endif

    @if ($noCountries)
        {{-- No active dial-code countries: degrade to the plain field so the form keeps working. --}}
        <input id="{{ $nationalId }}" name="{{ $name }}" type="tel" value="{{ $rawValue }}"
               @if ($required) required @endif autocomplete="{{ $autocomplete }}"
               placeholder="{{ $placeholder }}"
               class="{{ $inputClass }}" data-phone-national>
    @else
        <div class="flex w-full items-stretch gap-2">
            @if ($isSingle)
                <span data-phone-prefix class="flex flex-none items-center rounded-lg border border-[#e7e7e7] bg-[#f8fafb] px-3 text-xs text-[#6d6d6d]">{{ $activeCountries->first()->phone_code }}</span>
            @else
                <div class="flex-none">
                    <x-select
                        name="{{ $name }}_country"
                        :options="$activeCountries->mapWithKeys(fn ($c) => [$c->code => $c->phone_code.' — '.$c->name])->all()"
                        :selected="$selectedCountry?->code"
                        :searchable="true"
                        :triggerClass="$triggerClass"
                    />
                </div>
            @endif
            <input id="{{ $nationalId }}" type="tel" value="{{ $nationalValue }}" inputmode="tel"
                   @if ($required) required @endif autocomplete="{{ $autocomplete }}"
                   placeholder="{{ $placeholder }}"
                   class="{{ $inputClass }}" data-phone-national>
        </div>
        {{-- Canonical E.164 actually submitted under the existing field name (sole owner of `name=`). --}}
        <input type="hidden" id="{{ $baseId }}" name="{{ $name }}" value="{{ $canonicalValue }}" data-phone-canonical>
    @endif

    @if ($hint)
        <p class="mt-1 text-[11px] text-[#9a9a9a]" data-phone-hint>{{ $hint }}</p>
    @endif
    @isset($errors)
        <p class="mt-1 hidden text-[11px] text-red-500" data-phone-error></p>
        @error($name)<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
    @endisset
</div>

@once
<script>
(function () {
    function phoneCountries(root) {
        try { return JSON.parse(root.getAttribute('data-countries') || '[]'); }
        catch (e) { return []; }
    }

    function findCountry(countries, code) {
        for (var i = 0; i < countries.length; i++) {
            if (countries[i].code === code) return countries[i];
        }
        return null;
    }

    function showError(root, msg) {
        var err = root.querySelector('[data-phone-error]');
        if (!err) return;
        if (msg) { err.textContent = msg; err.classList.remove('hidden'); }
        else { err.textContent = ''; err.classList.add('hidden'); }
    }

    /** Keep the placeholder in step with the selected country. */
    function refresh(root) {
        var national = root.querySelector('[data-phone-national]');
        var box = root.querySelector('[data-mono-select]');
        if (!national || national.value) return;

        var code = box ? box.querySelector('[data-ms-input]').value : '';
        var single = root.getAttribute('data-single') === '1';
        var countries = phoneCountries(root);
        var picked = single ? countries[0] : findCountry(countries, code);
        if (picked) national.setAttribute('placeholder', picked.placeholder);
    }

    function setup(root) {
        if (root.__phoneInputSync) return;
        var national = root.querySelector('[data-phone-national]');
        var hidden = root.querySelector('[data-phone-canonical]');
        var box = root.querySelector('[data-mono-select]');
        var single = root.getAttribute('data-single') === '1';
        var countries = phoneCountries(root);

        function dialFor(code) {
            var c = findCountry(countries, code);
            if (c) return c.dial;
            return single && countries.length ? countries[0].dial : '';
        }

        function sync(fromUser) {
            if (!national || !hidden) return; // degraded plain-field mode
            var digits = (national.value || '').replace(/\D+/g, '');
            if (!digits) {
                hidden.value = '';
                showError(root, '');
                return;
            }
            var code = box ? box.querySelector('[data-ms-input]').value : '';
            var dial = dialFor(code);
            if (!dial) {
                // No country picked yet: keep the raw digits visible; submit
                // them so the server can report the authoritative error.
                hidden.value = digits;
                if (fromUser) showError(root, 'Please select a country code.');
                return;
            }
            var dialDigits = String(dial).replace(/\D+/g, '');
            if (digits.indexOf(dialDigits) === 0) digits = digits.slice(dialDigits.length);
            digits = digits.replace(/^0+/, '');
            var e164 = '+' + dialDigits + digits;
            if (digits.length < 6 || digits.length > 14) {
                hidden.value = e164;
                if (fromUser) showError(root, 'Please enter a valid phone number.');
                return;
            }
            showError(root, '');
            hidden.value = e164;
        }

        if (national) national.addEventListener('input', function () { sync(true); });
        if (box) box.addEventListener('change', function () { refresh(root); sync(true); });

        var form = root.closest('form');
        if (form && !form.__phoneInputBound) {
            form.__phoneInputBound = true;
            form.addEventListener('submit', function () {
                form.querySelectorAll('[data-phone-input]').forEach(function (r) {
                    if (r.__phoneInputSync) r.__phoneInputSync(false);
                });
            });
        }

        root.__phoneInputSync = sync;
        refresh(root);
        sync(false);
    }

    document.querySelectorAll('[data-phone-input]').forEach(setup);
})();
</script>
@endonce
