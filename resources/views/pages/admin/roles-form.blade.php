<x-layouts.dashboard :title="($role->exists ? __('admin.roles.edit') : __('admin.roles.create')).' - Dreams LMS'" active="roles" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs p-6 sm:p-8 max-w-3xl">
        <h3 class="font-bold text-[#191919] mb-6">{{ $role->exists ? __('admin.roles.edit') : __('admin.roles.create') }}</h3>

        <form action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST" class="space-y-6">
            @csrf
            @if ($role->exists) @method('PUT') @endif

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-[#191919]">{{ __('admin.roles.name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#ff4667]">
                @error('name')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold text-[#191919]">{{ __('admin.roles.permissions') }}</label>
                    <span class="text-xs text-gray-400">{{ __('admin.roles.groups') }}: {{ $permissions->count() }}</span>
                </div>

                @foreach ($permissions as $group => $groupPermissions)
                    <div class="border border-[#e7e7e7] rounded-xl p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">{{ $group }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach ($groupPermissions as $permission)
                                <label class="flex items-center gap-2.5 cursor-pointer select-none text-sm text-[#191919]">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        @checked(in_array($permission->id, old('permissions', $role->exists ? $role->permissions->pluck('id')->all() : [])))
                                        class="control">
                                    <span>{{ $permission->name }} <span class="text-[11px] text-gray-400">({{ $permission->slug }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-colors">{{ __('app.common.save') }}</button>
                <a href="{{ route('admin.roles.index') }}" class="text-sm font-semibold text-[#5d5d5d] hover:text-[#191919] px-4 py-2.5">{{ __('app.common.cancel') }}</a>
            </div>
        </form>
    </div>

</x-layouts.dashboard>
