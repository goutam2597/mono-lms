@php
    $sampleCourse = \App\Models\Course::published()->latest()->value('slug');
    $sampleInstructor = \App\Models\Instructor::where('status', 'active')->first();
@endphp

<!-- Top Bar -->
<div class="bg-[#08131e] text-white text-xs py-2 px-4 sm:px-8 border-b border-[#1b2737] relative z-50">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-2">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 text-gray-300">
                <svg class="w-3.5 h-3.5 text-[#738a6e]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                <span>{{ __('app.footer.address') }}</span>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-gray-300">
                <svg class="w-3.5 h-3.5 text-[#738a6e]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.44-5.15-3.75-6.59-6.59l1.97-1.57c.28-.28.36-.67.25-1.02A11.36 11.36 0 018.57 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.57c0-.55-.45-1-.99-1.05z"/>
                </svg>
                <span>{{ __('app.footer.phone') }}</span>
            </div>
        </div>
        <div class="flex items-center gap-5">
            <!-- Language (storefront session) -->
            <x-language-switcher panel="store" class="text-gray-300 [&>button]:text-gray-300" />

            <!-- Social Icons -->
            <div class="flex items-center gap-3 text-gray-400">
                <a href="#" class="hover:text-white transition-colors" aria-label="Facebook">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12z"/></svg>
                </a>
                <a href="#" class="hover:text-white transition-colors" aria-label="Instagram">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
                <a href="#" class="hover:text-white transition-colors" aria-label="Twitter">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="#" class="hover:text-white transition-colors" aria-label="LinkedIn">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<header class="bg-white border-b border-[#f4f6f9] sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3.5 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center shrink-0">
            <img src="{{ \App\Models\Setting::logoUrl() }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" width="150" height="40" fetchpriority="high" decoding="sync" class="h-9 sm:h-10 w-[135px] sm:w-[150px] aspect-[938/250] object-contain shrink-0" />
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-7 text-[14px] font-medium text-[#191919]">
            <a href="{{ route('home') }}" class="py-2 hover:text-[#738a6e] transition-colors {{ $active === 'home' ? 'text-[#344c3d] font-semibold' : '' }}">
                {{ __('app.nav.home') }}
            </a>
            <a href="{{ route('courses.index') }}" class="py-2 hover:text-[#738a6e] transition-colors {{ Str::startsWith($active, 'course') ? 'text-[#344c3d] font-semibold' : '' }}">
                {{ __('app.nav.courses') }}
            </a>
            <a href="{{ route('instructors.index') }}" class="py-2 hover:text-[#738a6e] transition-colors {{ $active === 'instructors' ? 'text-[#344c3d] font-semibold' : '' }}">
                {{ __('app.nav.instructors') }}
            </a>
            <a href="{{ route('blog.index') }}" class="py-2 hover:text-[#738a6e] transition-colors {{ $active === 'blog' ? 'text-[#344c3d] font-semibold' : '' }}">
                {{ __('app.nav.blog') }}
            </a>
            @if (\App\Models\Setting::get('membership_enabled'))
                <a href="{{ route('membership.index') }}" class="py-2 hover:text-[#738a6e] transition-colors {{ $active === 'membership' ? 'text-[#344c3d] font-semibold' : '' }}">
                    {{ __('app.nav.membership') }}
                </a>
            @endif
        </nav>

        <!-- Right Action Buttons -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Global Search Icon Button -->
            <button type="button"
                    id="global-search-toggle"
                    aria-label="Search courses"
                    aria-expanded="false"
                    title="Search courses (Press /)"
                    class="w-9 h-9 rounded-full hover:bg-slate-100 text-slate-700 hover:text-black transition-colors flex items-center justify-center cursor-pointer shrink-0">
                <svg id="global-search-icon-open" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z"/>
                </svg>
                <svg id="global-search-icon-close" class="w-4 h-4 hidden text-slate-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Mobile Menu (below lg) -->
            <details class="lg:hidden relative">
                <summary class="w-9 h-9 rounded-xl border border-[#e7e7e7] flex items-center justify-center text-gray-700 hover:text-[#738a6e] hover:border-[#738a6e] transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </summary>
                <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-[60]">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.home') }}</a>
                    <a href="{{ route('courses.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.courses') }}</a>
                    <a href="{{ route('instructors.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.instructors') }}</a>
                    <a href="{{ route('blog.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.blog') }}</a>
                    @if (\App\Models\Setting::get('membership_enabled'))
                        <a href="{{ route('membership.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.membership') }}</a>
                    @endif
                    <div class="border-t border-gray-100 my-1"></div>
                    @if (auth('student')->check())
                        <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.dashboard') }}</a>
                        <a href="{{ route('student.wishlist') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('student.nav.saved_courses') }}</a>
                        <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.profile') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-start px-4 py-2 text-sm text-red-500 hover:bg-red-50">{{ __('app.nav.sign_out') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.sign_in') }}</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.register') }}</a>
                    @endif
                </div>
            </details>

            @if (auth('student')->check())
                @php $student = auth('student')->user(); @endphp
                <!-- Student Account Dropdown -->
                <div class="relative group">
                    <button class="flex items-center py-1 cursor-pointer focus:outline-none" aria-label="{{ $student->name }}" title="{{ $student->name }}">
                        @if ($student->avatarUrl())
                            <img src="{{ $student->avatarUrl() }}" alt="{{ $student->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-transparent group-hover:ring-[var(--theme-primary,#344c3d)]/40 transition-all shadow-xs" onerror="this.style.display='none'; if(this.nextElementSibling) { this.nextElementSibling.style.display='flex'; }">
                            <span class="w-9 h-9 rounded-full bg-[var(--theme-primary,#344c3d)] text-white font-bold text-sm items-center justify-center hidden shadow-xs">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                        @else
                            <span class="w-9 h-9 rounded-full bg-[var(--theme-primary,#344c3d)] text-white font-bold text-sm flex items-center justify-center shadow-xs">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                        @endif
                    </button>
                    <div class="absolute right-0 top-full pt-2 w-56 hidden group-hover:block z-50">
                        <div class="bg-white rounded-xl shadow-xl border border-gray-100 py-2">
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-sm font-semibold text-[#191919] truncate">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $student->email }}</p>
                            </div>
                            <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.dashboard') }}</a>
                            <a href="{{ route('student.wishlist') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('student.nav.saved_courses') }}</a>
                            <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#344c3d]">{{ __('app.nav.profile') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-start px-4 py-2 text-sm text-red-500 hover:bg-red-50 cursor-pointer">{{ __('app.nav.sign_out') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <!-- Sign In Button -->
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 bg-[#e7e7e7] text-[#191919] hover:bg-gray-300 px-4 py-2 rounded-xl font-medium text-[13px] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>{{ __('app.nav.sign_in') }}</span>
                </a>

                <!-- Register Button -->
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-[#344c3d] text-white hover:bg-[#25372c] px-4 py-2 rounded-xl font-medium text-[13px] shadow-sm transition-all hover:shadow">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>{{ __('app.nav.register') }}</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Global Full Search Bar Drawer (Expands below navbar on click) -->
    <div id="global-search-bar"
         class="hidden border-t border-slate-100 bg-white/98 backdrop-blur-md shadow-xl transition-all duration-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-4 sm:py-5">
            <form action="{{ route('courses.index') }}" method="GET" class="relative">
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-slate-400 pointer-events-none flex items-center">
                        <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z"/>
                        </svg>
                    </div>

                    <input type="text"
                           name="q"
                           id="global-search-input"
                           value="{{ request('q') }}"
                           placeholder="{{ __('app.common.search_placeholder') ?? 'What do you want to learn today?' }}"
                           autocomplete="off"
                           data-suggest="{{ route('api.search.suggestions') }}"
                           data-suggest-container="#global-search-bar form"
                           class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder:text-slate-400 text-sm sm:text-base font-medium rounded-xl border border-slate-200 focus:border-[var(--theme-primary,#344c3d)] focus:ring-3 focus:ring-[var(--theme-primary,#344c3d)]/10 pl-11 sm:pl-12 pr-28 sm:pr-32 py-3 sm:py-3.5 transition-all outline-none">

                    <div class="absolute right-2 sm:right-2.5 flex items-center gap-1.5">
                        <button type="submit"
                                class="bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white text-xs sm:text-[13px] font-semibold px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg transition-colors cursor-pointer shadow-xs">
                            {{ __('app.common.search') }}
                        </button>
                        <button type="button"
                                id="global-search-cancel"
                                aria-label="Close search"
                                title="Close (Esc)"
                                class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Popular search tags -->
                @php
                    $searchCategories = \App\Models\Category::take(5)->get();
                @endphp
                @if ($searchCategories->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mt-3 text-xs text-slate-500">
                        <span class="font-semibold text-slate-400 text-[11px] uppercase tracking-wider mr-1">Popular:</span>
                        @foreach ($searchCategories as $cat)
                            <a href="{{ route('courses.index', ['category' => $cat->id]) }}"
                               class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition-colors">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>
    </div>
</header>

<!-- Backdrop overlay when search is open -->
<div id="global-search-backdrop" class="hidden fixed inset-0 bg-slate-950/20 backdrop-blur-2xs z-30 transition-opacity"></div>

<script>
    window.searchSuggestI18n = {
        courses: @json(__('app.nav.courses')),
        categories: @json(__('home.categories.badge')),
        instructors: @json(__('app.nav.instructors')),
        courses_count: @json(__('app.common.courses')),
        no_results_for: @json(__('app.common.no_results_for')),
        see_all_results: @json(__('app.common.see_all_results'))
    };
</script>
