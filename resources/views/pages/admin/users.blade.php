<x-layouts.dashboard title="All Users - Mono LMS" active="users" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">User Directory</h1>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ $stats['total'] }} Total Accounts
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Unified directory of students, instructors, and system administrators.</p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="btn-add-user" class="action-btn action-btn-primary cursor-pointer">
                    <span>+ Add User</span>
                </button>
            </div>
        </div>

        <!-- 3 KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="bg-white border border-slate-200 p-4 shadow-xs hover:border-slate-400 transition-colors">
                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Learners (Students)</span>
                <p class="text-2xl font-extrabold text-blue-600 font-mono mt-1">{{ $stats['students'] }}</p>
                <span class="text-[11px] text-slate-400">Enrolled platform students</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'instructor']) }}" class="bg-white border border-slate-200 p-4 shadow-xs hover:border-slate-400 transition-colors">
                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Instructors & Mentors</span>
                <p class="text-2xl font-extrabold text-amber-600 font-mono mt-1">{{ $stats['instructors'] }}</p>
                <span class="text-[11px] text-slate-400">Active teaching faculty</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="bg-white border border-slate-200 p-4 shadow-xs hover:border-slate-400 transition-colors">
                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Administrators</span>
                <p class="text-2xl font-extrabold text-[#344c3d] font-mono mt-1">{{ $stats['admins'] }}</p>
                <span class="text-[11px] text-slate-400">Super admins & managers</span>
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white border border-slate-200 p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[260px]">
                <div class="relative flex-1 max-w-sm">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full pl-9 pr-3.5 py-1.5 text-xs border border-slate-200 focus:outline-none focus:border-[#344c3d]">
                </div>

                <div class="flex items-center gap-1.5">
                    <a href="{{ route('admin.users.index') }}"
                       class="h-8 px-3 inline-flex items-center border text-[10px] uppercase font-bold tracking-[0.12em] transition-colors {{ !request('role') ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        All
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'student']) }}"
                       class="h-8 px-3 inline-flex items-center border text-[10px] uppercase font-bold tracking-[0.12em] transition-colors {{ request('role') === 'student' ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        Students
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'instructor']) }}"
                       class="h-8 px-3 inline-flex items-center border text-[10px] uppercase font-bold tracking-[0.12em] transition-colors {{ request('role') === 'instructor' ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        Instructors
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
                       class="h-8 px-3 inline-flex items-center border text-[10px] uppercase font-bold tracking-[0.12em] transition-colors {{ request('role') === 'admin' ? 'bg-[#08131e] text-white border-[#08131e]' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        Admins
                    </a>
                </div>
            </div>

            <button type="submit" class="action-btn action-btn-primary cursor-pointer">
                Search
            </button>
        </form>

        <!-- Bulk Delete Toolbar -->
        <x-bulk-toolbar action="{{ route('admin.users.bulk-destroy') }}" />

        <!-- Users Table -->
        <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-[0.14em]">
                            <th class="w-10 px-4 py-3.5">
                                <input type="checkbox" id="check-all" class="control" aria-label="Select All">
                            </th>
                            <th class="py-3.5 px-4">User</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Registered</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="w-10 px-4 py-3.5">
                                    <input type="checkbox" class="control row-check" name="ids[]" value="{{ $user['composite_id'] }}" form="bulk-form" aria-label="{{ $user['name'] }}">
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        @if(!empty($user['avatar_url']))
                                            <img src="{{ $user['avatar_url'] }}" alt="{{ $user['name'] }}" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                                        @else
                                            <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($user['name'], 0, 1)) }}
                                            </span>
                                        @endif
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $user['name'] }}</span>
                                            @if(!empty($user['phone']))
                                                <span class="text-[10px] text-slate-400 font-mono">{{ $user['phone'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                                    {{ $user['email'] }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $roleClass = match($user['role']) {
                                            'Super Admin', 'Admin' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            'Instructor' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            default => 'bg-blue-50 text-blue-800 border-blue-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] border {{ $roleClass }}">
                                        {{ $user['role'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] border {{ $user['status'] === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                        {{ ucfirst($user['status'] ?? 'active') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap text-[11px] font-mono">
                                    {{ $user['created_at'] ? $user['created_at']->format('M d, Y') : '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button"
                                                class="edit-user-btn action-btn action-btn-ghost h-7 px-3 text-[10px] cursor-pointer"
                                                data-user="{{ json_encode($user) }}">
                                            Edit
                                        </button>
                                        @if(empty($user['is_current']))
                                            <form method="POST" action="{{ route('admin.users.destroy', ['type' => $user['type'], 'id' => $user['raw_id']]) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px] cursor-pointer">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 text-xs">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Add / Edit Modal (mono_market pattern) -->
    <div id="user-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="panel panel-md space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <h3 class="text-base font-bold text-slate-900" id="user-modal-heading">Create New User</h3>
                <button type="button" id="user-modal-close" class="text-slate-400 hover:text-slate-900 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form method="POST" id="user-form" action="{{ route('admin.students.store') }}" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" id="user-form-method" value="POST" disabled>
                <input type="hidden" name="redirect_to" value="{{ route('admin.users.index') }}">

                <!-- Account Type Selector -->
                <div id="user-type-container" class="space-y-1.5">
                    <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Account Type / Role *</label>
                    <select id="user-type-select" data-mselect data-mselect-no-search class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900 bg-white">
                        <option value="student">Student (Learner)</option>
                        <option value="instructor">Instructor (Teaching Faculty)</option>
                        <option value="admin">Administrator (Management)</option>
                    </select>
                </div>

                <!-- Common: Full Name & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Full Name *</label>
                        <input type="text" name="name" id="user-name" required maxlength="255" class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Email Address *</label>
                        <input type="email" name="email" id="user-email" required maxlength="255" class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <!-- Password & Phone -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">
                            Password <span id="user-password-hint" class="text-slate-400 font-normal">*</span>
                        </label>
                        <input type="password" name="password" id="user-password" class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900">
                    </div>
                    <div class="space-y-1.5" id="user-phone-container">
                        <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Phone Number</label>
                        <input type="text" name="phone" id="user-phone" maxlength="30" class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <!-- Instructor-Specific Fields -->
                <div id="instructor-fields" class="space-y-4 hidden">
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Designation / Title</label>
                        <input type="text" name="designation" id="user-designation" placeholder="e.g. Senior PHP Architect" maxlength="100" class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Instructor Bio</label>
                        <textarea name="bio" id="user-bio" rows="3" class="w-full border border-slate-300 p-3 text-xs focus:outline-none focus:border-slate-900"></textarea>
                    </div>
                </div>

                <!-- Admin-Specific Fields -->
                <div id="admin-fields" class="space-y-4 hidden">
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Administrative Role</label>
                        <select name="role_id" id="user-role-id" data-mselect class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900 bg-white">
                            <option value="">— Standard Admin —</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2.5 cursor-pointer select-none pt-1">
                        <input type="checkbox" name="is_super_admin" id="user-super-admin" value="1" class="control">
                        <span class="text-xs text-slate-800 font-semibold">Super Administrator</span>
                        <span class="text-[11px] text-slate-400 font-normal">— Full platform access</span>
                    </label>
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label class="block font-bold uppercase tracking-wider text-[11px] text-slate-700">Account Status</label>
                    <select name="status" id="user-status" data-mselect data-mselect-no-search class="h-10 w-full border border-slate-300 px-3.5 text-xs focus:outline-none focus:border-slate-900 bg-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Avatar Media Selector -->
                <div id="user-media-wrapper">
                    <x-media-selector
                        name="avatar"
                        label="Profile Avatar"
                        shape="circle"
                        hint="Pick from media library or leave blank for auto-generated avatar."
                    />
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                    <button type="button" id="user-modal-cancel" class="action-btn action-btn-ghost cursor-pointer">Cancel</button>
                    <button type="submit" id="user-modal-submit" class="action-btn action-btn-primary cursor-pointer">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client Script for Interactive Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal         = document.getElementById('user-modal');
            var form          = document.getElementById('user-form');
            var heading       = document.getElementById('user-modal-heading');
            var method        = document.getElementById('user-form-method');
            var addBtn        = document.getElementById('btn-add-user');
            var closeBtn      = document.getElementById('user-modal-close');
            var cancelBtn     = document.getElementById('user-modal-cancel');
            var typeContainer = document.getElementById('user-type-container');
            var typeSelect    = document.getElementById('user-type-select');
            var nameIn        = document.getElementById('user-name');
            var emailIn       = document.getElementById('user-email');
            var passIn        = document.getElementById('user-password');
            var passHint      = document.getElementById('user-password-hint');
            var phoneIn       = document.getElementById('user-phone');
            var desigIn       = document.getElementById('user-designation');
            var bioIn         = document.getElementById('user-bio');
            var roleIdIn      = document.getElementById('user-role-id');
            var superAdminIn  = document.getElementById('user-super-admin');
            var statusIn      = document.getElementById('user-status');
            var instFields    = document.getElementById('instructor-fields');
            var adminFields   = document.getElementById('admin-fields');

            var storeUrls = @json($storeUrls);

            function updateTypeFields(type) {
                if (type === 'instructor') {
                    instFields.classList.remove('hidden');
                    adminFields.classList.add('hidden');
                    if (roleIdIn) roleIdIn.disabled = true;
                    if (superAdminIn) superAdminIn.disabled = true;
                    if (desigIn) desigIn.disabled = false;
                    if (bioIn) bioIn.disabled = false;
                } else if (type === 'admin') {
                    instFields.classList.add('hidden');
                    adminFields.classList.remove('hidden');
                    if (roleIdIn) roleIdIn.disabled = false;
                    if (superAdminIn) superAdminIn.disabled = false;
                    if (desigIn) desigIn.disabled = true;
                    if (bioIn) bioIn.disabled = true;
                } else { // student
                    instFields.classList.add('hidden');
                    adminFields.classList.add('hidden');
                    if (roleIdIn) roleIdIn.disabled = true;
                    if (superAdminIn) superAdminIn.disabled = true;
                    if (desigIn) desigIn.disabled = true;
                    if (bioIn) bioIn.disabled = true;
                }
            }

            function setMediaValue(value, url) {
                var input = modal.querySelector('input[name="avatar"]');
                var img = modal.querySelector('[data-media-img]');
                var placeholder = modal.querySelector('[data-media-placeholder]');
                var removeBtn = modal.querySelector('[data-media-remove]');

                if (input) input.value = value || '';
                if (value || url) {
                    if (img) {
                        img.src = url || value;
                        img.classList.remove('hidden');
                    }
                    if (placeholder) placeholder.classList.add('hidden');
                    if (removeBtn) removeBtn.classList.remove('hidden');
                } else {
                    if (img) {
                        img.src = '';
                        img.classList.add('hidden');
                    }
                    if (placeholder) placeholder.classList.remove('hidden');
                    if (removeBtn) removeBtn.classList.add('hidden');
                }
            }

            function openAdd(defaultType) {
                defaultType = defaultType || 'student';
                heading.textContent = 'Create New User';
                method.disabled = true;
                method.value = 'POST';

                typeContainer.classList.remove('hidden');
                typeSelect.disabled = false;
                typeSelect.value = defaultType;
                form.action = storeUrls[defaultType] || storeUrls['student'];

                nameIn.value = '';
                emailIn.value = '';
                passIn.value = '';
                passIn.required = true;
                passHint.textContent = '*';
                phoneIn.value = '';
                if (desigIn) desigIn.value = '';
                if (bioIn) bioIn.value = '';
                if (roleIdIn) roleIdIn.value = '';
                if (superAdminIn) superAdminIn.checked = false;
                statusIn.value = 'active';

                updateTypeFields(defaultType);
                setMediaValue('', '');

                modal.classList.add('open');
                setTimeout(function () { nameIn.focus(); }, 100);
            }

            function openEdit(user) {
                heading.textContent = 'Edit ' + user.role + ' — ' + user.name;
                form.action = user.update_url;
                method.disabled = false;
                method.value = 'PUT';

                typeContainer.classList.add('hidden');
                typeSelect.disabled = true;
                typeSelect.value = user.type;

                nameIn.value = user.name || '';
                emailIn.value = user.email || '';
                passIn.value = '';
                passIn.required = false;
                passHint.textContent = '(leave blank to keep current)';
                phoneIn.value = user.phone || '';

                if (user.type === 'instructor') {
                    if (desigIn) desigIn.value = user.designation || '';
                    if (bioIn) bioIn.value = user.bio || '';
                } else if (user.type === 'admin') {
                    if (roleIdIn) roleIdIn.value = user.role_id || '';
                    if (superAdminIn) superAdminIn.checked = !!user.is_super_admin;
                }

                statusIn.value = user.status || 'active';
                updateTypeFields(user.type);
                setMediaValue(user.avatar || '', user.avatar_url || '');

                modal.classList.add('open');
                setTimeout(function () { nameIn.focus(); }, 100);
            }

            function closeModal() {
                modal.classList.remove('open');
            }

            if (addBtn) addBtn.addEventListener('click', function () { openAdd('student'); });
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

            typeSelect.addEventListener('change', function () {
                var selected = this.value;
                form.action = storeUrls[selected] || storeUrls['student'];
                updateTypeFields(selected);
            });

            document.querySelectorAll('.edit-user-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var userData = JSON.parse(btn.dataset.user || '{}');
                    openEdit(userData);
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