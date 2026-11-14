@props(['title' => 'Authentication - Mono LMS'])

<!DOCTYPE html>
<html lang="{{ $currentLocale ?? str_replace('_', '-', app()->getLocale()) }}" dir="{{ $currentDirection ?? 'ltr' }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">
    <link rel="shortcut icon" href="{{ \App\Models\Setting::faviconUrl() }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\Setting::faviconUrl() }}">

    <!-- Preload Logo for instant render on refresh -->
    <link rel="preload" as="image" href="{{ \App\Models\Setting::logoUrl() }}" fetchpriority="high">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-[#191919] min-h-screen" style="background: linear-gradient(144.39deg, #edf3ec 2.28%, #f8f7fc 74.3%);">
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[700px]">
            <!-- Left Illustration & Welcome Column -->
            <div class="lg:col-span-5 hidden lg:flex flex-col justify-between p-12 bg-gradient-to-br from-[#dce7da]/40 to-[#f4f2ff] relative overflow-hidden border-r border-gray-100">
                <!-- Decorative Circle -->
                <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-[#738a6e]/15 pointer-events-none"></div>
                <div class="absolute bottom-10 right-0 w-80 h-80 rounded-full bg-[#392c7d]/5 pointer-events-none"></div>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center z-10 shrink-0">
                    <img src="{{ \App\Models\Setting::logoUrl() }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" width="150" height="40" fetchpriority="high" decoding="sync" class="h-10 w-[150px] aspect-[938/250] object-contain shrink-0" />
                </a>

                <!-- Centered Vector Illustration Graphic -->
                <div class="my-auto py-8 z-10 flex flex-col items-center text-center">
                    <div class="relative w-64 h-64 mb-6 flex items-center justify-center">
                        <div class="absolute inset-0 bg-[#392c7d]/10 rounded-full animate-pulse"></div>
                        <div class="w-52 h-52 bg-white rounded-full shadow-lg flex items-center justify-center p-6 border-4 border-[#8ea58c]/30">
                            <!-- Learning Icon Graphic -->
                            <svg class="w-28 h-28 text-[#392c7d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="absolute top-2 right-4 bg-[#344c3d] text-white p-2 rounded-xl shadow-md rotate-12">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-[#191919] mb-2">
                        {{ __('app.common.welcome') }} Mono LMS
                    </h2>
                    <p class="text-sm text-[#6d6d6d] max-w-sm leading-relaxed">
                        {{ __('app.footer.about') }}
                    </p>

                    <!-- Progress Dots -->
                    <div class="flex items-center gap-2 mt-6">
                        <div class="w-8 h-2 rounded-full bg-[#344c3d]"></div>
                        <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                        <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                    </div>
                </div>

                <!-- Footer copyright note -->
                <div class="text-xs text-[#888888] z-10 text-center">
                    © {{ date('Y') }} Mono LMS. {{ __('app.footer.copyright') }}
                </div>
            </div>

            <!-- Right Form Column -->
            <div class="lg:col-span-7 p-8 sm:p-12 lg:p-16 flex flex-col justify-between">
                <!-- Top Navigation back -->
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('home') }}" class="lg:hidden flex items-center shrink-0">
                        <img src="{{ \App\Models\Setting::logoUrl() }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" width="120" height="32" fetchpriority="high" decoding="sync" class="h-8 w-[120px] aspect-[938/250] object-contain shrink-0" />
                    </a>
                    @php
                        $localePanel = request()->is('admin*') ? 'admin' : (request()->is('instructor*') ? 'instructor' : 'student');
                    @endphp
                    <div class="ml-auto flex items-center gap-4">
                        <x-language-switcher :panel="$localePanel" class="text-[14px] font-medium text-[#191919]" />
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-[#738a6e] hover:text-[#344c3d] hover:underline">
                            <span>{{ __('auth.back_to_home') }}</span>
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="w-full max-w-md mx-auto my-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    @include('components.cookie-consent')
</body>
</html>
