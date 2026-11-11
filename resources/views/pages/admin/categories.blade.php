<x-layouts.dashboard :title="__('admin.categories.title').' - Mono LMS'" active="categories" userType="admin">

    <style>
        #cat-modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); padding: 24px; box-sizing: border-box; }
        #cat-modal.open { display: flex !important; }
        #cat-modal .panel { background: #ffffff; width: 520px; max-width: 100%; max-height: calc(100vh - 48px); overflow-y: auto; padding: 24px; border-radius: 0 !important; border: 1px solid #e5e5e5; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); box-sizing: border-box; }
    </style>

    <div class="bg-white border border-[#e7e7e7] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.categories.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.categories.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if ($translatorSet ?? false)
                    <button type="button" id="bulk-translate-btn"
                            data-confirm="{{ __('admin.languages.bulk_taxonomy_confirm') ?? 'Translate missing category names across all active languages using AI?' }}"
                            data-running="{{ __('admin.languages.translating') ?? 'Translating Categories…' }}"
                            data-done="{{ __('admin.languages.bulk_taxonomy_done_alert') ?? 'Translated :count categories into :targets.' }}"
                            data-none="{{ __('admin.languages.bulk_taxonomy_none') ?? 'All categories are already translated.' }}"
                            class="action-btn action-btn-ghost h-10 px-4 text-[11px] font-medium uppercase tracking-[0.14em]">
                        {{ __('admin.languages.translate_all') ?? 'Translate All Categories' }}
                    </button>
                @endif
                <button type="button" id="btn-add-category" class="action-btn action-btn-primary h-10 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">
                    + {{ __('admin.categories.create') }}
                </button>
            </div>
        </div>

        <x-bulk-toolbar action="{{ route('admin.categories.bulk-destroy') }}" />

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="w-10 px-6 py-3">
                            <input type="checkbox" id="check-all" class="control" aria-label="{{ __('admin.common.select_all') ?? 'Select All' }}">
                        </th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.categories.icon') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.dashboard.total_courses') }}</th>
                        <th class="px-6 py-3 text-right font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-[#f8fafb] transition-colors" id="category-row-{{ $category->id }}">
                            <td class="w-10 px-6 py-3.5">
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $category->id }}" form="bulk-form" aria-label="{{ $category->trans('name') }}">
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-[#191919]">
                                <span>{{ $category->trans('name') }}</span>
                                @if ($category->trans('name') !== $category->name)
                                    <span class="text-[10px] text-gray-400 italic">({{ \Illuminate\Support\Str::limit($category->name, 25) }})</span>
                                @endif
                                @if (!empty($category->name_translations))
                                    <span class="inline-flex items-center gap-1 ml-1.5 align-middle">
                                        @foreach (array_keys($category->name_translations) as $code)
                                            <span class="px-1.5 py-0.2 text-[9px] font-mono font-semibold rounded bg-neutral-100 text-neutral-600 border border-neutral-200 uppercase">{{ $code }}</span>
                                        @endforeach
                                    </span>
                                @endif
                                <span class="text-xs text-gray-400 font-normal">({{ $category->slug }})</span>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($category->imageUrl())
                                    <img src="{{ $category->imageUrl() }}" 
                                         alt="{{ $category->trans('name') }}" 
                                         class="w-8 h-8 object-contain rounded-md bg-white border border-gray-200 p-0.5"
                                         onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                                         loading="lazy">
                                    <div class="hidden w-8 h-8 rounded-md items-center justify-center" style="background: {{ $category->color }}15; color: {{ $category->color }};">
                                        @if ($category->icon && str_starts_with($category->icon, 'M'))
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $category->icon }}" /></svg>
                                        @else
                                            <span class="text-xl">{{ $category->icon ?? '—' }}</span>
                                        @endif
                                    </div>
                                @elseif ($category->icon && str_starts_with($category->icon, 'M'))
                                    <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background: {{ $category->color }}15; color: {{ $category->color }};">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $category->icon }}" /></svg>
                                    </div>
                                @else
                                    <span class="text-xl">{{ $category->icon ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $category->courses_count }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        class="btn-edit-category action-btn action-btn-ghost h-7 px-3 text-[10px]"
                                        data-id="{{ $category->id }}"
                                        data-action="{{ route('admin.categories.update', $category) }}"
                                        data-name="{{ $category->name }}"
                                        data-translations='@json($category->name_translations ?? [])'
                                        data-color="{{ $category->color ?? '#137547' }}"
                                        data-icon="{{ $category->icon ?? '' }}"
                                        data-image="{{ $category->image ?? '' }}"
                                        data-image-url="{{ $category->imageUrl() ?? '' }}">
                                        {{ __('app.common.edit') }}
                                    </button>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
    </div>

    <!-- Category Add / Edit Modal (mono_market style) -->
    <div id="cat-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                <h3 class="text-base font-bold text-neutral-900" id="cat-modal-heading">{{ __('admin.categories.create') }}</h3>
                <button type="button" id="cat-modal-close" class="text-neutral-400 hover:text-neutral-900 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" id="cat-form" action="{{ route('admin.categories.store') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" id="cat-form-method" value="POST" disabled>

                @php
                    $languages = $languages ?? \App\Models\Language::active();
                    $defaultCode = $defaultCode ?? \App\Models\Language::defaultCode();
                    $transLangs = $languages->where('code', '!=', $defaultCode)->values();
                    $translatorSet = $translatorSet ?? (bool) app(\App\Services\AiTranslationService::class)->provider();
                    $generatorSet = $generatorSet ?? (bool) \App\Models\AiProvider::resolveConfigured('ai_generator_provider');
                    $imageSet = $imageSet ?? (bool) \App\Models\AiProvider::resolveConfigured('ai_image_provider');
                @endphp

                <!-- Language tabs + AI toolbar -->
                <div class="flex flex-wrap items-end justify-between gap-3 border-b border-neutral-200 dark:border-neutral-800">
                    <div class="lang-tabs -mb-px flex flex-wrap gap-1" role="tablist">
                        <button type="button" class="lang-tab is-active" data-lang-panel="cat-panel-default" data-translate-locale-tab="{{ $defaultCode }}" role="tab" aria-selected="true">
                            <span>{{ $languages->firstWhere('code', $defaultCode)?->native_name ?? strtoupper($defaultCode) }}</span>
                            <span class="lang-badge">{{ __('admin.courses.lang_default_badge') ?? 'Default' }}</span>
                        </button>
                        @foreach ($transLangs as $lang)
                            <button type="button" class="lang-tab" data-lang-panel="cat-panel-{{ $lang->code }}" data-translate-locale-tab="{{ $lang->code }}" role="tab" aria-selected="false">
                                <span>{{ $lang->native_name }}</span>
                            </button>
                        @endforeach
                    </div>
                    @if ($generatorSet || $translatorSet)
                        <div class="flex flex-wrap items-center gap-3 pb-2">
                            <span id="cat-generate-status" class="text-[12px] text-neutral-400"></span>
                            @if ($generatorSet)
                                <button type="button" id="cat-generate-ai" class="action-btn action-btn-ghost h-9 inline-flex items-center gap-1.5 text-[10px] tracking-[0.14em]">
                                    <svg class="w-3.5 h-3.5 text-brand" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
                                    </svg>
                                    <span>{{ __('admin.categories.generate_ai') ?? 'Generate with AI' }}</span>
                                </button>
                            @endif
                            @if ($translatorSet)
                                <x-admin.translation-toolbar :enabled="$translatorSet" context="taxonomy" container="#cat-form" :source="$defaultCode" />
                            @endif
                        </div>
                    @endif
                </div>

                @if ($generatorSet)
                    <div id="cat-generate-row" class="hidden border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/60 p-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <input type="text" id="cat-generate-prompt" maxlength="500" placeholder="{{ __('admin.categories.generate_ph') ?? 'Describe the category in a few words…' }}"
                                   class="h-9 min-w-0 basis-full flex-1 border sm:min-w-[240px] sm:basis-auto border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-sm placeholder:text-neutral-400 focus:border-neutral-900 dark:focus:border-neutral-100 focus:outline-none">
                            <button type="button" id="cat-generate-run" class="action-btn h-9 px-5 text-[10px] font-medium tracking-[0.14em]">
                                {{ __('admin.categories.generate') ?? 'Generate' }}
                            </button>
                            <span id="cat-generate-row-status" class="text-[12px] text-neutral-400"></span>
                        </div>
                    </div>
                @endif

                <!-- Default Language Panel -->
                <div id="cat-panel-default" class="lang-panel space-y-1.5" role="tabpanel">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" id="cat-name-input" required maxlength="100"
                           data-translate-key="name" data-translate-locale="{{ $defaultCode }}"
                           class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <!-- Other Language Panels -->
                @foreach ($transLangs as $lang)
                    <div id="cat-panel-{{ $lang->code }}" class="lang-panel hidden space-y-1.5" role="tabpanel" dir="{{ $lang->direction }}">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.name') }} ({{ $lang->native_name }})</label>
                        <input type="text" name="translations[{{ $lang->code }}][name]" id="cat-trans-name-{{ $lang->code }}" maxlength="100"
                               data-translate-key="name" data-translate-locale="{{ $lang->code }}"
                               class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                        <p class="text-[11px] text-neutral-400">Blank falls back to default language name.</p>
                    </div>
                @endforeach

                <!-- Category Image Media Selector + AI Button -->
                <div id="cat-media-wrapper">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600 mb-1.5">Category Image / Logo</label>
                    <x-media-selector
                        name="image"
                        shape="square"
                        hint="Used inside category cards. Leave blank to use fallback icon."
                    >
                        @if ($imageSet)
                            <button type="button" id="cat-ai-img-btn" class="action-btn h-9 px-4 inline-flex items-center gap-2 text-[11px] font-medium uppercase tracking-[0.14em] cursor-pointer">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.2 6.9L21 11.1l-6.8 2.2L12 20.2l-2.2-6.9L3 11.1l6.8-2.2L12 2z"/></svg>
                                <span>{{ __('admin.imagegen.button') ?? 'Generate with AI' }}</span>
                            </button>
                        @endif
                    </x-media-selector>
                </div>

                <!-- Accent Color -->
                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">Accent Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" id="cat-color-input" value="#137547" class="w-10 h-10 border border-neutral-300 cursor-pointer p-0.5">
                        <input type="text" id="cat-color-preview" value="#137547" readonly class="w-28 h-10 border border-neutral-300 px-3 text-xs text-neutral-600 bg-neutral-50 font-mono">
                        <span class="text-[11px] text-neutral-400">Card tint & badge highlight</span>
                    </div>
                </div>

                <!-- Fallback Icon / Emoji / SVG Path -->
                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.categories.icon') }} (Fallback Emoji or SVG Path)</label>
                    <input type="text" name="icon" id="cat-icon-input" placeholder="💻 or SVG Path (M...)" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-neutral-200">
                    <button type="button" id="cat-modal-cancel" class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.cancel') }}</button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal      = document.getElementById('cat-modal');
        var form       = document.getElementById('cat-form');
        var heading    = document.getElementById('cat-modal-heading');
        var method     = document.getElementById('cat-form-method');
        var nameIn     = document.getElementById('cat-name-input');
        var colorIn    = document.getElementById('cat-color-input');
        var colorPrev  = document.getElementById('cat-color-preview');
        var iconIn     = document.getElementById('cat-icon-input');
        var addBtn     = document.getElementById('btn-add-category');
        var closeBtn   = document.getElementById('cat-modal-close');
        var cancelBtn  = document.getElementById('cat-modal-cancel');
        var mediaWrap  = document.getElementById('cat-media-wrapper');
        var aiImgBtn   = document.getElementById('cat-ai-img-btn');
        var bulkBtn    = document.getElementById('bulk-translate-btn');
        var storeUrl   = @json(route('admin.categories.store'));

        function setMediaValue(path, url) {
            if (!mediaWrap) return;
            var input       = mediaWrap.querySelector('[data-media-input]');
            var img         = mediaWrap.querySelector('[data-media-img]');
            var placeholder = mediaWrap.querySelector('[data-media-placeholder]');
            var removeBtn   = mediaWrap.querySelector('[data-media-remove]');

            if (input) input.value = path || '';
            if (url) {
                if (img) { img.src = url; img.classList.remove('hidden'); }
                if (placeholder) placeholder.classList.add('hidden');
                if (removeBtn) removeBtn.classList.remove('hidden');
            } else {
                if (img) { img.src = ''; img.classList.add('hidden'); }
                if (placeholder) placeholder.classList.remove('hidden');
                if (removeBtn) removeBtn.classList.add('hidden');
            }
        }

        function resetTabs() {
            document.querySelectorAll('#cat-modal .lang-tab').forEach(function (b, idx) {
                var isActive = (idx === 0);
                b.classList.toggle('is-active', isActive);
                b.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
            document.querySelectorAll('#cat-modal .lang-panel').forEach(function (p, idx) {
                p.classList.toggle('hidden', idx !== 0);
            });
        }

        // Tab click handler
        document.querySelectorAll('#cat-modal .lang-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('#cat-modal .lang-tab').forEach(function (t) {
                    var isActive = (t === tab);
                    t.classList.toggle('is-active', isActive);
                    t.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });
                document.querySelectorAll('#cat-modal .lang-panel').forEach(function (p) {
                    p.classList.add('hidden');
                });
                var target = document.getElementById(tab.dataset.langPanel);
                if (target) target.classList.remove('hidden');
            });
        });

        // Category AI Text Content Generation
        var catGenBtn = document.getElementById('cat-generate-ai');
        if (catGenBtn) {
            var gRow = document.getElementById('cat-generate-row');
            var gPrompt = document.getElementById('cat-generate-prompt');
            var gRun = document.getElementById('cat-generate-run');
            var gStatus = document.getElementById('cat-generate-status');
            var gRowStatus = document.getElementById('cat-generate-row-status');
            var gUrl = @json(route('admin.generate'));
            var gToken = @json(csrf_token());
            var gLang = @json($defaultCode);
            var lblGenerating = @json(__('admin.categories.generating') ?? 'Generating…');
            var lblGenDone = @json(__('admin.categories.generate_done') ?? 'Generated — review and save.');
            var lblGenFailed = @json(__('admin.categories.generate_failed') ?? 'Generation failed.');
            var lblGenEmpty = @json(__('admin.categories.generate_empty') ?? 'Enter a few words about the category first.');
            var lblGenIdle = @json(__('admin.categories.generate') ?? 'Generate');
            var generating = false;

            catGenBtn.addEventListener('click', function () {
                gRow.classList.toggle('hidden');
                if (! gRow.classList.contains('hidden')) {
                    gPrompt.focus();
                }
            });

            gPrompt.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    gRun.click();
                }
            });

            gRun.addEventListener('click', function () {
                var prompt = gPrompt.value.trim();
                if (! prompt) {
                    if (gRowStatus) gRowStatus.textContent = lblGenEmpty;
                    return;
                }
                if (generating) return;
                generating = true;
                gRun.disabled = true;

                var gFx = window.aiFx && window.aiFx.button
                    ? window.aiFx.button.start(gRun, { label: lblGenerating, console: 'CATEGORY CONTENT GENERATOR', note: '“' + prompt + '” · ' + gLang })
                    : null;
                if (! gFx) gRun.textContent = lblGenerating;
                var gOk = false, gMsg = '';
                if (gRowStatus) gRowStatus.textContent = '';
                if (gStatus) gStatus.textContent = '';

                fetch(gUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': gToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ prompt: prompt, language: gLang, type: 'category' })
                })
                .then(function (res) {
                    return res.text().then(function (raw) {
                        var body;
                        try { body = raw ? JSON.parse(raw) : {}; }
                        catch (e) { throw new Error(lblGenFailed + ' (HTTP ' + res.status + ')'); }
                        if (! res.ok) throw new Error(body.message || lblGenFailed);
                        return body;
                    });
                })
                .then(function (body) {
                    if (nameIn && (body.name || body.title)) {
                        nameIn.value = body.name || body.title;
                        nameIn.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    if (gRowStatus) gRowStatus.textContent = lblGenDone;
                    if (gStatus) gStatus.textContent = lblGenDone;
                    gMsg = lblGenDone;
                    gOk = true;
                })
                .catch(function (e) {
                    if (gRowStatus) gRowStatus.textContent = e.message;
                    if (gStatus) gStatus.textContent = e.message;
                    gMsg = e.message;
                })
                .finally(function () {
                    generating = false;
                    gRun.disabled = false;
                    if (gFx) gFx.done(gOk, gMsg);
                    else gRun.textContent = lblGenIdle;
                });
            });
        }

        if (aiImgBtn) {
            aiImgBtn.addEventListener('click', function () {
                if (!window.aiImageModal) {
                    console.error('aiImageModal not initialized');
                    return;
                }
                window.aiImageModal.open({
                    title: nameIn.value,
                    style: {!! json_encode(__('admin.imagegen.style_category') ?: 'Clean minimal category icon or illustration, modern flat aesthetic, neutral background, no text, no watermark') !!},
                    size: '1024x1024',
                    multiple: false,
                    onApply: function (imgs) {
                        if (!imgs || !imgs.length) return;
                        setMediaValue(imgs[0].path, imgs[0].url);
                    }
                });
            });
        }

        if (bulkBtn) {
            bulkBtn.addEventListener('click', function () {
                if (!window.confirm(bulkBtn.dataset.confirm)) return;
                bulkBtn.disabled = true;
                var originalText = bulkBtn.textContent;
                bulkBtn.textContent = bulkBtn.dataset.running;

                fetch(@json(route('admin.translate.taxonomy')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json'
                    },
                    body: '{}'
                }).then(function (res) {
                    return res.json().then(function (body) { return { ok: res.ok, body: body }; });
                }).then(function (data) {
                    if (!data.ok) {
                        window.alert(data.body.message || 'Translation failed.');
                    } else if ((data.body.count || 0) === 0) {
                        window.alert(bulkBtn.dataset.none);
                    } else {
                        window.alert(bulkBtn.dataset.done
                            .replace(':count', data.body.count)
                            .replace(':targets', data.body.targets));
                        window.location.reload();
                    }
                }).catch(function () {
                    window.alert('Translation failed.');
                }).finally(function () {
                    bulkBtn.disabled = false;
                    bulkBtn.textContent = originalText;
                });
            });
        }

        function openAdd() {
            heading.textContent = @json(__('admin.categories.create'));
            form.action = storeUrl;
            method.disabled = true;
            nameIn.value = '';
            colorIn.value = '#137547';
            colorPrev.value = '#137547';
            iconIn.value = '';
            setMediaValue('', '');
            document.querySelectorAll('[id^="cat-trans-name-"]').forEach(function (input) {
                input.value = '';
            });
            resetTabs();
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function openEdit(btn) {
            heading.textContent = @json(__('admin.categories.edit'));
            form.action = btn.dataset.action;
            method.disabled = false;
            method.value = 'PUT';
            nameIn.value = btn.dataset.name || '';
            colorIn.value = btn.dataset.color || '#137547';
            colorPrev.value = btn.dataset.color || '#137547';
            iconIn.value = btn.dataset.icon || '';
            setMediaValue(btn.dataset.image || '', btn.dataset.imageUrl || '');

            var trans = {};
            try { trans = JSON.parse(btn.dataset.translations || '{}'); } catch (e) {}
            document.querySelectorAll('[id^="cat-trans-name-"]').forEach(function (input) {
                var code = input.id.replace('cat-trans-name-', '');
                input.value = typeof trans[code] === 'string' ? trans[code] : '';
            });

            resetTabs();
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        if (addBtn) addBtn.addEventListener('click', openAdd);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });

        if (colorIn && colorPrev) {
            colorIn.addEventListener('input', function () {
                colorPrev.value = colorIn.value;
            });
        }

        document.querySelectorAll('.btn-edit-category').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openEdit(btn);
            });
        });

        var params = new URLSearchParams(window.location.search);
        if (params.get('action') === 'create') {
            openAdd();
        } else if (params.get('edit')) {
            var targetBtn = document.querySelector('.btn-edit-category[data-id="' + params.get('edit') + '"]');
            if (targetBtn) openEdit(targetBtn);
        }
    });
    </script>

</x-layouts.dashboard>
