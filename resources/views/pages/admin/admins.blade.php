<x-layouts.dashboard :title="__('admin.admins.title').' - Mono LMS'" active="admins" userType="admin">

    <style>
        #admin-modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); padding: 24px; box-sizing: border-box; }
        #admin-modal.open { display: flex !important; }
        #admin-modal .panel { background: #ffffff; width: 520px; max-width: 100%; max-height: calc(100vh - 48px); overflow-y: auto; padding: 24px; border-radius: 0 !important; border: 1px solid #e5e5e5; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); box-sizing: border-box; }
    </style>

    <div class="bg-white border border-[#e7e7e7] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.admins.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.admins.subtitle') }}</p>
            </div>
            <button type="button" id="add-admin-btn" class="action-btn action-btn-primary h-10 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">+ {{ __('admin.admins.create') }}</button>
        </div>

        <x-bulk-toolbar action="{{ route('admin.admins.bulk-destroy') }}" />

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="w-10 px-6 py-3">
                            <input type="checkbox" id="check-all" class="control" aria-label="{{ __('admin.common.select_all') ?? 'Select All' }}">
                        </th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.admins.role') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 text-right font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="w-10 px-6 py-3.5">
                                @if ($admin->id !== auth('admin')->id())
                                    <input type="checkbox" class="control row-check" name="ids[]" value="{{ $admin->id }}" form="bulk-form" aria-label="{{ $admin->name }}">
                                @else
                                    <span class="w-4 h-4 inline-block"></span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if ($admin->avatarUrl())
                                        <img src="{{ $admin->avatarUrl() }}" alt="{{ $admin->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                        <span class="w-9 h-9 rounded-full bg-[#392c7d]/10 text-[#392c7d] font-bold text-sm items-center justify-center hidden">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                    @else
                                        <span class="w-9 h-9 rounded-full bg-[#392c7d]/10 text-[#392c7d] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-[#191919]">{{ $admin->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $admin->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($admin->is_super_admin)
                                    <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] bg-[#738a6e]/10 text-[#738a6e] border border-[#738a6e]/20">{{ __('admin.admins.super_admin') }}</span>
                                @else
                                    <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] bg-[#392c7d]/10 text-[#392c7d] border border-[#392c7d]/20">{{ $admin->role?->name ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] {{ $admin->status === 'active' ? 'bg-[#03c95a]/10 text-[#0d7a38] border border-[#03c95a]/20' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    {{ $admin->status === 'active' ? __('app.common.active') : __('app.common.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            class="edit-admin-btn action-btn action-btn-ghost h-7 px-3 text-[10px]"
                                            data-action="{{ route('admin.admins.update', $admin) }}"
                                            data-admin="{{ json_encode([
                                                'id' => $admin->id,
                                                'name' => $admin->name,
                                                'email' => $admin->email,
                                                'phone' => $admin->phone,
                                                'avatar' => $admin->avatar,
                                                'avatar_url' => $admin->avatarUrl(),
                                                'role_id' => $admin->role_id,
                                                'status' => $admin->status,
                                                'is_super_admin' => (bool)$admin->is_super_admin,
                                            ]) }}">
                                        {{ __('app.common.edit') }}
                                    </button>
                                    @unless ($admin->id === auth('admin')->id())
                                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px]">{{ __('app.common.delete') }}</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $admins->links() }}</div>
    </div>

    <!-- Administrator Add / Edit Modal (mono_market pattern) -->
    <div id="admin-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                <h3 class="text-base font-bold text-neutral-900" id="admin-modal-heading">{{ __('admin.admins.create') }}</h3>
                <button type="button" id="admin-modal-close" class="text-neutral-400 hover:text-neutral-900 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" id="admin-form" action="{{ route('admin.admins.store') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" id="admin-form-method" value="POST" disabled>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" id="admin-name" required maxlength="255" class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.email') }} *</label>
                    <input type="email" name="email" id="admin-email" required maxlength="255" class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">
                        {{ __('app.common.password') }}
                        <span id="admin-password-hint" class="text-neutral-400 font-normal">({{ __('instructor.profile.new_password_hint') }})</span>
                    </label>
                    <input type="password" name="password" id="admin-password" class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <!-- Avatar Media Selector -->
                <div id="admin-media-wrapper">
                    <x-media-selector
                        name="avatar"
                        label="Avatar Image"
                        shape="circle"
                        hint="Picked from media library. Leave blank to generate initials avatar."
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('admin.admins.role') }}</label>
                        <select name="role_id" id="admin-role" data-mselect class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900 bg-white">
                            <option value="">—</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.status') }}</label>
                        <select name="status" id="admin-status" data-mselect data-mselect-no-search class="h-11 w-full border border-neutral-300 rounded-lg px-4 text-sm focus:outline-none focus:border-neutral-900 bg-white">
                            <option value="active">{{ __('app.common.active') }}</option>
                            <option value="inactive">{{ __('app.common.inactive') }}</option>
                        </select>
                    </div>
                </div>

                <label class="flex items-start gap-2.5 cursor-pointer select-none pt-1">
                    <input type="checkbox" name="is_super_admin" id="admin-super" value="1" class="control mt-0.5">
                    <span class="text-xs text-neutral-800 font-medium">{{ __('admin.admins.super_admin') }} <span class="text-neutral-400 font-normal">— {{ __('admin.admins.super_admin_hint') }}</span></span>
                </label>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-neutral-200">
                    <button type="button" id="admin-modal-cancel" class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.cancel') }}</button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em]">{{ __('app.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal       = document.getElementById('admin-modal');
        var form        = document.getElementById('admin-form');
        var heading     = document.getElementById('admin-modal-heading');
        var method      = document.getElementById('admin-form-method');
        var addBtn      = document.getElementById('add-admin-btn');
        var closeBtn    = document.getElementById('admin-modal-close');
        var cancelBtn   = document.getElementById('admin-modal-cancel');
        var nameIn      = document.getElementById('admin-name');
        var emailIn     = document.getElementById('admin-email');
        var passIn      = document.getElementById('admin-password');
        var passHint    = document.getElementById('admin-password-hint');
        var roleIn      = document.getElementById('admin-role');
        var statusIn    = document.getElementById('admin-status');
        var superIn     = document.getElementById('admin-super');
        var mediaWrap   = document.getElementById('admin-media-wrapper');
        var storeUrl    = @json(route('admin.admins.store'));

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
            heading.textContent = @json(__('admin.admins.create'));
            form.action = storeUrl;
            method.disabled = true;
            nameIn.value = '';
            emailIn.value = '';
            passIn.value = '';
            passIn.required = true;
            passHint.textContent = '*';
            roleIn.value = '';
            statusIn.value = 'active';
            superIn.checked = false;
            setMediaValue('', '');
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function openEdit(data, action) {
            heading.textContent = @json(__('admin.admins.edit'));
            form.action = action;
            method.disabled = false;
            method.value = 'PUT';
            nameIn.value = data.name || '';
            emailIn.value = data.email || '';
            passIn.value = '';
            passIn.required = false;
            passHint.textContent = '(' + @json(__('instructor.profile.new_password_hint')) + ')';
            roleIn.value = data.role_id || '';
            statusIn.value = data.status || 'active';
            superIn.checked = Boolean(data.is_super_admin);
            setMediaValue(data.avatar || '', data.avatar_url || '');
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        if (addBtn) addBtn.addEventListener('click', openAdd);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        document.querySelectorAll('.edit-admin-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var data = JSON.parse(btn.dataset.admin || '{}');
                openEdit(data, btn.dataset.action);
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });
    });
    </script>

</x-layouts.dashboard>
