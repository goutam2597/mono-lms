<x-layouts.dashboard title="Saved Courses - Mono LMS" active="saved-courses" userType="student">

    <div class="space-y-5">
        <!-- Header & Search Bar (Boxy) -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 sm:p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">{{ __('student.nav.saved_courses') ?? 'Saved Courses' }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] text-[11px] font-bold">
                        {{ $courses->total() }} {{ $courses->total() === 1 ? 'Course' : 'Courses' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Courses bookmarked to enroll or review later</p>
            </div>

            <!-- Search Filter Form -->
            <form action="{{ route('student.wishlist') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
                <div class="relative flex-1 md:w-64">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search saved courses..."
                           class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[var(--theme-primary,#344c3d)] rounded-[6px] pl-8 pr-3 py-1.5 text-xs text-slate-900 focus:outline-none transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if(request('q'))
                    <a href="{{ route('student.wishlist') }}" class="px-2.5 py-1.5 rounded-[6px] bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-[8px] text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($courses as $course)
                @php
                    $isEnrolled = $student->enrollments()->where('course_id', $course->id)->exists();
                    $instructor = $course->instructor;
                    $instAvatar = $instructor?->avatarUrl();
                @endphp
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] overflow-hidden shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail & Badge -->
                        <div class="relative w-full aspect-[16/10] overflow-hidden bg-slate-100 border-b border-slate-100">
                            <img src="{{ $course->image ?? 'https://picsum.photos/seed/course-'.$course->id.'/480/300' }}"
                                 alt="{{ $course->trans('title') }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                            <!-- Remove from saved button -->
                            <form action="{{ route('student.wishlist.destroy', $course) }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        title="Remove from saved courses"
                                        class="w-8 h-8 rounded-full bg-white/95 hover:bg-white text-rose-500 hover:text-rose-600 shadow-sm flex items-center justify-center transition-transform hover:scale-110 cursor-pointer">
                                    <svg class="w-4 h-4 fill-rose-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                </button>
                            </form>

                            @if ($course->category)
                                <span class="absolute bottom-2.5 left-2.5 text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-[4px] bg-slate-900/80 backdrop-blur-xs text-white">
                                    {{ $course->category->trans('name') }}
                                </span>
                            @endif
                        </div>

                        <!-- Card Content -->
                        <div class="p-4 space-y-2.5">
                            <!-- Instructor Info -->
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full overflow-hidden bg-slate-200 ring-1 ring-slate-300 flex items-center justify-center shrink-0">
                                    @if ($instAvatar)
                                        <img src="{{ $instAvatar }}"
                                             alt="{{ $instructor->name }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                        <span class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white text-[8px] font-bold items-center justify-center hidden">
                                            {{ strtoupper(substr($instructor->name ?? 'I', 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="w-full h-full bg-[var(--theme-primary,#344c3d)] text-white text-[8px] font-bold flex items-center justify-center">
                                            {{ strtoupper(substr($instructor->name ?? 'I', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-500 font-medium truncate">{{ $instructor->name ?? 'Instructor' }}</span>
                            </div>

                            <h3 class="font-bold text-sm text-slate-900 line-clamp-2 leading-snug">
                                <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-[var(--theme-primary,#344c3d)] transition-colors">
                                    {{ $course->trans('title') }}
                                </a>
                            </h3>

                            <!-- Rating & Lessons -->
                            <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                                <span class="flex items-center gap-1 text-amber-500 font-semibold">
                                    <span>★</span>
                                    <span class="text-slate-700">{{ $course->averageRating() ?: '5.0' }}</span>
                                    <span class="text-slate-400 font-normal">({{ $course->reviewCount() }})</span>
                                </span>
                                <span>{{ $course->lessons_count }} Lessons</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-4 py-3 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between gap-3">
                        <div class="flex flex-col justify-center min-w-0 flex-1 pr-1.5">
                            <span class="text-base font-extrabold text-slate-900 leading-tight truncate">
                                {{ $course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free') }}
                            </span>
                            @if ($course->old_price && $course->old_price > $course->price)
                                <span class="text-[11px] text-slate-400 line-through leading-tight truncate">
                                    {{ __('app.common.currency') }}{{ number_format($course->old_price, 2) }}
                                </span>
                            @endif
                        </div>

                        @if ($isEnrolled)
                            <a href="{{ route('courses.show', $course->slug) }}"
                               class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 rounded-[5px] transition-colors">
                                Enrolled
                            </a>
                        @else
                            <a href="{{ route('courses.show', $course->slug) }}"
                               class="bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-3.5 py-1.5 rounded-[5px] transition-colors shadow-2xs">
                                View Course
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-[#e2e8f0] rounded-[10px] p-12 text-center shadow-xs">
                    <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">No saved courses found</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                        Explore our course catalog and click the heart icon on any course card to save it here for quick access later.
                    </p>
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center gap-1.5 mt-4 bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs font-semibold px-5 py-2.5 rounded-[6px] transition-colors shadow-xs">
                        <span>Browse Courses</span>
                    </a>
                </div>
            @endforelse
        </div>

        @if ($courses->hasPages())
            <div class="pt-2">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

</x-layouts.dashboard>
