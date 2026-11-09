<x-layouts.dashboard :title="__('admin.media.heading').' - Mono LMS'" active="media" userType="admin">

    <div class="space-y-6">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-none p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">{{ __('admin.media.heading') }}</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-none bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $media->total() }} {{ __('admin.media.items') }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ __('admin.media.sub') }}</p>
            </div>

            <!-- Upload Form -->
            <form id="media-upload-form" method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="flex items-center gap-3">
                @csrf
                <input type="file" name="files[]" id="media-files" multiple accept="image/jpeg,image/png,image/webp,image/svg+xml" class="hidden">
                <button type="button" id="media-upload-btn" class="action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider shrink-0 cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('admin.media.upload') }}</span>
                </button>
            </form>
        </div>

        @if ($errors->any())
            <div class="rounded-none border border-red-200 bg-red-50 p-4 text-xs text-red-700 shadow-xs">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search & Bulk Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-white border border-slate-200 rounded-none p-4 shadow-xs">
            <!-- Search -->
            <form method="GET" action="{{ route('admin.media.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <input type="search" name="q" value="{{ $q }}" placeholder="{{ __('admin.media.search_placeholder') }}"
                           class="w-full h-9 pl-9 pr-3 text-xs border border-slate-200 rounded-none bg-slate-50/50 focus:bg-white focus:outline-none focus:border-slate-900 transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <button type="submit" class="action-btn action-btn-ghost h-9 px-3.5 text-xs font-semibold uppercase tracking-wider cursor-pointer">
                    {{ __('admin.common.search') }}
                </button>
                @if ($q !== '')
                    <a href="{{ route('admin.media.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-medium ml-1">
                        {{ __('admin.media.clear') }}
                    </a>
                @endif
            </form>

            <!-- Bulk Delete Actions -->
            <form id="bulk-form" method="POST" action="{{ route('admin.media.bulk-destroy') }}" onsubmit="return confirm('{{ __('admin.media.bulk_confirm') }}')">
                @csrf
            </form>
            <div class="flex items-center gap-3 ml-auto">
                <span class="text-xs text-slate-500 font-medium">
                    <span id="sel-count" class="font-bold text-slate-800">0</span> {{ __('admin.common.selected') }}
                </span>
                <button type="submit" form="bulk-form" id="bulk-btn" disabled
                        class="action-btn action-btn-danger h-9 px-3.5 text-xs font-semibold uppercase tracking-wider cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                    {{ __('admin.common.delete_selected') }}
                </button>
            </div>
        </div>

        <!-- Media Grid -->
        @if ($media->isEmpty())
            <div class="bg-white border border-slate-200 rounded-none p-16 text-center shadow-xs">
                <div class="w-16 h-16 rounded-none bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">{{ __('admin.media.empty') }}</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    {{ __('admin.media.upload_hint', ['max' => \App\Services\MediaService::imageMaxLabel()]) }}
                </p>
                <button type="button" onclick="document.getElementById('media-files').click()"
                        class="mt-4 action-btn action-btn-primary h-9 px-4 text-xs font-semibold uppercase tracking-wider cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ __('admin.media.upload') }}</span>
                </button>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3.5">
                @foreach ($media as $item)
                    <div class="group relative overflow-hidden rounded-none border border-slate-200 bg-white hover:border-slate-400 hover:shadow-md transition-all flex flex-col justify-between">
                        <!-- Checkbox Overlay -->
                        <label class="absolute top-2 left-2 z-20 flex h-5 w-5 cursor-pointer items-center justify-center rounded-none border border-slate-300 bg-white/90 backdrop-blur-xs transition-all hover:border-slate-500">
                            <input type="checkbox" class="row-check sr-only" name="ids[]" value="{{ $item->id }}" form="bulk-form">
                            <svg class="check-icon hidden w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </label>

                        <!-- Actions Hover Floating Menu -->
                        <div class="absolute top-2 right-2 z-20 hidden group-hover:flex items-center gap-1 bg-white/95 backdrop-blur-xs rounded-none p-0.5 shadow-sm border border-slate-200">
                            <!-- Copy URL -->
                            <button type="button" onclick="copyMediaUrl('{{ $item->url() }}', this)" title="Copy image URL"
                                    class="p-1 rounded-none text-slate-500 hover:text-[#344c3d] hover:bg-slate-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/>
                                </svg>
                            </button>
                            <!-- Delete -->
                            <form method="POST" action="{{ route('admin.media.destroy', $item) }}" onsubmit="return confirm('{{ __('admin.media.delete_confirm') }}')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="{{ __('admin.common.delete') }}" class="p-1 rounded-none text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Thumbnail -->
                        <div class="aspect-square w-full overflow-hidden bg-slate-100 flex items-center justify-center relative cursor-pointer" onclick="openPreviewModal('{{ $item->url() }}', '{{ addslashes($item->name) }}', '{{ number_format($item->size / 1024, 0) }} KB', '{{ $item->width ? $item->width.'×'.$item->height.' px' : '' }}')">
                            <img src="{{ $item->url() }}" alt="{{ $item->alt ?? $item->name }}" loading="lazy" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <!-- Info Footer -->
                        <div class="p-2.5 border-t border-slate-100 bg-white">
                            <p class="truncate text-[11px] font-semibold text-slate-800 leading-tight" title="{{ $item->name }}">{{ $item->name }}</p>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                                <span>{{ number_format($item->size / 1024, 0) }} KB</span>
                                @if ($item->width && $item->height)
                                    <span>{{ $item->width }}×{{ $item->height }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $media->links() }}
            </div>
        @endif

    </div>

    <!-- Image Preview Modal -->
    <div id="media-preview-modal" class="modal-overlay" role="dialog" aria-modal="true" onclick="if(event.target===this)closePreviewModal()">
        <div class="panel panel-lg flex flex-col p-0! overflow-hidden rounded-none border border-slate-200">
            <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-white">
                <div class="min-w-0 pr-4">
                    <h3 id="prev-title" class="text-sm font-bold text-slate-800 truncate">Image Preview</h3>
                    <p id="prev-meta" class="text-xs text-slate-400 mt-0.5"></p>
                </div>
                <button type="button" onclick="closePreviewModal()" class="p-1 rounded-none text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-4 bg-slate-50 flex items-center justify-center max-h-[65vh] overflow-auto">
                <img id="prev-img" src="" alt="" class="max-h-[60vh] max-w-full rounded-none object-contain shadow-xs">
            </div>
            <div class="flex items-center justify-end gap-2 p-3 border-t border-slate-100 bg-white">
                <button type="button" id="prev-copy-btn" class="action-btn action-btn-ghost h-8 px-3 text-[10px] cursor-pointer">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                    <span>Copy URL</span>
                </button>
                <button type="button" onclick="closePreviewModal()" class="action-btn action-btn-primary h-8 px-4 text-[10px] cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Client Script -->
    <script>
    (function () {
        var fileInput = document.getElementById('media-files');
        var uploadBtn = document.getElementById('media-upload-btn');
        var uploadForm = document.getElementById('media-upload-form');

        if (uploadBtn && fileInput && uploadForm) {
            uploadBtn.addEventListener('click', function () { fileInput.click(); });
            fileInput.addEventListener('change', function () {
                if (fileInput.files.length) {
                    uploadBtn.disabled = true;
                    uploadBtn.innerHTML = '<span>Uploading...</span>';
                    uploadForm.submit();
                }
            });
        }

        var checks = document.querySelectorAll('.row-check');
        var countEl = document.getElementById('sel-count');
        var btn = document.getElementById('bulk-btn');

        function sync() {
            var selected = document.querySelectorAll('.row-check:checked');
            var n = selected.length;
            if (countEl) countEl.textContent = n;
            if (btn) btn.disabled = (n === 0);

            document.querySelectorAll('.row-check').forEach(function (cb) {
                var label = cb.closest('label');
                var icon = label ? label.querySelector('.check-icon') : null;
                if (!label) return;
                if (cb.checked) {
                    label.classList.add('bg-[#344c3d]', 'border-[#344c3d]');
                    label.classList.remove('bg-white/90', 'border-slate-300');
                    if (icon) icon.classList.remove('hidden');
                } else {
                    label.classList.remove('bg-[#344c3d]', 'border-[#344c3d]');
                    label.classList.add('bg-white/90', 'border-slate-300');
                    if (icon) icon.classList.add('hidden');
                }
            });
        }

        checks.forEach(function (c) { c.addEventListener('change', sync); });
    })();

    function copyMediaUrl(url, btn) {
        if (!navigator.clipboard) {
            prompt('Image URL:', url);
            return;
        }
        navigator.clipboard.writeText(url).then(function () {
            var orig = btn.innerHTML;
            btn.innerHTML = '<span class="text-[9px] font-bold text-emerald-600 px-1">Copied!</span>';
            setTimeout(function () { btn.innerHTML = orig; }, 1500);
        });
    }

    var currentPreviewUrl = '';
    function openPreviewModal(url, name, size, dims) {
        currentPreviewUrl = url;
        var modal = document.getElementById('media-preview-modal');
        var img = document.getElementById('prev-img');
        var title = document.getElementById('prev-title');
        var meta = document.getElementById('prev-meta');
        var copyBtn = document.getElementById('prev-copy-btn');

        img.src = url;
        title.textContent = name;
        meta.textContent = size + (dims ? ' · ' + dims : '');

        copyBtn.onclick = function () { copyMediaUrl(url, copyBtn); };

        modal.classList.add('open');
    }

    function closePreviewModal() {
        var modal = document.getElementById('media-preview-modal');
        modal.classList.remove('open');
    }
    </script>

</x-layouts.dashboard>
