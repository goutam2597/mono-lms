<x-layouts.dashboard :title="__('admin.categories.title').' - Mono LMS'" active="categories" userType="admin">

    <style>
        #cat-modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); padding: 24px; box-sizing: border-box; }
        #cat-modal.open { display: flex !important; }
        #cat-modal .panel { background: #ffffff; width: 520px; max-width: 100%; max-height: calc(100vh - 48px); overflow-y: auto; padding: 24px; border-radius: 0 !important; border: 1px solid #e5e5e5; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); box-sizing: border-box; }
    </style>

    <div class="bg-white border border-[#e7e7e7] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.categories.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.categories.subtitle') }}</p>
            </div>
            <button type="button" id="btn-add-category" class="action-btn action-btn-primary h-10 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">
                + {{ __('admin.categories.create') }}
            </button>
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
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $category->id }}" form="bulk-form" aria-label="{{ $category->name }}">
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-[#191919]">
                                {{ $category->name }}
                                <span class="text-xs text-gray-400 font-normal">({{ $category->slug }})</span>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($category->imageUrl())
                                    <img src="{{ $category->imageUrl() }}" 
                                         alt="{{ $category->name }}" 
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

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" id="cat-name-input" required maxlength="100" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <!-- Category Image Media Selector -->
                <div id="cat-media-wrapper">
                    <x-media-selector
                        name="image"
                        label="Category Image / Logo"
                        shape="square"
                        hint="Used inside the central square on category cards. Leave blank to use fallback icon."
                    />
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

        function openAdd() {
            heading.textContent = @json(__('admin.categories.create'));
            form.action = storeUrl;
            method.disabled = true;
            nameIn.value = '';
            colorIn.value = '#137547';
            colorPrev.value = '#137547';
            iconIn.value = '';
            setMediaValue('', '');
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
