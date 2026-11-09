@props([
    'name' => 'image',
    'value' => null,
    'label' => null,
    'hint' => null,
    'shape' => 'square',      // 'square' | 'circle' | 'wide'
    'defaultUrl' => null,     // fallback image URL when value is empty
    'buttonText' => null,     // custom button text (defaults to 'Choose from Media')
    'required' => false,
])

@php
    $resolvedUrl = \App\Support\PublicMedia::url($value, $defaultUrl);
    $hasImage = ! empty($resolvedUrl);
    $boxClasses = match ($shape) {
        'circle' => 'w-14 h-14 rounded-full border border-neutral-300 dark:border-neutral-800 overflow-hidden bg-neutral-100 dark:bg-neutral-900 shrink-0 flex items-center justify-center',
        'wide'   => 'w-28 h-16 border border-neutral-300 dark:border-neutral-800 overflow-hidden bg-neutral-50 dark:bg-neutral-900 shrink-0 flex items-center justify-center p-1',
        default  => 'w-14 h-14 border border-neutral-300 dark:border-neutral-800 overflow-hidden bg-neutral-50 dark:bg-neutral-900 shrink-0 flex items-center justify-center p-1',
    };
    $imgClasses = match ($shape) {
        'circle' => 'w-full h-full object-cover',
        'wide'   => 'max-w-full max-h-full object-contain',
        default  => 'max-w-full max-h-full object-contain',
    };
@endphp

<div class="space-y-1.5" data-media-selector data-default-url="{{ $defaultUrl ?? '' }}">
    @if ($label)
        <label class="text-xs font-semibold text-[#191919] dark:text-neutral-300 block">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="flex items-center gap-3">
        <!-- Preview Box -->
        <div class="{{ $boxClasses }}" data-media-box>
            <img data-media-img
                 src="{{ $resolvedUrl }}"
                 alt="Preview"
                 class="{{ $imgClasses }} {{ $hasImage ? '' : 'hidden' }}">

            <div data-media-placeholder class="{{ $hasImage ? 'hidden' : 'flex' }} items-center justify-center text-slate-300 dark:text-neutral-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="m21 15-5-5L5 21"/>
                </svg>
            </div>
        </div>

        <!-- Controls -->
        <div class="flex-1 space-y-1">
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" data-media-input>

            <div class="flex items-center gap-2">
                <button type="button"
                        data-media-pick
                        class="action-btn action-btn-primary inline-flex items-center gap-1.5 h-9 px-4 text-[11px] font-semibold uppercase tracking-[0.12em] bg-[#344c3d] hover:bg-[#283a2f] text-white transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                    <span>{{ $buttonText ?? __('admin.media.choose') }}</span>
                </button>

                <button type="button"
                        data-media-remove
                        class="text-xs text-red-600 dark:text-red-400 hover:underline cursor-pointer {{ ($value || ($defaultUrl && $value)) ? '' : 'hidden' }}">
                    Remove
                </button>
            </div>

            @if ($hint)
                <p class="text-[11px] text-gray-400">{{ $hint }}</p>
            @endif
        </div>
    </div>

    @error($name)
        <p class="text-[11px] text-red-500">{{ $message }}</p>
    @enderror
</div>

<script>
if (!window.__mediaSelectorInitialized) {
    window.__mediaSelectorInitialized = true;

    document.addEventListener('click', function (e) {
        var pickBtn = e.target.closest('[data-media-pick]');
        if (pickBtn) {
            var container = pickBtn.closest('[data-media-selector]');
            if (!container || !window.mediaPicker) return;

            window.mediaPicker.open({
                multiple: false,
                onPick: function (items) {
                    if (!items || !items.length) return;
                    var item = items[0];
                    var input = container.querySelector('[data-media-input]');
                    var img = container.querySelector('[data-media-img]');
                    var placeholder = container.querySelector('[data-media-placeholder]');
                    var removeBtn = container.querySelector('[data-media-remove]');

                    if (input) input.value = item.path;
                    if (img) {
                        img.src = item.url;
                        img.classList.remove('hidden');
                    }
                    if (placeholder) placeholder.classList.add('hidden');
                    if (removeBtn) removeBtn.classList.remove('hidden');
                }
            });
            return;
        }

        var removeBtn = e.target.closest('[data-media-remove]');
        if (removeBtn) {
            var container = removeBtn.closest('[data-media-selector]');
            if (!container) return;

            var input = container.querySelector('[data-media-input]');
            var img = container.querySelector('[data-media-img]');
            var placeholder = container.querySelector('[data-media-placeholder]');
            var defaultUrl = container.getAttribute('data-default-url') || '';

            if (input) input.value = '';

            if (defaultUrl) {
                if (img) {
                    img.src = defaultUrl;
                    img.classList.remove('hidden');
                }
                if (placeholder) placeholder.classList.add('hidden');
            } else {
                if (img) {
                    img.src = '';
                    img.classList.add('hidden');
                }
                if (placeholder) placeholder.classList.remove('hidden');
            }

            removeBtn.classList.add('hidden');
        }
    });
}
</script>
