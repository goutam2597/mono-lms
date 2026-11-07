@php
    $sampleCourse = \App\Models\Course::published()->latest()->value('slug');
    $sampleInstructor = \App\Models\Instructor::where('status', 'active')->first();
@endphp

<!-- Top Bar -->
<div class="bg-[#08131e] text-white text-xs py-2 px-4 sm:px-8 border-b border-[#1b2737]">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-2">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 text-gray-300">
                <svg class="w-3.5 h-3.5 text-[#ff4667]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                <span>{{ __('app.footer.address') }}</span>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-gray-300">
                <svg class="w-3.5 h-3.5 text-[#ff4667]" fill="currentColor" viewBox="0 0 24 24">
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
<header class="bg-white border-b border-[#f4f6f9] sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3.5 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-lg bg-[#392c7d] flex items-center justify-center text-white shadow-sm">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
            </div>
            <span class="text-2xl font-bold tracking-tight text-[#191919]">Mono<span class="text-[#ff4667]">LMS</span></span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-7 text-[14px] font-medium text-[#191919]">
            <a href="{{ route('home') }}" class="py-2 hover:text-[#ff4667] transition-colors {{ $active === 'home' ? 'text-[#ff4667]' : '' }}">
                {{ __('app.nav.home') }}
            </a>
            <a href="{{ route('courses.index') }}" class="py-2 hover:text-[#ff4667] transition-colors {{ Str::startsWith($active, 'course') ? 'text-[#ff4667]' : '' }}">
                {{ __('app.nav.courses') }}
            </a>
            <a href="{{ route('instructors.index') }}" class="py-2 hover:text-[#ff4667] transition-colors {{ $active === 'instructors' ? 'text-[#ff4667]' : '' }}">
                {{ __('app.nav.instructors') }}
            </a>
            <a href="{{ route('blog.index') }}" class="py-2 hover:text-[#ff4667] transition-colors {{ $active === 'blog' ? 'text-[#ff4667]' : '' }}">
                {{ __('app.nav.blog') }}
            </a>
            @if (\App\Models\Setting::get('membership_enabled'))
                <a href="{{ route('membership.index') }}" class="py-2 hover:text-[#ff4667] transition-colors {{ $active === 'membership' ? 'text-[#ff4667]' : '' }}">
                    {{ __('app.nav.membership') }}
                </a>
            @endif
        </nav>

        <!-- Right Action Buttons -->
        <div class="flex items-center gap-3">
            <!-- Mobile Menu (below lg) -->
            <details class="lg:hidden relative">
                <summary class="w-9 h-9 rounded-full border border-[#e7e7e7] flex items-center justify-center text-gray-700 hover:text-[#ff4667] hover:border-[#ff4667] transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </summary>
                <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-[60]">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.home') }}</a>
                    <a href="{{ route('courses.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.courses') }}</a>
                    <a href="{{ route('instructors.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.instructors') }}</a>
                    <a href="{{ route('blog.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.blog') }}</a>
                    @if (\App\Models\Setting::get('membership_enabled'))
                        <a href="{{ route('membership.index') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.membership') }}</a>
                    @endif
                    <div class="border-t border-gray-100 my-1"></div>
                    @guest('student')
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.sign_in') }}</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.register') }}</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.dashboard') }}</a>
                    @endguest
                </div>
            </details>

            @guest('student')
                <!-- Sign In Button -->
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 bg-[#e7e7e7] text-[#191919] hover:bg-gray-300 px-4 py-2 rounded-full font-medium text-[13px] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>{{ __('app.nav.sign_in') }}</span>
                </a>

                <!-- Register Button -->
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-[#ff4667] text-white hover:bg-[#e03a58] px-4 py-2 rounded-full font-medium text-[13px] shadow-sm transition-all hover:shadow">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>{{ __('app.nav.register') }}</span>
                </a>
            @else
                @php $student = auth('student')->user(); @endphp
                <!-- Student Account Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-2 py-1.5">
                        <span class="w-9 h-9 rounded-full bg-[#392c7d] text-white font-bold text-sm flex items-center justify-center">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                        <span class="hidden md:inline text-[13px] font-medium text-[#191919] max-w-[110px] truncate">{{ $student->name }}</span>
                        <svg class="w-4 h-4 text-gray-500 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute right-0 top-full pt-2 w-52 hidden group-hover:block z-50">
                        <div class="bg-white rounded-xl shadow-xl border border-gray-100 py-2">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-[#191919] truncate">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $student->email }}</p>
                            </div>
                            <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.dashboard') }}</a>
                            <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-sm hover:bg-[#f4f6f9] hover:text-[#ff4667]">{{ __('app.nav.profile') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-start px-4 py-2 text-sm text-red-500 hover:bg-red-50">{{ __('app.nav.sign_out') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>
