<x-layouts.dashboard :title="__('admin.admins.title').' - Dreams LMS'" active="admins" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.admins.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.admins.subtitle') }}</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" class="bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-semibold px-4 py-2.5 rounded-full transition-colors whitespace-nowrap">+ {{ __('admin.admins.create') }}</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.admins.role') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-[#392c7d]/10 text-[#392c7d] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="font-semibold text-[#191919]">{{ $admin->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $admin->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($admin->is_super_admin)
                                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full bg-[#ff4667]/10 text-[#ff4667]">{{ __('admin.admins.super_admin') }}</span>
                                @else
                                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full bg-[#392c7d]/10 text-[#392c7d]">{{ $admin->role?->name ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $admin->status === 'active' ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $admin->status === 'active' ? __('app.common.active') : __('app.common.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.admins.edit', $admin) }}" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('app.common.edit') }}</a>
                                    @unless ($admin->id === auth('admin')->id())
                                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">{{ __('admin.dashboard.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">{{ $admins->links() }}</div>
    </div>

</x-layouts.dashboard>
