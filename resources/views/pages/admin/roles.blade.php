<x-layouts.dashboard :title="__('admin.roles.title').' - Mono LMS'" active="roles" userType="admin">

    <div class="space-y-6">
        <div class="bg-[#392c7d]/5 border border-[#392c7d]/20 px-5 py-3.5 text-xs text-[#392c7d] font-medium flex items-center gap-2.5">
            <svg class="w-4 h-4 text-[#392c7d] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ __('admin.roles.super_admin_role_note') }}</span>
        </div>

        <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-[#191919] text-base">{{ __('admin.roles.title') }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">{{ __('admin.roles.subtitle') }}</p>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="action-btn action-btn-primary whitespace-nowrap">
                    + {{ __('admin.roles.create') }}
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($roles as $role)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/60 transition-colors">
                        <div class="min-w-0">
                            <p class="font-bold text-[#191919] text-xs">
                                {{ $role->name }} 
                                <span class="text-[11px] text-slate-400 font-mono font-normal">({{ $role->slug }})</span>
                            </p>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @forelse ($role->permissions as $permission)
                                    <span class="text-[10px] font-mono px-2 py-0.5 border border-slate-200 bg-slate-50 text-slate-600">{{ $permission->slug }}</span>
                                @empty
                                    <span class="text-[10px] text-slate-400">—</span>
                                @endforelse
                            </div>
                            <p class="text-[11px] text-slate-400 font-mono mt-2">{{ __('admin.roles.admins_with_role') }}: {{ $role->admins_count }}</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="action-btn action-btn-ghost h-7 px-3 text-[10px]">
                                {{ __('app.common.edit') }}
                            </a>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger h-7 px-3 text-[10px] cursor-pointer">
                                    {{ __('app.common.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-slate-400 text-xs">{{ __('admin.dashboard.no_data') }}</p>
                @endforelse
            </div>
        </div>
    </div>

</x-layouts.dashboard>