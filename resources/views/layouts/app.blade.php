@props(['title' => 'Mono LMS - Online Learning Management System', 'active' => ''])

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

    <!-- Preload Nav Logo for instant render on refresh -->
    <link rel="preload" as="image" href="{{ \App\Models\Setting::logoUrl() }}" fetchpriority="high">

    <!-- Theme Script: Storefront is strictly light only; Student portal supports independent dark mode -->
    @php
        $isStudentPortal = request()->is('student*');
        $studentTheme = $isStudentPortal ? session('student_theme', 'light') : 'light';
    @endphp
    <script>
        (function () {
            var isStudent = @json($isStudentPortal);
            if (isStudent) {
                var stored = null;
                try { stored = localStorage.getItem('mono_theme_student'); } catch (e) {}
                var theme = stored || @json($studentTheme) || 'light';
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } else {
                // Public storefront is strictly light theme only
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style id="dynamic-theme-vars">
        :root {
            --theme-primary: {{ \App\Models\Setting::get('theme_primary', '#344c3d') }};
            --theme-primary-hover: {{ \App\Models\Setting::get('theme_primary_hover', '#25372c') }};
            --theme-accent: {{ \App\Models\Setting::get('theme_accent', '#738a6e') }};
            --theme-dark: {{ \App\Models\Setting::get('theme_dark', '#08131e') }};
            --theme-bg: {{ \App\Models\Setting::get('theme_bg', '#f4f6f9') }};
            --color-sage-evergreen: var(--theme-primary);
            --color-sage-moss: var(--theme-accent);
        }
    </style>
</head>
<body class="font-sans antialiased text-[#191919] min-h-screen flex flex-col bg-white">
    <x-header :active="$active" />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-footer />

    @if (session('success'))
        <div data-flash-toast data-type="success" data-message="{{ session('success') }}" data-title="Success" class="hidden"></div>
    @elseif (session('error'))
        <div data-flash-toast data-type="error" data-message="{{ session('error') }}" data-title="Error" class="hidden"></div>
    @elseif (session('status'))
        <div data-flash-toast data-type="info" data-message="{{ session('status') }}" data-title="Notice" class="hidden"></div>
    @endif
</body>
</html>
