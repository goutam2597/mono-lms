{{-- Media picker modal — used by courses/categories/blog/users/settings forms.
     Include once per layout or page and call: window.mediaPicker.open({ multiple: false, onPick: function(items) { ... } })
--}}
<style>
    .mediap-modal { position: fixed; inset: 0; z-index: 200; display: none; align-items: center; justify-content: center; padding: 24px; box-sizing: border-box; }
    .mediap-modal.open { display: flex !important; }
    .mediap-backdrop { position: absolute; inset: 0; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); }
    .mediap-card { position: relative; width: 920px; max-width: 100%; max-height: calc(100vh - 48px); background: #ffffff; border-radius: 0 !important; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column; overflow: hidden; border: 1px solid #e5e5e5; box-sizing: border-box; }
    .mediap-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; overflow-y: auto; padding: 16px; max-height: 52vh; }
    @media(min-width: 480px){ .mediap-grid { grid-template-columns: repeat(3, 1fr); } }
    @media(min-width: 640px){ .mediap-grid { grid-template-columns: repeat(4, 1fr); } }
    @media(min-width: 840px){ .mediap-grid { grid-template-columns: repeat(6, 1fr); } }
    .mediap-tile { position: relative; aspect-ratio: 1; border-radius: 0 !important; border: 1px solid #e5e5e5; overflow: hidden; cursor: pointer; background: #fafafa; transition: all .15s ease; }
    .mediap-tile:hover { border-color: #191919; transform: translateY(-1px); }
    .mediap-tile img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .mediap-tile.selected { border-color: var(--theme-primary, #344c3d); outline: 2px solid var(--theme-primary, #344c3d); }
    .mediap-tile .mediap-check { position: absolute; top: 6px; right: 6px; display: none; width: 20px; height: 20px; border-radius: 0 !important; background: var(--theme-primary, #344c3d); color: #fff; align-items: center; justify-content: center; }
    .mediap-tile.selected .mediap-check { display: flex; }
    .mediap-tile .mediap-caption { position: absolute; inset-inline: 0; bottom: 0; background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 60%, transparent 100%); padding: 18px 8px 6px; color: #fff; font-size: 10px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; opacity: 0; transition: opacity .15s; }
    .mediap-tile:hover .mediap-caption { opacity: 1; }
    .mediap-empty { grid-column: 1/-1; padding: 48px 16px; text-align: center; color: #a3a3a3; font-size: 13px; }
</style>

<div id="mediap-modal" class="mediap-modal" role="dialog" aria-modal="true">
    <div class="mediap-backdrop" data-mediap-close></div>

    <div class="mediap-card animate-in fade-in zoom-in-95 duration-150">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 bg-white">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-emerald-50 text-[var(--theme-primary,#344c3d)] flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-neutral-900">{{ __('admin.media.heading') }}</h3>
                    <p class="text-[11px] text-neutral-400">{{ __('admin.media.picker_title') }}</p>
                </div>
            </div>
            <button type="button" data-mediap-close class="p-1 text-neutral-400 hover:text-neutral-900 transition-colors" aria-label="{{ __('admin.common.cancel') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex items-center gap-2 border-b border-neutral-200 px-6 py-2.5 bg-neutral-50">
            <button type="button" data-mediap-tab="library" class="mediap-tab is-active h-8 px-4 text-[10px] font-semibold uppercase tracking-[0.12em] bg-[var(--theme-primary,#344c3d)] text-white transition-colors">{{ __('admin.media.tab_library') }}</button>
            <button type="button" data-mediap-tab="upload" class="mediap-tab h-8 px-4 text-[10px] font-semibold uppercase tracking-[0.12em] text-neutral-600 hover:text-neutral-900 hover:bg-neutral-200 transition-colors">{{ __('admin.media.tab_upload') }}</button>
        </div>

        <!-- Tab 1: Library -->
        <div id="mediap-library" class="flex-1 flex flex-col overflow-hidden">
            <!-- Search bar -->
            <div class="flex items-center gap-2 border-b border-neutral-200 px-6 py-3 bg-white">
                <div class="relative flex-1">
                    <input type="search" id="mediap-q" placeholder="{{ __('admin.media.search_placeholder') }}"
                           class="w-full h-9 pl-9 pr-3 text-xs border border-neutral-300 bg-neutral-50/60 focus:bg-white focus:outline-none focus:border-[var(--theme-primary,#344c3d)] transition-colors">
                    <svg class="w-4 h-4 text-neutral-400 absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <button type="button" id="mediap-search-btn" class="h-9 px-4 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-[11px] font-semibold uppercase tracking-[0.12em] border border-neutral-300 transition-colors">
                    {{ __('admin.common.search') }}
                </button>
            </div>

            <!-- Grid container -->
            <div id="mediap-grid" class="mediap-grid custom-sidebar-scroll"></div>

            <!-- Pager -->
            <div id="mediap-pager" class="flex items-center justify-center gap-1.5 border-t border-neutral-200 px-6 py-2.5 bg-neutral-50"></div>
        </div>

        <!-- Tab 2: Upload -->
        <div id="mediap-upload" class="hidden flex-1 p-8">
            <label id="mediap-dropzone" class="flex cursor-pointer flex-col items-center justify-center gap-3 border-2 border-dashed border-neutral-300 hover:border-[var(--theme-primary,#344c3d)] bg-neutral-50 hover:bg-emerald-50/20 px-6 py-14 text-center transition-all">
                <input type="file" id="mediap-file" accept="image/jpeg,image/png,image/webp,image/svg+xml" multiple class="hidden">
                <div class="w-14 h-14 bg-white border border-neutral-300 flex items-center justify-center text-[var(--theme-primary,#344c3d)]">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-neutral-800 block">{{ __('admin.media.upload') }}</span>
                    <span class="text-[11px] text-neutral-400 mt-1 block">{{ __('admin.media.upload_hint', ['max' => \App\Services\MediaService::imageMaxLabel()]) }}</span>
                </div>
            </label>
            <p id="mediap-upload-status" class="mt-4 hidden text-xs font-semibold text-center text-neutral-600 bg-neutral-100 py-2 border border-neutral-200"></p>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between gap-3 border-t border-neutral-200 px-6 py-3.5 bg-white">
            <span id="mediap-count" class="text-xs font-semibold text-[var(--theme-primary,#344c3d)]"></span>
            <div class="flex items-center gap-2 ml-auto">
                <button type="button" data-mediap-close class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">
                    {{ __('admin.common.cancel') }}
                </button>
                <button type="button" id="mediap-apply" disabled class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em] disabled:opacity-40 disabled:cursor-not-allowed">
                    {{ __('admin.media.select') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.mediaPicker = (function () {
    var modal     = document.getElementById('mediap-modal');
    var grid      = document.getElementById('mediap-grid');
    var pager     = document.getElementById('mediap-pager');
    var qEl       = document.getElementById('mediap-q');
    var searchBtn = document.getElementById('mediap-search-btn');
    var fileEl    = document.getElementById('mediap-file');
    var statusEl  = document.getElementById('mediap-upload-status');
    var countEl   = document.getElementById('mediap-count');
    var applyBtn  = document.getElementById('mediap-apply');
    var libPane   = document.getElementById('mediap-library');
    var upPane    = document.getElementById('mediap-upload');
    var dropzone  = document.getElementById('mediap-dropzone');

    var jsonUrl   = @json(route('admin.media.json'));
    var uploadUrl = @json(route('admin.media.upload'));
    var csrf      = @json(csrf_token());

    var selectedTemplate = @json(__('admin.media.picker_selected', ['count' => ':count']));
    var uploadingLabel   = @json(__('admin.media.picker_uploading'));
    var uploadedTemplate = @json(__('admin.media.picker_uploaded', ['count' => ':count']));
    var failedLabel      = @json(__('admin.media.picker_failed'));
    var expiredLabel     = @json(__('admin.media.picker_expired'));
    var httpLabel        = @json(__('admin.media.picker_http_error'));
    var emptyLabel       = @json(__('admin.media.empty'));

    var multiple = false, onPick = null, selected = new Map(), page = 1, q = '', cache = [];

    function setTab(name) {
        document.querySelectorAll('[data-mediap-tab]').forEach(function (btn) {
            var active = btn.dataset.mediapTab === name;
            btn.classList.toggle('is-active', active);
            if (active) {
                btn.className = 'mediap-tab is-active px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-[#344c3d] text-white shadow-xs transition-colors';
            } else {
                btn.className = 'mediap-tab px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 transition-colors';
            }
        });
        libPane.classList.toggle('hidden', name !== 'library');
        upPane.classList.toggle('hidden', name !== 'upload');
    }

    document.querySelectorAll('[data-mediap-tab]').forEach(function (btn) {
        btn.addEventListener('click', function () { setTab(btn.dataset.mediapTab); });
    });

    function render() {
        grid.innerHTML = '';
        if (!cache.length) {
            var empty = document.createElement('div');
            empty.className = 'mediap-empty';
            empty.textContent = emptyLabel;
            grid.appendChild(empty);
        } else {
            cache.forEach(function (m) {
                var isSel = selected.has(m.id);
                var tile = document.createElement('div');
                tile.className = 'mediap-tile' + (isSel ? ' selected' : '');
                tile.title = m.name;

                var img = document.createElement('img');
                img.src = m.url;
                img.loading = 'lazy';
                img.alt = m.name;

                var chk = document.createElement('span');
                chk.className = 'mediap-check';
                chk.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';

                var cap = document.createElement('div');
                cap.className = 'mediap-caption';
                cap.textContent = m.name;

                tile.appendChild(img);
                tile.appendChild(chk);
                tile.appendChild(cap);

                tile.addEventListener('click', function () {
                    if (multiple) {
                        if (selected.has(m.id)) {
                            selected.delete(m.id);
                        } else {
                            selected.set(m.id, m);
                        }
                    } else {
                        selected.clear();
                        selected.set(m.id, m);
                    }
                    render();
                });

                tile.addEventListener('dblclick', function () {
                    selected.clear();
                    selected.set(m.id, m);
                    applySelection();
                });

                grid.appendChild(tile);
            });
        }

        countEl.textContent = selected.size ? selectedTemplate.replace(':count', selected.size) : '';
        applyBtn.disabled = selected.size === 0;
    }

    function renderPager(meta) {
        pager.innerHTML = '';
        if (!meta || meta.last_page <= 1) return;

        for (var p = 1; p <= meta.last_page; p++) {
            (function (pp) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = pp;
                btn.className = 'h-7 w-7 text-xs font-semibold rounded-lg transition-colors ' +
                    (pp === meta.current_page
                        ? 'bg-[#344c3d] text-white shadow-xs'
                        : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100');
                btn.addEventListener('click', function () {
                    page = pp;
                    load();
                });
                pager.appendChild(btn);
            })(p);
        }
    }

    function load() {
        var url = jsonUrl + '?page=' + page + (q ? '&q=' + encodeURIComponent(q) : '');
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json(); })
            .then(function (body) {
                cache = body.data || [];
                render();
                renderPager(body);
            })
            .catch(function () {
                cache = [];
                render();
            });
    }

    function open(opts) {
        multiple = !!(opts && opts.multiple);
        onPick   = (opts && opts.onPick) || null;
        selected.clear();
        page = 1;
        q = '';
        qEl.value = '';
        setTab((opts && opts.tab) === 'upload' ? 'upload' : 'library');
        render();
        modal.classList.add('open');
        load();
    }

    function close() {
        modal.classList.remove('open');
    }

    function applySelection() {
        if (!selected.size || !onPick) return;
        var picked = Array.from(selected.values());
        onPick(picked);
        close();
    }

    // Search trigger
    function doSearch() {
        q = qEl.value.trim();
        page = 1;
        load();
    }
    searchBtn.addEventListener('click', doSearch);
    qEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            doSearch();
        }
    });

    // Upload files handler
    function uploadFiles(fileList) {
        if (!fileList || !fileList.length) return;

        var fd = new FormData();
        for (var i = 0; i < fileList.length; i++) {
            fd.append('files[]', fileList[i]);
        }

        statusEl.textContent = uploadingLabel;
        statusEl.classList.remove('hidden', 'text-red-600');
        statusEl.classList.add('text-slate-600');

        fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: fd,
        })
            .then(function (res) {
                return res.text().then(function (text) {
                    var body = null;
                    try { body = JSON.parse(text); } catch (e) { body = null; }
                    if (res.ok) return body || {};
                    if (res.status === 419) throw new Error(expiredLabel);
                    if (body === null) throw new Error(httpLabel.replace(':status', res.status));
                    throw new Error(body.message || failedLabel);
                });
            })
            .then(function (body) {
                var uploaded = body.data || [];
                uploaded.forEach(function (m) {
                    cache.unshift(m);
                });

                selected.clear();
                if (multiple) {
                    uploaded.forEach(function (m) { selected.set(m.id, m); });
                } else if (uploaded[0]) {
                    selected.set(uploaded[0].id, uploaded[0]);
                }

                statusEl.textContent = uploadedTemplate.replace(':count', uploaded.length);
                setTab('library');
                render();
                fileEl.value = '';
            })
            .catch(function (err) {
                statusEl.textContent = err.message;
                statusEl.classList.remove('text-slate-600');
                statusEl.classList.add('text-red-600');
            });
    }

    fileEl.addEventListener('change', function () {
        uploadFiles(fileEl.files);
    });

    // Drag and Drop
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.add('border-[#344c3d]', 'bg-emerald-50/40');
            });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-[#344c3d]', 'bg-emerald-50/40');
            });
        });
        dropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files) {
                uploadFiles(e.dataTransfer.files);
            }
        });
    }

    applyBtn.addEventListener('click', applySelection);

    modal.querySelectorAll('[data-mediap-close]').forEach(function (el) {
        el.addEventListener('click', close);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) {
            close();
        }
    });

    return {
        open: open,
        close: close,
    };
})();
</script>
