<x-layouts.dashboard :title="__('admin.students.title').' - Dreams LMS'" active="students" userType="admin">

    <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-[#191919]">{{ __('admin.students.title') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('admin.students.subtitle') }}</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.common.search') }}..." class="border border-[#e7e7e7] rounded-full px-4 py-2 text-xs w-56 focus:outline-none focus:border-[#ff4667]">
                <button type="submit" class="bg-[#08131e] text-white text-xs font-semibold px-4 py-2 rounded-full hover:bg-[#1b2737] transition-colors">{{ __('app.common.search') }}</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.students.enrollments') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('admin.students.joined') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        <th class="px-6 py-3 font-semibold">{{ __('app.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-[#0dcaf0]/10 text-[#0891b2] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="font-semibold text-[#191919]">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $student->enrollments_count }}</td>
                            <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $student->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $student->status === 'active' ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $student->status === 'active' ? __('app.common.active') : __('app.common.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="text-xs font-semibold text-[#392c7d] hover:underline">{{ __('app.common.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('{{ __('app.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">{{ __('app.common.delete') }}</button>
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

        <div class="px-6 py-4 border-t border-gray-100">{{ $students->appends(request()->query())->links() }}</div>
    </div>

</x-layouts.dashboard>
