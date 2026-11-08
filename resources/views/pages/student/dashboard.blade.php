<x-layouts.dashboard :title="__('student.dashboard.title').' - Mono LMS'" active="dashboard" userType="student">

    <div class="space-y-8">
        <!-- Quiz Alert Banner -->
        <div class="bg-gradient-to-r from-[#738a6e] to-[#ff6b84] rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative overflow-hidden">
            <div class="flex items-center gap-4">
                <span class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </span>
                <p class="text-sm font-medium">{{ __('student.dashboard.browse_hint') }}</p>
            </div>
            <a href="{{ route('courses.index') }}" class="bg-white text-[#738a6e] text-xs font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-colors whitespace-nowrap">{{ __('student.nav.browse') }}</a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['label' => __('student.dashboard.enrolled'), 'value' => str_pad((string) $stats['enrolled'], 2, '0', STR_PAD_LEFT), 'color' => '#5625e8'],
                ['label' => __('student.dashboard.in_progress'), 'value' => str_pad((string) $stats['in_progress'], 2, '0', STR_PAD_LEFT), 'color' => '#0dcaf0'],
                ['label' => __('student.dashboard.completed'), 'value' => str_pad((string) $stats['completed'], 2, '0', STR_PAD_LEFT), 'color' => '#03c95a'],
                ['label' => __('student.dashboard.certificates'), 'value' => str_pad((string) $stats['certificates'], 2, '0', STR_PAD_LEFT), 'color' => '#ffc107'],
            ] as $card)
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs">
                    <p class="text-3xl font-bold" style="color: {{ $card['color'] }}">{{ $card['value'] }}</p>
                    <p class="text-sm text-[#6d6d6d] mt-1">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Recently Enrolled -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#191919]">{{ __('student.dashboard.recently_enrolled') }}</h3>
                <a href="{{ route('student.enrolled-courses') }}" class="text-xs font-semibold text-[#738a6e] hover:underline">{{ __('app.common.view_all') }}</a>
            </div>

            @forelse ($enrollments->take(3) as $enrollment)
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-4">
                    <img src="{{ $enrollment->course->image ?? 'https://picsum.photos/seed/course-'.$enrollment->course->id.'/160/120' }}" alt="{{ $enrollment->course->title }}" class="w-full sm:w-32 h-24 object-cover rounded-xl">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-[#191919] truncate">{{ $enrollment->course->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $enrollment->course->instructor?->name }}</p>
                        <div class="flex items-center gap-2 mt-3">
                            <div class="flex-1 max-w-xs h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $enrollment->progress >= 100 ? 'bg-[#03c95a]' : 'bg-[#5625e8]' }}" style="width: {{ $enrollment->progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $enrollment->progress }}%</span>
                        </div>
                    </div>
                    <a href="{{ route('courses.show', $enrollment->course->slug) }}" class="bg-[#f4f6f9] hover:bg-[#392c7d] hover:text-white text-[#191919] text-xs font-bold py-2.5 px-5 rounded-xl transition-colors whitespace-nowrap">
                        {{ __('student.dashboard.keep_learning') }}
                    </a>
                </div>
            @empty
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-10 text-center shadow-xs">
                    <p class="text-sm text-gray-400">{{ __('student.dashboard.no_enrollments') }}</p>
                    <a href="{{ route('courses.index') }}" class="inline-block mt-4 bg-[#344c3d] hover:bg-[#283a2f] text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-colors">{{ __('student.nav.browse') }}</a>
                </div>
            @endforelse
        </div>

        <!-- Recent Invoices (enrollments) -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-[#191919]">{{ __('student.dashboard.invoices') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 bg-[#f8fafb]">
                            <th class="px-6 py-3 font-semibold">{{ __('student.dashboard.order_id') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('student.dashboard.course') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('student.dashboard.date') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('student.dashboard.amount') }}</th>
                            <th class="px-6 py-3 font-semibold">{{ __('student.dashboard.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($enrollments as $enrollment)
                            <tr>
                                <td class="px-6 py-3.5 font-mono text-xs font-semibold text-[#392c7d]">ENR-{{ str_pad((string) $enrollment->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-3.5 text-[#5d5d5d]">{{ \Illuminate\Support\Str::limit($enrollment->course->title, 35) }}</td>
                                <td class="px-6 py-3.5 text-[#5d5d5d]">{{ $enrollment->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-3.5 font-semibold text-[#191919]">{{ $enrollment->course->price > 0 ? __('app.common.currency').number_format($enrollment->course->price, 2) : __('courses.price_free') }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $enrollment->status === 'completed' ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-[#5625e8]/10 text-[#5625e8]' }}">
                                        {{ $enrollment->status === 'completed' ? __('student.dashboard.completed_label') : __('app.common.active') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">{{ __('student.dashboard.no_enrollments') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
