@props(['title' => 'Mono LMS - Online Learning Management System', 'active' => ''])

<!DOCTYPE html>
<html lang="{{ $currentLocale ?? str_replace('_', '-', app()->getLocale()) }}" dir="{{ $currentDirection ?? 'ltr' }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <!-- Preload Nav Logo for instant render on refresh -->
    <link rel="preload" as="image" href="{{ asset('images/logo.png') }}" fetchpriority="high">

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
</body>
</html>
