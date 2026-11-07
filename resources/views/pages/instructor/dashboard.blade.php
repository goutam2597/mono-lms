<x-layouts.dashboard :title="__('instructor.dashboard.title').' - Dreams LMS'" active="dashboard" userType="instructor">

    <div class="space-y-8">
        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['label' => __('instructor.dashboard.revenue'), 'value' => __('app.common.currency').number_format($stats['revenue'], 0), 'color' => '#03c95a', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => __('instructor.dashboard.students'), 'value' => $stats['students'], 'color' => '#5625e8', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => __('instructor.dashboard.courses'), 'value' => $stats['courses'], 'color' => '#ff4667', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => __('instructor.dashboard.rating'), 'value' => ($stats['rating'] ?: '—').($stats['rating'] ? ' ★' : ''), 'color' => '#ffc107', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
            ] as $card)
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0" style="background: {{ $card['color'] }}15; color: {{ $card['color'] }};">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-[#191919] leading-tight">{{ $card['value'] }}</p>
                        <p class="text-sm text-[#6d6d6d] mt-0.5">{{ $card['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Enrollments chart -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs">
                <h3 class="font-bold text-[#191919] mb-6">{{ __('instructor.dashboard.earnings_chart') }}</h3>
                @if ($bars->sum('total') > 0)
                    <div class="flex items-end justify-between gap-2 h-44">
                        @foreach ($bars as $bar)
                            @php $height = max(4, round($bar['total'] / max(1, $bars->max('total')) * 100)); @endphp
                            <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end" title="{{ $bar['total'] }}">
                                <span class="text-[10px] text-gray-400">{{ $bar['total'] ?: '' }}</span>
                                <div class="w-full max-w-[26px] rounded-t-lg bg-gradient-to-t from-[#392c7d] to-[#5625e8] transition-all" style="height: {{ $height }}%"></div>
                                <span class="text-[10px] text-gray-400">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 py-16 text-center">{{ __('instructor.dashboard.no_data') }}</p>
                @endif
            </div>

            <!-- Recent enrollments -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-[#191919]">{{ __('instructor.dashboard.recent_students') }}</h3>
                    <a href="{{ route('instructor.students') }}" class="text-xs font-semibold text-[#ff4667] hover:underline">{{ __('app.common.view_all') }}</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentEnrollments as $enrollment)
                        <div class="px-6 py-3.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-9 h-9 rounded-full bg-[#392c7d]/10 text-[#392c7d] font-bold text-sm flex items-center justify-center shrink-0">{{ strtoupper(substr($enrollment->student?->name ?? 'S', 0, 1)) }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-[#191919] truncate">{{ $enrollment->student?->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $enrollment->course?->title }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $enrollment->created_at->format('d M') }}</span>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-gray-400 text-sm">{{ __('instructor.dashboard.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top courses -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-[#191919]">{{ __('instructor.dashboard.top_courses') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                            <th class="px-6 py-3 font-semibold">{{ __('instructor.courses.fields.title') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('admin.courses.enrolled') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('admin.courses.price') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('app.common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($topCourses as $course)
                            <tr>
                                <td class="px-6 py-3.5 font-semibold text-[#191919]">{{ \Illuminate\Support\Str::limit($course->title, 40) }}</td>
                                <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $course->enrollments_count }}</td>
                                <td class="px-6 py-3.5 font-semibold text-[#392c7d]">{{ __('app.common.currency') }}{{ number_format($course->price, 2) }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $course->is_published ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $course->is_published ? __('app.common.published') : __('app.common.draft') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">{{ __('instructor.dashboard.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
