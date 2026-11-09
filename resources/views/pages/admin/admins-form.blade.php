<x-layouts.dashboard :title="($admin->exists ? __('admin.admins.edit') : __('admin.admins.create')).' - Mono LMS'" active="admins" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-2xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ $admin->exists ? __('admin.admins.edit') : __('admin.admins.create') }}</h3>
            <p class="text-xs text-slate-500 mt-1">Configure administrator profile credentials, role assignments, and permissions.</p>
        </div>

        <form action="{{ $admin->exists ? route('admin.admins.update', $admin) : route('admin.admins.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($admin->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.email') }} *</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('email')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.password') }} {{ $admin->exists ? '('.__('instructor.profile.new_password_hint').')' : '*' }}</label>
                <input type="password" name="password" class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('password')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <!-- Avatar -->
            <x-media-selector
                name="avatar"
                :value="old('avatar', $admin->avatar)"
                label="Avatar Image"
                shape="circle"
                :defaultUrl="'https://ui-avatars.com/api/?name='.urlencode($admin->name ?? 'Admin').'&background=344c3d&color=fff'"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.admins.role') }}</label>
                    <x-select name="role_id"
                        :options="$roles->pluck('name', 'id')->prepend('—', '')"
                        :selected="old('role_id', $admin->role_id)"
                        placeholder="—" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('app.common.status') }}</label>
                    <x-select name="status"
                        :options="['active' => __('app.common.active'), 'inactive' => __('app.common.inactive')]"
                        :selected="old('status', $admin->status ?? 'active')"
                        :searchable="false" />
                </div>
            </div>

            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                <input type="checkbox" name="is_super_admin" value="1" @checked(old('is_super_admin', $admin->is_super_admin)) class="control mt-0.5">
                <span class="text-xs text-[#191919] font-medium">{{ __('admin.admins.super_admin') }} <span class="text-xs text-slate-400 font-normal">— {{ __('admin.admins.super_admin_hint') }}</span></span>
            </label>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.admins.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>