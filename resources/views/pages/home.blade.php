<x-layouts.app title="Dreams LMS – Home" active="home">

    <!-- 1. HERO SECTION (Figma: 2355:29935 & 2355:29979) -->
    <section class="bg-gradient-to-b from-[#f8fafb] via-[#fbfcfe] to-white pt-8 pb-16 lg:pt-12 lg:pb-24 relative" data-node-id="2355:29908">
        <!-- Ambient decorative shapes (clipped in their own layer so the
             category dropdown can overflow the section freely) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 right-0 w-[550px] h-[550px] rounded-full bg-[#392c7d]/5 blur-3xl"></div>
            <div class="absolute top-1/3 -left-32 w-[450px] h-[450px] rounded-full bg-[#ff4667]/5 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Content Column (Figma: 2355:29935) -->
                <div class="lg:col-span-6 space-y-7 text-left" data-node-id="2355:29935">
                    <!-- Top Pill Tagline (Figma: 2355:29938) -->
                    <div class="inline-block" data-node-id="2355:29937">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-[#ff4667] text-white shadow-sm" data-node-id="2355:29939">
                            {{ __('home.hero.badge') }}
                        </span>
                    </div>

                    <!-- Main Display Heading (Figma: 2355:29941) -->
                    <h1 class="text-4xl sm:text-5xl lg:text-[52px] font-bold text-[#191919] leading-[1.18] tracking-tight" data-node-id="2355:29941">
                        {{ __('home.hero.title_line1') }} <span class="text-[#ff4667]">&amp;</span> {{ __('home.hero.title_highlight') }} {{ __('home.hero.title_line2') }}
                    </h1>

                    <!-- Subtitle Description (Figma: 2355:29943) -->
                    <p class="text-[#6d6d6d] text-base sm:text-lg leading-relaxed max-w-xl" data-node-id="2355:29943">
                        {{ __('home.hero.subtitle') }}
                    </p>

                    <!-- Rounded Search Bar (Figma: 2355:29944) -->
                    <form action="{{ route('courses.index') }}" method="GET" class="bg-white rounded-[10px] p-2.5 shadow-[0px_4px_18px_rgba(216,216,216,0.35)] border border-[#e9ecef] flex flex-col sm:flex-row items-stretch sm:items-center gap-3 max-w-xl" data-node-id="2355:29944">
                        <!-- Category Select Pill (custom MonoSelect) -->
                        <div class="bg-[#e7e7e7] rounded-[6px] flex items-center" data-node-id="2355:29947">
                            <x-select name="category"
                                :options="$categories->pluck('name', 'slug')"
                                :selected="request('category')"
                                placeholder="{{ __('home.hero.select_category') }}"
                                :searchable="false"
                                triggerClass="bg-transparent rounded-[6px] px-4 py-2.5 text-sm font-medium text-[#191919] border-0" />
                        </div>

                        <!-- Divider Line (Figma: 2355:29951) -->
                        <div class="hidden sm:block w-px h-8 bg-[#e9ecef]"></div>

                        <!-- Search Input with School/Building Icon (Figma: 2355:29952) -->
                        <div class="flex-1 flex items-center gap-2.5 px-2">
                            <svg class="w-5 h-5 text-[#b0b0b0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.common.search_placeholder') }}" class="w-full text-sm text-[#191919] placeholder-[#b0b0b0] bg-transparent focus:outline-none" data-node-id="2355:29975" />
                        </div>

                        <!-- Arrow Submit Button (Figma: 2355:29976) -->
                        <button type="submit" class="bg-[#392c7d] hover:bg-[#2c2263] text-white p-3 rounded-[10px] transition-colors flex items-center justify-center shrink-0 shadow-sm cursor-pointer" aria-label="{{ __('app.common.search') }}" data-node-id="2355:29976">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Right Visual Column (Figma: 2355:29979) -->
                <div class="lg:col-span-6 relative flex justify-center items-center" data-node-id="2355:29979">
                    <!-- Dynamic curved background vectors matching Figma Group 8 (2355:29980) -->
                    <div class="relative w-full max-w-[540px] aspect-[4/4.5] flex items-center justify-center">
                        <!-- Curved Decorative Ribbon Layers -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 540 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Vector 3 (Deep Indigo wave) -->
                            <path d="M120 40C260 -20 460 20 520 180C580 340 480 520 340 580C200 640 40 560 10 420C-20 280 20 80 120 40Z" fill="#392C7D" fill-opacity="0.95" />
                            <!-- Vector 2 (Coral wave) -->
                            <path d="M160 140C280 90 480 160 510 320C540 480 400 580 270 570C140 560 30 460 60 320C90 180 80 170 160 140Z" fill="#FF4667" />
                            <!-- Vector 1 (Cyan curved accent) -->
                            <path d="M220 280C320 240 460 300 480 420C500 540 380 600 270 590C160 580 120 500 140 400C160 300 160 300 220 280Z" fill="#0DCAF0" />
                        </svg>

                        <!-- Hero Model Image (Figma: 2355:29984) -->
                        <div class="relative z-10 w-[85%] h-[92%] rounded-[24px] overflow-hidden shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=900&auto=format&fit=crop&q=85" alt="Smiling student with study materials" class="w-full h-full object-cover object-top" />
                        </div>

                        <!-- Floating Stat Badge 1 (6K+ Certified Courses) -->
                        <div class="absolute -bottom-4 -left-6 z-20 bg-white border border-[#e9ecef] rounded-xl p-3.5 shadow-xl flex items-center gap-3">
                            <div class="w-11 h-11 rounded-lg bg-[#0dcaf0]/15 flex items-center justify-center text-[#0dcaf0]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-base font-bold text-[#0dcaf0]">{{ $stats['courses'] }}+</p>
                                <p class="text-xs text-[#6d6d6d] font-medium">{{ __('home.hero.stats.courses') }}</p>
                            </div>
                        </div>

                        <!-- Floating Stat Badge 2 (60K+ Online Students) -->
                        <div class="absolute top-8 -right-4 z-20 bg-white border border-[#e9ecef] rounded-xl p-3.5 shadow-xl flex items-center gap-3">
                            <div class="w-11 h-11 rounded-lg bg-[#03c95a]/15 flex items-center justify-center text-[#03c95a]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-base font-bold text-[#03c95a]">{{ $stats['students'] }}+</p>
                                <p class="text-xs text-[#6d6d6d] font-medium">{{ __('home.hero.stats.students') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. FOUR QUICK STATISTICS RIBBON (Figma: 2550:20078) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 -mt-6 lg:-mt-10 relative z-20" data-node-id="2550:20078">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Stat 1: 10K Online Courses (Figma: 2550:20079) -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow" data-node-id="2550:20079">
                <div class="w-16 h-16 rounded-xl bg-[#5625e8]/10 flex items-center justify-center text-[#5625e8] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#5625e8] leading-tight" data-node-id="2550:20091">{{ $stats['courses'] }}</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.courses') }}</p>
                </div>
            </div>

            <!-- Stat 2: 200+ Expert Tutors (Figma: 2550:20092) -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow" data-node-id="2550:20092">
                <div class="w-16 h-16 rounded-xl bg-[#ffc107]/15 flex items-center justify-center text-[#ffc107] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#ffc107] leading-tight" data-node-id="2550:20102">{{ $stats['instructors'] }}+</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.instructors') }}</p>
                </div>
            </div>

            <!-- Stat 3: 6K+ Certified Courses (Figma: 2550:20103) -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow" data-node-id="2550:20103">
                <div class="w-16 h-16 rounded-xl bg-[#0dcaf0]/15 flex items-center justify-center text-[#0dcaf0] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#0dcaf0] leading-tight" data-node-id="2550:20114">{{ $stats['courses'] }}</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.courses') }}</p>
                </div>
            </div>

            <!-- Stat 4: 60K+ Online Students (Figma: 2550:20115) -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow" data-node-id="2550:20115">
                <div class="w-16 h-16 rounded-xl bg-[#03c95a]/15 flex items-center justify-center text-[#03c95a] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#03c95a] leading-tight" data-node-id="2550:20130">{{ $stats['students'] }}+</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.students') }}</p>
                </div>
            </div>

        </div>
    </section>

    <!-- 3. MASTER THE SKILLS TO DRIVE YOUR CAREER (Figma: 2355:30034) -->
    <section class="py-20 lg:py-28 overflow-hidden bg-white" data-node-id="2355:30034">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Layered Image Composition (Figma: 2355:30036) -->
                <div class="lg:col-span-5 relative" data-node-id="2355:30036">
                    <div class="relative w-full max-w-[440px] mx-auto aspect-[4/4.8]">
                        <!-- Decorative dotted turquoise ornament & color tabs -->
                        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full border-8 border-[#00c9a7]/20 border-dashed pointer-events-none"></div>
                        <div class="absolute -bottom-6 -left-6 w-28 h-28 bg-gradient-to-tr from-[#392c7d] to-[#0dcaf0] rounded-3xl opacity-20 pointer-events-none"></div>

                        <!-- Main Image 1 (Graduate student with cap) -->
                        <div class="absolute top-0 left-0 w-[78%] h-[78%] rounded-[30px] overflow-hidden shadow-xl border-4 border-white">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&auto=format&fit=crop&q=80" alt="Students learning together" class="w-full h-full object-cover" />
                        </div>

                        <!-- Secondary Overlapping Image (Thumbs-up student) -->
                        <div class="absolute bottom-0 right-0 w-[68%] h-[68%] rounded-[30px] overflow-hidden shadow-2xl border-4 border-white">
                            <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=700&auto=format&fit=crop&q=80" alt="Student with certificate" class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>

                <!-- Right Text & 4 Features Grid (Figma: 2355:30043) -->
                <div class="lg:col-span-7 space-y-8" data-node-id="2355:30043">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#191919] tracking-tight leading-tight" data-node-id="2355:30046">
                            Master the skills to drive your career
                        </h2>
                        <!-- Multi-color Rainbow Bar (Figma: 2355:30049) -->
                        <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-4 mb-5"></div>
                        <p class="text-[#6d6d6d] text-base leading-relaxed" data-node-id="2355:30050">
                            Get certified, master modern tech skills, and level up your career whether you’re starting out or a seasoned pro. 95% of eLearning learners report our hands-on content directly helped their careers.
                        </p>
                    </div>

                    <!-- 4 Features (2x2 Grid) (Figma: 2355:30051) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                        <!-- Feature 1 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-[10px] bg-white border border-[#e7e7e7] shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center justify-center text-[#5625e8] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-[#191919] leading-snug">
                                Get certified with 100+ certification courses
                            </h4>
                        </div>

                        <!-- Feature 2 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-[10px] bg-white border border-[#e7e7e7] shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center justify-center text-[#ffc107] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-[#191919] leading-snug">
                                Build skills your way, from labs to courses
                            </h4>
                        </div>

                        <!-- Feature 3 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-[10px] bg-white border border-[#e7e7e7] shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center justify-center text-[#0dcaf0] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-[#191919] leading-snug">
                                Stay motivated with engaging instructors
                            </h4>
                        </div>

                        <!-- Feature 4 -->
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-[10px] bg-white border border-[#e7e7e7] shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center justify-center text-[#03c95a] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-[#191919] leading-snug">
                                Keep up with the latest in cloud technology
                            </h4>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Divider Line (Figma: 2355:30085) -->
            <div class="my-16 border-t border-[#e9ecef]"></div>

            <!-- Choose Favourite Course From top Category (Figma: 2355:30088) -->
            <div class="space-y-8" data-node-id="2355:30088">
                <!-- Section Header with View All -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#191919]" data-node-id="2355:30092">
                            Choose Favourite Course From top Category
                        </h3>
                        <div class="w-14 h-1 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors" data-node-id="2355:30098">
                        <span>View All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Category Cards (DB-driven, Figma: 2355:30100) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach ($categories->take(5) as $category)
                        <a href="{{ route('courses.index', ['category' => $category->slug]) }}"
                           class="bg-white border border-[#e9ecef] rounded-[10px] p-5 shadow-[0px_4px_10px_rgba(231,231,231,0.25)] hover:shadow-xl transition-all group flex flex-col justify-between">
                            <div class="flex flex-col items-center text-center space-y-3">
                            <div class="w-16 h-16 rounded-[8px] flex items-center justify-center group-hover:scale-110 transition-transform" style="background: {{ $category->color }}15; color: {{ $category->color }};">
                                @if ($category->icon && str_starts_with($category->icon, 'M'))
                                    <svg class="w-9 h-9" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $category->icon }}" /></svg>
                                @else
                                    <span class="text-3xl">{{ $category->icon ?? '📚' }}</span>
                                @endif
                            </div>
                                <h4 class="text-base font-bold text-[#191919] group-hover:text-[#ff4667] transition-colors">
                                    {{ $category->name }}
                                </h4>
                            </div>
                            <div class="mt-5 pt-4 border-t border-[#f1f3f5] flex items-center justify-between text-xs">
                                <span class="text-[#6d6d6d] font-medium">{{ __('home.categories.courses_count', ['count' => $category->courses_count]) }}</span>
                                <span class="w-7 h-7 rounded-full bg-[#392c7d] text-white text-[10px] font-bold flex items-center justify-center border-2 border-white">{{ $category->courses_count }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Carousel Dots (Figma: 2355:30189) -->
                <div class="flex justify-center items-center gap-2 pt-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#e9ecef]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#ff4667]"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#e9ecef]"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FEATURED COURSES 8-GRID (Figma: 2355:30195) -->
    <section class="py-20 bg-[#f8fafb]" data-node-id="2355:30195">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Section Title & Filter Tabs -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]" data-node-id="2355:30203">
                            Featured Courses
                        </h2>
                        <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors" data-node-id="2355:30209">
                        <span>View All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Tabs (Figma: 2355:30211) -->
                <div class="flex flex-wrap items-center gap-2.5 pt-2">
                    <button class="px-5 py-2 rounded-[5px] text-sm font-medium border border-[#ff4667] text-[#ff4667] bg-white shadow-xs">All</button>
                    <button class="px-5 py-2 rounded-[5px] text-sm font-medium text-[#191919] hover:bg-white hover:shadow-xs transition">Most popular</button>
                    <button class="px-5 py-2 rounded-[5px] text-sm font-medium text-[#191919] hover:bg-white hover:shadow-xs transition">Business</button>
                    <button class="px-5 py-2 rounded-[5px] text-sm font-medium text-[#191919] hover:bg-white hover:shadow-xs transition">Design</button>
                    <button class="px-5 py-2 rounded-[5px] text-sm font-medium text-[#191919] hover:bg-white hover:shadow-xs transition">Music</button>
                    <button class="px-5 py-2 rounded-[5px] text-sm font-medium text-[#191919] hover:bg-white hover:shadow-xs transition">Programming</button>
                    <button class="px-5 py-2 rounded-[5px] text-sm font-medium text-[#191919] hover:bg-white hover:shadow-xs transition">Database</button>
                </div>
            </div>

            <!-- Featured Course Cards (DB-driven, Figma: 2355:30226) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredCourses->take(8) as $course)
                    <x-course-card
                        :slug="$course->slug"
                        :title="$course->title"
                        :category="$course->category?->name ?? ''"
                        :instructor="$course->instructor?->name ?? ''"
                        :instructorAvatar="$course->instructor?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor?->name ?? 'I').'&background=392c7d&color=fff'"
                        :image="$course->image ?? 'https://picsum.photos/seed/course-'.$course->id.'/640/420'"
                        :rating="$course->averageRating() ?: '5.0'"
                        :reviews="$course->reviewCount()"
                        :price="$course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free')"
                        :oldPrice="$course->old_price ? __('app.common.currency').number_format($course->old_price, 2) : null"
                        :lessons="$course->lessons->count()"
                        :duration="$course->duration_minutes.' '.__('app.common.minutes')"
                    />
                @endforeach
            </div>

            <!-- View All Courses Button -->
            <div class="flex justify-center mt-12">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-base font-semibold shadow-md transition-all">
                    {{ __('app.common.view_all') }}
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>
    </section>

    <!-- 5. TRANSFORM ACCESS TO EDUCATION BANNER (Figma: 2355:30431) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 my-16" data-node-id="2355:30431">
        <div class="bg-[#392c7d] rounded-[20px] p-8 sm:p-12 lg:p-16 relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8 text-white shadow-xl">
            <!-- Background Waves -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-[#ff4667]/10 pointer-events-none"></div>

            <div class="space-y-3 max-w-2xl relative z-10 text-center lg:text-left">
                <h3 class="text-2xl sm:text-3xl lg:text-[32px] font-bold leading-tight" data-node-id="2355:30435">
                    Transform Access To Education
                </h3>
                <p class="text-white/80 text-sm sm:text-base leading-relaxed" data-node-id="2355:30436">
                    Create an account to receive our newsletter, course recommendations and promotions.
                </p>
            </div>

            <div class="relative z-10 shrink-0">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-base font-medium shadow-lg transition-all" data-node-id="2355:30437">
                    View all Courses
                </a>
            </div>
        </div>
    </section>

    <!-- 6. ACHIEVE YOUR GOALS WITH DREAMSLMS (Figma: 2355:30488) -->
    <section class="py-12 bg-white" data-node-id="2355:30488">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10">

                <!-- Left Title -->
                <div class="max-w-md">
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919] leading-tight" data-node-id="2355:30489">
                        Acheive you Goals with DreamsLMS
                    </h2>
                </div>

                <!-- Right 3 Stats with circles (Figma: 2355:30490) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-12 flex-1 max-w-3xl">

                    <!-- Stat 1 -->
                    <div class="relative pl-4 border-l-2 border-[#5625e8]/20" data-node-id="2355:30491">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl sm:text-4xl font-bold text-[#191919]" data-node-id="2355:30494">253,085</span>
                            <span class="w-5 h-5 rounded-full bg-[#5625e8] shrink-0 inline-block"></span>
                        </div>
                        <p class="text-sm text-[#6d6d6d] font-normal mt-2 leading-relaxed" data-node-id="2355:30495">
                            Students Enrolled all over World
                        </p>
                    </div>

                    <!-- Stat 2 -->
                    <div class="relative pl-4 border-l-2 border-[#0dcaf0]/20" data-node-id="2355:30496">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl sm:text-4xl font-bold text-[#191919]" data-node-id="2355:30499">1,205</span>
                            <span class="w-5 h-5 rounded-full bg-[#0dcaf0] shrink-0 inline-block"></span>
                        </div>
                        <p class="text-sm text-[#6d6d6d] font-normal mt-2 leading-relaxed" data-node-id="2355:30500">
                            Total Courses on our Platform
                        </p>
                    </div>

                    <!-- Stat 3 -->
                    <div class="relative pl-4 border-l-2 border-[#03c95a]/20" data-node-id="2355:30501">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl sm:text-4xl font-bold text-[#191919]" data-node-id="2355:30504">56</span>
                            <span class="w-5 h-5 rounded-full bg-[#03c95a] shrink-0 inline-block"></span>
                        </div>
                        <p class="text-sm text-[#6d6d6d] font-normal mt-2 leading-relaxed" data-node-id="2355:30505">
                            Various Countries of Students
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- 7. ACCELERATE CLOUD SUCCESS (3 ALTERNATING SHOWCASES) (Figma: 2355:30508) -->
    <section class="py-24 bg-[#071136] text-white relative overflow-hidden" data-node-id="2355:30508">
        <!-- Ambient background glows -->
        <div class="absolute top-1/4 -right-40 w-96 h-96 rounded-full bg-[#5625e8]/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 -left-40 w-96 h-96 rounded-full bg-[#ff4667]/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-20 relative z-10">

            <!-- Section Header (Figma: 2355:30509) -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-white/10">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight" data-node-id="2355:30511">
                        Accelerate Cloud Success
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <p class="text-white/80 text-sm sm:text-base max-w-xl leading-relaxed" data-node-id="2355:30515">
                    96% of eLearning for Business customers see improved results within six months. Whether you’re a team of 10 or 10,000, faster cloud fluency starts here.
                </p>
            </div>

            <!-- Showcase 1: Image Left, Text Right (Figma: 2355:30518) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Dual Images Left -->
                <div class="lg:col-span-6 relative">
                    <div class="relative w-full max-w-[460px] mx-auto aspect-[4/4.5]">
                        <div class="absolute -top-4 -left-4 w-32 h-32 rounded-full border-4 border-[#0dcaf0]/30 border-dashed pointer-events-none"></div>
                        <div class="absolute top-0 left-0 w-[78%] h-[82%] rounded-[30px] overflow-hidden shadow-2xl border-2 border-white/10">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80" alt="Students with notebooks" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute bottom-0 right-0 w-[65%] h-[68%] rounded-[30px] overflow-hidden shadow-2xl border-4 border-[#071136]">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=700&auto=format&fit=crop&q=80" alt="Student smiling" class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>

                <!-- Text Right (Gold Trophy) -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="w-14 h-14 rounded-xl bg-[#ffc107]/15 flex items-center justify-center text-[#ffc107]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-bold text-[#ffc107]" data-node-id="2355:30538">
                        Award Winning Course Management
                    </h3>

                    <p class="text-white/80 text-base leading-relaxed" data-node-id="2355:30539">
                        An award-winning course management system (CMS) or program is typically recognized for its exceptional quality, innovation, and effectiveness in helping both instructors and students succeed.
                    </p>

                    <!-- 5 Checkpoints -->
                    <ul class="space-y-3.5 pt-2">
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#ffc107] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Interactive Tools for Engagement</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#ffc107] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Customizable Course Creation</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#ffc107] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Robust Analytics and Reporting</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#ffc107] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Collaborative and Peer-to-Peer Learning</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#ffc107] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>The Most World Class Instructors</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-white/10"></div>

            <!-- Showcase 2: Text Left, Image Right (Figma: 2355:30557) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Text Left (Cyan Clock) -->
                <div class="lg:col-span-6 space-y-6 order-2 lg:order-1">
                    <div class="w-14 h-14 rounded-xl bg-[#0dcaf0]/15 flex items-center justify-center text-[#0dcaf0]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-bold text-[#0dcaf0]" data-node-id="2355:30570">
                        Learn anything from anywhere anytime
                    </h3>

                    <p class="text-white/80 text-base leading-relaxed" data-node-id="2355:30571">
                        In today’s fast-paced, digital world, the ability to learn anything, from anywhere, and at any time is more accessible than ever. Whether you are looking to expand your knowledge, gain new skills.
                    </p>

                    <!-- 5 Checkpoints -->
                    <ul class="space-y-3.5 pt-2">
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#0dcaf0] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Access to a World of Knowledge</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#0dcaf0] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Diverse Learning Formats</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#0dcaf0] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Learn at Your Own Pace</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#0dcaf0] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Affordable and Flexible Pricing</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#0dcaf0] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Learning from Anywhere</span>
                        </li>
                    </ul>
                </div>

                <!-- Dual Images Right -->
                <div class="lg:col-span-6 relative order-1 lg:order-2">
                    <div class="relative w-full max-w-[460px] mx-auto aspect-[4/4.5]">
                        <div class="absolute -top-4 -right-4 w-32 h-32 rounded-full border-4 border-[#ff4667]/30 border-dashed pointer-events-none"></div>
                        <div class="absolute top-0 right-0 w-[78%] h-[82%] rounded-[30px] overflow-hidden shadow-2xl border-2 border-white/10">
                            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80" alt="Student with laptop outdoors" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute bottom-0 left-0 w-[65%] h-[68%] rounded-[30px] overflow-hidden shadow-2xl border-4 border-[#071136]">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=700&auto=format&fit=crop&q=80" alt="Professional in blue suit" class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-white/10"></div>

            <!-- Showcase 3: Image Left, Text Right (Figma: 2355:30598) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Dual Images Left -->
                <div class="lg:col-span-6 relative">
                    <div class="relative w-full max-w-[460px] mx-auto aspect-[4/4.5]">
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 rounded-full border-4 border-[#03c95a]/30 border-dashed pointer-events-none"></div>
                        <div class="absolute top-0 left-0 w-[78%] h-[82%] rounded-[30px] overflow-hidden shadow-2xl border-2 border-white/10">
                            <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80" alt="Student cheerful" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute bottom-0 right-0 w-[65%] h-[68%] rounded-[30px] overflow-hidden shadow-2xl border-4 border-[#071136]">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=700&auto=format&fit=crop&q=80" alt="Student with books" class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>

                <!-- Text Right (Green Certificate) -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="w-14 h-14 rounded-xl bg-[#03c95a]/15 flex items-center justify-center text-[#03c95a]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-bold text-[#03c95a]">
                        Certification for solid development of your Career
                    </h3>

                    <p class="text-white/80 text-base leading-relaxed">
                        Certifications are a powerful way to enhance your skills, build credibility, and boost your career growth. In today’s competitive job market, a professional certification gives you an edge.
                    </p>

                    <!-- 5 Checkpoints -->
                    <ul class="space-y-3.5 pt-2">
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#03c95a] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Demonstrates Expertise</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#03c95a] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Boosts Your Credibility</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#03c95a] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Improves Job Security</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#03c95a] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Facilitates Career Advancement</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm sm:text-base font-medium text-white/90">
                            <svg class="w-5 h-5 text-[#03c95a] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Fosters Personal Growth</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </section>

    <!-- 8. MOST TRENDING COURSES (Figma: 2355:30643) -->
    <section class="py-20 bg-[#f8fafb]" data-node-id="2355:30643">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Section Title & View All -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]" data-node-id="2355:30650">
                        Most Trending Courses
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors" data-node-id="2355:30656">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Trending Course Cards (DB-driven, Figma: 2355:30658) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredCourses->skip(2)->take(4) as $course)
                    <x-course-card
                        :slug="$course->slug"
                        :title="$course->title"
                        :category="$course->category?->name ?? ''"
                        :instructor="$course->instructor?->name ?? ''"
                        :instructorAvatar="$course->instructor?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor?->name ?? 'I').'&background=392c7d&color=fff'"
                        :image="$course->image ?? 'https://picsum.photos/seed/course-'.$course->id.'/640/420'"
                        :rating="$course->averageRating() ?: '5.0'"
                        :reviews="$course->reviewCount()"
                        :price="$course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free')"
                        :oldPrice="$course->old_price ? __('app.common.currency').number_format($course->old_price, 2) : null"
                        :lessons="$course->lessons->count()"
                        :duration="$course->duration_minutes.' '.__('app.common.minutes')"
                    />
                @endforeach
            </div>
            <!-- Dots -->
            <div class="flex justify-center items-center gap-2 pt-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#e9ecef]"></span>
                <span class="w-3 h-3 rounded-full bg-[#ff4667]"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#e9ecef]"></span>
            </div>
        </div>
    </section>

    <!-- 9. COURSES TAUGHT BY REAL INSTRUCTOR (Figma: 2355:30752) -->
    <section class="py-20 bg-white" data-node-id="2355:30752">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]" data-node-id="2355:30756">
                        Courses Taught by Real Instructor
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('instructors.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors" data-node-id="2355:30762">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Masonry Grid with Tall Center Card (Figma: 2355:30764) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">

                <!-- Left 4 Instructor Photos (md:col-span-4) -->
                <div class="md:col-span-4 grid grid-cols-2 gap-4">
                    @foreach ($topInstructors->take(4) as $instructor)
                        <a href="{{ route('instructors.show', \Illuminate\Support\Str::slug($instructor->name)) }}" class="h-56 rounded-[10px] overflow-hidden shadow-sm group bg-[#392c7d]/10 flex items-center justify-center">
                            @if($instructor->avatar)
                                <img src="{{ $instructor->avatar }}" alt="{{ $instructor->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <span class="text-4xl font-bold text-[#392c7d]">{{ strtoupper(substr($instructor->name, 0, 1)) }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- Center Featured Card with Overlay (md:col-span-4) (Figma: 2355:30773 & 2355:30780) -->
                <div class="md:col-span-4 h-[470px] relative rounded-[14px] overflow-hidden shadow-2xl group">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80" alt="Hendriques" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div class="absolute inset-0 bg-[#392c7d]/85 flex flex-col justify-between p-6 text-white">
                        <div class="flex justify-end">
                            <span class="w-8 h-8 rounded-full bg-white/20 hover:bg-[#ff4667] flex items-center justify-center transition-colors cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold tracking-tight" data-node-id="2355:30785">Hendriques</h3>
                                <p class="text-sm text-white/80 mt-1" data-node-id="2355:30786">Python Developer</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white group-hover:bg-[#ff4667] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right 3-4 Instructor Photos (md:col-span-4) -->
                <div class="md:col-span-4 grid grid-cols-2 gap-4">
                    @foreach ($topInstructors->skip(4) as $instructor)
                        <a href="{{ route('instructors.show', \Illuminate\Support\Str::slug($instructor->name)) }}" class="h-56 rounded-[10px] overflow-hidden shadow-sm group bg-[#ff4667]/10 flex items-center justify-center">
                            @if($instructor->avatar)
                                <img src="{{ $instructor->avatar }}" alt="{{ $instructor->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <span class="text-4xl font-bold text-[#ff4667]">{{ strtoupper(substr($instructor->name, 0, 1)) }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- 10. BECOME AN INSTRUCTOR BANNER (Figma: 2355:31060) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 my-16" data-node-id="2355:31060">
        <div class="bg-[#ff4667] rounded-[20px] p-8 sm:p-12 lg:p-16 flex flex-col lg:flex-row items-center justify-between gap-8 text-white shadow-xl">
            <div class="space-y-3 max-w-2xl text-center lg:text-left">
                <h3 class="text-2xl sm:text-3xl lg:text-[32px] font-bold leading-tight" data-node-id="2355:31064">
                    Become An Instructor
                </h3>
                <p class="text-white/90 text-sm sm:text-base leading-relaxed" data-node-id="2355:31065">
                    Top instructors from around the world teach millions of students on DreamsLMS.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('instructor.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-white text-[#191919] hover:bg-gray-100 text-base font-semibold shadow-lg transition-all" data-node-id="2355:31066">
                    Join Our Community
                </a>
            </div>
        </div>
    </section>

    <!-- 11. 500+ LEADING UNIVERSITIES AND COMPANIES (Figma: 2355:30789) -->
    <section class="py-16 bg-white" data-node-id="2355:30789">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Section Title & View All -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#191919]" data-node-id="2355:30795">
                        500+ Leading Universities And Companies
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors" data-node-id="2355:30801">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Brand Logos Row (Figma: 2355:30803) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-8 items-center justify-items-center opacity-85 hover:opacity-100 transition-opacity">
                <!-- Jira Software -->
                <div class="flex items-center gap-2 font-bold text-lg text-[#0052cc] hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.53 2c0 2.4 1.97 4.35 4.39 4.35h2.44v2.41c0 2.42 1.97 4.37 4.39 4.37V2h-11.22zm-4.75 4.77c0 2.4 1.97 4.35 4.39 4.35h2.44v2.41c0 2.42 1.97 4.37 4.39 4.37V6.77H6.78zM2 11.53c0 2.4 1.97 4.35 4.39 4.35h2.44v2.41c0 2.42 1.97 4.37 4.39 4.37V11.53H2z"/>
                    </svg>
                    <span>Jira Software</span>
                </div>

                <!-- Calendly -->
                <div class="flex items-center gap-1.5 font-bold text-lg text-[#006bff] hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                    <span>Calendly</span>
                </div>

                <!-- LiveChat -->
                <div class="flex items-center gap-1.5 font-bold text-lg text-[#ff5000] hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                    <span>LiveChat</span>
                </div>

                <!-- Dropbox -->
                <div class="flex items-center gap-1.5 font-bold text-lg text-[#0061ff] hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.02 2L0 6.13l5.98 4.79L12 6.88 6.02 2zM17.98 2L12 6.88l6.02 4.04L24 6.13 17.98 2zM0 15.71l6.02 4.13L12 14.96l-6.02-4.04L0 15.71zm24 0l-6.02-4.79L12 14.96l5.98 4.88L24 15.71zM12 16.17l-5.98 4.79L12 24l5.98-3.04-5.98-4.79z"/>
                    </svg>
                    <span>Dropbox</span>
                </div>

                <!-- mixpanel -->
                <div class="flex items-center gap-1.5 font-bold text-lg text-[#191919] hover:scale-105 transition-transform">
                    <span class="w-3 h-3 rounded-full bg-[#7856ff] inline-block"></span>
                    <span>mixpanel</span>
                </div>

                <!-- make -->
                <div class="flex items-center gap-1.5 font-bold text-lg text-[#b000ff] hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    <span>make</span>
                </div>
            </div>

        </div>
    </section>

    <!-- 12. TRUSTED BY MANY TESTIMONIALS (Figma: 2355:30810) -->
    <section class="py-24 bg-[#071136] text-white relative overflow-hidden" data-node-id="2355:30810">
        <!-- Dotted world map background overlay -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Content (Figma: 2355:30812) -->
                <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight leading-tight" data-node-id="2355:30813">
                        Trusted By Many
                    </h2>

                    <p class="text-white/80 text-lg leading-relaxed max-w-md" data-node-id="2355:30814">
                        We are a very happy because we have a happy Learners
                    </p>

                    <div>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-white text-[#191919] hover:bg-gray-100 text-base font-semibold shadow-lg transition-all" data-node-id="2355:30815">
                            Join Our Community
                        </a>
                    </div>
                </div>

                <!-- Right Cascading Testimonials (Figma: 2355:30816) -->
                <div class="lg:col-span-7 relative flex flex-col items-center lg:items-end">
                    <!-- Featured Active Review Card -->
                    <div class="bg-white text-[#191919] rounded-[14px] p-8 shadow-2xl max-w-xl relative border border-gray-100 space-y-6" data-node-id="2355:30818">
                        <!-- Purple Quote Icon (Figma: 2355:30820) -->
                        <div class="text-[#5625e8]">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>

                        <!-- Quote Text (Figma: 2355:30825) -->
                        <p class="text-[#4f4f4f] text-base sm:text-lg leading-relaxed font-normal" data-node-id="2355:30825">
                            “I often felt like the mentor’s answers were too detailed, which made it hard for me to keep up. Sometimes, a simpler explanation would have helped me understand things faster. But overall, I’ve become more organized, confident, and focused thanks to my life coach.”
                        </p>

                        <!-- Rating Stars (Figma: 2355:30826) -->
                        <div class="flex items-center gap-3">
                            <div class="flex text-[#ffc107]">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current opacity-50" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            </div>
                            <span class="text-sm font-semibold text-[#685f78]" data-node-id="2355:30833">4.5 ratings</span>
                        </div>

                        <!-- Reviewer Info (Figma: 2355:30837) -->
                        <div class="pt-4 border-t border-gray-100 flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Martin Harn" class="w-12 h-12 rounded-full object-cover shadow-sm" />
                            <div>
                                <h4 class="text-base font-bold text-[#002058]" data-node-id="2355:30840">Martin Harn</h4>
                                <p class="text-sm text-[#685f78]" data-node-id="2355:30841">Docker Development</p>
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Controls (Figma: 2355:30894) -->
                    <div class="flex items-center gap-3 mt-8">
                        <button class="w-10 h-10 rounded-full bg-white text-[#191919] hover:bg-[#ff4667] hover:text-white flex items-center justify-center transition-colors shadow-md" aria-label="Previous Review">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button class="w-10 h-10 rounded-full bg-white text-[#191919] hover:bg-[#ff4667] hover:text-white flex items-center justify-center transition-colors shadow-md" aria-label="Next Review">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 13. LATEST NEWS & EVENTS (Figma: 2355:30969) -->
    <section class="py-20 bg-white" data-node-id="2355:30969">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]" data-node-id="2355:30975">
                        Latest news &amp; Events
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors" data-node-id="2355:30981">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

                    <!-- News Cards (DB-driven blog posts) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse ($posts as $post)
                            <a href="{{ route('blog.show', $post->slug) }}" class="rounded-[14px] overflow-hidden relative shadow-md group min-h-[220px] flex flex-col justify-end p-5">
                                <img src="{{ $post->image ?? 'https://picsum.photos/seed/post-'.$post->id.'/640/420' }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                <div class="relative z-10 space-y-2">
                                    <div class="flex items-center gap-2 text-[11px] text-white">
                                        <span class="border border-white/80 rounded-full px-2 py-0.5">{{ $post->author?->name ?? 'DreamsLMS' }}</span>
                                        <span class="opacity-80">{{ $post->published_at?->format('d M Y') }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-white group-hover:text-[#ff4667] transition-colors line-clamp-2">
                                        {{ $post->title }}
                                    </h4>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center text-gray-400 text-sm py-10">{{ __('blog.empty') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>