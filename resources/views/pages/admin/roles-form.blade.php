<x-layouts.dashboard :title="($role->exists ? __('admin.roles.edit') : __('admin.roles.create')).' - Mono LMS'" active="roles" userType="admin">

    <div class="bg-white border border-slate-200 shadow-xs p-6 sm:p-8 max-w-3xl">
        <div class="border-b border-slate-200 pb-4 mb-6">
            <h3 class="font-bold text-[#191919] text-base">{{ $role->exists ? __('admin.roles.edit') : __('admin.roles.create') }}</h3>
            <p class="text-xs text-slate-500 mt-1">Configure role permissions and access scopes across administrative modules.</p>
        </div>

        <form action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST" class="space-y-6">
            @csrf
            @if ($role->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.roles.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="w-full border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:border-[#344C3D]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('admin.roles.permissions') }}</label>
                    <span class="text-xs font-mono text-slate-400">{{ __('admin.roles.groups') }}: {{ $permissions->count() }}</span>
                </div>

                @foreach ($permissions as $group => $groupPermissions)
                    <div class="border border-slate-200 p-4 bg-slate-50/40">
                        <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 mb-3">{{ $group }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach ($groupPermissions as $permission)
                                <label class="flex items-center gap-2.5 cursor-pointer select-none text-xs text-slate-800">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        @checked(in_array($permission->id, old('permissions', $role->exists ? $role->permissions->pluck('id')->all() : [])))
                                        class="control">
                                    <span>{{ $permission->name }} <span class="text-[10px] font-mono text-slate-400">({{ $permission->slug }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="action-btn action-btn-primary cursor-pointer">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.roles.index') }}" class="action-btn action-btn-ghost">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>