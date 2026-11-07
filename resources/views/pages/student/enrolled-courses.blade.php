<x-layouts.dashboard :title="__('student.enrolled.title').' - Mono LMS'" active="enrolled" userType="student">

    <div class="space-y-6">
        <!-- Header & Filter Tabs -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-[#191919]">{{ __('student.enrolled.title') }}</h2>
            <div class="flex items-center gap-2">
                @foreach (['' => __('student.enrolled.all'), 'active' => __('student.enrolled.in_progress'), 'completed' => __('student.enrolled.completed')] as $key => $label)
                    <a href="{{ route('student.enrolled-courses', array_filter(['filter' => $key])) }}"
                       class="text-xs font-semibold px-4 py-2 rounded-full transition-colors {{ request('filter') == $key ? 'bg-[#ff4667] text-white' : 'bg-[#f4f6f9] text-[#5d5d5d] hover:bg-gray-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        @forelse ($enrollments as $enrollment)
            <div class="bg-white border border-[#e7e7e7] rounded-2xl p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center gap-5">
                <img src="{{ $enrollment->course->image ?? 'https://picsum.photos/seed/course-'.$enrollment->course->id.'/240/160' }}" alt="{{ $enrollment->course->title }}" class="w-full md:w-44 h-28 object-cover rounded-xl">
                <div class="flex-1 min-w-0">
                    <span class="text-[11px] font-semibold text-[#ff4667]">{{ $enrollment->course->category?->name ?? '' }}</span>
                    <h3 class="font-bold text-[#191919] mt-1">{{ $enrollment->course->title }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $enrollment->course->instructor?->name }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="text-xs text-gray-400">{{ __('student.enrolled.progress') }}</span>
                        <div class="w-40 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $enrollment->progress >= 100 ? 'bg-[#03c95a]' : 'bg-[#5625e8]' }}" style="width: {{ $enrollment->progress }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-[#191919]">{{ $enrollment->progress }}%</span>
                    </div>
                </div>
                <a href="{{ route('courses.show', $enrollment->course->slug) }}"
                   class="text-center bg-[#392c7d] hover:bg-[#ff4667] text-white text-xs font-bold py-2.5 px-6 rounded-full transition-colors whitespace-nowrap">
                    {{ $enrollment->progress >= 100 ? __('student.enrolled.download_certificate') : __('student.enrolled.continue') }}
                </a>
            </div>
        @empty
            <div class="bg-white border border-[#e7e7e7] rounded-2xl p-12 text-center shadow-xs">
                <p class="text-sm text-gray-400">{{ __('student.enrolled.no_courses') }}</p>
                <a href="{{ route('courses.index') }}" class="inline-block mt-4 bg-[#ff4667] hover:bg-[#e03a58] text-white text-xs font-bold px-5 py-2.5 rounded-full transition-colors">{{ __('student.nav.browse') }}</a>
            </div>
        @endforelse
    </div>

</x-layouts.dashboard>
