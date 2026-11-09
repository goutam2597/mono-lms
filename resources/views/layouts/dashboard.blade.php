@props(['title' => 'Dashboard - Mono LMS', 'active' => 'dashboard', 'userType' => 'student'])

@if ($userType === 'admin' || $userType === 'instructor')
    <!DOCTYPE html>
    <html lang="{{ $currentLocale ?? str_replace('_', '-', app()->getLocale()) }}" dir="{{ $currentDirection ?? 'ltr' }}" class="h-full bg-[var(--theme-bg,#f4f6f9)]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">
        <link rel="shortcut icon" href="{{ \App\Models\Setting::faviconUrl() }}">
        <link rel="apple-touch-icon" href="{{ \App\Models\Setting::faviconUrl() }}">

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

        <style>
            .custom-sidebar-scroll {
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.12) transparent;
            }
            .custom-sidebar-scroll::-webkit-scrollbar {
                width: 4px;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.12);
                border-radius: 4px;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.24);
            }

            /* ---------------- Action buttons (mono_market pattern) ---------------- */
            .action-btn { display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; height: 32px; padding: 0 14px; font-size: 10px; font-weight: 500; line-height: 1.2; text-transform: uppercase; letter-spacing: .12em; white-space: nowrap; color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; cursor: pointer; font-family: inherit; text-decoration: none; box-sizing: border-box; transition: color .15s, background .15s, border-color .15s; }
            .action-btn:hover { color: #fff; background: #404040; border-color: #404040; }
            .action-btn-primary { color: #fff; background: var(--theme-primary, #344c3d); border: 1px solid var(--theme-primary, #344c3d); }
            .action-btn-primary:hover { color: #fff; background: var(--theme-primary-hover, #25372c); border-color: var(--theme-primary-hover, #25372c); }
            .action-btn-ghost { color: #0a0a0a; background: #fff; border: 1px solid #e5e5e5; }
            .action-btn-ghost:hover { background: #f5f5f5; border-color: #d4d4d4; color: #0a0a0a; }
            .action-btn-danger { color: #fff; background: #0a0a0a; border: 1px solid #0a0a0a; }
            .action-btn-danger:hover { color: #fff; background: #dc2626; border-color: #dc2626; }
            .action-btn-success { color: #fff; background: #059669; border: 1px solid #059669; }
            .action-btn-success:hover { color: #fff; background: #047857; border-color: #047857; }
            .action-btn.sm, .action-btn.h-7 { height: 28px; padding: 0 10px; font-size: 9px; }

            /* ---------------- Standard Modal System (mono_market pattern) ---------------- */
            .modal-overlay {
                position: fixed;
                inset: 0;
                z-index: 120;
                display: none;
                align-items: center;
                justify-content: center;
                background: rgba(10, 10, 10, 0.65);
                backdrop-filter: blur(4px);
                padding: 24px;
                box-sizing: border-box;
            }
            .modal-overlay.open {
                display: flex !important;
            }
            .modal-overlay .panel {
                background: #ffffff;
                width: 520px;
                max-width: 100%;
                max-height: calc(100vh - 48px);
                overflow-y: auto;
                padding: 24px;
                border-radius: 0 !important;
                border: 1px solid #e5e5e5;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
                position: relative;
                box-sizing: border-box;
            }
            .modal-overlay .panel-sm, .modal-overlay .panel.panel-sm { width: 440px; }
            .modal-overlay .panel-md, .modal-overlay .panel.panel-md { width: 520px; }
            .modal-overlay .panel-lg, .modal-overlay .panel.panel-lg { width: 640px; }
            .modal-overlay .panel-xl, .modal-overlay .panel.panel-xl { width: 920px; }

            /* ---------------- Row actions dropdown (mono_market pattern) ---------------- */
            .row-action-menu { position: fixed; z-index: 130; display: none; padding: 4px; background: #fff; border: 1px solid #e5e5e5; box-shadow: 0 12px 32px rgba(10,10,10,.14); min-width: 130px; }
            .row-action-menu.open { display: block; }
            .row-action-menu form { margin: 0; padding: 0; display: block; }
            .row-action-menu .menu-item { display: block; width: 100%; padding: 6px 14px; font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: .12em; text-align: start; white-space: nowrap; color: #525252; background: transparent; border: 0; cursor: pointer; font-family: inherit; text-decoration: none; transition: background .12s, color .12s; }
            .row-action-menu .menu-item:hover { background: #f5f5f5; color: #0a0a0a; }
            .row-action-menu .menu-item.danger { color: #b91c1c; }
            .row-action-menu .menu-item.danger:hover { background: #fef2f2; color: #991b1b; }
            .row-action-menu .menu-sep { height: 1px; margin: 4px 0; background: #f0f0f0; }
        </style>
    </head>
    <body class="font-sans antialiased text-[#191919] min-h-screen bg-[var(--theme-bg,#f4f6f9)] flex">

        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/70 z-40 hidden lg:hidden transition-opacity backdrop-blur-xs"></div>

        <!-- Full-height Standalone Sidebar (Figma Dev Mode Dark #0b0d0e) -->
        <aside id="dashboard-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0b0d0e] text-[#94a3b8] flex flex-col justify-between border-r border-[#1e293b]/70 transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none select-none">
            <!-- Sidebar Header & Scrollable Nav -->
            <div class="flex-1 flex flex-col min-h-0">
                <!-- Top Brand Header -->
                <div class="h-[60px] px-5 flex items-center justify-between border-b border-white/[0.07] bg-[#07090a] shrink-0">
                    <a href="{{ $userType === 'admin' ? route('admin.dashboard') : route('instructor.dashboard') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ \App\Models\Setting::logoUrl(asset('images/logo-white.png')) }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" class="h-6 w-auto object-contain transition-transform duration-200 group-hover:scale-105" />
                        <span class="text-[9px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-[4px] {{ $userType === 'admin' ? 'bg-[var(--theme-primary,#344c3d)]/20 text-[var(--theme-accent,#738a6e)] border border-[var(--theme-primary,#344c3d)]/40' : 'bg-emerald-950 text-emerald-200 border border-emerald-700/40' }}">
                            {{ $userType === 'admin' ? 'ADMIN' : 'INSTRUCTOR' }}
                        </span>
                    </a>
                    <button type="button" id="sidebar-close" class="lg:hidden p-1.5 rounded-[5px] text-slate-400 hover:text-white hover:bg-white/[0.08] transition-colors" aria-label="Close sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Navigation Links List (Scrollable) -->
                <div class="flex-1 p-3.5 overflow-y-auto custom-sidebar-scroll">
                    @if ($userType === 'admin')
                        <x-admin-sidebar :active="$active" />
                    @else
                        <x-instructor-sidebar :active="$active" />
                    @endif
                </div>
            </div>

            <!-- Sidebar Bottom: User Profile Card -->
            <div class="p-3 border-t border-white/[0.07] bg-[#07090a] shrink-0">
                @if ($userType === 'admin')
                    @php
                        $admin = auth('admin')->user();
                        $adminAvatar = $admin?->avatarUrl();
                    @endphp
                    <div class="bg-white/[0.03] border border-white/[0.06] rounded-[6px] p-2 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative shrink-0">
                                @if ($adminAvatar)
                                    <img src="{{ $adminAvatar }}" alt="{{ $admin->name }}" class="w-8 h-8 rounded-[5px] object-cover ring-1 ring-slate-700" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                    <div class="w-8 h-8 rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs hidden">
                                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs flex items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs">
                                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                                <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-[#07090a]"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate leading-tight">{{ $admin->name ?? 'Administrator' }}</p>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $admin->isSuperAdmin() ? 'Super Admin' : ($admin->role?->name ?? 'Administrator') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0">
                            <a href="{{ route('home') }}" target="_blank" title="View Website" class="p-1 rounded-[4px] text-slate-400 hover:text-white hover:bg-white/[0.06] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                                @csrf
                                <button type="submit" title="{{ __('app.nav.sign_out') }}" class="p-1 rounded-[4px] text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @php
                        $instructor = auth('instructor')->user();
                        $instructorAvatar = $instructor?->avatarUrl();
                    @endphp
                    <div class="bg-white/[0.03] border border-white/[0.06] rounded-[6px] p-2 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative shrink-0">
                                @if ($instructorAvatar)
                                    <img src="{{ $instructorAvatar }}" alt="{{ $instructor->name }}" class="w-8 h-8 rounded-[5px] object-cover ring-1 ring-slate-700" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                    <div class="w-8 h-8 rounded-[5px] bg-[#344c3d] text-white font-bold text-xs items-center justify-center ring-1 ring-[#344c3d]/40 shadow-xs hidden">
                                        {{ strtoupper(substr($instructor->name ?? 'I', 0, 1)) }}
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-[5px] bg-[#344c3d] text-white font-bold text-xs flex items-center justify-center ring-1 ring-[#344c3d]/40 shadow-xs">
                                        {{ strtoupper(substr($instructor->name ?? 'I', 0, 1)) }}
                                    </div>
                                @endif
                                <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-[#07090a]"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate leading-tight">{{ $instructor->name ?? 'Instructor' }}</p>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $instructor->designation ?? __('app.common.instructor') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0">
                            <a href="{{ route('home') }}" target="_blank" title="View Website" class="p-1 rounded-[4px] text-slate-400 hover:text-white hover:bg-white/[0.06] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                            <form method="POST" action="{{ route('instructor.logout') }}" class="inline">
                                @csrf
                                <button type="submit" title="{{ __('instructor.nav.logout') }}" class="p-1 rounded-[4px] text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64 min-h-screen">
            <!-- Full-width Sticky Top Header (Figma Dev Mode Node 10073:144208) -->
            <header class="h-[60px] bg-white border-b border-[#e2e8f0] px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-[0px_1px_3px_rgba(0,0,0,0.02)]">
                <!-- Search Keyword with ⌘ Badge -->
                <div class="flex items-center gap-3">
                    <button type="button" id="sidebar-toggle" class="lg:hidden p-2 rounded-[5px] text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle navigation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="bg-white border border-[#e2e8f0] flex items-center gap-2 h-[34px] px-3 rounded-[5px] w-52 sm:w-68">
                        <input type="text" placeholder="Search Keyword..." class="flex-1 text-[13px] text-slate-700 placeholder-slate-400 bg-transparent border-0 focus:outline-none min-w-0">
                        <span class="bg-[var(--theme-primary,#344c3d)] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-[4px] shrink-0">⌘</span>
                    </div>
                </div>

                <!-- Right Toolbar Icons (Figma Dev Mode specs) -->
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <!-- Minimus / Fullscreen -->
                    <button type="button" onclick="document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen()" title="Toggle Fullscreen" class="h-[34px] w-[34px] border border-[#e2e8f0] hover:bg-slate-50 text-slate-600 rounded-[5px] transition-colors cursor-pointer hidden sm:flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                    </button>

                    <!-- Language Switcher Flag (Unified 34px height) -->
                    <div class="h-[34px] border border-[#e2e8f0] rounded-[5px] px-2.5 flex items-center bg-white hover:bg-slate-50 transition-colors shrink-0">
                        <x-language-switcher :panel="$userType" class="text-xs font-semibold text-slate-700 h-full flex items-center [&>button]:h-full [&>button]:py-0 [&>button]:gap-1.5" />
                    </div>

                    <!-- Notification Bell with Red Dot Indicator -->
                    <a href="{{ route('admin.messages.index') }}" title="Notifications" class="h-[34px] w-[34px] border border-[#e2e8f0] hover:bg-slate-50 text-slate-600 rounded-[5px] relative transition-colors flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </a>

                    <!-- Dark/Light Mode Toggle -->
                    <button type="button" title="Light Theme Active" class="h-[34px] w-[34px] border border-[#e2e8f0] hover:bg-slate-50 text-slate-600 rounded-[5px] transition-colors cursor-pointer hidden sm:flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    </button>

                    <!-- User Profile Avatar (Figma rectangle with 5px radius) -->
                    <div class="flex items-center pl-1 shrink-0">
                        @php
                            $u = ($userType === 'admin') ? auth('admin')->user() : auth('instructor')->user();
                            $navAvatar = $u?->avatarUrl();
                        @endphp
                        @if ($navAvatar)
                            <img src="{{ $navAvatar }}" alt="{{ $u->name }}" class="w-[34px] h-[34px] rounded-[5px] object-cover ring-1 ring-slate-200" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                            <div class="w-[34px] h-[34px] rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs hidden">
                                {{ strtoupper(substr($u->name ?? 'A', 0, 1)) }}
                            </div>
                        @else
                            <div class="w-[34px] h-[34px] rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs flex items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs">
                                {{ strtoupper(substr($u->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Full-width Main Page Content -->
            <main class="flex-1 p-5 sm:p-6 lg:p-7 min-w-0">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-[5px] text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">&times;</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-[5px] text-xs sm:text-sm font-medium flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-800 cursor-pointer">&times;</button>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer (Figma Dev Mode Node 10060:84225) -->
            <footer class="border-t border-[#e2e8f0] py-3.5 px-6 text-center text-xs text-[#94a3b8] bg-white">
                <p>&copy; {{ date('Y') }} Mono LMS. All Rights Reserved</p>
            </footer>
        </div>

        <!-- Mobile sidebar drawer toggle script -->
        <script>
            (function () {
                const toggle = document.getElementById('sidebar-toggle');
                const closeBtn = document.getElementById('sidebar-close');
                const sidebar = document.getElementById('dashboard-sidebar');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (!toggle || !sidebar) return;

                function open() {
                    sidebar.classList.remove('-translate-x-full');
                    if (backdrop) backdrop.classList.remove('hidden');
                }
                function close() {
                    sidebar.classList.add('-translate-x-full');
                    if (backdrop) backdrop.classList.add('hidden');
                }
                toggle.addEventListener('click', open);
                if (closeBtn) closeBtn.addEventListener('click', close);
                if (backdrop) backdrop.addEventListener('click', close);
            })();
        </script>

        <!-- Global row action dropdown positioning script (mono_market pattern) -->
        <script>
            (function () {
                var openMenu = null;
                function closeMenu() {
                    if (openMenu) { openMenu.classList.remove('open'); openMenu = null; }
                }
                document.addEventListener('click', function (e) {
                    var toggle = e.target.closest('[data-menu-toggle]');
                    if (toggle) {
                        e.stopPropagation();
                        var targetId = toggle.getAttribute('data-menu-toggle');
                        var menu = document.getElementById(targetId);
                        if (! menu) return;
                        if (openMenu === menu) { closeMenu(); return; }
                        closeMenu();
                        menu.style.minWidth = Math.max(toggle.offsetWidth, 130) + 'px';
                        menu.classList.add('open');
                        var r = toggle.getBoundingClientRect();
                        var left = r.right - menu.offsetWidth;
                        var top = r.bottom + 6;
                        if (left < 8) left = 8;
                        if (top + menu.offsetHeight > window.innerHeight - 8) {
                            top = r.top - menu.offsetHeight - 6;
                        }
                        menu.style.left = left + 'px';
                        menu.style.top = top + 'px';
                        openMenu = menu;
                        return;
                    }
                    if (openMenu && ! openMenu.contains(e.target)) {
                        closeMenu();
                    }
                });
                window.addEventListener('scroll', closeMenu, true);
                window.addEventListener('resize', closeMenu);
            })();
        </script>

        <!-- Global modal overlay backdrop click & Escape key support -->
        <script>
            (function () {
                document.addEventListener('click', function (e) {
                    if (e.target && e.target.classList && e.target.classList.contains('modal-overlay')) {
                        e.target.classList.remove('open');
                    }
                    var closeBtn = e.target.closest('[data-modal-close]');
                    if (closeBtn) {
                        var m = closeBtn.closest('.modal-overlay');
                        if (m) m.classList.remove('open');
                    }
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        var openModal = document.querySelector('.modal-overlay.open');
                        if (openModal) openModal.classList.remove('open');
                    }
                });
            })();
        </script>

        @if ($userType === 'admin')
            <x-media-picker />
        @endif
    </body>
    </html>
@else
    <!-- Student Learning Portal (keeps customer storefront nav & student profile view) -->
    <x-layouts.app :title="$title" active="student">
        <div class="bg-[#f4f6f9] py-8 min-h-[calc(100vh-200px)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                @php $student = auth('student')->user(); @endphp
                <x-student-header-banner
                    :name="$student->name"
                    :avatar="$student->avatar"
                    :enrolled="str_pad((string) $student->enrollments()->count(), 2, '0', STR_PAD_LEFT)"
                    :completed="str_pad((string) $student->enrollments()->where('status', 'completed')->count(), 2, '0', STR_PAD_LEFT)"
                />

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <div class="lg:col-span-3">
                        <x-student-sidebar :active="$active" />
                    </div>
                    <div class="lg:col-span-9">
                        @if (session('success'))
                            <div class="mb-6 bg-[#03c95a]/10 border border-[#03c95a]/30 text-[#0d7a38] px-4 py-3 rounded-xl text-sm font-medium">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </x-layouts.app>
@endif
