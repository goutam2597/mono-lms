<x-layouts.dashboard :title="__('admin.students.title').' - Mono LMS'" active="students" userType="admin">

    <style>
        #student-modal { position: fixed; inset: 0; z-index: 120; display: none; align-items: center; justify-content: center; background: rgba(10, 10, 10, 0.65); backdrop-filter: blur(4px); padding: 24px; box-sizing: border-box; }
        #student-modal.open { display: flex !important; }
        #student-modal .panel { background: #ffffff; width: 520px; max-width: 100%; max-height: calc(100vh - 48px); overflow-y: auto; padding: 24px; border-radius: 0 !important; border: 1px solid #e5e5e5; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); box-sizing: border-box; }
    </style>

    <div class="bg-white border border-[#e7e7e7] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.students.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.students.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" class="flex items-center gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.common.search') }}..." class="h-9 border border-neutral-300 px-3.5 text-xs w-56 focus:outline-none focus:border-neutral-900">
                    <button type="submit" class="action-btn action-btn-ghost h-9 px-4 text-[10px]">{{ __('app.common.search') }}</button>
                </form>
                <button type="button" id="add-student-btn" class="action-btn action-btn-primary h-9 px-4 text-[10px] cursor-pointer">
                    + Add Student
                </button>
            </div>
        </div>

        <x-bulk-toolbar action="{{ route('admin.students.bulk-destroy') }}" />

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="w-10 px-6 py-3">
                            <input type="checkbox" id="check-all" class="control" aria-label="{{ __('admin.common.select_all') ?? 'Select All' }}">
                        </th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.students.enrollments') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.students.joined') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 text-right font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="w-10 px-6 py-3.5">
                                <input type="checkbox" class="control row-check" name="ids[]" value="{{ $student->id }}" form="bulk-form" aria-label="{{ $student->name }}">
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if ($student->avatarUrl())
                                        <img src="{{ $student->avatarUrl() }}" alt="{{ $student->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                                    @else
                                        <span class="w-9 h-9 rounded-full bg-[#0dcaf0]/10 text-[#0891b2] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-[#191919]">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $student->enrollments_count }}</td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $student->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center justify-center h-6 px-2.5 text-[10px] font-semibold uppercase tracking-[0.12em] {{ $student->status === 'active' ? 'bg-[#03c95a]/10 text-[#0d7a38] border border-[#03c95a]/20' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    {{ $student->status === 'active' ? __('app.common.active') : __('app.common.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            class="edit-student-btn action-btn action-btn-ghost h-7 px-3 text-[10px] cursor-pointer"
                                            data-action="{{ route('admin.students.update', $student) }}"
                                            data-student="{{ json_encode([
                                                'id' => $student->id,
                                                'name' => $student->name,
                                                'email' => $student->email,
                                                'phone' => $student->phone,
                                                'avatar' => $student->avatar,
                                                'avatar_url' => $student->avatarUrl(),
                                                'status' => $student->status,
                                            ]) }}">
                                        {{ __('app.common.edit') }}
                                    </button>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px] cursor-pointer">{{ __('app.common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $students->links() }}</div>
    </div>

    <!-- Student Add / Edit Modal (mono_market pattern) -->
    <div id="student-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
                <h3 class="text-base font-bold text-neutral-900" id="student-modal-heading">Add Student</h3>
                <button type="button" id="student-modal-close" class="text-neutral-400 hover:text-neutral-900 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" id="student-form" action="{{ route('admin.students.store') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" id="student-form-method" value="POST" disabled>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.name') }} *</label>
                    <input type="text" name="name" id="student-name" required maxlength="255" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.email') }} *</label>
                    <input type="email" name="email" id="student-email" required maxlength="255" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">
                        {{ __('app.common.password') }}
                        <span id="student-password-hint" class="text-neutral-400 font-normal">*</span>
                    </label>
                    <input type="password" name="password" id="student-password" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.phone') }}</label>
                        <input type="text" name="phone" id="student-phone" maxlength="30" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-semibold uppercase tracking-wider text-[11px] text-neutral-600">{{ __('app.common.status') }}</label>
                        <select name="status" id="student-status" class="h-11 w-full border border-neutral-300 px-4 text-sm focus:outline-none focus:border-neutral-900 bg-white">
                            <option value="active">{{ __('app.common.active') }}</option>
                            <option value="inactive">{{ __('app.common.inactive') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Avatar Media Selector -->
                <div id="student-media-wrapper">
                    <x-media-selector
                        name="avatar"
                        label="Avatar Image"
                        shape="circle"
                        hint="Picked from media library. Leave blank to generate initials avatar."
                    />
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-neutral-200">
                    <button type="button" id="student-modal-cancel" class="action-btn action-btn-ghost h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em] cursor-pointer">{{ __('app.common.cancel') }}</button>
                    <button type="submit" class="action-btn action-btn-primary h-9 px-5 text-[11px] font-medium uppercase tracking-[0.14em] cursor-pointer">{{ __('app.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal       = document.getElementById('student-modal');
        var form        = document.getElementById('student-form');
        var heading     = document.getElementById('student-modal-heading');
        var method      = document.getElementById('student-form-method');
        var addBtn      = document.getElementById('add-student-btn');
        var closeBtn    = document.getElementById('student-modal-close');
        var cancelBtn   = document.getElementById('student-modal-cancel');
        var nameIn      = document.getElementById('student-name');
        var emailIn     = document.getElementById('student-email');
        var passIn      = document.getElementById('student-password');
        var passHint    = document.getElementById('student-password-hint');
        var phoneIn     = document.getElementById('student-phone');
        var statusIn    = document.getElementById('student-status');
        var mediaWrap   = document.getElementById('student-media-wrapper');
        var storeUrl    = @json(route('admin.students.store'));

        function setMediaValue(path, url) {
            if (!mediaWrap) return;
            var input       = mediaWrap.querySelector('[data-media-input]');
            var img         = mediaWrap.querySelector('[data-media-img]');
            var placeholder = mediaWrap.querySelector('[data-media-placeholder]');
            var removeBtn   = mediaWrap.querySelector('[data-media-remove]');

            if (input) input.value = path || '';
            if (url || path) {
                if (img) { img.src = url || path; img.classList.remove('hidden'); }
                if (placeholder) placeholder.classList.add('hidden');
                if (removeBtn) removeBtn.classList.remove('hidden');
            } else {
                if (img) { img.src = ''; img.classList.add('hidden'); }
                if (placeholder) placeholder.classList.remove('hidden');
                if (removeBtn) removeBtn.classList.add('hidden');
            }
        }

        function openAdd() {
            heading.textContent = 'Add Student';
            form.action = storeUrl;
            method.disabled = true;
            nameIn.value = '';
            emailIn.value = '';
            passIn.value = '';
            passIn.required = true;
            passHint.textContent = '*';
            phoneIn.value = '';
            statusIn.value = 'active';
            setMediaValue('', '');
            modal.classList.add('open');
            setTimeout(function () { nameIn.focus(); }, 100);
        }

        function openEdit(data, action) {
            heading.textContent = @json(__('app.common.edit')) + ' Student — ' + (data.name || '');
            form.action = action;
            method.disabled = false;
            method.value = 'PUT';
            nameIn.value = data.name || '';
            emailIn.value = data.email || '';
            passIn.value = '';
            passIn.required = false;
            passHint.textContent = '(leave blank to keep current)';
            phoneIn.value = data.phone || '';
            statusIn.value = data.status || 'active';
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

        document.querySelectorAll('.edit-student-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var data = JSON.parse(btn.dataset.student || '{}');
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