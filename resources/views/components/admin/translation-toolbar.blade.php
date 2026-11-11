@props([
    'enabled' => false,
    'context',
    'container' => null,
    'source' => null,
    'targets' => [],
    'label' => __('admin.courses.translate_ai') ?? 'Translate with AI',
])

@if ($enabled)
    @php
        $sourceCode = $source ?: \App\Models\Language::defaultCode();
        $targetCodes = collect($targets)
            ->when($targets === [], fn ($codes) => \App\Models\Language::active()->pluck('code'))
            ->reject(fn ($code) => $code === $sourceCode)
            ->unique()
            ->values();
        $targetLanguages = \App\Models\Language::active()
            ->whereIn('code', $targetCodes)
            ->sortBy(fn ($language) => $targetCodes->search($language->code));
    @endphp
    <div class="flex flex-wrap items-center justify-end gap-3"
         data-ai-translator
         data-context="{{ $context }}"
         @if ($container) data-container="{{ $container }}" @endif
         data-source="{{ $sourceCode }}"
         data-targets='@json($targetCodes->all())'>
        <label class="inline-flex cursor-pointer items-center gap-2 text-[11px] text-neutral-500 hover:text-neutral-900 select-none">
            <input type="checkbox" data-translate-overwrite class="control">
            <span>{{ __('admin.languages.overwrite') }}</span>
        </label>
        <span data-translate-status aria-live="polite" class="text-[12px] text-neutral-400"></span>
        @foreach ($targetLanguages as $targetLanguage)
            <button type="button"
                    data-translate-run
                    data-translate-target="{{ $targetLanguage->code }}"
                    @if ($targetCodes->count() > 1) hidden @endif
                    class="action-btn action-btn-ghost h-9 text-[10px] tracking-[0.14em]">
                {{ $label }} · {{ $targetLanguage->native_name ?: strtoupper($targetLanguage->code) }}
            </button>
        @endforeach
        @if ($targetCodes->count() >= 1)
            <button type="button"
                    data-translate-run
                    data-translate-all
                    class="action-btn h-9 text-[10px] tracking-[0.14em]">
                {{ __('admin.languages.translate_all') }}
            </button>
        @endif
    </div>
@endif
