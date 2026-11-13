{{-- Shared AI image-generation modal. Include once per page, then call:
     window.aiImageModal.open({ title, description, multiple, style, onApply }) --}}
<style>
    .aiimg-modal { position: fixed; inset: 0; z-index: 300; display: none; align-items: center; justify-content: center; padding: 20px; }
    .aiimg-modal.open { display: flex; }
    .aiimg-backdrop { position: absolute; inset: 0; background: rgba(10,10,10,.6); backdrop-filter: blur(4px); }
    .aiimg-card { position: relative; width: 100%; max-width: 560px; max-height: calc(100vh - 40px); overflow-y: auto; background: #fff; border: 1px solid #e5e5e5; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .aiimg-res { position: relative; aspect-ratio: 1; cursor: pointer; border: 2px solid transparent; border-radius: 8px; background: #f5f5f5; transition: border-color .15s; overflow: hidden; }
    .aiimg-res img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .aiimg-res:hover { border-color: #a3a3a3; }
    .aiimg-res.selected { border-color: #0a0a0a; }
    .aiimg-res .aiimg-check { position: absolute; top: 6px; right: 6px; display: none; width: 20px; height: 20px; border-radius: 4px; background: #0a0a0a; color: #fff; align-items: center; justify-content: center; }
    .aiimg-res.selected .aiimg-check { display: flex; }
    .aiimg-res.aiimg-crop { aspect-ratio: 2.4; aspect-ratio: var(--aiimg-ratio, 2.4); }
</style>

<div id="aiimg-modal" class="aiimg-modal" role="dialog" aria-modal="true">
    <div class="aiimg-backdrop" data-aiimg-close></div>
    <div class="aiimg-card">
        <div class="flex items-center justify-between border-b border-neutral-100 px-6 py-4">
            <h3 class="text-sm font-medium uppercase tracking-[0.14em] text-neutral-900">{{ __('admin.imagegen.title') ?? 'Generate image with AI' }}</h3>
            <button type="button" data-aiimg-close class="text-neutral-400 transition hover:text-neutral-900 cursor-pointer" aria-label="{{ __('app.common.cancel') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="space-y-4 px-6 py-5">
            <div>
                <label class="mb-1.5 block text-[11px] font-medium uppercase tracking-[0.14em] text-neutral-500">{{ __('admin.imagegen.prompt') ?? 'Prompt' }}</label>
                <textarea id="aiimg-prompt" rows="4" maxlength="4000"
                          class="w-full border border-neutral-300 rounded-lg px-4 py-3 text-sm placeholder:text-neutral-400 focus:border-neutral-900 focus:outline-none"></textarea>
                <p class="mt-1 text-[11px] text-neutral-400">{{ __('admin.imagegen.prompt_hint') ?? 'Built from the form\'s title and description — edit freely before generating.' }}</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-[11px] font-medium uppercase tracking-[0.14em] text-neutral-500">{{ __('admin.imagegen.size') ?? 'Size' }}</label>
                    <select id="aiimg-size"
                            class="h-11 w-full cursor-pointer border border-neutral-300 rounded-lg bg-white px-3 text-sm focus:border-neutral-900 focus:outline-none">
                        <option value="1024x1024">{{ __('admin.imagegen.size_square') ?? 'Square — 1024×1024' }}</option>
                        <option value="1792x1024" selected>{{ __('admin.imagegen.size_landscape') ?? 'Landscape — 1792×1024' }}</option>
                        <option value="1024x1792">{{ __('admin.imagegen.size_portrait') ?? 'Portrait — 1024×1792' }}</option>
                    </select>
                </div>
                <div id="aiimg-count-wrap">
                    <label class="mb-1.5 block text-[11px] font-medium uppercase tracking-[0.14em] text-neutral-500">{{ __('admin.imagegen.count') ?? 'Images' }}</label>
                    <select id="aiimg-count"
                            class="h-11 w-full cursor-pointer border border-neutral-300 rounded-lg bg-white px-3 text-sm focus:border-neutral-900 focus:outline-none">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
            </div>

            <div id="aiimg-results" class="hidden grid-cols-2 gap-3 sm:grid-cols-3"></div>
            <p id="aiimg-cropnote" class="hidden text-[11px] leading-relaxed text-neutral-400"></p>
            <p id="aiimg-status" class="hidden text-[12px] text-neutral-500"></p>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-neutral-100 px-6 py-4">
            <button type="button" data-aiimg-close class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em] cursor-pointer">{{ __('app.common.cancel') }}</button>
            <button type="button" id="aiimg-run" class="action-btn h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em] disabled:opacity-60 cursor-pointer">{{ __('admin.imagegen.generate') ?? 'Generate' }}</button>
            <button type="button" id="aiimg-apply" disabled class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em] disabled:cursor-not-allowed disabled:opacity-40 cursor-pointer">{{ __('admin.imagegen.apply') ?? 'Use selected' }}</button>
        </div>
    </div>
</div>

<script>
window.aiImageModal = (function () {
    var modal    = document.getElementById('aiimg-modal');
    if (!modal) return null;
    var promptEl = document.getElementById('aiimg-prompt');
    var sizeEl   = document.getElementById('aiimg-size');
    var countEl  = document.getElementById('aiimg-count');
    var countWrap = document.getElementById('aiimg-count-wrap');
    var results  = document.getElementById('aiimg-results');
    var status   = document.getElementById('aiimg-status');
    var runBtn   = document.getElementById('aiimg-run');
    var applyBtn = document.getElementById('aiimg-apply');
    var cropNoteEl = document.getElementById('aiimg-cropnote');

    var url      = @json(request()->routeIs('instructor.*') ? route('instructor.generate.image') : route('admin.generate.image'));
    var token    = @json(csrf_token());
    var lblGenerating = @json(__('admin.imagegen.generating') ?? 'Generating…');
    var lblGenerate   = @json(__('admin.imagegen.generate') ?? 'Generate');
    var lblFailed     = @json(__('admin.imagegen.failed') ?? 'Image generation failed.');
    var lblPick       = @json(__('admin.imagegen.pick') ?? 'Click an image to select it, then "Use selected".');

    var multiple = false;
    var onApply  = null;
    var images   = [];
    var cropRatio = 0;
    var sizeDefaultHTML = sizeEl ? sizeEl.innerHTML : '';

    function setSizeOptions(sizes) {
        if (!sizeEl) return;
        if (sizes && sizes.length) {
            sizeEl.innerHTML = '';
            sizes.forEach(function (s) {
                var o = document.createElement('option');
                o.value = s.value;
                o.textContent = s.label || s.value;
                sizeEl.appendChild(o);
            });
        } else if (sizeEl.innerHTML !== sizeDefaultHTML) {
            sizeEl.innerHTML = sizeDefaultHTML;
        }
    }

    function stripTags(html) {
        var d = document.createElement('div');
        d.innerHTML = html || '';
        return d.textContent.replace(/\s+/g, ' ').trim();
    }

    function setStatus(msg, isError) {
        if (!status) return;
        status.textContent = msg || '';
        status.classList.toggle('hidden', ! msg);
        status.style.color = isError ? '#dc2626' : '';
    }

    function selected() {
        return images.filter(function (im) { return im.selected; });
    }

    function syncApply() {
        if (applyBtn) applyBtn.disabled = selected().length === 0;
    }

    function render() {
        if (!results) return;
        results.innerHTML = '';
        images.forEach(function (im, i) {
            var tile = document.createElement('div');
            tile.className = 'aiimg-res' + (im.selected ? ' selected' : '') + (cropRatio > 0 ? ' aiimg-crop' : '');
            var img = document.createElement('img');
            img.src = im.url;
            img.alt = '';
            var check = document.createElement('span');
            check.className = 'aiimg-check';
            check.innerHTML = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>';
            tile.appendChild(img);
            tile.appendChild(check);
            tile.addEventListener('click', function () {
                if (multiple) {
                    im.selected = ! im.selected;
                } else {
                    images.forEach(function (other) { other.selected = false; });
                    im.selected = true;
                }
                render();
                syncApply();
            });
            results.appendChild(tile);
        });
        results.classList.toggle('hidden', images.length === 0);
        results.classList.toggle('grid', images.length > 0);
    }

    function close() {
        modal.classList.remove('open');
        images = [];
        render();
        syncApply();
        setStatus('', false);
    }

    modal.querySelectorAll('[data-aiimg-close]').forEach(function (el) {
        el.addEventListener('click', close);
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) close();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) close();
    });

    if (runBtn) {
        runBtn.addEventListener('click', function () {
            var prompt = (promptEl ? promptEl.value : '').trim();
            if (! prompt) {
                setStatus('Enter a prompt first.', true);
                return;
            }
            runBtn.disabled = true;
            runBtn.textContent = lblGenerating;
            setStatus('', false);

            var size = sizeEl ? sizeEl.value : '1792x1024';
            var count = parseInt(countEl ? countEl.value : '1', 10) || 1;

            var fx = window.aiFx && window.aiFx.button
                ? window.aiFx.button.start(runBtn, {
                    label: lblGenerating,
                    console: @json(__('admin.imagegen.title') ?? 'Generate image with AI'),
                    note: prompt + ' · ' + size,
                    waiting: 'generating ' + count + ' image(s)…'
                })
                : null;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    prompt: prompt,
                    size: size,
                    count: count
                })
            }).then(function (r) {
                return r.json().then(function (body) {
                    return { ok: r.ok, body: body };
                });
            }).then(function (res) {
                if (! res.ok) {
                    var msg = (res.body && res.body.message) || lblFailed;
                    setStatus(msg, true);
                    if (fx) fx.done(false, msg);
                    return;
                }
                var list = (res.body && res.body.images) || [];
                images = list.map(function (item, idx) {
                    return {
                        path: item.path,
                        url: item.url,
                        selected: ! multiple && idx === 0
                    };
                });
                render();
                syncApply();
                if (images.length) {
                    setStatus(multiple ? 'Select the images you want, then click "' + (applyBtn ? applyBtn.textContent.trim() : 'Use selected') + '".' : lblPick, false);
                }
                if (fx) fx.done(true, 'generated ' + images.length + ' image(s)');
            }).catch(function (err) {
                var msg = (err && err.message) || lblFailed;
                setStatus(msg, true);
                if (fx) fx.done(false, msg);
            }).finally(function () {
                runBtn.disabled = false;
                runBtn.textContent = lblGenerate;
            });
        });
    }

    if (applyBtn) {
        applyBtn.addEventListener('click', function () {
            var chosen = selected();
            if (! chosen.length) return;
            if (typeof onApply === 'function') {
                onApply(chosen);
            }
            close();
        });
    }

    return {
        open: function (opts) {
            opts = opts || {};
            multiple = !! opts.multiple;
            onApply  = opts.onApply || null;
            images   = [];
            render();
            syncApply();
            setStatus('', false);

            if (countWrap) countWrap.style.display = multiple ? '' : 'none';
            if (countEl && ! multiple) countEl.value = '1';

            var parts = [];
            if (opts.title) parts.push(stripTags(opts.title));
            if (opts.description) parts.push(stripTags(opts.description).slice(0, 300));
            if (opts.style) parts.push(opts.style);
            if (promptEl) promptEl.value = parts.filter(Boolean).join(', ');

            if (opts.size && sizeEl) sizeEl.value = opts.size;

            modal.classList.add('open');
            setTimeout(function () { if (promptEl) promptEl.focus(); }, 50);
        },
        close: close
    };
})();
</script>
