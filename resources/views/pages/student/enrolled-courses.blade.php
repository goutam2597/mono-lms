<x-layouts.dashboard :title="__('student.enrolled.title').' - Mono LMS'" active="enrolled" userType="student">

    <div class="space-y-5">
        <!-- Header & Filter Tabs (Boxy) -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">{{ __('student.enrolled.title') }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Track your curriculum and resume ongoing lessons</p>
            </div>
            <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-[6px]">
                @foreach (['' => __('student.enrolled.all'), 'active' => __('student.enrolled.in_progress'), 'completed' => __('student.enrolled.completed')] as $key => $label)
                    @php $isCurrent = request('filter') == $key; @endphp
                    <a href="{{ route('student.enrolled-courses', array_filter(['filter' => $key])) }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-[5px] transition-all {{ $isCurrent ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        @forelse ($enrollments as $enrollment)
            @php
                $inst = $enrollment->course->instructor;
                $instAvatar = $inst?->avatarUrl();
            @endphp
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 sm:p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center gap-5 hover:border-slate-300 transition-colors">
                <div class="w-full md:w-44 h-28 rounded-[8px] overflow-hidden shrink-0 border border-slate-200 bg-slate-100 relative group">
                    <img src="{{ $enrollment->course->image ?? 'https://picsum.photos/seed/course-'.$enrollment->course->id.'/240/160' }}"
                         alt="{{ $enrollment->course->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="w-9 h-9 rounded-full bg-white/95 text-[var(--theme-primary,#344c3d)] flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                    </div>
                </div>

                <div class="flex-1 min-w-0 w-full">
                    <div class="flex items-center gap-2 mb-1">
                        @if ($enrollment->course->category)
                            <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-[4px] bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20">
                                {{ $enrollment->course->category->name }}
                            </span>
                        @endif
                        <span class="text-[11px] text-slate-400">
                            Enrolled {{ $enrollment->created_at->format('M d, Y') }}
                        </span>
                    </div>

                    <h3 class="font-bold text-sm sm:text-base text-slate-900 truncate">
                        <a href="{{ route('courses.show', $enrollment->course->slug) }}" class="hover:text-[var(--theme-primary,#344c3d)] transition-colors">
                            {{ $enrollment->course->title }}
                        </a>
                    </h3>

                    <!-- Instructor with Avatar -->
                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-5 h-5 rounded-full overflow-hidden bg-slate-200 ring-1 ring-slate-300 flex items-center justify-center shrink-0">
                            @if ($instAvatar)
                                <img src="{{ $instAvatar }}"
                                     alt="{{ $inst->name }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <span class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white text-[8px] font-bold items-center justify-center hidden">
                                    {{ strtoupper(substr($inst->name ?? 'I', 0, 1)) }}
                                </span>
                            @else
                                <span class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white text-[8px] font-bold flex items-center justify-center">
                                    {{ strtoupper(substr($inst->name ?? 'I', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-slate-500 font-medium">{{ $inst->name ?? 'Course Instructor' }}</span>
                    </div>

                    <div class="flex items-center gap-3 mt-3">
                        <span class="text-xs text-slate-500 font-medium">{{ __('student.enrolled.progress') }}:</span>
                        <div class="flex-1 max-w-xs h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $enrollment->progress >= 100 ? 'bg-emerald-500' : 'bg-[var(--theme-primary,#344c3d)]' }} transition-all"
                                 style="width: {{ $enrollment->progress }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-900">{{ $enrollment->progress }}%</span>
                    </div>
                </div>

                <div class="shrink-0 w-full md:w-auto flex flex-col sm:flex-row md:flex-col items-stretch sm:items-center md:items-end gap-2">
                    @if ($enrollment->progress >= 100)
                        <a href="{{ route('student.certificates') }}"
                           class="w-full inline-flex items-center justify-center gap-1.5 text-center bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold py-2.5 px-5 rounded-[6px] transition-colors shadow-xs">
                            <span>{{ __('student.enrolled.download_certificate') }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif

                    <a href="{{ route('student.courses.learn', $enrollment->course) }}"
                       class="w-full inline-flex items-center justify-center gap-1.5 text-center bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold py-2.5 px-5 rounded-[6px] transition-colors shadow-xs">
                        <span>{{ __('student.enrolled.continue') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    @php $myReview = $enrollment->course->reviews->first(); @endphp
                    @if ($myReview)
                        <a href="{{ route('courses.show', $enrollment->course->slug) }}#reviews"
                           class="w-full inline-flex items-center justify-center gap-1.5 text-[11px] font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 py-1.5 px-3 rounded-[6px] transition-colors">
                            <span>★ Rated {{ $myReview->rating }}.0</span>
                            <span class="text-[10px] text-amber-600 font-normal">({{ ucfirst($myReview->status) }})</span>
                        </a>
                    @else
                        <a href="{{ route('courses.show', $enrollment->course->slug) }}#reviews"
                           class="w-full inline-flex items-center justify-center gap-1.5 text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 py-1.5 px-3 rounded-[6px] transition-colors">
                            <span class="text-amber-500 font-bold">★</span>
                            <span>Rate & Review</span>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-12 text-center shadow-xs">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="text-sm font-semibold text-slate-700">{{ __('student.enrolled.no_courses') }}</p>
                <p class="text-xs text-slate-400 mt-1">Enroll in any curriculum to start learning.</p>
                <a href="{{ route('courses.index') }}"
                   class="inline-flex items-center gap-1.5 mt-4 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-5 py-2.5 rounded-[6px] transition-colors shadow-xs">
                    <span>{{ __('student.nav.browse') }}</span>
                </a>
            </div>
        @endforelse
    </div>

</x-layouts.dashboard>
