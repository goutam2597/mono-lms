<x-layouts.dashboard :title="__('admin.roles.title').' - Dreams LMS'" active="roles" userType="admin">

    <div class="space-y-6">
        <div class="bg-[#392c7d]/5 border border-[#392c7d]/15 rounded-xl px-5 py-3.5 text-xs text-[#392c7d] font-medium">
            {{ __('admin.roles.super_admin_role_note') }}
        </div>

        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-[#191919]">{{ __('admin.roles.title') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.roles.subtitle') }}</p>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-semibold px-4 py-2.5 rounded-full transition-colors whitespace-nowrap">+ {{ __('admin.roles.create') }}</a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($roles as $role)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-[#191919]">{{ $role->name }} <span class="text-xs text-gray-400 font-normal">({{ $role->slug }})</span></p>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @forelse ($role->permissions as $permission)
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#f4f6f9] text-[#5d5d5d]">{{ $permission->slug }}</span>
                                @empty
                                    <span class="text-[10px] text-gray-400">—</span>
                                @endforelse
                            </div>
                            <p class="text-xs text-gray-400 mt-2">{{ __('admin.roles.admins_with_role') }}: {{ $role->admins_count }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('app.common.edit') }}</a>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</p>
                @endforelse
            </div>
        </div>
    </div>

</x-layouts.dashboard>
