<x-layouts.dashboard :title="($post->exists ? __('admin.blog.edit') : __('admin.blog.create')).' - Mono LMS'" active="blog" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-4xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ $post->exists ? __('admin.blog.edit') : __('admin.blog.create') }}</h3>
            <p class="text-xs text-slate-500 mt-1">Compose editorial stories, releases, and platform updates.</p>
        </div>

        <form id="blog-form" action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}" method="POST" class="space-y-6">
            @csrf
            @if ($post->exists) @method('PUT') @endif

            @php
                $languages = $languages ?? \App\Models\Language::active();
                $defaultCode = $defaultCode ?? \App\Models\Language::defaultCode();
                $transLangs = $languages->where('code', '!=', $defaultCode)->values();
                $translatorSet = $translatorSet ?? (bool) app(\App\Services\AiTranslationService::class)->provider();
                $generatorSet = $generatorSet ?? (bool) \App\Models\AiProvider::resolveConfigured('ai_generator_provider');
                $imageSet = $imageSet ?? (bool) \App\Models\AiProvider::resolveConfigured('ai_image_provider');
            @endphp

            <!-- Language Tabs & AI Toolbar -->
            <div class="flex flex-wrap items-end justify-between gap-4 border-b border-neutral-200 dark:border-neutral-800">
                <div class="lang-tabs -mb-px flex flex-wrap gap-1" role="tablist">
                    <button type="button" class="lang-tab is-active" data-lang-panel="blog-lang-panel-default" role="tab" aria-selected="true">
                        <span>{{ $languages->firstWhere('code', $defaultCode)?->native_name ?? strtoupper($defaultCode) }}</span>
                        <span class="lang-badge">{{ __('admin.courses.lang_default_badge') ?? 'Default' }}</span>
                    </button>
                    @foreach ($transLangs as $lang)
                        <button type="button" class="lang-tab" data-lang-panel="blog-lang-panel-{{ $lang->code }}" role="tab" aria-selected="false">
                            <span>{{ $lang->native_name }}</span>
                        </button>
                    @endforeach
                </div>
                @if ($generatorSet || $translatorSet)
                    <div class="flex flex-wrap items-center gap-3 pb-2">
                        <span id="blog-generate-status" class="text-[12px] text-neutral-400"></span>
                        @if ($generatorSet)
                            <button type="button" id="blog-generate-ai" class="action-btn action-btn-ghost h-9 inline-flex items-center gap-1.5 text-[10px] tracking-[0.14em]">
                                <svg class="w-3.5 h-3.5 text-brand" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
                                </svg>
                                <span>{{ __('admin.blog.generate_ai') ?? 'Generate with AI' }}</span>
                            </button>
                        @endif
                        @if ($translatorSet)
                            <x-admin.translation-toolbar :enabled="$translatorSet" context="blog" container="#blog-form" :source="$defaultCode" />
                        @endif
                    </div>
                @endif
            </div>

            @if ($generatorSet)
                <div id="blog-generate-row" class="hidden border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/60 p-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="text" id="blog-generate-prompt" maxlength="500" placeholder="{{ __('admin.blog.generate_ph') ?? 'Describe the blog article topic in a few words…' }}"
                               class="h-9 min-w-0 basis-full flex-1 border sm:min-w-[240px] sm:basis-auto border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-sm placeholder:text-neutral-400 focus:border-neutral-900 dark:focus:border-neutral-100 focus:outline-none">
                        <button type="button" id="blog-generate-run" class="action-btn h-9 px-5 text-[10px] font-medium tracking-[0.14em]">
                            {{ __('admin.blog.generate') ?? 'Generate' }}
                        </button>
                        <span id="blog-generate-row-status" class="text-[12px] text-neutral-400"></span>
                    </div>
                </div>
            @endif

            <!-- Default Language Panel -->
            <div id="blog-lang-panel-default" class="lang-panel space-y-5" role="tabpanel">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('instructor.courses.fields.title') }} *</label>
                    <input type="text" name="title" id="blog-title-default" value="{{ old('title', $post->title) }}" required
                           data-translate-key="title" data-translate-locale="{{ $defaultCode }}"
                           class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                    @error('title')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.blog.excerpt') }}</label>
                    <textarea name="excerpt" id="blog-excerpt-default" rows="2"
                              data-translate-key="excerpt" data-translate-locale="{{ $defaultCode }}"
                              class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.blog.content') }}</label>
                    <textarea name="content" id="blog-content-default" rows="12"
                              data-translate-key="content" data-translate-locale="{{ $defaultCode }}"
                              class="w-full border border-slate-200 px-3.5 py-2.5 text-xs font-mono focus:outline-none focus:border-[#344C3D]">{{ old('content', $post->content) }}</textarea>
                    <p class="text-[11px] text-slate-400">{{ __('admin.blog.content_hint') }}</p>
                </div>
            </div>

            <!-- Other Language Panels -->
            @foreach ($transLangs as $lang)
                <div id="blog-lang-panel-{{ $lang->code }}" class="lang-panel hidden space-y-5" role="tabpanel" dir="{{ $lang->direction }}">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('instructor.courses.fields.title') }} · {{ $lang->native_name }}</label>
                        <input type="text" name="translations[{{ $lang->code }}][title]"
                               value="{{ old("translations.{$lang->code}.title", $post->translationMap('title')[$lang->code] ?? '') }}"
                               data-translate-key="title" data-translate-locale="{{ $lang->code }}"
                               class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                        <p class="text-[11px] text-neutral-400">Blank falls back to default title.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.blog.excerpt') }} · {{ $lang->native_name }}</label>
                        <textarea name="translations[{{ $lang->code }}][excerpt]" rows="2"
                                  data-translate-key="excerpt" data-translate-locale="{{ $lang->code }}"
                                  class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">{{ old("translations.{$lang->code}.excerpt", $post->translationMap('excerpt')[$lang->code] ?? '') }}</textarea>
                        <p class="text-[11px] text-neutral-400">Blank falls back to default excerpt.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.blog.content') }} · {{ $lang->native_name }}</label>
                        <textarea name="translations[{{ $lang->code }}][content]" rows="12"
                                  data-translate-key="content" data-translate-locale="{{ $lang->code }}"
                                  class="w-full border border-slate-200 px-3.5 py-2.5 text-xs font-mono focus:outline-none focus:border-[#344C3D]">{{ old("translations.{$lang->code}.content", $post->translationMap('content')[$lang->code] ?? '') }}</textarea>
                        <p class="text-[11px] text-neutral-400">Blank falls back to default content.</p>
                    </div>
                </div>
            @endforeach

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                <div id="blog-media-wrap">
                    <x-media-selector
                        name="image"
                        :value="old('image', $post->image)"
                        label="{{ __('instructor.courses.fields.image') }}"
                        shape="wide"
                    >
                        @if ($imageSet)
                            <button type="button" id="blog-ai-img-btn" class="action-btn h-9 px-4 inline-flex items-center gap-2 text-[11px] font-medium uppercase tracking-[0.14em] cursor-pointer">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.2 6.9L21 11.1l-6.8 2.2L12 20.2l-2.2-6.9L3 11.1l6.8-2.2L12 2z"/></svg>
                                <span>{{ __('admin.imagegen.button') ?? 'Generate with AI' }}</span>
                            </button>
                        @endif
                    </x-media-selector>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['draft' => __('app.common.draft'), 'published' => __('app.common.published')]"
                        :selected="old('status', $post->status ?? 'draft')"
                        :searchable="false" />
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.blog.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('#blog-form .lang-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('#blog-form .lang-tab').forEach(function (t) {
                    var isActive = (t === tab);
                    t.classList.toggle('is-active', isActive);
                    t.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });
                document.querySelectorAll('#blog-form .lang-panel').forEach(function (p) {
                    p.classList.add('hidden');
                });
                var target = document.getElementById(tab.dataset.langPanel);
                if (target) target.classList.remove('hidden');
            });
        });

        // Blog AI Text Content Generation
        var blogGenBtn = document.getElementById('blog-generate-ai');
        if (blogGenBtn) {
            var gRow = document.getElementById('blog-generate-row');
            var gPrompt = document.getElementById('blog-generate-prompt');
            var gRun = document.getElementById('blog-generate-run');
            var gStatus = document.getElementById('blog-generate-status');
            var gRowStatus = document.getElementById('blog-generate-row-status');
            var gUrl = @json(route('admin.generate'));
            var gToken = @json(csrf_token());
            var gLang = @json($defaultCode);
            var lblGenerating = @json(__('admin.blog.generating') ?? 'Generating…');
            var lblGenDone = @json(__('admin.blog.generate_done') ?? 'Generated — review and save.');
            var lblGenFailed = @json(__('admin.blog.generate_failed') ?? 'Generation failed.');
            var lblGenEmpty = @json(__('admin.blog.generate_empty') ?? 'Enter a few words about the blog post first.');
            var lblGenIdle = @json(__('admin.blog.generate') ?? 'Generate');
            var generating = false;

            blogGenBtn.addEventListener('click', function () {
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
                    ? window.aiFx.button.start(gRun, { label: lblGenerating, console: 'BLOG CONTENT GENERATOR', note: '“' + prompt + '” · ' + gLang })
                    : null;
                if (! gFx) gRun.textContent = lblGenerating;
                var gOk = false, gMsg = '';
                if (gRowStatus) gRowStatus.textContent = '';
                if (gStatus) gStatus.textContent = '';

                fetch(gUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': gToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ prompt: prompt, language: gLang, type: 'blog' })
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
                    var titleInput = document.getElementById('blog-title-default');
                    if (titleInput && (body.title || body.name)) {
                        titleInput.value = body.title || body.name;
                        titleInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    var excerptInput = document.getElementById('blog-excerpt-default');
                    if (excerptInput && (body.excerpt || body.subtitle || body.description)) {
                        excerptInput.value = body.excerpt || body.subtitle || body.description;
                        excerptInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    var contentInput = document.getElementById('blog-content-default');
                    if (contentInput && (body.content || body.description)) {
                        contentInput.value = body.content || body.description;
                        contentInput.dispatchEvent(new Event('input', { bubbles: true }));
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

        var blogAiImgBtn = document.getElementById('blog-ai-img-btn');
        if (blogAiImgBtn) {
            blogAiImgBtn.addEventListener('click', function () {
                if (!window.aiImageModal) {
                    console.error('aiImageModal not initialized');
                    return;
                }
                var title = document.getElementById('blog-title-default') ? document.getElementById('blog-title-default').value : '';
                var desc = document.getElementById('blog-excerpt-default') ? document.getElementById('blog-excerpt-default').value : '';
                window.aiImageModal.open({
                    title: title,
                    description: desc,
                    style: {!! json_encode(__('admin.imagegen.style_blog') ?: 'Professional editorial blog illustration, clean modern aesthetic, natural authentic feel, high detail, 16:9 ratio, no text, no watermark') !!},
                    size: '1792x1024',
                    multiple: false,
                    onApply: function (imgs) {
                        if (!imgs || !imgs.length) return;
                        var wrap = document.getElementById('blog-media-wrap');
                        if (!wrap) return;
                        var input = wrap.querySelector('[data-media-input]');
                        var img = wrap.querySelector('[data-media-img]');
                        var ph = wrap.querySelector('[data-media-placeholder]');
                        var rm = wrap.querySelector('[data-media-remove]');
                        if (input) input.value = imgs[0].path;
                        if (img) { img.src = imgs[0].url; img.classList.remove('hidden'); }
                        if (ph) ph.classList.add('hidden');
                        if (rm) rm.classList.remove('hidden');
                    }
                });
            });
        }
    </script>

</x-layouts.dashboard>
