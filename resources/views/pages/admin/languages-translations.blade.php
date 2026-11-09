<x-layouts.dashboard :title="__('admin.languages.translations') . ' — ' . $language->name . ' - Mono LMS'" active="languages" userType="admin">

<style>
    .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 36px; padding: 0 16px; font-size: 11px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; transition: color .15s, background .15s, border-color .15s; }
    .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
    .action-btn:disabled { opacity: .5; cursor: wait; }
    .action-btn-ghost { color: #525252; background: #fff; border-color: #d4d4d4; }
    .action-btn-ghost:hover { color: #0a0a0a; background: #fafafa; border-color: #0a0a0a; }
</style>

<div class="space-y-6">
    <div class="flex flex-col gap-2">
        <a href="{{ route('admin.languages.index') }}" class="inline-flex items-center gap-1.5 text-xs text-neutral-400 hover:text-neutral-900 transition-colors">
            ← {{ __('admin.languages.back') }}
        </a>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-medium tracking-tight text-neutral-900 flex items-center gap-2.5">
                    @if ($language->flag && file_exists(public_path('images/flags/'.$language->flag.'.png')))
                        <img src="{{ asset('images/flags/'.$language->flag.'.png') }}" alt="{{ $language->name }}" class="h-5 w-7 border border-neutral-200 object-cover" />
                    @endif
                    <span>{{ __('admin.languages.translations_for', ['language' => $language->name]) }}</span>
                    <span class="font-mono text-sm text-neutral-400">({{ strtoupper($language->code) }})</span>
                </h2>
                <p class="mt-1 text-sm text-neutral-500">{{ __('admin.languages.translations_sub') }}</p>
            </div>
        </div>

        @php
            $overallTotal = array_sum(array_column($groups, 'total'));
            $overallDone  = array_sum(array_column($groups, 'translated'));
            $overallLeft  = max(0, $overallTotal - $overallDone);
            $overallPct   = $overallTotal > 0 ? (int) round($overallDone / $overallTotal * 100) : 100;
        @endphp
        <div class="mt-2 flex flex-wrap items-center gap-4" data-overall-progress>
            <div class="h-2 w-64 overflow-hidden bg-neutral-200">
                <div data-overall-bar class="h-full bg-neutral-900 transition-all duration-300" style="width: {{ $overallPct }}%"></div>
            </div>
            <span data-overall-label class="text-xs text-neutral-500">
                <strong class="tnum font-semibold text-neutral-900">{{ __('admin.languages.completed', ['percent' => $overallPct]) }}</strong>
                · <span class="tnum font-medium">{{ number_format($overallDone) }}/{{ number_format($overallTotal) }}</span>
                @if ($overallLeft > 0)
                    · <span class="text-amber-600 font-medium">{{ __('admin.languages.remaining', ['count' => number_format($overallLeft)]) }}</span>
                @endif
            </span>
        </div>

        @unless ($isDefault)
            <div class="mt-3 flex flex-wrap items-center gap-4 border border-neutral-200 bg-white p-4 shadow-xs">
                <button type="button" id="translate-all" class="action-btn h-9 px-5">
                    <svg class="mr-1.5 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/></svg>
                    {{ __('admin.languages.translate_all') }}
                </button>
                <label class="flex cursor-pointer items-center gap-2 text-xs text-neutral-700 select-none">
                    <input type="checkbox" id="translate-overwrite" class="control">
                    {{ __('admin.languages.overwrite') }}
                </label>
                <span id="translate-status" class="text-xs text-neutral-500 font-medium"></span>
                <div id="translate-progress" class="hidden h-1.5 w-48 overflow-hidden bg-neutral-200">
                    <div id="translate-progress-bar" class="h-full bg-neutral-900 transition-all duration-200" style="width: 0%"></div>
                </div>
            </div>
        @endunless
    </div>

    @php
        $lmsFiles = ['courses', 'student', 'instructor', 'instructors'];
        $webFiles = ['app', 'auth', 'blog', 'emails', 'home', 'membership'];
        $adminFiles = ['admin'];
        $otherFiles = array_diff(array_keys($groups), array_merge($adminFiles, $lmsFiles, $webFiles));

        $tabs = [
            'admin' => ['label' => __('admin.languages.tab_admin'), 'files' => array_values(array_intersect($adminFiles, array_keys($groups)))],
            'lms'   => ['label' => __('admin.languages.tab_lms'),   'files' => array_values(array_intersect($lmsFiles, array_keys($groups)))],
            'web'   => ['label' => __('admin.languages.tab_web'),   'files' => array_values(array_intersect(array_merge($webFiles, $otherFiles), array_keys($groups)))],
            'all'   => ['label' => 'All (' . count($groups) . ')', 'files' => array_keys($groups)],
        ];
    @endphp

    <div data-tabs>
        <div class="flex gap-6 border-b border-neutral-200">
            @foreach ($tabs as $key => $tab)
                <button type="button" data-tab="{{ $key }}"
                        class="-mb-px border-b-2 pb-3 text-xs font-semibold uppercase tracking-[0.14em] transition-colors {{ $loop->first ? 'border-neutral-900 text-neutral-900' : 'border-transparent text-neutral-400 hover:text-neutral-700' }}">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        @foreach ($tabs as $key => $tab)
            <div data-tab-panel="{{ $key }}" class="{{ $loop->first ? '' : 'hidden' }} space-y-6 mt-6">
                @foreach ($tab['files'] as $file)
                    @continue(! isset($groups[$file]))
                    @php $group = $groups[$file]; @endphp
                    <div class="border border-neutral-200 bg-white shadow-xs p-6" data-file-card="{{ $file }}">
                        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-neutral-900">
                                    {{ $file }}.php
                                    <span class="ms-2 text-xs font-normal normal-case tracking-normal text-neutral-400">({{ __('admin.languages.keys', ['count' => $group['total']]) }})</span>
                                </h3>
                                <div class="mt-2 flex items-center gap-3">
                                    <div class="h-1.5 w-44 overflow-hidden bg-neutral-200">
                                        <div data-file-bar="{{ $file }}" class="h-full bg-neutral-900 transition-all duration-300" style="width: {{ $group['percent'] }}%"></div>
                                    </div>
                                    <span data-file-label="{{ $file }}" class="text-xs text-neutral-500">
                                        <strong class="tnum font-medium text-neutral-700">{{ __('admin.languages.completed', ['percent' => $group['percent']]) }}</strong>
                                        @if ($group['total'] - $group['translated'] > 0)
                                            · <span class="text-amber-600">{{ __('admin.languages.remaining', ['count' => number_format($group['total'] - $group['translated'])]) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @unless ($isDefault)
                                    <button type="button" data-translate-file="{{ $file }}" class="action-btn action-btn-ghost h-9 px-4">
                                        <svg class="mr-1.5 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/></svg>
                                        {{ __('admin.languages.translate_ai') }}
                                    </button>
                                @endunless
                                <button type="submit" form="trans-{{ $file }}" class="action-btn h-9 px-5">{{ __('admin.common.save') }}</button>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.languages.translations.update', $language) }}" id="trans-{{ $file }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="file" value="{{ $file }}">
                        </form>

                        <div class="mt-4 hidden grid-cols-[240px_1fr_1fr] gap-4 border-b border-neutral-200 pb-2.5 text-[10px] font-bold uppercase tracking-[0.14em] text-neutral-400 lg:grid">
                            <span>{{ __('admin.languages.code') }}</span>
                            <span>{{ __('admin.languages.reference') }} (EN)</span>
                            <span>{{ $language->name }} ({{ strtoupper($language->code) }})</span>
                        </div>

                        <div class="divide-y divide-neutral-100 max-h-[600px] overflow-y-auto pr-1">
                            @foreach ($group['reference'] as $k => $enValue)
                                <div class="grid gap-2 py-3 lg:grid-cols-[240px_1fr_1fr] lg:items-center lg:gap-4">
                                    <p class="break-all font-mono text-[11px] text-neutral-400 select-all">{{ $k }}</p>
                                    <p class="text-xs text-neutral-600 leading-relaxed">{{ $enValue }}</p>
                                    <input type="text" form="trans-{{ $file }}" name="t[{{ $k }}]"
                                           value="{{ $group['values'][$k] ?? '' }}" placeholder="{{ $enValue }}"
                                           dir="{{ $language->direction }}"
                                           class="h-9 w-full border border-neutral-200 bg-white px-3 text-xs placeholder:text-neutral-300 focus:border-neutral-900 focus:outline-none">
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex justify-end border-t border-neutral-100 pt-3">
                            <button type="submit" form="trans-{{ $file }}" class="action-btn h-9 px-5">{{ __('admin.common.save') }}</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<script>
(function () {
    // Tab switching
    document.querySelectorAll('[data-tab]').forEach(function (tabBtn) {
        tabBtn.addEventListener('click', function () {
            var target = tabBtn.dataset.tab;
            document.querySelectorAll('[data-tab]').forEach(function (b) {
                b.classList.remove('border-neutral-900', 'text-neutral-900');
                b.classList.add('border-transparent', 'text-neutral-400');
            });
            tabBtn.classList.remove('border-transparent', 'text-neutral-400');
            tabBtn.classList.add('border-neutral-900', 'text-neutral-900');

            document.querySelectorAll('[data-tab-panel]').forEach(function (panel) {
                if (panel.dataset.tabPanel === target) {
                    panel.classList.remove('hidden');
                } else {
                    panel.classList.add('hidden');
                }
            });
        });
    });

    var url            = @json(route('admin.languages.translate', $language));
    var token          = @json(csrf_token());
    var files          = @json(array_keys($groups));
    var lblTranslating = @json(__('admin.languages.translating'));
    var lblComplete    = @json(__('admin.languages.translate_complete'));
    var lblFailed      = @json(__('admin.languages.translate_failed'));
    var lblSaving      = @json(__('admin.languages.translate_saving'));
    var lblSaved       = @json(__('admin.languages.translate_saved'));
    var lblCompleted   = @json(__('admin.languages.completed', ['percent' => '__PCT__']));
    var lblRemaining   = @json(__('admin.languages.remaining', ['count' => '__COUNT__']));

    var status         = document.getElementById('translate-status');
    var allBtn         = document.getElementById('translate-all');
    var overwrite      = document.getElementById('translate-overwrite');
    var progress       = document.getElementById('translate-progress');
    var progressBar    = document.getElementById('translate-progress-bar');
    var running        = false;

    function refreshFileProgress(file) {
        var inputs = document.querySelectorAll('input[form="trans-' + file + '"][name^="t["]');
        if (! inputs.length) return;
        var done = 0;
        inputs.forEach(function (i) { if (i.value.trim() !== '') done++; });
        var left = inputs.length - done;
        var pct  = Math.round(done / inputs.length * 100);

        var bar = document.querySelector('[data-file-bar="' + file + '"]');
        var label = document.querySelector('[data-file-label="' + file + '"]');
        if (bar) bar.style.width = pct + '%';
        if (label) {
            label.innerHTML = '<strong class="tnum font-medium text-neutral-700">' +
                lblCompleted.replace('__PCT__', pct) + '</strong>' +
                (left > 0 ? ' · <span class="text-amber-600">' + lblRemaining.replace('__COUNT__', left.toLocaleString()) + '</span>' : '');
        }

        var totalDone = 0, totalCount = 0;
        document.querySelectorAll('[data-file-card]').forEach(function (card) {
            var f = card.getAttribute('data-file-card');
            var fi = document.querySelectorAll('input[form="trans-' + f + '"][name^="t["]');
            totalCount += fi.length;
            fi.forEach(function (i) { if (i.value.trim() !== '') done++; totalDone++; });
        });
        var overallPct = totalCount ? Math.round(totalDone / totalCount * 100) : 100;
        var overallBar = document.querySelector('[data-overall-bar]');
        var overallLabel = document.querySelector('[data-overall-label]');
        if (overallBar) overallBar.style.width = overallPct + '%';
        if (overallLabel) {
            overallLabel.innerHTML = '<strong class="tnum font-semibold text-neutral-900">' +
                lblCompleted.replace('__PCT__', overallPct) + '</strong> · <span class="tnum font-medium">' +
                totalDone.toLocaleString() + '/' + totalCount.toLocaleString() + '</span>' +
                (totalCount - totalDone > 0 ? ' · <span class="text-amber-600">' + lblRemaining.replace('__COUNT__', (totalCount - totalDone).toLocaleString()) + '</span>' : '');
        }
    }

    document.querySelectorAll('input[name^="t["]').forEach(function (input) {
        input.addEventListener('input', function () {
            var formId = input.getAttribute('form');
            if (formId && formId.indexOf('trans-') === 0) {
                refreshFileProgress(formId.replace('trans-', ''));
            }
        });
    });

    function setStatus(t) { if (status) status.textContent = t; }

    function applyTranslations(file, translations) {
        Object.keys(translations || {}).forEach(function (key) {
            var input = document.querySelector('input[form="trans-' + file + '"][name="t[' + key + ']"]');
            if (input) input.value = translations[key];
        });
        refreshFileProgress(file);
    }

    function saveFile(file) {
        var form = document.getElementById('trans-' + file);
        if (! form) return Promise.resolve();
        return fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form)
        }).then(function (res) {
            if (! res.ok) throw new Error(lblFailed);
        });
    }

    function translateFile(file, offset, done, total, onProgress, onDone, onError) {
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ file: file, offset: offset, overwrite: overwrite && overwrite.checked ? 1 : 0 })
        })
        .then(function (res) {
            return res.json().then(function (body) {
                if (! res.ok) throw new Error(body.message || lblFailed);
                return body;
            });
        })
        .then(function (body) {
            applyTranslations(file, body.translations);
            var n = Object.keys(body.translations || {}).length;
            done += n;
            if (! total) total = body.total;
            if (total > 0) onProgress(Math.min(done, total), total, n);
            if (body.remaining > 0 && n > 0) {
                translateFile(file, body.next, done, total, onProgress, onDone, onError);
            } else {
                onDone();
            }
        })
        .catch(function (e) { onError(e); });
    }

    function run(filesQueue, btn, restoreLabel) {
        if (running) return;
        running = true;
        if (progress) progress.classList.remove('hidden');
        if (progressBar) progressBar.style.width = '0%';
        if (btn) { btn.disabled = true; btn.textContent = lblTranslating; }

        var i = 0;
        var multi = filesQueue.length > 1;

        (function next() {
            if (i >= filesQueue.length) {
                running = false;
                if (btn) { btn.disabled = false; btn.textContent = restoreLabel; }
                if (progressBar) progressBar.style.width = '100%';
                setStatus(lblComplete + ' ' + lblSaved);
                return;
            }
            var file = filesQueue[i];
            var label = multi ? file + '.php (' + (i + 1) + '/' + filesQueue.length + ')' : file + '.php';
            setStatus(lblTranslating + ' ' + label);

            translateFile(file, 0, 0, 0,
                function (done, total) {
                    if (progressBar && total > 0) {
                        progressBar.style.width = Math.round(done / total * 100) + '%';
                    }
                    setStatus(lblTranslating + ' ' + label + ' ' + done + '/' + total);
                },
                function () {
                    setStatus(lblSaving + ' ' + label);
                    saveFile(file).then(function () {
                        i++;
                        next();
                    }).catch(function (e) {
                        running = false;
                        if (btn) { btn.disabled = false; btn.textContent = restoreLabel; }
                        setStatus((e.message || lblFailed) + ' (' + file + ')');
                    });
                },
                function (e) {
                    running = false;
                    if (btn) { btn.disabled = false; btn.textContent = restoreLabel; }
                    setStatus((e.message || lblFailed) + ' (' + file + ')');
                }
            );
        })();
    }

    if (allBtn) {
        allBtn.addEventListener('click', function () {
            run(files, allBtn, @json(__('admin.languages.translate_all')));
        });
    }

    document.querySelectorAll('[data-translate-file]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var file = btn.dataset.translateFile;
            run([file], btn, @json(__('admin.languages.translate_ai')));
        });
    });
})();
</script>

</x-layouts.dashboard>
