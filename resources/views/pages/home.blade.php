<x-layouts.app title="Mono LMS – Home" active="home">

    <!-- 1. HERO SECTION -->
    <section class="bg-gradient-to-b from-[#f8fafb] via-[#fbfcfe] to-white pt-8 pb-16 lg:pt-12 lg:pb-24 relative">
        <!-- Ambient decorative shapes (clipped in their own layer so the
             category dropdown can overflow the section freely) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 right-0 w-[550px] h-[550px] rounded-full bg-[#392c7d]/5 blur-3xl anim-pulse-soft"></div>
            <div class="absolute top-1/3 -left-32 w-[450px] h-[450px] rounded-full bg-[#738a6e]/8 blur-3xl anim-pulse-soft"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Content Column -->
                <div class="lg:col-span-6 space-y-7 text-left">
                    <!-- Top Pill Tagline -->
                    <div class="inline-block anim-fade-up">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-[#344C3D] text-[#DCE7DA] shadow-sm">
                            {{ __('home.hero.badge') }}
                        </span>
                    </div>

                    <!-- Main Display Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-[52px] font-bold text-[#191919] leading-[1.18] tracking-tight anim-fade-up anim-d1">
                        {{ __('home.hero.title_line1') }} <span class="text-[#738A6E]">&amp;</span> {{ __('home.hero.title_highlight') }} {{ __('home.hero.title_line2') }}
                    </h1>

                    <!-- Subtitle Description -->
                    <p class="text-[#6d6d6d] text-base sm:text-lg leading-relaxed max-w-xl anim-fade-up anim-d2">
                        {{ __('home.hero.subtitle') }}
                    </p>

                    <!-- Rounded Search Bar -->
                    <form action="{{ route('courses.index') }}" method="GET" class="relative z-40 anim-fade-up anim-d3 bg-white rounded-[10px] p-2.5 shadow-[0px_4px_18px_rgba(216,216,216,0.35)] border border-[#e9ecef] flex flex-col sm:flex-row items-stretch sm:items-center gap-3 max-w-xl">
                        <!-- Category Select Pill (custom MonoSelect) -->
                        <div class="bg-[#DCE7DA]/50 rounded-[6px] flex items-center relative z-10">
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
                            <svg class="w-5 h-5 text-[#8EA58C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('app.common.search_placeholder') }}" class="w-full text-sm text-[#191919] placeholder-[#888888] bg-transparent focus:outline-none" />
                        </div>

                        <!-- Arrow Submit Button -->
                        <button type="submit" class="bg-[#344C3D] hover:bg-[#25372c] text-white p-3 rounded-[10px] transition-colors flex items-center justify-center shrink-0 shadow-sm cursor-pointer" aria-label="{{ __('app.common.search') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>

                    <!-- Quick Category Chips (Single Row) -->
                    <div class="flex items-center gap-2 anim-fade-up anim-d4 overflow-x-auto no-scrollbar py-0.5">
                        <span class="text-xs text-gray-400 font-medium shrink-0">{{ __('home.hero.popular') }}</span>
                        @foreach ($categories->take(3) as $chipCategory)
                            <a href="{{ route('courses.index', ['category' => $chipCategory->slug]) }}" class="px-3 py-1.5 rounded-full bg-white border border-[#BFCFBB]/80 text-xs font-medium text-[#191919] hover:border-[#738A6E] hover:text-[#344C3D] hover:bg-[#DCE7DA]/30 transition-colors shrink-0 whitespace-nowrap">
                                {{ $chipCategory->name }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Trust Row -->
                    <div class="flex items-center gap-3 anim-fade-up anim-d5">
                        <div class="flex -space-x-2.5">
                            @foreach ($topInstructors->take(4) as $trustInstructor)
                                <img src="{{ $trustInstructor->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($trustInstructor->name).'&background=344C3D&color=DCE7DA' }}" alt="{{ $trustInstructor->name }}" class="w-9 h-9 rounded-full border-2 border-white object-cover shadow-sm" />
                            @endforeach
                            <span class="w-9 h-9 rounded-full bg-[#344C3D] text-[#DCE7DA] text-[10px] font-bold flex items-center justify-center border-2 border-white shadow-sm">+{{ $stats['instructors'] }}</span>
                        </div>
                        <div class="text-xs text-[#6d6d6d] leading-snug">
                            <span class="text-[#738A6E]">★★★★★</span> <strong class="text-[#191919]">{{ __('home.hero.rating_label') }}</strong><br>
                            Trusted by <strong class="text-[#191919]">{{ $stats['students'] }}+ {{ __('home.hero.students_word') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Right Visual Column (Orbital Showcase) -->
                <div class="lg:col-span-6 relative flex justify-center items-center py-6 lg:py-0">
                    <div data-hero-showcase class="relative w-full max-w-[500px] sm:max-w-[550px] lg:max-w-[580px] aspect-square flex items-center justify-center select-none">

                        <!-- Ambient Soft Glow Blobs in the background -->
                        <div class="absolute -top-6 -right-6 w-56 h-56 rounded-full bg-[#BFCFBB]/35 blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-8 -left-8 w-64 h-64 rounded-full bg-[#8EA58C]/25 blur-3xl pointer-events-none"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 rounded-full bg-[#344C3D]/15 blur-3xl pointer-events-none"></div>

                        <!-- 1. Outer Concentric Dashed Ring -->
                        <div class="absolute inset-[3%] rounded-full border border-dashed border-[#8EA58C]/40 pointer-events-none"></div>

                        <!-- 2. Glowing Conic Sage Green Orbital Track -->
                        <div class="absolute inset-[9%] rounded-full p-[2.5px] pointer-events-none"
                             style="background: conic-gradient(from 0deg, #344C3D 0deg, #738A6E 72deg, #8EA58C 144deg, #BFCFBB 216deg, #DCE7DA 288deg, #344C3D 360deg);
                                    mask: radial-gradient(farthest-side, transparent calc(100% - 2.5px), #fff calc(100% - 2px));
                                    -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 2.5px), #fff calc(100% - 2px));
                                    filter: drop-shadow(0 0 7px rgba(115,138,110,0.45));">
                        </div>

                        <!-- 3. SVG Beads flanking badges on the orbit ring (viewBox 500x500, R=205) -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none z-10" viewBox="0 0 500 500" fill="none">
                            <!-- Evergreen beads (Top) -->
                            <circle cx="215" cy="49" r="4" fill="#344C3D" stroke="#ffffff" stroke-width="1.5" />
                            <circle cx="285" cy="49" r="4" fill="#344C3D" stroke="#ffffff" stroke-width="1.5" />

                            <!-- Moss beads (Top-Right) -->
                            <circle cx="430" cy="155" r="4" fill="#738A6E" stroke="#ffffff" stroke-width="1.5" />
                            <circle cx="445" cy="225" r="4" fill="#738A6E" stroke="#ffffff" stroke-width="1.5" />

                            <!-- Sage beads (Bottom-Right) -->
                            <circle cx="395" cy="385" r="4" fill="#8EA58C" stroke="#ffffff" stroke-width="1.5" />
                            <circle cx="340" cy="435" r="4" fill="#8EA58C" stroke="#ffffff" stroke-width="1.5" />

                            <!-- Mint beads (Bottom-Left) -->
                            <circle cx="160" cy="435" r="4" fill="#BFCFBB" stroke="#ffffff" stroke-width="1.5" />
                            <circle cx="105" cy="385" r="4" fill="#BFCFBB" stroke="#ffffff" stroke-width="1.5" />

                            <!-- Evergreen beads (Top-Left) -->
                            <circle cx="55" cy="225" r="4" fill="#344C3D" stroke="#ffffff" stroke-width="1.5" />
                            <circle cx="70" cy="155" r="4" fill="#344C3D" stroke="#ffffff" stroke-width="1.5" />
                        </svg>

                        <!-- 4. Central Multi-Ring Photo Avatar Container -->
                        <div class="relative z-10 w-[58%] aspect-square flex items-center justify-center">
                            <!-- Outer Sage Green Gradient Rim -->
                            <div class="absolute -inset-1.5 rounded-full p-[3.5px] pointer-events-none shadow-[0_20px_50px_rgba(52,76,61,0.22)]"
                                 style="background: conic-gradient(from 180deg, #344C3D 0deg, #738A6E 90deg, #8EA58C 180deg, #BFCFBB 270deg, #344C3D 360deg);">
                                <div class="w-full h-full rounded-full bg-white/40 backdrop-blur-xs"></div>
                            </div>

                            <!-- White Spacer Ring -->
                            <div class="relative w-full h-full rounded-full p-2 bg-white/90 backdrop-blur-md shadow-2xl ring-1 ring-black/5">
                                <!-- Photo Slides (crossfade) -->
                                <div class="relative w-full h-full rounded-full overflow-hidden shadow-inner bg-slate-900">
                                    @foreach ([
                                        ['src' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.student')],
                                        ['src' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.courses')],
                                        ['src' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.teachers')],
                                        ['src' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=900&auto=format&fit=crop&q=85', 'alt' => __('home.hero.slides.graduation')],
                                    ] as $slide)
                                        <img data-hero-slide src="{{ $slide['src'] }}" alt="{{ $slide['alt'] }}" class="absolute inset-0 w-full h-full object-cover object-top opacity-0 transition-opacity duration-700 ease-in-out" />
                                    @endforeach

                                    <!-- Bottom Sage Luminous Arc Rim inside Photo -->
                                    <div class="absolute inset-0 rounded-full pointer-events-none ring-[3px] ring-inset ring-[#8EA58C]/70 shadow-[inset_0_-22px_32px_rgba(52,76,61,0.50)]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Five Circular Floating Badge Pods (Pentagon Alignment) -->

                        <!-- Pod 1: TOP (12 o'clock) — Categories (Evergreen) -->
                        <div class="absolute left-[50%] top-[9%] -translate-x-1/2 -translate-y-1/2 z-20 group">
                            <div class="anim-orbit-float-1 relative flex items-center justify-center">
                                <div class="absolute -inset-2.5 rounded-full bg-[#344C3D]/18 blur-lg pointer-events-none group-hover:bg-[#344C3D]/30 transition-colors"></div>
                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 lg:w-26 lg:h-26 rounded-full bg-white/95 backdrop-blur-md border border-white shadow-[0_10px_28px_rgba(0,0,0,0.06),0_2px_8px_rgba(52,76,61,0.18)] flex flex-col items-center justify-center p-1.5 transition-transform duration-300 group-hover:scale-105">
                                    <div class="w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-[#DCE7DA] border border-[#BFCFBB] text-[#344C3D] flex items-center justify-center mb-0.5 shrink-0 shadow-2xs">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <p class="text-[11px] sm:text-xs lg:text-[13px] font-bold text-[#344C3D] leading-tight">{{ $categories->count() }}</p>
                                    <p class="text-[7.5px] sm:text-[8.5px] lg:text-[9.5px] text-[#738A6E] font-medium leading-tight text-center px-1 max-w-[75px]">{{ __('home.hero.items.categories') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pod 2: TOP-RIGHT (2:24) — Total Enrollments (Moss) -->
                        <div class="absolute left-[89%] top-[37%] -translate-x-1/2 -translate-y-1/2 z-20 group">
                            <div class="anim-orbit-float-2 relative flex items-center justify-center">
                                <div class="absolute -inset-2.5 rounded-full bg-[#738A6E]/20 blur-lg pointer-events-none group-hover:bg-[#738A6E]/32 transition-colors"></div>
                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 lg:w-26 lg:h-26 rounded-full bg-white/95 backdrop-blur-md border border-white shadow-[0_10px_28px_rgba(0,0,0,0.06),0_2px_8px_rgba(115,138,110,0.18)] flex flex-col items-center justify-center p-1.5 transition-transform duration-300 group-hover:scale-105">
                                    <div class="w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-[#DCE7DA] border border-[#BFCFBB] text-[#738A6E] flex items-center justify-center mb-0.5 shrink-0 shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <p class="text-[11px] sm:text-xs lg:text-[13px] font-bold text-[#191919] leading-tight">{{ $stats['enrollments'] }}+</p>
                                    <p class="text-[7.5px] sm:text-[8.5px] lg:text-[9.5px] text-[#738A6E] font-medium leading-tight text-center px-1 max-w-[75px]">{{ __('home.hero.items.enrollments') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pod 3: BOTTOM-RIGHT (4:48) — Live Classes (Sage) -->
                        <div class="absolute left-[74%] top-[83%] -translate-x-1/2 -translate-y-1/2 z-20 group">
                            <div class="anim-orbit-float-1 relative flex items-center justify-center">
                                <div class="absolute -inset-2.5 rounded-full bg-[#8EA58C]/22 blur-lg pointer-events-none group-hover:bg-[#8EA58C]/35 transition-colors"></div>
                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 lg:w-26 lg:h-26 rounded-full bg-white/95 backdrop-blur-md border border-white shadow-[0_10px_28px_rgba(0,0,0,0.06),0_2px_8px_rgba(142,165,140,0.22)] flex flex-col items-center justify-center p-1.5 transition-transform duration-300 group-hover:scale-105">
                                    <div class="w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-[#DCE7DA] border border-[#8EA58C]/60 text-[#344C3D] flex items-center justify-center mb-0.5 shrink-0 shadow-2xs">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-[11px] sm:text-xs lg:text-[13px] font-bold text-[#191919] leading-tight">{{ __('home.hero.items.live_value') }}</p>
                                    <p class="text-[7.5px] sm:text-[8.5px] lg:text-[9.5px] text-[#738A6E] font-medium leading-tight text-center px-1 max-w-[75px]">{{ __('home.hero.items.live') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pod 4: BOTTOM-LEFT (7:12) — Online Courses (Mint) -->
                        <div class="absolute left-[26%] top-[83%] -translate-x-1/2 -translate-y-1/2 z-20 group">
                            <div class="anim-orbit-float-2 relative flex items-center justify-center">
                                <div class="absolute -inset-2.5 rounded-full bg-[#BFCFBB]/28 blur-lg pointer-events-none group-hover:bg-[#BFCFBB]/45 transition-colors"></div>
                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 lg:w-26 lg:h-26 rounded-full bg-white/95 backdrop-blur-md border border-white shadow-[0_10px_28px_rgba(0,0,0,0.06),0_2px_8px_rgba(115,138,110,0.16)] flex flex-col items-center justify-center p-1.5 transition-transform duration-300 group-hover:scale-105">
                                    <div class="w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-[#DCE7DA] border border-[#BFCFBB] text-[#738A6E] flex items-center justify-center mb-0.5 shrink-0 shadow-2xs">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <p class="text-[11px] sm:text-xs lg:text-[13px] font-bold text-[#191919] leading-tight">{{ $stats['courses'] }}+</p>
                                    <p class="text-[7.5px] sm:text-[8.5px] lg:text-[9.5px] text-[#738A6E] font-medium leading-tight text-center px-1 max-w-[75px]">{{ __('home.hero.items.courses') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pod 5: TOP-LEFT (9:36) — Expert Instructors (Evergreen) -->
                        <div class="absolute left-[11%] top-[37%] -translate-x-1/2 -translate-y-1/2 z-20 group">
                            <div class="anim-orbit-float-1 relative flex items-center justify-center">
                                <div class="absolute -inset-2.5 rounded-full bg-[#344C3D]/18 blur-lg pointer-events-none group-hover:bg-[#344C3D]/30 transition-colors"></div>
                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 lg:w-26 lg:h-26 rounded-full bg-white/95 backdrop-blur-md border border-white shadow-[0_10px_28px_rgba(0,0,0,0.06),0_2px_8px_rgba(52,76,61,0.18)] flex flex-col items-center justify-center p-1.5 transition-transform duration-300 group-hover:scale-105">
                                    <div class="w-5.5 h-5.5 sm:w-6 sm:h-6 rounded-full bg-[#DCE7DA] border border-[#BFCFBB] text-[#344C3D] flex items-center justify-center mb-0.5 shrink-0 shadow-2xs">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                        </svg>
                                    </div>
                                    <p class="text-[11px] sm:text-xs lg:text-[13px] font-bold text-[#191919] leading-tight">{{ $stats['instructors'] }}+</p>
                                    <p class="text-[7.5px] sm:text-[8.5px] lg:text-[9.5px] text-[#738A6E] font-medium leading-tight text-center px-1 max-w-[75px]">{{ __('home.hero.items.instructors') }}</p>
                                </div>
                            </div>
                        </div>

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
                        <circle cx="12" cy="8" r="6" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8l2 2 4-4M15.5 13.5L17 22l-5-3-5 3 1.5-8.5"/>
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
                        <!-- Sage Green Gradient Bar -->
                        <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-4 mb-5"></div>
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
                        <div class="w-14 h-1 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-3"></div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                        <span>View All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Category Slider (Single-line, Infinite Scroll, Arrows on Both Sides, Dynamic Pagination) -->
                <div class="relative px-2 sm:px-0" data-category-slider>
                    <!-- Left Arrow Button (Left Side) -->
                    <button type="button"
                            data-slider-prev
                            aria-label="Previous categories"
                            class="absolute -left-3 sm:-left-5 lg:-left-6 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.12)] border border-gray-100 flex items-center justify-center text-[#191919] hover:bg-[#344c3d] hover:text-white transition-all duration-200 cursor-pointer focus:outline-none group/btn">
                        <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover/btn:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </button>

                    <!-- Slider Container & Track (Single Row) -->
                    <div class="overflow-hidden py-3 px-1" data-slider-container>
                        <div class="flex gap-5 sm:gap-6 select-none will-change-transform" data-slider-track>
                            @foreach ($categories as $category)
                                <div class="shrink-0 w-[240px] sm:w-[calc(50%-12px)] md:w-[calc(33.333%-16px)] lg:w-[calc(20%-19.2px)]" data-slider-card>
                                    <a href="{{ route('courses.index', ['category' => $category->slug]) }}"
                                       class="h-full bg-white border border-gray-100 rounded-[22px] sm:rounded-[24px] p-4.5 sm:p-5 pb-3 sm:pb-3.5 shadow-[0_6px_20px_rgba(0,0,0,0.04)] hover:shadow-[0_14px_32px_rgba(0,0,0,0.08)] transition-all duration-300 group flex flex-col justify-between relative overflow-hidden">

                                        <!-- Top Section: Logo / Image Box & Title -->
                                        <div class="flex flex-col items-center text-center">
                                            <!-- Logo / Icon Square Container -->
                                            <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-[20px] flex items-center justify-center mb-3 sm:mb-3.5 transition-transform duration-300 group-hover:scale-105"
                                                 style="background-color: {{ $category->color ? $category->color.'10' : '#f0fdf4' }}; border: 1.5px solid {{ $category->color ? $category->color.'25' : '#dcfce7' }};">

                                                <!-- Category Image / SVG / Emoji -->
                                                @if ($category->imageUrl())
                                                    <img src="{{ $category->imageUrl() }}" alt="{{ $category->name }}" class="w-11 h-11 sm:w-12 sm:h-12 object-contain pointer-events-none">
                                                @elseif ($category->icon && str_starts_with($category->icon, 'M'))
                                                    <svg class="w-10 h-10 pointer-events-none" viewBox="0 0 24 24" fill="currentColor" style="color: {{ $category->color ?: '#137547' }};">
                                                        <path d="{{ $category->icon }}" />
                                                    </svg>
                                                @else
                                                    <span class="text-3xl sm:text-4xl pointer-events-none">{{ $category->icon ?? '📚' }}</span>
                                                @endif
                                            </div>

                                            <!-- Category Title -->
                                            <h4 class="font-bold text-[15px] sm:text-[16px] text-[#0f172a] group-hover:text-[#344c3d] transition-colors leading-snug line-clamp-2 min-h-[2.4rem] flex items-center justify-center">
                                                {{ $category->name }}
                                            </h4>
                                        </div>

                                        <!-- Middle Section: Divider Line with Center Accent Bar -->
                                        <div class="relative flex items-center justify-center my-2.5 sm:my-3">
                                            <div class="w-full border-t border-gray-100"></div>
                                            <div class="absolute w-10 sm:w-12 h-1 rounded-full" style="background-color: {{ $category->color ?: '#137547' }};"></div>
                                        </div>

                                        <!-- Bottom Row: Courses Count & Circular Arrow Button -->
                                        <div class="flex items-center justify-between text-xs sm:text-[13px]">
                                            <!-- Courses Count -->
                                            <div class="flex items-center gap-1.5 font-semibold text-[#475569] min-w-0">
                                                <svg class="w-4 h-4 text-[#475569] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                <span class="truncate">{{ __('home.categories.courses_count', ['count' => $category->courses_count]) }}</span>
                                            </div>

                                            <!-- Circular Action Button -->
                                            <div class="w-8 h-8 sm:w-8.5 sm:h-8.5 rounded-full flex items-center justify-center text-white transition-all shadow-sm group-hover:scale-110 group-hover:shadow-md shrink-0"
                                                 style="background-color: {{ $category->color ?: '#137547' }};">
                                                <svg class="w-4 h-4 text-white shrink-0 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Arrow Button (Right Side) -->
                    <button type="button"
                            data-slider-next
                            aria-label="Next categories"
                            class="absolute -right-3 sm:-right-5 lg:-right-6 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.12)] border border-gray-100 flex items-center justify-center text-[#191919] hover:bg-[#344c3d] hover:text-white transition-all duration-200 cursor-pointer focus:outline-none group/btn">
                        <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                </div>

                <!-- Dynamic Pagination Dots (Placed after/below the items) -->
                <div class="flex justify-center items-center gap-2 pt-2" data-slider-dots>
                    <!-- Populated dynamically by category-slider.js -->
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
                        <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-3"></div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                        <span>View All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap items-center gap-2.5 pt-2">
                    <button class="px-5 py-2 rounded-[5px] text-sm font-semibold border border-[#344c3d] text-[#344c3d] bg-[#dce7da]/40 shadow-xs">All</button>
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
                        :instructorAvatar="$course->instructor?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor?->name ?? 'I').'&background=344C3D&color=DCE7DA'"
                        :image="$course->image ?? 'https://picsum.photos/seed/course-'.$course->id.'/640/420'"
                        :rating="$course->averageRating() ?: '5.0'"
                        :reviews="$course->reviewCount()"
                        :price="$course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free')"
                        :oldPrice="$course->old_price ? __('app.common.currency').number_format($course->old_price, 2) : null"
                        :lessons="$course->lessons->count()"
                        :duration="$course->duration_minutes"
                        :level="$course->level"
                    />
                @endforeach
            </div>

            <!-- View All Courses Button -->
            <div class="flex justify-center mt-12">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-[#344c3d] hover:bg-[#25372c] text-white text-base font-semibold shadow-md transition-all">
                    {{ __('app.common.view_all') }}
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>
    </section>

    <!-- 5. TRANSFORM ACCESS TO EDUCATION BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 my-16">
        <div class="bg-[#192A20] rounded-[20px] p-8 sm:p-12 lg:p-16 relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8 text-white shadow-xl">
            <!-- Background Waves -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-[#738a6e]/15 pointer-events-none"></div>

            <div class="space-y-3 max-w-2xl relative z-10 text-center lg:text-left">
                <h3 class="text-2xl sm:text-3xl lg:text-[32px] font-bold leading-tight">
                    Transform Access To Education
                </h3>
                <p class="text-white/80 text-sm sm:text-base leading-relaxed">
                    Create an account to receive our newsletter, course recommendations and promotions.
                </p>
            </div>

            <div class="relative z-10 shrink-0">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl bg-[#344c3d] hover:bg-[#25372c] text-white text-base font-medium shadow-lg transition-all">
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
        <div class="absolute bottom-1/4 -left-40 w-96 h-96 rounded-full bg-[#738a6e]/15 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-20 relative z-10">

            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-white/10">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">
                        Accelerate Cloud Success
                    </h2>
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-3"></div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9H4.5a2.5 2.5 0 010-5H6M18 9h1.5a2.5 2.5 0 000-5H18M4 22h16M10 14.66V17c0 .55-.45 1-1 1H7v4h10v-4h-2c-.55 0-1-.45-1-1v-2.34M18 2H6v7a6 6 0 0012 0V2z" />
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
                        <div class="absolute -top-4 -right-4 w-32 h-32 rounded-full border-4 border-[#738a6e]/40 border-dashed pointer-events-none"></div>
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
                            <circle cx="12" cy="8" r="6" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8l2 2 4-4M15.5 13.5L17 22l-5-3-5 3 1.5-8.5"/>
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
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
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
                        :instructorAvatar="$course->instructor?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor?->name ?? 'I').'&background=344C3D&color=DCE7DA'"
                        :image="$course->image ?? 'https://picsum.photos/seed/course-'.$course->id.'/640/420'"
                        :rating="$course->averageRating() ?: '5.0'"
                        :reviews="$course->reviewCount()"
                        :price="$course->price > 0 ? __('app.common.currency').number_format($course->price, 2) : __('courses.price_free')"
                        :oldPrice="$course->old_price ? __('app.common.currency').number_format($course->old_price, 2) : null"
                        :lessons="$course->lessons->count()"
                        :duration="$course->duration_minutes"
                        :level="$course->level"
                    />
                @endforeach
            </div>
            <!-- Dots -->
            <div class="flex justify-center items-center gap-2 pt-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#e9ecef]"></span>
                <span class="w-3 h-3 rounded-full bg-[#344c3d]"></span>
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
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-3"></div>
                </div>
                <a href="{{ route('instructors.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
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
                            <span class="w-8 h-8 rounded-full bg-white/20 hover:bg-[#738a6e] flex items-center justify-center transition-colors cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold tracking-tight">Hendriques</h3>
                                <p class="text-sm text-white/80 mt-1">Python Developer</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white group-hover:bg-[#738a6e] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right 3-4 Instructor Photos (md:col-span-4) -->
                <div class="md:col-span-4 grid grid-cols-2 gap-4">
                    @foreach ($topInstructors->skip(4) as $instructor)
                        <a href="{{ route('instructors.show', \Illuminate\Support\Str::slug($instructor->name)) }}" class="h-56 rounded-[10px] overflow-hidden shadow-sm group bg-[#738a6e]/15 flex items-center justify-center">
                            @if($instructor->avatar)
                                <img src="{{ $instructor->avatar }}" alt="{{ $instructor->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <span class="text-4xl font-bold text-[#344c3d]">{{ strtoupper(substr($instructor->name, 0, 1)) }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- 10. BECOME AN INSTRUCTOR BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 my-16">
        <div class="bg-gradient-to-r from-[#344c3d] via-[#2d4235] to-[#738a6e] rounded-[20px] p-8 sm:p-12 lg:p-16 flex flex-col lg:flex-row items-center justify-between gap-8 text-white shadow-xl">
            <div class="space-y-3 max-w-2xl text-center lg:text-left">
                <h3 class="text-2xl sm:text-3xl lg:text-[32px] font-bold leading-tight">
                    Become An Instructor
                </h3>
                <p class="text-white/90 text-sm sm:text-base leading-relaxed">
                    Top instructors from around the world teach millions of students on MonoLMS.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('instructor.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl bg-white text-[#191919] hover:bg-[#dce7da] text-base font-semibold shadow-lg transition-all">
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
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
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
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl bg-white text-[#191919] hover:bg-gray-100 text-base font-semibold shadow-lg transition-all">
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
                        <button class="w-10 h-10 rounded-xl bg-white text-[#191919] hover:bg-[#344c3d] hover:text-white flex items-center justify-center transition-colors shadow-md" aria-label="Previous Review">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button class="w-10 h-10 rounded-xl bg-white text-[#191919] hover:bg-[#344c3d] hover:text-white flex items-center justify-center transition-colors shadow-md" aria-label="Next Review">
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
                    <div class="w-14 h-1.5 rounded-full bg-gradient-to-r from-[#344c3d] via-[#738a6e] to-[#bfcfbb] mt-3"></div>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
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
                                    <h4 class="text-sm font-bold text-white group-hover:text-[#738a6e] transition-colors line-clamp-2">
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