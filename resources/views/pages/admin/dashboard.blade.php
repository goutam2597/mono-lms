<x-layouts.dashboard :title="__('admin.dashboard.title').' - Dreams LMS'" active="dashboard" userType="admin">

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach ([
                ['label' => __('admin.dashboard.total_admins'), 'value' => $stats['admins'], 'color' => '#5625e8', 'route' => 'admin.admins.index', 'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => __('admin.dashboard.total_instructors'), 'value' => $stats['instructors'], 'color' => '#ff4667', 'route' => 'admin.instructors.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => __('admin.dashboard.total_students'), 'value' => $stats['students'], 'color' => '#0dcaf0', 'route' => 'admin.students.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => __('admin.dashboard.total_courses'), 'value' => $stats['courses'], 'color' => '#03c95a', 'route' => 'admin.courses.index', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ] as $card)
                <a href="{{ route($card['route']) }}" class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs hover:shadow-md transition-all flex items-center gap-4 group">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0" style="background: {{ $card['color'] }}15; color: {{ $card['color'] }};">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-[#191919] leading-tight">{{ $card['value'] }}</p>
                        <p class="text-sm text-[#6d6d6d] mt-0.5">{{ $card['label'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Secondary stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white border border-[#e7e7e7] rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <span class="text-sm text-[#6d6d6d]">{{ __('admin.dashboard.published_courses') }}</span>
                <span class="text-xl font-bold text-[#03c95a]">{{ $stats['published_courses'] }}</span>
            </div>
            <div class="bg-white border border-[#e7e7e7] rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <span class="text-sm text-[#6d6d6d]">{{ __('admin.dashboard.total_enrollments') }}</span>
                <span class="text-xl font-bold text-[#392c7d]">{{ $stats['enrollments'] }}</span>
            </div>
            <div class="bg-white border border-[#e7e7e7] rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <span class="text-sm text-[#6d6d6d]">{{ __('admin.dashboard.pending_reviews') }}</span>
                <span class="text-xl font-bold text-[#ff4667]">{{ $pendingReviews->count() }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Recent Students -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-[#191919]">{{ __('admin.dashboard.recent_students') }}</h3>
                    <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-[#ff4667] hover:underline">{{ __('app.common.view_all') }}</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentStudents as $student)
                        <div class="px-6 py-3.5 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-full bg-[#392c7d]/10 text-[#392c7d] font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                <div>
                                    <p class="text-sm font-semibold text-[#191919]">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $student->created_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <p class="px-6 py-8 text-sm text-gray-400 text-center">{{ __('admin.dashboard.no_data') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- Latest Courses -->
            <div class="bg-white border border-[#e7e7e7] rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-[#191919]">{{ __('admin.dashboard.recent_courses') }}</h3>
                    <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-[#ff4667] hover:underline">{{ __('app.common.view_all') }}</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentCourses as $course)
                        <div class="px-6 py-3.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-[#191919] truncate">{{ $course->title }}</p>
                                <p class="text-xs text-gray-400">{{ $course->instructor?->name }} · {{ $course->category?->name ?? '—' }}</p>
                            </div>
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-full whitespace-nowrap {{ $course->is_published ? 'bg-[#03c95a]/10 text-[#0d7a38]' : 'bg-gray-100 text-gray-500' }}">
                                {{ $course->is_published ? __('app.common.published') : __('app.common.draft') }}
                            </span>
                        </div>
                    @empty
                        <p class="px-6 py-8 text-sm text-gray-400 text-center">{{ __('admin.dashboard.no_data') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
