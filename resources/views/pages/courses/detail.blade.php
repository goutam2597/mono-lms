<x-layouts.app :title="$course->title.' - Mono LMS'" active="courses">

    @if (session('success') || session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-8 pt-6">
            <div class="bg-[#edf3ec] border border-[#bfcabb]/60 text-[#344c3d] px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-[#344c3d] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') ?? session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- Course Hero Section -->
    <div class="bg-gradient-to-b from-[#132018] to-[#1c2e22] text-white py-12 sm:py-16 relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#344c3d]/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#738a6e]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-8 space-y-4">
                    <!-- Breadcrumbs -->
                    <nav class="flex flex-wrap items-center gap-2 text-xs text-[#bfcabb]">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span class="text-white/40">/</span>
                        <a href="{{ route('courses.index') }}" class="hover:text-white transition-colors">Courses</a>
                        @if ($course->category)
                            <span class="text-white/40">/</span>
                            <a href="{{ route('courses.index', ['category' => $course->category->slug]) }}" class="hover:text-white transition-colors">{{ $course->category->name }}</a>
                        @endif
                        <span class="text-white/40">/</span>
                        <span class="text-white font-medium truncate max-w-xs sm:max-w-md">{{ $course->title }}</span>
                    </nav>

                    <!-- Category & Level Badges -->
                    <div class="flex flex-wrap items-center gap-2.5 pt-1">
                        @if ($course->category)
                            <a href="{{ route('courses.index', ['category' => $course->category->slug]) }}" class="inline-flex items-center gap-1.5 bg-[#dce7da] text-[#344c3d] text-xs font-semibold px-3 py-1 rounded-full hover:bg-white transition-colors shadow-xs">
                                <span>{{ $course->category->name }}</span>
                            </a>
                        @endif
                        <span class="inline-flex items-center gap-1 bg-white/10 text-white/90 text-xs font-medium px-3 py-1 rounded-full backdrop-blur-xs">
                            <svg class="w-3.5 h-3.5 text-[#bfcabb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>{{ __('app.levels.'.$course->level) }}</span>
                        </span>
                        @if ($course->is_featured)
                            <span class="inline-flex items-center gap-1 bg-[#738a6e] text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                                ★ Featured
                            </span>
                        @endif
                    </div>

                    <!-- Course Title -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        {{ $course->title }}
                    </h1>

                    @if ($course->subtitle)
                        <p class="text-[#dce7da]/90 text-sm sm:text-base leading-relaxed max-w-3xl">
                            {{ $course->subtitle }}
                        </p>
                    @endif

                    <!-- Instructor & Meta Row -->
                    <div class="flex flex-wrap items-center gap-y-3 gap-x-6 pt-3 text-xs sm:text-sm text-gray-300">
                        <div class="flex items-center gap-2.5">
                            @if ($course->instructor?->avatarUrl())
                                <img src="{{ $course->instructor->avatarUrl() }}" class="w-8 h-8 rounded-full object-cover border border-[#bfcabb]/40" alt="{{ $course->instructor->name }}" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <span class="w-8 h-8 rounded-full bg-[#344c3d] border border-[#bfcabb]/40 items-center justify-center text-xs font-bold text-white hidden">{{ strtoupper(substr($course->instructor?->name ?? 'I', 0, 1)) }}</span>
                            @else
                                <span class="w-8 h-8 rounded-full bg-[#344c3d] border border-[#bfcabb]/40 flex items-center justify-center text-xs font-bold text-white">{{ strtoupper(substr($course->instructor?->name ?? 'I', 0, 1)) }}</span>
                            @endif
                            <span>{{ __('courses.about_instructor') }}: <a href="{{ route('instructors.show', $course->instructor?->name ?? '') }}" class="text-white font-semibold hover:underline hover:text-[#bfcabb] transition-colors">{{ $course->instructor?->name ?? 'Instructor' }}</a></span>
                        </div>

                        <div class="flex items-center gap-1.5 text-[#ffc107]">
                            <span class="text-sm">★</span>
                            <span class="font-bold text-white text-sm">{{ $course->averageRating() ?: '5.0' }}</span>
                            <span class="text-gray-400 text-xs">({{ $course->reviewCount() }} {{ __('app.common.reviews') }})</span>
                        </div>

                        <div class="flex items-center gap-1.5 text-gray-300">
                            <svg class="w-4 h-4 text-[#bfcabb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>{{ number_format($course->enrolledCount() ?: 24) }} {{ __('app.common.students') }}</span>
                        </div>

                        <div class="flex items-center gap-1.5 text-gray-300">
                            <svg class="w-4 h-4 text-[#bfcabb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $course->duration_minutes }} {{ __('app.common.minutes') }}</span>
                        </div>

                        <div class="flex items-center gap-1.5 text-gray-300">
                            <svg class="w-4 h-4 text-[#bfcabb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Updated {{ $course->updated_at ? $course->updated_at->format('M Y') : 'Nov 2026' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content & Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-10 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            
            <!-- Left Overview Column -->
            <div class="lg:col-span-8 space-y-8">

                <!-- In-page Jump Navigation -->
                <div class="flex items-center gap-6 border-b border-gray-200 pb-3 text-sm font-semibold overflow-x-auto text-gray-500">
                    <a href="#learn" class="text-[#344c3d] hover:text-[#344c3d] whitespace-nowrap transition-colors">What You'll Learn</a>
                    <a href="#overview" class="hover:text-[#344c3d] whitespace-nowrap transition-colors">Overview</a>
                    <a href="#curriculum" class="hover:text-[#344c3d] whitespace-nowrap transition-colors">Curriculum</a>
                    <a href="#instructor" class="hover:text-[#344c3d] whitespace-nowrap transition-colors">Instructor</a>
                    <a href="#reviews" class="hover:text-[#344c3d] whitespace-nowrap transition-colors">Reviews</a>
                </div>

                <!-- What You'll Learn Box -->
                <div id="learn" class="bg-[#edf3ec]/70 border border-[#dce7da] rounded-2xl p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-[#344c3d] text-white flex items-center justify-center font-bold">
                            <svg class="w-5 h-5 text-[#dce7da]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#191919]">{{ __('courses.what_you_get') }}</h2>
                            <p class="text-xs text-[#5d5d5d]">Key competencies and practical skills covered in this syllabus</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start gap-3 bg-white p-3.5 rounded-xl border border-[#e2ece0] shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-[#dce7da] text-[#344c3d] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                            <span class="text-xs sm:text-sm text-[#191919] font-medium leading-relaxed">Build responsive websites and mobile layouts using modern front-end techniques</span>
                        </div>
                        <div class="flex items-start gap-3 bg-white p-3.5 rounded-xl border border-[#e2ece0] shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-[#dce7da] text-[#344c3d] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                            <span class="text-xs sm:text-sm text-[#191919] font-medium leading-relaxed">Master typography, responsive flex/grid layouts, and semantic HTML5 structure</span>
                        </div>
                        <div class="flex items-start gap-3 bg-white p-3.5 rounded-xl border border-[#e2ece0] shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-[#dce7da] text-[#344c3d] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                            <span class="text-xs sm:text-sm text-[#191919] font-medium leading-relaxed">Write clean, accessible, SEO-optimized markup that passes strict web standards</span>
                        </div>
                        <div class="flex items-start gap-3 bg-white p-3.5 rounded-xl border border-[#e2ece0] shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-[#dce7da] text-[#344c3d] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                            <span class="text-xs sm:text-sm text-[#191919] font-medium leading-relaxed">Cross-browser debugging and mobile viewport testing strategies</span>
                        </div>
                        <div class="flex items-start gap-3 bg-white p-3.5 rounded-xl border border-[#e2ece0] shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-[#dce7da] text-[#344c3d] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                            <span class="text-xs sm:text-sm text-[#191919] font-medium leading-relaxed">Interactive micro-interactions, responsive navigation bars, and cards</span>
                        </div>
                        <div class="flex items-start gap-3 bg-white p-3.5 rounded-xl border border-[#e2ece0] shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-[#dce7da] text-[#344c3d] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                            <span class="text-xs sm:text-sm text-[#191919] font-medium leading-relaxed">Complete portfolio-ready project from wireframe to deployment</span>
                        </div>
                    </div>
                </div>

                <!-- Course Description / Overview -->
                <div id="overview" class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs space-y-4">
                    <h2 class="text-xl font-bold text-[#191919]">{{ __('courses.description') }}</h2>
                    <div class="text-sm text-[#5d5d5d] leading-relaxed space-y-3">
                        <p>{{ $course->description }}</p>
                        @if (!$course->description)
                            <p>Welcome to <strong>{{ $course->title }}</strong>. This in-depth course provides hands-on, practical guidance from seasoned professionals to help you reach career-level mastery with clear demonstrations, real code, and interactive practice.</p>
                        @endif
                    </div>
                </div>

                <!-- Course Curriculum (DB-driven lessons) -->
                <div id="curriculum" class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-xl font-bold text-[#191919]">{{ __('courses.curriculum') }}</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Structured into clear, sequential modules</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 bg-[#edf3ec] text-[#344c3d] text-xs font-semibold px-3 py-1.5 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>{{ $course->lessons->count() }} {{ __('app.common.lessons') }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-[#edf3ec] text-[#344c3d] text-xs font-semibold px-3 py-1.5 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $course->duration_minutes }} {{ __('app.common.minutes') }}</span>
                            </span>
                        </div>
                    </div>

                    @if ($course->lessons->count())
                        <div class="space-y-3">
                            @foreach ($course->lessons as $lesson)
                                <div class="flex items-center justify-between bg-[#f8faf8] hover:bg-[#edf3ec] border border-[#e7e7e7] hover:border-[#8ea58c]/50 rounded-xl px-4 py-3.5 transition-colors group text-sm">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-7 h-7 rounded-full bg-white border border-[#dce7da] text-[#344c3d] group-hover:bg-[#344c3d] group-hover:text-white flex items-center justify-center text-xs font-bold shrink-0 transition-colors">
                                            {{ $loop->iteration }}
                                        </span>
                                        <span class="text-[#191919] font-medium truncate">{{ $lesson->title }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0 ml-4">
                                        @if ($loop->first)
                                            <span class="bg-[#dce7da] text-[#344c3d] text-[11px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">Preview</span>
                                        @endif
                                        <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $lesson->duration_minutes }} {{ __('app.common.minutes') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400 text-sm">
                            {{ __('instructor.lessons.no_lessons') }}
                        </div>
                    @endif
                </div>

                <!-- Requirements & Target Audience -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-[#edf3ec] text-[#344c3d] flex items-center justify-center font-bold text-sm">✓</span>
                            <h3 class="text-base font-bold text-[#191919]">Requirements</h3>
                        </div>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-[#5d5d5d]">
                            <li class="flex items-start gap-2">
                                <span class="text-[#344c3d] font-bold mt-0.5">•</span>
                                <span>No prior coding or web design experience required — start from ground zero.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#344c3d] font-bold mt-0.5">•</span>
                                <span>Any operating system (Windows, macOS, or Linux) with an up-to-date web browser.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#344c3d] font-bold mt-0.5">•</span>
                                <span>A modern code editor such as VS Code (we guide you through free installation).</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-[#edf3ec] text-[#344c3d] flex items-center justify-center font-bold text-sm">👥</span>
                            <h3 class="text-base font-bold text-[#191919]">Who This Course Is For</h3>
                        </div>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-[#5d5d5d]">
                            <li class="flex items-start gap-2">
                                <span class="text-[#344c3d] font-bold mt-0.5">•</span>
                                <span>Beginners seeking a structured path into front-end design & web development.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#344c3d] font-bold mt-0.5">•</span>
                                <span>Designers wanting to convert layouts into clean, responsive web code.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-[#344c3d] font-bold mt-0.5">•</span>
                                <span>Engineers wishing to refresh foundational HTML5, responsive layout, and accessibility.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- About the Instructor Card -->
                <div id="instructor" class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
                    <h2 class="text-xl font-bold text-[#191919]">{{ __('courses.about_instructor') }}</h2>
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        @if ($course->instructor?->avatarUrl())
                            <img src="{{ $course->instructor->avatarUrl() }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover shrink-0 border border-[#e7e7e7] shadow-xs" alt="{{ $course->instructor->name }}" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                            <span class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[#344c3d] text-white items-center justify-center text-2xl font-bold shrink-0 shadow-xs hidden">{{ strtoupper(substr($course->instructor?->name ?? 'I', 0, 1)) }}</span>
                        @else
                            <span class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[#344c3d] text-white flex items-center justify-center text-2xl font-bold shrink-0 shadow-xs">{{ strtoupper(substr($course->instructor?->name ?? 'I', 0, 1)) }}</span>
                        @endif

                        <div class="space-y-2.5 flex-1 min-w-0">
                            <div>
                                <h3 class="text-lg font-bold text-[#191919]">
                                    <a href="{{ route('instructors.show', $course->instructor?->name ?? '') }}" class="hover:text-[#344c3d] transition-colors">
                                        {{ $course->instructor?->name ?? 'Course Instructor' }}
                                    </a>
                                </h3>
                                <p class="text-xs font-semibold text-[#738a6e]">{{ $course->instructor?->designation ?? 'Senior Educator & Professional' }}</p>
                            </div>

                            <p class="text-sm text-[#5d5d5d] leading-relaxed">
                                {{ $course->instructor?->bio ?? 'Experienced software architect and mentor passionate about empowering students worldwide with practical, industry-aligned training.' }}
                            </p>

                            <div class="flex flex-wrap items-center gap-y-2 gap-x-5 pt-1 text-xs text-gray-500 font-medium">
                                <span class="flex items-center gap-1.5"><span class="text-[#ffc107]">★</span> 4.9 Instructor Rating</span>
                                <span class="flex items-center gap-1.5"><span class="text-[#738a6e]">👥</span> {{ number_format(($course->instructor?->enrollments()->count() ?? 0) + 1240) }}+ Students</span>
                                <span class="flex items-center gap-1.5"><span class="text-[#344c3d]">📚</span> {{ $course->instructor?->courses()->count() ?? 1 }} Courses</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Feedback & Reviews -->
                <div id="reviews" class="bg-white border border-[#e7e7e7] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-xl font-bold text-[#191919]">Student Reviews</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Real feedback from enrolled students</p>
                        </div>
                        <div class="flex items-center gap-1.5 text-[#ffc107] font-bold text-base">
                            <span>★</span>
                            <span class="text-[#191919]">{{ $course->averageRating() ?: '5.0' }}</span>
                            <span class="text-xs text-gray-400 font-normal">({{ $course->reviewCount() }} reviews)</span>
                        </div>
                    </div>

                    <!-- Rating Distribution -->
                    <div class="bg-[#f8faf8] border border-[#e7e7e7] rounded-xl p-5 flex flex-col sm:flex-row items-center gap-6">
                        <div class="text-center sm:border-r sm:border-gray-200 sm:pr-8">
                            <div class="text-4xl font-extrabold text-[#191919]">{{ $course->averageRating() ?: '5.0' }}</div>
                            <div class="flex items-center justify-center gap-1 text-[#ffc107] my-1 text-sm">
                                ★ ★ ★ ★ ★
                            </div>
                            <div class="text-xs text-gray-500 font-medium">Course Rating</div>
                        </div>
                        <div class="flex-1 w-full space-y-2 text-xs">
                            <div class="flex items-center gap-3">
                                <span class="w-12 text-gray-600 font-medium">5 stars</span>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#344c3d] rounded-full" style="width: 95%;"></div>
                                </div>
                                <span class="w-8 text-right text-gray-500">95%</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-12 text-gray-600 font-medium">4 stars</span>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#738a6e] rounded-full" style="width: 5%;"></div>
                                </div>
                                <span class="w-8 text-right text-gray-500">5%</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-12 text-gray-600 font-medium">3 stars</span>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#bfcabb] rounded-full" style="width: 0%;"></div>
                                </div>
                                <span class="w-8 text-right text-gray-500">0%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    @if ($course->reviews->count())
                        <div class="space-y-4 pt-2">
                            @foreach ($course->reviews as $review)
                                <div class="p-4 rounded-xl border border-gray-100 bg-white space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-[#edf3ec] text-[#344c3d] font-bold text-xs flex items-center justify-center">
                                                {{ strtoupper(substr($review->student?->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-[#191919]">{{ $review->student?->name ?? 'Verified Student' }}</h4>
                                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                                    <span class="text-[#ffc107]">{{ str_repeat('★', (int) $review->rating) }}</span>
                                                    <span>•</span>
                                                    <span>{{ $review->created_at ? $review->created_at->diffForHumans() : 'Recently' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-[#5d5d5d] leading-relaxed pt-1">
                                        {{ $review->comment }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-400 text-sm">
                            No reviews submitted yet for this course.
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Sticky Pricing Card -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-6 shadow-xl space-y-6">
                    <!-- Video Preview Container -->
                    <div class="relative aspect-video rounded-xl overflow-hidden bg-[#132018] group shadow-inner">
                        <img src="{{ $course->image ?? 'https://picsum.photos/seed/course-'.$course->id.'/640/420' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90" alt="{{ $course->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 flex items-center justify-center">
                            <button type="button" class="w-14 h-14 rounded-xl bg-[#344c3d] hover:bg-[#25372c] text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform cursor-pointer" aria-label="Play course preview">
                                <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </button>
                        </div>
                        <div class="absolute bottom-2.5 right-2.5 bg-black/70 backdrop-blur-xs text-white text-[11px] font-medium px-2 py-0.5 rounded-lg">
                            Preview Video
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="space-y-1">
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#191919]">
                                {{ $course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free') }}
                            </span>
                            @if ($course->old_price && $course->old_price > $course->price)
                                <span class="text-base text-gray-400 line-through">
                                    {{ __('app.common.currency') }}{{ number_format($course->old_price, 2) }}
                                </span>
                                <span class="bg-[#dce7da] text-[#344c3d] text-xs font-bold px-2.5 py-0.5 rounded-lg">
                                    {{ round((($course->old_price - $course->price) / $course->old_price) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">Includes lifetime access & verified certificate</p>
                    </div>

                    <!-- Buttons (12px max radius boxy) -->
                    <div class="space-y-3">
                        @auth('student')
                            @if ($isEnrolled)
                                <a href="{{ route('student.enrolled-courses') }}" class="block w-full text-center bg-[#344c3d] hover:bg-[#25372c] text-white font-bold py-3.5 rounded-xl transition-all shadow-md text-sm flex items-center justify-center gap-2">
                                    <span>{{ __('courses.go_to_course') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @else
                                <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-[#344c3d] hover:bg-[#25372c] text-white font-bold py-3.5 rounded-xl transition-all shadow-md text-sm cursor-pointer flex items-center justify-center gap-2">
                                        <span>{{ $course->price > 0 ? __('courses.enroll_now') : __('courses.enroll_free') }}</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center bg-[#344c3d] hover:bg-[#25372c] text-white font-bold py-3.5 rounded-xl transition-all shadow-md text-sm flex items-center justify-center gap-2">
                                <span>{{ __('courses.enroll_now') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <p class="text-center text-[11px] text-gray-400">
                                {{ __('courses.guest_enroll_hint') }}
                            </p>
                        @endauth

                        @php
                            $studentAuth = auth('student')->user();
                            $isSavedCourse = $studentAuth ? $studentAuth->hasWishlisted($course) : false;
                        @endphp
                        <button type="button"
                                data-wishlist-toggle="{{ route('courses.wishlist.toggle', $course) }}"
                                data-saved="{{ $isSavedCourse ? '1' : '0' }}"
                                data-guest="{{ $studentAuth ? '0' : '1' }}"
                                class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold py-3 rounded-xl transition-all shadow-2xs text-xs flex items-center justify-center gap-2 cursor-pointer group/wishlist {{ $isSavedCourse ? 'text-rose-500' : '' }}">
                            <svg class="w-4 h-4 transition-transform group-hover/wishlist:scale-110 pointer-events-none {{ $isSavedCourse ? 'fill-rose-500 stroke-rose-500 text-rose-500' : 'fill-none text-slate-500 group-hover/wishlist:text-rose-500' }}"
                                 fill="{{ $isSavedCourse ? 'currentColor' : 'none' }}"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span data-wishlist-label>{{ $isSavedCourse ? 'Saved in Wishlist' : 'Save to Wishlist' }}</span>
                        </button>
                    </div>

                    <!-- Guarantee Note -->
                    <div class="flex items-center justify-center gap-2 text-xs text-[#5d5d5d] bg-[#f8faf8] border border-gray-100 rounded-xl py-2.5 px-3">
                        <svg class="w-4 h-4 text-[#738a6e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="font-medium">30-Day Money-Back Guarantee</span>
                    </div>

                    <!-- Features List -->
                    <div class="border-t border-gray-100 pt-5 space-y-3.5 text-xs text-[#5d5d5d]">
                        <div class="font-bold text-[#191919] text-sm">{{ __('courses.includes') }}:</div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#738a6e] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>{{ $course->lessons->count() }} {{ __('courses.on_demand_video') }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#738a6e] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ __('app.common.duration') }}: {{ $course->duration_minutes }} {{ __('app.common.minutes') }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#738a6e] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>{{ __('app.common.level') }}: {{ __('app.levels.'.$course->level) }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#738a6e] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>Access on mobile, tablet & desktop</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#738a6e] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>{{ __('courses.full_lifetime_access') }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#738a6e] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <span>{{ __('courses.certificate_completion') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Related Courses Section -->
    @if ($related->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-8 pb-16 pt-6 border-t border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-[#191919]">{{ __('courses.related_courses') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">More popular courses in {{ $course->category?->name ?? 'this category' }}</p>
                </div>
                @if ($course->category)
                    <a href="{{ route('courses.index', ['category' => $course->category->slug]) }}" class="text-xs font-semibold text-[#344c3d] hover:text-[#738a6e] flex items-center gap-1 transition-colors">
                        <span>View all {{ $course->category->name }} courses</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($related as $rel)
                    <x-course-card
                        :id="$rel->id"
                        :slug="$rel->slug"
                        :title="$rel->title"
                        :category="$rel->category?->name ?? 'Course'"
                        :instructor="$rel->instructor?->name ?? 'Instructor'"
                        :instructorAvatar="$rel->instructor?->avatarUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($rel->instructor?->name ?? 'I').'&background=344C3D&color=DCE7DA'"
                        :image="$rel->image ?? 'https://picsum.photos/seed/course-'.$rel->id.'/640/420'"
                        :rating="(string) ($rel->averageRating() ?: '5.0')"
                        :reviews="(string) $rel->reviewCount()"
                        :price="$rel->price > 0 ? '$'.number_format($rel->price, 2) : 'Free'"
                        :oldPrice="$rel->old_price ? '$'.number_format($rel->old_price, 2) : null"
                        :lessons="(string) $rel->lessons()->count()"
                        :duration="$rel->duration_minutes"
                        :level="$rel->level"
                    />
                @endforeach
            </div>
        </div>
    @endif

</x-layouts.app>
