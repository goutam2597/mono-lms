<x-layouts.dashboard :title="__('instructor.students.title').' - Dreams LMS'" active="students" userType="instructor">

    <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-6">
        <div>
            <h2 class="text-xl font-bold text-[#191919]">{{ __('instructor.students.title') }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('instructor.students.subtitle') }} · {{ __('instructor.students.count', ['count' => $enrollments->total()]) }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                        <th class="px-4 py-3 font-semibold">{{ __('app.common.name') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('instructor.students.course') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('instructor.students.progress') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('instructor.students.enrolled_on') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($enrollments as $enrollment)
                        <tr class="hover:bg-[#f8fafb] transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-[#392c7d]/10 text-[#392c7d] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($enrollment->student?->name ?? 'S', 0, 1)) }}</span>
                                    <div>
                                        <p class="font-semibold text-[#191919]">{{ $enrollment->student?->name ?? '—' }}</p>
                                        <p class="text-xs text-gray-400">{{ $enrollment->student?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-[#5d5d5d]">{{ \Illuminate\Support\Str::limit($enrollment->course?->title, 35) }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2 min-w-[120px]">
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $enrollment->progress >= 100 ? 'bg-[#03c95a]' : 'bg-[#5625e8]' }}" style="width: {{ $enrollment->progress }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-9">{{ $enrollment->progress }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-[#5d5d5d]">{{ $enrollment->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">{{ __('instructor.students.no_students') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">{{ $enrollments->links() }}</div>
    </div>

</x-layouts.dashboard>
