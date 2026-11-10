<x-layouts.app :title="($page->meta_title ?: $page->title).' - Mono LMS'" active="about">

    @if ($isDraftPreview ?? false)
        <div class="bg-amber-500 text-slate-950 font-bold text-xs py-2 px-4 text-center sticky top-0 z-50 shadow-md">
            ⚠️ Draft Preview Mode: This page is currently unpublished and only visible to administrators.
        </div>
    @endif

    <!-- Hero Header -->
    <section class="bg-[#f4f6f9] py-14 sm:py-20 border-b border-[#e7e7e7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 text-center">
            @if ($eyebrow = $page->setting('eyebrow'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] border border-[var(--theme-primary,#344c3d)]/20 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--theme-primary,#344c3d)]"></span>
                    {{ $eyebrow }}
                </span>
            @endif
            <h1 class="text-3xl sm:text-5xl font-extrabold text-[#191919] tracking-tight max-w-3xl mx-auto">
                {{ $page->title }}
            </h1>
            @if ($subtitle = $page->setting('subtitle'))
                <p class="text-base sm:text-lg text-slate-600 mt-4 max-w-2xl mx-auto leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
            <nav class="flex items-center justify-center gap-2 text-xs text-[#6d6d6d] mt-6">
                <a href="{{ route('home') }}" class="hover:text-[#344c3d]">{{ __('app.nav.home') }}</a>
                <span>/</span>
                <span class="text-[#738a6e] font-medium">{{ $page->title }}</span>
            </nav>
        </div>
    </section>

    <!-- Mission & Vision Cards -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mission -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-8 sm:p-10 shadow-xs relative overflow-hidden group hover:border-[var(--theme-primary,#344c3d)]/40 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-3">
                        {{ $page->setting('mission_title', 'Our Mission') }}
                    </h2>
                    <p class="text-slate-600 leading-relaxed text-sm sm:text-base">
                        {{ $page->setting('mission') }}
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-8 sm:p-10 shadow-xs relative overflow-hidden group hover:border-emerald-600/40 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-3">
                        {{ $page->setting('vision_title', 'Our Vision') }}
                    </h2>
                    <p class="text-slate-600 leading-relaxed text-sm sm:text-base">
                        {{ $page->setting('vision') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Platform Stats -->
    @if ($stats = $page->setting('stats'))
        <section class="py-12 bg-[#08131e] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-800">
                    @foreach ($stats as $stat)
                        <div class="pt-4 md:pt-0 px-4">
                            <p class="text-3xl sm:text-5xl font-extrabold text-[#bfcfbb]">{{ $stat['value'] ?? '' }}</p>
                            <p class="text-xs sm:text-sm text-slate-400 font-medium uppercase tracking-wider mt-2">{{ $stat['label'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Story / Ethos Section -->
    @if ($page->setting('story_title') || $page->content)
        <section class="py-16 sm:py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-8">
                @if ($storyTitle = $page->setting('story_title'))
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 text-center mb-6">
                        {{ $storyTitle }}
                    </h2>
                @endif

                @if ($p1 = $page->setting('story_p1'))
                    <p class="text-base sm:text-lg text-slate-700 leading-relaxed mb-4">
                        {{ $p1 }}
                    </p>
                @endif

                @if ($p2 = $page->setting('story_p2'))
                    <p class="text-base sm:text-lg text-slate-700 leading-relaxed mb-8">
                        {{ $p2 }}
                    </p>
                @endif

                @if ($page->content)
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed pt-4 border-t border-slate-100">
                        {!! $page->content !!}
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Features / Highlights -->
    @if ($features = $page->setting('features'))
        <section class="py-16 bg-[#f8fafc] border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs uppercase tracking-wider font-bold text-[var(--theme-primary,#344c3d)]">Why Mono LMS</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Built to Accelerate Your Career</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($features as $feat)
                        <div class="bg-white p-6 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-lg bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] flex items-center justify-center mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-2">{{ $feat['title'] ?? '' }}</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $feat['description'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Leadership / Team -->
    @if ($team = $page->setting('team'))
        <section class="py-16 sm:py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs uppercase tracking-wider font-bold text-[var(--theme-primary,#344c3d)]">Our Leadership</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Visionary Educators & Pioneers</h2>
                    <p class="text-sm text-slate-500 mt-2">The dedicated team behind the Mono LMS educational revolution.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($team as $member)
                        <div class="text-center group">
                            <div class="w-40 h-40 mx-auto rounded-2xl overflow-hidden mb-4 ring-2 ring-slate-100 group-hover:ring-[var(--theme-primary,#344c3d)]/30 transition-all shadow-sm">
                                <img src="{{ $member['image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($member['name'] ?? 'Team').'&background=344c3d&color=fff' }}"
                                     alt="{{ $member['name'] ?? '' }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <h3 class="font-bold text-slate-900 text-base">{{ $member['name'] ?? '' }}</h3>
                            <p class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] mt-0.5">{{ $member['role'] ?? '' }}</p>
                            @if (!empty($member['bio']))
                                <p class="text-xs text-slate-500 mt-2 max-w-xs mx-auto leading-relaxed">{{ $member['bio'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="py-16 bg-[#08131e] text-white border-t border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold">Ready to Start Learning?</h2>
            <p class="text-slate-300 mt-3 max-w-xl mx-auto text-sm sm:text-base leading-relaxed">
                Explore our catalog of certified courses taught by industry leaders and take the next step in your career.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('courses.index') }}" class="px-7 py-3 rounded-xl font-bold text-xs uppercase tracking-wider bg-[#344c3d] hover:bg-[#25372c] text-white shadow-lg transition-transform hover:scale-105">
                    Browse Catalog
                </a>
                <a href="{{ route('instructors.index') }}" class="px-7 py-3 rounded-xl font-bold text-xs uppercase tracking-wider bg-white/10 hover:bg-white/20 text-white transition-colors">
                    Meet Instructors
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
