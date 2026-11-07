<x-layouts.app title="Mono LMS – Home" active="home">

    <!-- 1. HERO SECTION -->
    <section class="bg-gradient-to-b from-[#f8fafb] via-[#fbfcfe] to-white pt-8 pb-16 lg:pt-12 lg:pb-24 relative">
        <!-- Ambient decorative shapes (clipped in their own layer so the
             category dropdown can overflow the section freely) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 right-0 w-[550px] h-[550px] rounded-full bg-[#392c7d]/5 blur-3xl anim-pulse-soft"></div>
            <div class="absolute top-1/3 -left-32 w-[450px] h-[450px] rounded-full bg-[#ff4667]/5 blur-3xl anim-pulse-soft"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Content Column -->
                <div class="lg:col-span-6 space-y-7 text-left">
                    <!-- Top Pill Tagline -->
                    <div class="inline-block anim-fade-up">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-[#ff4667] text-white shadow-sm">
                            {{ __('home.hero.badge') }}
                        </span>
                    </div>

                    <!-- Main Display Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-[52px] font-bold text-[#191919] leading-[1.18] tracking-tight anim-fade-up anim-d1">
                        {{ __('home.hero.title_line1') }} <span class="text-[#ff4667]">&amp;</span> {{ __('home.hero.title_highlight') }} {{ __('home.hero.title_line2') }}
                    </h1>

                    <!-- Subtitle Description -->
                    <p class="text-[#6d6d6d] text-base sm:text-lg leading-relaxed max-w-xl anim-fade-up anim-d2">
                        {{ __('home.hero.subtitle') }}
                    </p>

                    <!-- Rounded Search Bar -->
                    <form action="{{ route('courses.index') }}" method="GET" class="anim-fade-up anim-d3 bg-white rounded-[10px] p-2.5 shadow-[0px_4px_18px_rgba(216,216,216,0.35)] border border-[#e9ecef] flex flex-col sm:flex-row items-stretch sm:items-center gap-3 max-w-xl">
                        <!-- Category Select Pill (custom MonoSelect) -->
                        <div class="bg-[#e7e7e7] rounded-[6px] flex items-center">
                            <x-select name="category"
                                :options="$categories->pluck('name', 'slug')"
                                :selected="request('category')"
                                placeholder="{{ __('home.hero.select_category') }}"
                                :searchable="false"
                                triggerClass="bg-transparent rounded-[6px] px-4 py-2.5 text-sm font-medium text-[#191919] border-0" />
                        </div>

                        <!-- Divider Line -->
                        <div class="hidden sm:block w-px h-8 bg-[#e9ecef]"></div>

                        <!-- Search Input with School/Building Icon -->
                        <div class="flex-1 flex items-center gap-2.5 px-2">
                            <svg class="w-5 h-5 text-[#b0b0b0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.common.search_placeholder') }}" class="w-full text-sm text-[#191919] placeholder-[#b0b0b0] bg-transparent focus:outline-none" />
                        </div>

                        <!-- Arrow Submit Button -->
                        <button type="submit" class="bg-[#392c7d] hover:bg-[#2c2263] text-white p-3 rounded-[10px] transition-colors flex items-center justify-center shrink-0 shadow-sm cursor-pointer" aria-label="{{ __('app.common.search') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>

                    <!-- Quick Category Chips -->
                    <div class="flex flex-wrap items-center gap-2 anim-fade-up anim-d4">
                        <span class="text-xs text-gray-400 font-medium">{{ __('home.hero.popular') }}</span>
                        @foreach ($categories->take(4) as $chipCategory)
                            <a href="{{ route('courses.index', ['category' => $chipCategory->slug]) }}" class="px-3 py-1.5 rounded-full bg-white border border-[#e7e7e7] text-xs font-medium text-[#191919] hover:border-[#ff4667] hover:text-[#ff4667] transition-colors">
                                {{ $chipCategory->name }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Trust Row -->
                    <div class="flex items-center gap-3 anim-fade-up anim-d5">
                        <div class="flex -space-x-2.5">
                            @foreach ($topInstructors->take(4) as $trustInstructor)
                                <img src="{{ $trustInstructor->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($trustInstructor->name).'&background=392c7d&color=fff' }}" alt="{{ $trustInstructor->name }}" class="w-9 h-9 rounded-full border-2 border-white object-cover shadow-sm" />
                            @endforeach
                            <span class="w-9 h-9 rounded-full bg-[#ff4667] text-white text-[10px] font-bold flex items-center justify-center border-2 border-white shadow-sm">+{{ $stats['instructors'] }}</span>
                        </div>
                        <div class="text-xs text-[#6d6d6d] leading-snug">
                            <span class="text-[#ffc107]">★★★★★</span> <strong class="text-[#191919]">{{ __('home.hero.rating_label') }}</strong><br>
                            Trusted by <strong class="text-[#191919]">{{ $stats['students'] }}+ {{ __('home.hero.students_word') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Right Visual Column -->
                <div class="lg:col-span-6 relative flex justify-center items-center">
                    <!-- Dynamic curved background vectors -->
                    <div data-hero-showcase class="relative w-full max-w-[540px] aspect-[4/4.5] flex items-center justify-center">
                        <!-- Curved Decorative Ribbon Layers -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 540 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Vector 3 (Deep Indigo wave) -->
                            <path d="M120 40C260 -20 460 20 520 180C580 340 480 520 340 580C200 640 40 560 10 420C-20 280 20 80 120 40Z" fill="#392C7D" fill-opacity="0.95" />
                            <!-- Vector 2 (Coral wave) -->
                            <path d="M160 140C280 90 480 160 510 320C540 480 400 580 270 570C140 560 30 460 60 320C90 180 80 170 160 140Z" fill="#FF4667" />
                            <!-- Vector 1 (Cyan curved accent) -->
                            <path d="M220 280C320 240 460 300 480 420C500 540 380 600 270 590C160 580 120 500 140 400C160 300 160 300 220 280Z" fill="#0DCAF0" />
                                     <!-- Image Slides (student / instructors / courses — crossfade every 10s) -->
                        <div class="relative z-10 w-[82%] aspect-square rounded-full overflow-hidden shadow-2xl ring-8 ring-white/40 border-4 border-white/60">
                            @foreach ([
                                ['src' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.student')],
                                ['src' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.courses')],
                                ['src' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.teachers')],
                                ['src' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.graduation')],
                            ] as $slide)
                                <img data-hero-slide src="{{ $slide['src'] }}" alt="{{ $slide['alt'] }}" />
                            @endforeach
                        </div>

                        <!-- 6 Floating Info Chips — rotate every 10s -->
                        @php
                            $heroItems = [
                                ['value' => $stats['courses'].'+', 'label' => __('home.hero.items.courses'), 'color' => '#ff4667', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                ['value' => $stats['students'].'+', 'label' => __('home.hero.items.students'), 'color' => '#03c95a', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                                ['value' => $stats['instructors'].'+', 'label' => __('home.hero.items.instructors'), 'color' => '#5625e8', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                                ['value' => $stats['enrollments'].'+', 'label' => __('home.hero.items.enrollments'), 'color' => '#0dcaf0', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                                ['value' => $categories->count(), 'label' => __('home.hero.items.categories'), 'color' => '#ffc107', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                                ['value' => __('home.hero.items.live_value'), 'label' => __('home.hero.items.live'), 'color' => '#ff2d55', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                                ['value' => __('home.hero.items.certificates_value'), 'label' => __('home.hero.items.certificates'), 'color' => '#03c95a', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                                ['value' => __('home.hero.items.rating_value'), 'label' => __('home.hero.items.rating'), 'color' => '#ffc107', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                                ['value' => __('home.hero.items.support_value'), 'label' => __('home.hero.items.support'), 'color' => '#0dcaf0', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
                                ['value' => __('home.hero.items.guidance_value'), 'label' => __('home.hero.items.guidance'), 'color' => '#5625e8', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                ['value' => __('home.hero.items.access_value'), 'label' => __('home.hero.items.access'), 'color' => '#03c95a', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                                ['value' => __('home.hero.items.anywhere_value'), 'label' => __('home.hero.items.anywhere'), 'color' => '#ff4667', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                            ];
                        @endphp

                        @foreach ([
                            ['pos' => '-top-5 right-0', 'item' => 4],
                            ['pos' => '-bottom-5 left-6', 'item' => 0],
                            ['pos' => 'top-1/3 -left-3 hidden lg:block', 'item' => 2],
                            ['pos' => 'bottom-1/3 -right-3 hidden lg:block', 'item' => 3],
                            ['pos' => '-top-6 left-14 hidden lg:block', 'item' => 4],
                            ['pos' => 'bottom-10 -right-3 hidden lg:block', 'item' => 5],
                        ] as $slotConfig)
                            <div data-hero-slot class="absolute {{ $slotConfig['pos'] }} z-20">
                                <div class="hero-chip bg-white border border-[#e9ecef] rounded-full p-3.5 shadow-xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-lg flex items-center justify-center shrink-0" style="background: {{ $heroItems[$slotConfig['item']]['color'] }}15; color: {{ $heroItems[$slotConfig['item']]['color'] }};">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $heroItems[$slotConfig['item']]['icon'] }}"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-base font-bold text-[#191919] leading-tight">{{ $heroItems[$slotConfig['item']]['value'] }}</p>
                                            <p class="text-xs text-[#6d6d6d] font-medium">{{ $heroItems[$slotConfig['item']]['label'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Rotation templates (JS swaps chip content every 10s) -->
                        @foreach ($heroItems as $heroItem)
                            <template data-hero-item>
                                <div class="hero-chip bg-white border border-[#e9ecef] rounded-full p-3.5 shadow-xl">
                                    <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-lg flex items-center justify-center shrink-0" style="background: {{ $heroItem['color'] }}15; color: {{ $heroItem['color'] }};">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $heroItem['icon'] }}"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-[#191919] leading-tight">{{ $heroItem['value'] }}</p>
                                        <p class="text-xs text-[#6d6d6d] font-medium">{{ $heroItem['label'] }}</p>
                                    </div>
                                </div>
                            
                                </div>
                            </template>
                        @endforeach            </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. FOUR QUICK STATISTICS RIBBON -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 -mt-6 lg:-mt-10 relative z-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Stat 1: 10K Online Courses -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-[#5625e8]/10 flex items-center justify-center text-[#5625e8] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#5625e8] leading-tight" data-countup="{{ $stats['courses'] }}">0</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.courses') }}</p>
                </div>
            </div>

            <!-- Stat 2: 200+ Expert Tutors -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-[#ffc107]/15 flex items-center justify-center text-[#ffc107] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#ffc107] leading-tight" data-countup="{{ $stats['instructors'] }}" data-suffix="+">0+</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.instructors') }}</p>
                </div>
            </div>

            <!-- Stat 3: 6K+ Certified Courses -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-[#0dcaf0]/15 flex items-center justify-center text-[#0dcaf0] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#0dcaf0] leading-tight" data-countup="{{ $stats['courses'] }}">0</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.courses') }}</p>
                </div>
            </div>

            <!-- Stat 4: 60K+ Online Students -->
            <div class="bg-white border border-[#e9ecef] rounded-[10px] p-6 shadow-[0px_4px_14px_rgba(231,231,231,0.35)] flex items-center gap-4 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 rounded-xl bg-[#03c95a]/15 flex items-center justify-center text-[#03c95a] shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-[#03c95a] leading-tight" data-countup="{{ $stats['students'] }}" data-suffix="+">0+</h3>
                    <p class="text-sm font-medium text-[#6d6d6d] mt-1">{{ __('home.hero.stats.students') }}</p>
                </div>
            </div>

        </div>
    </section>

    <!-- 3. MASTER THE SKILLS TO DRIVE YOUR CAREER -->
    <section class="py-20 lg:py-28 overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Layered Image Composition -->
                <div class="lg:col-span-5 relative">
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

                <!-- Right Text & 4 Features Grid -->
                <div class="lg:col-span-7 space-y-8">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#191919] tracking-tight leading-tight">
                            Master the skills to drive your career
                        </h2>
                        <!-- Multi-color Rainbow Bar -->
                        <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-4 mb-5"></div>
                        <p class="text-[#6d6d6d] text-base leading-relaxed">
                            Get certified, master modern tech skills, and level up your career whether you’re starting out or a seasoned pro. 95% of eLearning learners report our hands-on content directly helped their careers.
                        </p>
                    </div>

                    <!-- 4 Features (2x2 Grid) -->
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

            <!-- Divider Line -->
            <div class="my-16 border-t border-[#e9ecef]"></div>

            <!-- Choose Favourite Course From top Category -->
            <div class="space-y-8">
                <!-- Section Header with View All -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#191919]">
                            Choose Favourite Course From top Category
                        </h3>
                        <div class="w-14 h-1 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors">
                        <span>View All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Category Cards (DB-driven) -->
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

                <!-- Carousel Dots -->
                <div class="flex justify-center items-center gap-2 pt-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#e9ecef]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#ff4667]"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#e9ecef]"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FEATURED COURSES 8-GRID -->
    <section class="py-20 bg-[#f8fafb]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Section Title & Filter Tabs -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]">
                            Featured Courses
                        </h2>
                        <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors">
                        <span>View All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Tabs -->
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

            <!-- Featured Course Cards (DB-driven) -->
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

    <!-- 5. TRANSFORM ACCESS TO EDUCATION BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 my-16">
        <div class="bg-[#392c7d] rounded-[20px] p-8 sm:p-12 lg:p-16 relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8 text-white shadow-xl">
            <!-- Background Waves -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-[#ff4667]/10 pointer-events-none"></div>

            <div class="space-y-3 max-w-2xl relative z-10 text-center lg:text-left">
                <h3 class="text-2xl sm:text-3xl lg:text-[32px] font-bold leading-tight">
                    Transform Access To Education
                </h3>
                <p class="text-white/80 text-sm sm:text-base leading-relaxed">
                    Create an account to receive our newsletter, course recommendations and promotions.
                </p>
            </div>

            <div class="relative z-10 shrink-0">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-[#ff4667] hover:bg-[#e03a58] text-white text-base font-medium shadow-lg transition-all">
                    View all Courses
                </a>
            </div>
        </div>
    </section>

    <!-- 6. ACHIEVE YOUR GOALS WITH DREAMSLMS -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10">

                <!-- Left Title -->
                <div class="max-w-md">
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919] leading-tight">
                        Acheive you Goals with MonoLMS
                    </h2>
                </div>

                <!-- Right 3 Stats with circles -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-12 flex-1 max-w-3xl">

                    <!-- Stat 1 -->
                    <div class="relative pl-4 border-l-2 border-[#5625e8]/20">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl sm:text-4xl font-bold text-[#191919]">253,085</span>
                            <span class="w-5 h-5 rounded-full bg-[#5625e8] shrink-0 inline-block"></span>
                        </div>
                        <p class="text-sm text-[#6d6d6d] font-normal mt-2 leading-relaxed">
                            Students Enrolled all over World
                        </p>
                    </div>

                    <!-- Stat 2 -->
                    <div class="relative pl-4 border-l-2 border-[#0dcaf0]/20">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl sm:text-4xl font-bold text-[#191919]">1,205</span>
                            <span class="w-5 h-5 rounded-full bg-[#0dcaf0] shrink-0 inline-block"></span>
                        </div>
                        <p class="text-sm text-[#6d6d6d] font-normal mt-2 leading-relaxed">
                            Total Courses on our Platform
                        </p>
                    </div>

                    <!-- Stat 3 -->
                    <div class="relative pl-4 border-l-2 border-[#03c95a]/20">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl sm:text-4xl font-bold text-[#191919]">56</span>
                            <span class="w-5 h-5 rounded-full bg-[#03c95a] shrink-0 inline-block"></span>
                        </div>
                        <p class="text-sm text-[#6d6d6d] font-normal mt-2 leading-relaxed">
                            Various Countries of Students
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- 7. ACCELERATE CLOUD SUCCESS (3 ALTERNATING SHOWCASES) -->
    <section class="py-24 bg-[#071136] text-white relative overflow-hidden">
        <!-- Ambient background glows -->
        <div class="absolute top-1/4 -right-40 w-96 h-96 rounded-full bg-[#5625e8]/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 -left-40 w-96 h-96 rounded-full bg-[#ff4667]/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-20 relative z-10">

            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-white/10">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">
                        Accelerate Cloud Success
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <p class="text-white/80 text-sm sm:text-base max-w-xl leading-relaxed">
                    96% of eLearning for Business customers see improved results within six months. Whether you’re a team of 10 or 10,000, faster cloud fluency starts here.
                </p>
            </div>

            <!-- Showcase 1: Image Left, Text Right -->
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

                    <h3 class="text-2xl sm:text-3xl font-bold text-[#ffc107]">
                        Award Winning Course Management
                    </h3>

                    <p class="text-white/80 text-base leading-relaxed">
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

            <!-- Showcase 2: Text Left, Image Right -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Text Left (Cyan Clock) -->
                <div class="lg:col-span-6 space-y-6 order-2 lg:order-1">
                    <div class="w-14 h-14 rounded-xl bg-[#0dcaf0]/15 flex items-center justify-center text-[#0dcaf0]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-bold text-[#0dcaf0]">
                        Learn anything from anywhere anytime
                    </h3>

                    <p class="text-white/80 text-base leading-relaxed">
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

            <!-- Showcase 3: Image Left, Text Right -->
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

    <!-- 8. MOST TRENDING COURSES -->
    <section class="py-20 bg-[#f8fafb]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Section Title & View All -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]">
                        Most Trending Courses
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Trending Course Cards (DB-driven) -->
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

    <!-- 9. COURSES TAUGHT BY REAL INSTRUCTOR -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]">
                        Courses Taught by Real Instructor
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('instructors.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Masonry Grid with Tall Center Card -->
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

                <!-- Center Featured Card with Overlay (md:col-span-4) -->
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
                                <h3 class="text-2xl font-bold tracking-tight">Hendriques</h3>
                                <p class="text-sm text-white/80 mt-1">Python Developer</p>
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

    <!-- 10. BECOME AN INSTRUCTOR BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 my-16">
        <div class="bg-[#ff4667] rounded-[20px] p-8 sm:p-12 lg:p-16 flex flex-col lg:flex-row items-center justify-between gap-8 text-white shadow-xl">
            <div class="space-y-3 max-w-2xl text-center lg:text-left">
                <h3 class="text-2xl sm:text-3xl lg:text-[32px] font-bold leading-tight">
                    Become An Instructor
                </h3>
                <p class="text-white/90 text-sm sm:text-base leading-relaxed">
                    Top instructors from around the world teach millions of students on MonoLMS.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('instructor.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-white text-[#191919] hover:bg-gray-100 text-base font-semibold shadow-lg transition-all">
                    Join Our Community
                </a>
            </div>
        </div>
    </section>

    <!-- 11. 500+ LEADING UNIVERSITIES AND COMPANIES -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Section Title & View All -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#191919]">
                        500+ Leading Universities And Companies
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors">
                    <span>View All</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Brand Logos Row -->
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

    <!-- 12. TRUSTED BY MANY TESTIMONIALS -->
    <section class="py-24 bg-[#071136] text-white relative overflow-hidden">
        <!-- Dotted world map background overlay -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Content -->
                <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight leading-tight">
                        Trusted By Many
                    </h2>

                    <p class="text-white/80 text-lg leading-relaxed max-w-md">
                        We are a very happy because we have a happy Learners
                    </p>

                    <div>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-white text-[#191919] hover:bg-gray-100 text-base font-semibold shadow-lg transition-all">
                            Join Our Community
                        </a>
                    </div>
                </div>

                <!-- Right Cascading Testimonials -->
                <div class="lg:col-span-7 relative flex flex-col items-center lg:items-end">
                    <!-- Featured Active Review Card -->
                    <div class="bg-white text-[#191919] rounded-[14px] p-8 shadow-2xl max-w-xl relative border border-gray-100 space-y-6">
                        <!-- Purple Quote Icon -->
                        <div class="text-[#5625e8]">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>

                        <!-- Quote Text -->
                        <p class="text-[#4f4f4f] text-base sm:text-lg leading-relaxed font-normal">
                            “I often felt like the mentor’s answers were too detailed, which made it hard for me to keep up. Sometimes, a simpler explanation would have helped me understand things faster. But overall, I’ve become more organized, confident, and focused thanks to my life coach.”
                        </p>

                        <!-- Rating Stars -->
                        <div class="flex items-center gap-3">
                            <div class="flex text-[#ffc107]">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <svg class="w-5 h-5 fill-current opacity-50" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            </div>
                            <span class="text-sm font-semibold text-[#685f78]">4.5 ratings</span>
                        </div>

                        <!-- Reviewer Info -->
                        <div class="pt-4 border-t border-gray-100 flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Martin Harn" class="w-12 h-12 rounded-full object-cover shadow-sm" />
                            <div>
                                <h4 class="text-base font-bold text-[#002058]">Martin Harn</h4>
                                <p class="text-sm text-[#685f78]">Docker Development</p>
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Controls -->
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

    <!-- 13. LATEST NEWS & EVENTS -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-10">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#191919]">
                        Latest news &amp; Events
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#ff4667] via-[#ffc107] to-[#0dcaf0] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#ff4667] hover:text-[#e03a58] transition-colors">
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
                                        <span class="border border-white/80 rounded-full px-2 py-0.5">{{ $post->author?->name ?? 'MonoLMS' }}</span>
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
    </section>

</x-layouts.app>