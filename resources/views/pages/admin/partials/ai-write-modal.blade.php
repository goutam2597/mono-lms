@props([
    'languages' => null,
    'defaultCode' => 'en',
    'type' => 'course',
    'generateUrl' => null,
])

@php
    $languages = $languages ?? \App\Models\Language::active();
    $defaultCode = $defaultCode ?? \App\Models\Language::defaultCode();
    $generateUrl = $generateUrl ?? (request()->routeIs('instructor.*') ? route('instructor.generate') : route('admin.generate'));
@endphp

<!-- AI Write Modal (Mono Market System) -->
<div id="aiWriteModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 bg-black/70 backdrop-blur-xs select-none" role="dialog" aria-modal="true">
    <div class="relative w-full max-w-xl bg-white dark:bg-[#121417] border border-neutral-200 dark:border-neutral-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 dark:border-neutral-800 bg-neutral-50/50 dark:bg-neutral-900/40">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 border border-teal-200/50 dark:border-teal-800/50">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-neutral-900 dark:text-white truncate">Generate Content with AI</h3>
                    <p class="text-[11px] text-neutral-500 dark:text-neutral-400 truncate">Enter topic or keywords, and AI will generate title, subtitle & description.</p>
                </div>
            </div>
            <button type="button" onclick="closeAIWriteModal()" class="text-neutral-400 hover:text-neutral-900 dark:hover:text-white text-xl font-bold p-1 cursor-pointer transition-colors" aria-label="Close">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4 overflow-y-auto">
            <div class="grid grid-cols-1 sm:grid-cols-[1fr_180px] gap-3">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Topic / Keywords <span class="text-red-500">*</span></label>
                    <input type="text" id="aiWriteHint" placeholder="e.g. Master Modern Web Design with Figma & Tailwind" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-teal-600 dark:focus:border-teal-400">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Generate For</label>
                    <select id="aiWriteLang" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs font-medium rounded-lg focus:outline-none focus:border-teal-600 dark:focus:border-teal-400">
                        @foreach ($languages as $l)
                            <option value="{{ $l->code }}" @selected($l->code === $defaultCode)>
                                {{ $l->native_name ?: $l->name }} ({{ strtoupper($l->code) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Error Banner -->
            <div id="aiWriteError" class="hidden p-3 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-xs text-red-600 dark:text-red-400"></div>

            <!-- Results Section -->
            <div id="aiWriteResults" class="hidden space-y-3.5 pt-3 border-t border-neutral-100 dark:border-neutral-800">
                <!-- Title Result -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Generated Title</span>
                        <button type="button" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline cursor-pointer" onclick="applyAIContent('title')">Apply Title</button>
                    </div>
                    <div id="aiWriteTitle" class="p-3 bg-neutral-50 dark:bg-neutral-800/60 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-900 dark:text-neutral-100 font-medium"></div>
                </div>

                <!-- Subtitle Result -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Subtitle / Tagline</span>
                        <button type="button" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline cursor-pointer" onclick="applyAIContent('subtitle')">Apply Subtitle</button>
                    </div>
                    <div id="aiWriteSubtitle" class="p-3 bg-neutral-50 dark:bg-neutral-800/60 border border-neutral-200 dark:border-neutral-700 rounded-lg text-xs text-neutral-700 dark:text-neutral-300"></div>
                </div>

                <!-- Description Result -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Full Description</span>
                        <button type="button" class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline cursor-pointer" onclick="applyAIContent('description')">Apply Description</button>
                    </div>
                    <div id="aiWriteDesc" class="p-3 bg-neutral-50 dark:bg-neutral-800/60 border border-neutral-200 dark:border-neutral-700 rounded-lg text-xs text-neutral-700 dark:text-neutral-300 max-h-36 overflow-y-auto leading-relaxed"></div>
                </div>

                <button type="button" onclick="applyAllAIContent()" class="w-full h-10 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer flex items-center justify-center gap-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Apply All to Form</span>
                </button>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 px-6 py-3.5 bg-neutral-50 dark:bg-neutral-900/60 border-t border-neutral-100 dark:border-neutral-800">
            <button type="button" onclick="closeAIWriteModal()" class="px-4 py-2 text-xs font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white cursor-pointer transition-colors">Cancel</button>
            <button type="button" id="aiWriteGenBtn" onclick="runAIWrite()" class="h-9 px-5 rounded-lg bg-neutral-900 hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 text-white text-xs font-bold uppercase tracking-wider flex items-center gap-2 cursor-pointer transition-colors shadow-xs">
                <svg id="aiWriteSpin" class="hidden w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-dasharray="32" stroke-linecap="round"/></svg>
                <span id="aiWriteLabel">Generate Content</span>
            </button>
        </div>
    </div>
</div>

<script>
    var _aiWriteData = null;
    var _aiWriteUrl = @json($generateUrl);
    var _aiWriteCsrf = @json(csrf_token());
    var _aiWriteType = @json($type);
    var _aiWriteDefaultLang = @json($defaultCode);

    function openAIWriteModal() {
        var modal = document.getElementById('aiWriteModal');
        if (! modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        var hint = document.getElementById('aiWriteHint');
        if (hint && ! hint.value.trim()) {
            var activeTitleInput = document.querySelector('.course-lang-tab.is-active');
            var defaultTitle = document.getElementById('course-title-default');
            if (defaultTitle && defaultTitle.value.trim()) {
                hint.value = defaultTitle.value.trim();
            }
        }
        var err = document.getElementById('aiWriteError');
        var res = document.getElementById('aiWriteResults');
        if (err) err.classList.add('hidden');
        if (res) res.classList.add('hidden');
        _aiWriteData = null;
        if (hint) setTimeout(function () { hint.focus(); }, 50);
    }

    function closeAIWriteModal() {
        var modal = document.getElementById('aiWriteModal');
        if (! modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    async function runAIWrite() {
        var hintEl = document.getElementById('aiWriteHint');
        var hint = hintEl ? hintEl.value.trim() : '';
        if (! hint) {
            alert('Please enter a topic or keywords first.');
            if (hintEl) hintEl.focus();
            return;
        }

        var langSel = document.getElementById('aiWriteLang');
        var langCode = langSel ? langSel.value : _aiWriteDefaultLang;
        var btn = document.getElementById('aiWriteGenBtn');
        var spin = document.getElementById('aiWriteSpin');
        var lbl = document.getElementById('aiWriteLabel');
        var err = document.getElementById('aiWriteError');
        var res = document.getElementById('aiWriteResults');

        if (btn) btn.disabled = true;
        if (spin) spin.classList.remove('hidden');
        if (lbl) lbl.textContent = 'Generating…';
        if (err) err.classList.add('hidden');
        if (res) res.classList.add('hidden');

        try {
            var response = await fetch(_aiWriteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': _aiWriteCsrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    prompt: hint,
                    language: langCode,
                    type: _aiWriteType
                })
            });

            var raw = await response.text();
            var data;
            try { data = raw ? JSON.parse(raw) : {}; }
            catch (e) { throw new Error('Generation failed (HTTP ' + response.status + ')'); }

            if (! response.ok || data.error) {
                var errorMsg = data.message || data.error || 'Generation failed. Check AI provider settings.';
                if (err) {
                    err.textContent = '⚠️ ' + errorMsg;
                    err.classList.remove('hidden');
                }
            } else {
                _aiWriteData = Object.assign({ langCode: langCode }, data);
                var titleText = data.title || data.name || '';
                var subtitleText = data.subtitle || data.excerpt || '';
                var descText = data.description || data.content || '';

                var tEl = document.getElementById('aiWriteTitle');
                var sEl = document.getElementById('aiWriteSubtitle');
                var dEl = document.getElementById('aiWriteDesc');
                if (tEl) tEl.textContent = titleText;
                if (sEl) sEl.textContent = subtitleText;
                if (dEl) dEl.innerHTML = descText;
                if (res) res.classList.remove('hidden');
            }
        } catch (e) {
            if (err) {
                err.textContent = '⚠️ ' + (e.message || 'Network error');
                err.classList.remove('hidden');
            }
        } finally {
            if (btn) btn.disabled = false;
            if (spin) spin.classList.add('hidden');
            if (lbl) lbl.textContent = 'Generate Content';
        }
    }

    function applyAIContent(field) {
        if (! _aiWriteData) return;
        var lc = _aiWriteData.langCode;
        var val = '';
        if (field === 'title') val = _aiWriteData.title || _aiWriteData.name || '';
        if (field === 'subtitle') val = _aiWriteData.subtitle || _aiWriteData.excerpt || '';
        if (field === 'description') val = _aiWriteData.description || _aiWriteData.content || '';

        var targetInput = null;
        if (lc === _aiWriteDefaultLang) {
            targetInput = document.getElementById('course-' + (field === 'description' ? 'desc' : field) + '-default')
                || document.querySelector('[data-translate-key="' + field + '"][data-translate-locale="' + lc + '"]');
        } else {
            targetInput = document.querySelector('[name="translations[' + lc + '][' + field + ']"]')
                || document.querySelector('[data-translate-key="' + field + '"][data-translate-locale="' + lc + '"]');
        }

        if (targetInput) {
            targetInput.value = val;
            targetInput.dispatchEvent(new Event('input', { bubbles: true }));
            targetInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        // Switch active tab to target language
        if (typeof switchCourseLangTab === 'function') {
            switchCourseLangTab(lc);
        }
    }

    function applyAllAIContent() {
        if (! _aiWriteData) return;
        ['title', 'subtitle', 'description'].forEach(applyAIContent);
        closeAIWriteModal();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var m = document.getElementById('aiWriteModal');
            if (m && ! m.classList.contains('hidden')) closeAIWriteModal();
        }
    });
</script>
