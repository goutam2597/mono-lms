@props([
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => '',
    'searchable' => 'auto',      // true | false | 'auto' (search only above the threshold)
    'searchThreshold' => 10,     // with 'auto', search appears above this many options
    'align' => 'left',
    'triggerClass' => 'w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm bg-white hover:border-[#ff4667]',
])

@php
    $uid = 'ms-'.str_replace('.', '-', uniqid('', true));
    $normalized = collect($options)
        ->map(fn ($label, $key) => is_array($label)
            ? ['value' => (string) $label['value'], 'label' => (string) $label['label']]
            : ['value' => (string) $key, 'label' => (string) $label])
        ->values();
    $currentLabel = $normalized->firstWhere('value', (string) $selected)['label'] ?? null;
    // Search is only worth showing for long lists.
    $showSearch = $searchable === true || ($searchable === 'auto' && $normalized->count() > $searchThreshold);
@endphp

<div class="mono-select relative" data-mono-select>
    <input type="hidden" name="{{ $name }}" value="{{ $selected }}" data-ms-input>

    <button type="button" data-ms-trigger
        class="flex items-center justify-between gap-2 {{ $triggerClass }} text-start cursor-pointer">
        <span data-ms-label class="truncate {{ $currentLabel ? 'text-[#191919]' : 'text-gray-400' }}">
            {{ $currentLabel ?? $placeholder }}
        </span>
        <svg data-ms-chevron class="w-4 h-4 text-gray-400 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div data-ms-panel class="hidden absolute {{ $align === 'right' ? 'right-0' : 'left-0' }} top-full mt-2 min-w-full w-max max-w-xs bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-[60]">
        @if ($showSearch)
            <div class="px-3 pb-2">
                <input type="text" data-ms-search placeholder="{{ __('app.common.search') }}..." autocomplete="off"
                    class="w-full bg-[#f8fafb] border border-[#e7e7e7] rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#ff4667]">
            </div>
        @endif

        <div data-ms-options class="max-h-56 overflow-y-auto">
            @foreach ($normalized as $option)
                <button type="button" data-ms-option value="{{ $option['value'] }}" data-label="{{ mb_strtolower($option['label']) }}"
                    class="w-full text-start px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667] transition-colors whitespace-nowrap {{ $option['value'] === (string) $selected ? 'text-[#ff4667] font-semibold' : 'text-[#191919]' }}">
                    <span data-ms-option-label class="block truncate">{{ $option['label'] }}</span>
                </button>
            @endforeach

            <p data-ms-empty class="hidden px-4 py-3 text-xs text-gray-400 text-center whitespace-nowrap">{{ __('app.common.no_matches') }}</p>
        </div>
    </div>
</div>
