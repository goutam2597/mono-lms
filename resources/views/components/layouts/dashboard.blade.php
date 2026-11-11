@props(['title' => 'Dashboard - Mono LMS', 'active' => 'dashboard', 'userType' => 'student'])

<!DOCTYPE html>
<html lang="{{ $currentLocale ?? str_replace('_', '-', app()->getLocale()) }}" dir="{{ $currentDirection ?? 'ltr' }}" class="h-full bg-[var(--theme-bg,#f4f6f9)] dark:bg-[#000000]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="icon" href="{{ \App\Models\Setting::faviconUrl() }}">
        <link rel="shortcut icon" href="{{ \App\Models\Setting::faviconUrl() }}">
        <link rel="apple-touch-icon" href="{{ \App\Models\Setting::faviconUrl() }}">

        <!-- Pre-render theme initialization script (prevents white flash, per-panel independent) -->
        @php $panelTheme = session($userType . '_theme', 'light'); @endphp
        <script>
            (function () {
                var panel = @json($userType);
                var stored = null;
                try { stored = localStorage.getItem('mono_theme_' + panel); } catch (e) {}
                var theme = stored || @json($panelTheme) || 'light';
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                try {
                    if (localStorage.getItem('mono-sidebar-collapsed') === '1') {
                        document.documentElement.classList.add('panel-sidebar-collapsed');
                    }
                } catch (e) {}
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/ai-progress.css') }}?v={{ filemtime(public_path('css/ai-progress.css')) }}">

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

            /* ---------------- Collapsible & RTL sidebar system (mono_market pattern) ---------------- */
            :root {
                --dashboard-sidebar-width: 16rem;
                --dashboard-sidebar-collapsed: 5rem;
            }

            #dashboard-sidebar {
                width: var(--dashboard-sidebar-width);
                position: fixed;
                top: 0;
                bottom: 0;
                z-index: 50;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                background-color: #0b0d0e;
                color: #94a3b8;
                user-select: none;
                transition: width 0.3s ease, transform 0.3s ease, left 0.3s ease, right 0.3s ease;
            }

            /* LTR Desktop positioning */
            html:not([dir="rtl"]) #dashboard-sidebar,
            [dir="ltr"] #dashboard-sidebar {
                left: 0 !important;
                right: auto !important;
                border-right: 1px solid rgba(30, 41, 59, 0.7) !important;
                border-left: none !important;
            }

            /* RTL Desktop positioning: sidebar docked to the right edge */
            [dir="rtl"] #dashboard-sidebar {
                right: 0 !important;
                left: auto !important;
                border-left: 1px solid rgba(30, 41, 59, 0.7) !important;
                border-right: none !important;
            }

            /* Main content area padding */
            #dashboard-main {
                min-width: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                flex: 1 1 0%;
                transition: padding-left .3s ease, padding-right .3s ease, padding-inline-start .3s ease;
            }

            @media (min-width: 1024px) {
                /* Expanded Sidebar */
                html:not([dir="rtl"]) #dashboard-main,
                [dir="ltr"] #dashboard-main {
                    padding-left: var(--dashboard-sidebar-width) !important;
                    padding-right: 0 !important;
                }
                [dir="rtl"] #dashboard-main {
                    padding-right: var(--dashboard-sidebar-width) !important;
                    padding-left: 0 !important;
                }

                /* Collapsed Sidebar */
                .panel-sidebar-collapsed:not([dir="rtl"]) #dashboard-main,
                [dir="ltr"].panel-sidebar-collapsed #dashboard-main,
                [dir="ltr"] .panel-sidebar-collapsed #dashboard-main {
                    padding-left: var(--dashboard-sidebar-collapsed) !important;
                    padding-right: 0 !important;
                }
                [dir="rtl"].panel-sidebar-collapsed #dashboard-main,
                [dir="rtl"] .panel-sidebar-collapsed #dashboard-main {
                    padding-right: var(--dashboard-sidebar-collapsed) !important;
                    padding-left: 0 !important;
                }

                .panel-sidebar-collapsed #dashboard-sidebar {
                    width: var(--dashboard-sidebar-collapsed) !important;
                }
            }

            /* Mobile drawer transform & visibility */
            @media (max-width: 1023.98px) {
                #dashboard-main {
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }
                html:not([dir="rtl"]) #dashboard-sidebar,
                [dir="ltr"] #dashboard-sidebar {
                    transform: translateX(-100%);
                }
                [dir="rtl"] #dashboard-sidebar {
                    transform: translateX(100%);
                }
                #dashboard-sidebar.sidebar-mobile-open {
                    transform: translateX(0) !important;
                }
            }
            @media (min-width: 1024px) {
                #dashboard-sidebar {
                    transform: none !important;
                }
            }

            /* RTL collapse button icon flip */
            [dir="rtl"] #sidebar-collapse-btn svg {
                transform: rotate(180deg);
            }

            /* RTL Submenu tree connector line */
            [dir="rtl"] #dashboard-sidebar .submenu-container {
                padding-right: 1rem;
                padding-left: 0.25rem;
            }
            [dir="rtl"] #dashboard-sidebar .submenu-container > div {
                border-left: none !important;
                border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
                padding-right: 0.75rem !important;
                padding-left: 0 !important;
            }

            /* RTL Header Dropdowns: align to left edge so they don't overflow */
            [dir="rtl"] #header-notifications-dropdown,
            [dir="rtl"] #header-user-menu-dropdown,
            [dir="rtl"] [data-language-dropdown] {
                left: 0 !important;
                right: auto !important;
            }

            [dir="rtl"] .sidebar-profile-card .relative > span.bg-emerald-500 {
                right: auto !important;
                left: -2px !important;
            }
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-caption,
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-label,
            .panel-sidebar-collapsed #dashboard-sidebar .chevron-arrow,
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-badge-pill { display: none !important; }
            .panel-sidebar-collapsed #dashboard-sidebar .custom-sidebar-scroll a,
            .panel-sidebar-collapsed #dashboard-sidebar .custom-sidebar-scroll button[data-accordion-toggle] { justify-content: center; padding-left: .5rem; padding-right: .5rem; gap: 0; }
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-logo-full { display: none; }
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-logo-icon { display: flex; }
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-user-info,
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-card-actions { display: none; }
            .panel-sidebar-collapsed #dashboard-sidebar .sidebar-profile-card { justify-content: center; padding-left: .5rem; padding-right: .5rem; }
            .panel-sidebar-collapsed #dashboard-sidebar [data-accordion-item] { position: relative; overflow: visible; }
            .panel-sidebar-collapsed #dashboard-sidebar [data-accordion-item] .submenu-container { display: none !important; }
            @media (min-width: 1024px) {
                .panel-sidebar-collapsed #dashboard-sidebar { width: 5rem; translate: none; }
                .panel-sidebar-collapsed #dashboard-sidebar [data-accordion-item] .submenu-container.panel-flyout-open {
                    display: block !important;
                    position: fixed;
                    top: 0;
                    left: 0;
                    min-width: 210px;
                    background: #0b0d0e;
                    border: 1px solid rgba(255, 255, 255, 0.08);
                    border-radius: 6px;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.35);
                    padding: .5rem;
                    z-index: 60;
                }
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
            .action-btn.h-8 { height: 32px; }
            .action-btn.h-9 { height: 36px; }
            .action-btn.h-10 { height: 40px; }
            .action-btn.h-11 { height: 44px; }
            .action-btn.h-12 { height: 48px; }

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

            /* ---------------- Language tabs (mono_market pattern) ---------------- */
            .lang-tabs {
                display: flex;
                flex-wrap: wrap;
                align-items: flex-end;
                gap: 4px;
                border-bottom: 1px solid #e5e5e5;
            }
            .dark .lang-tabs {
                border-bottom-color: rgba(255, 255, 255, 0.1);
            }
            .lang-tab {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                margin-bottom: -1px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .12em;
                color: #737373;
                background: transparent;
                border: 1px solid transparent;
                border-bottom: 1px solid #e5e5e5;
                cursor: pointer;
                font-family: inherit;
                transition: color .15s, border-color .15s, background-color .15s;
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
                user-select: none;
                line-height: 1.4;
            }
            .dark .lang-tab {
                color: #94a3b8;
                border-bottom-color: rgba(255, 255, 255, 0.1);
            }
            .lang-tab:hover {
                color: #0a0a0a;
                background: #fafafa;
            }
            .dark .lang-tab:hover {
                color: #f8fafc;
                background: rgba(255, 255, 255, 0.05);
            }
            .lang-tab.is-active,
            .lang-tab[aria-selected="true"] {
                color: #0a0a0a;
                font-weight: 700;
                background: #ffffff;
                border-color: #d4d4d4;
                border-bottom-color: #ffffff !important;
                box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.03);
            }
            .dark .lang-tab.is-active,
            .dark .lang-tab[aria-selected="true"] {
                color: #ffffff;
                background: #171717;
                border-color: rgba(255, 255, 255, 0.2);
                border-bottom-color: #171717 !important;
            }
            .lang-tab .lang-badge {
                display: inline-flex;
                align-items: center;
                padding: 2px 6px;
                font-size: 9px;
                font-weight: 700;
                letter-spacing: .08em;
                text-transform: uppercase;
                line-height: 1;
                border-radius: 9999px;
                background: #f0fdf4;
                color: #166534;
                border: 1px solid #bbf7d0;
            }
            .dark .lang-tab .lang-badge {
                background: rgba(22, 101, 52, 0.25);
                color: #86efac;
                border-color: rgba(134, 239, 172, 0.3);
            }
            [dir="rtl"] .lang-tab .lang-badge {
                margin-left: 0;
                margin-right: 6px;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-[#191919] dark:text-[#ededed] min-h-screen bg-[var(--theme-bg,#f4f6f9)] dark:bg-[#000000] flex">

        @if ($userType === 'admin' || $userType === 'instructor')
            @include('pages.admin.partials.ai-image-modal')
        @endif

        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/70 z-40 hidden lg:hidden transition-opacity backdrop-blur-xs"></div>

        <!-- Full-height Standalone Sidebar (Figma Dev Mode Dark #0b0d0e) -->
        <aside id="dashboard-sidebar" class="fixed inset-y-0 z-50 bg-[#0b0d0e] text-[#94a3b8] flex flex-col justify-between transition-[width,transform] duration-300 shadow-2xl lg:shadow-none select-none">
            <!-- Sidebar Header & Scrollable Nav -->
            <div class="flex-1 flex flex-col min-h-0">
                <!-- Top Brand Header -->
                <div class="h-[60px] px-3.5 flex items-center justify-between gap-1 border-b border-white/[0.07] bg-[#07090a] shrink-0">
                    <a href="{{ $userType === 'admin' ? route('admin.dashboard') : ($userType === 'instructor' ? route('instructor.dashboard') : route('student.dashboard')) }}" class="flex items-center gap-2.5 group min-w-0 flex-1">
                        <img src="{{ \App\Models\Setting::logoUrl(asset('images/logo-white.png')) }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" class="sidebar-logo-full h-8 w-auto object-contain transition-transform duration-200 group-hover:scale-105" />
                        <span class="sidebar-logo-icon hidden flex-none items-center justify-center">
                            <img src="{{ asset('images/favicon.png') }}" alt="{{ \App\Models\Setting::get('site_name', 'Mono LMS') }}" class="h-8 w-8 object-contain" onerror="this.style.display='none'" />
                        </span>
                    </a>
                    <button type="button" id="sidebar-collapse-btn" class="hidden lg:flex p-1.5 rounded-[5px] text-slate-400 hover:text-white hover:bg-white/[0.08] transition-colors cursor-pointer flex-none" aria-label="{{ __('app.nav.collapse_sidebar') }}" title="{{ __('app.nav.collapse_sidebar') }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l4-5-4-5M4 17l4-5-4-5"/></svg>
                    </button>
                    <button type="button" id="sidebar-close" class="lg:hidden p-1.5 rounded-[5px] text-slate-400 hover:text-white hover:bg-white/[0.08] transition-colors" aria-label="{{ __('app.nav.close_sidebar') }}" title="{{ __('app.nav.close_sidebar') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Navigation Links List (Scrollable) -->
                <div class="flex-1 p-3.5 overflow-y-auto custom-sidebar-scroll">
                    @if ($userType === 'admin')
                        <x-admin-sidebar :active="$active" />
                    @elseif ($userType === 'instructor')
                        <x-instructor-sidebar :active="$active" />
                    @else
                        <x-student-sidebar :active="$active" />
                    @endif
                </div>
            </div>

            <!-- Sidebar Bottom: User Profile Card -->
            <div class="p-3 border-t border-white/[0.07] bg-[#07090a] shrink-0">
                @php
                    $sidebarUser = match($userType) {
                        'admin' => auth('admin')->user(),
                        'instructor' => auth('instructor')->user(),
                        'student' => auth('student')->user(),
                        default => null,
                    };
                    $sidebarAvatar = $sidebarUser?->avatarUrl();
                    $sidebarProfileRoute = match($userType) {
                        'admin' => route('admin.profile'),
                        'instructor' => route('instructor.profile'),
                        'student' => route('student.profile'),
                        default => route('home'),
                    };
                    $sidebarLogoutRoute = match($userType) {
                        'admin' => route('admin.logout'),
                        'instructor' => route('instructor.logout'),
                        'student' => route('logout'),
                        default => route('logout'),
                    };
                    $sidebarSubtitle = match($userType) {
                        'admin' => $sidebarUser?->isSuperAdmin() ? 'Super Admin' : ($sidebarUser?->role?->name ?? 'Administrator'),
                        'instructor' => $sidebarUser?->designation ?? __('app.common.instructor'),
                        'student' => __('app.common.student'),
                        default => __('app.common.user'),
                    };
                @endphp
                <div class="bg-white/[0.03] border border-white/[0.06] rounded-[6px] p-2 flex items-center justify-between gap-2 sidebar-profile-card">
                    <a href="{{ $sidebarProfileRoute }}" title="{{ __('app.nav.edit_profile') }}" class="flex items-center gap-2.5 min-w-0 group hover:opacity-90 transition-opacity">
                        <div class="relative shrink-0">
                            @if ($sidebarAvatar)
                                <img src="{{ $sidebarAvatar }}" alt="" class="w-8 h-8 rounded-[5px] object-cover ring-1 ring-slate-700" onerror="this.style.display='none'; if(this.nextElementSibling) { this.nextElementSibling.style.display='flex'; }">
                                <div class="w-8 h-8 rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs hidden">
                                    {{ strtoupper(substr($sidebarUser->name ?? 'U', 0, 1)) }}
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs flex items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs">
                                    {{ strtoupper(substr($sidebarUser->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-[#07090a]"></span>
                        </div>
                        <div class="sidebar-user-info min-w-0">
                            <p class="text-xs font-semibold text-white group-hover:text-[var(--theme-accent,#738a6e)] transition-colors truncate leading-tight">{{ $sidebarUser->name ?? 'User' }}</p>
                            <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $sidebarSubtitle }}</p>
                        </div>
                    </a>
                    <div class="sidebar-card-actions flex items-center gap-0.5 shrink-0">
                        <a href="{{ $sidebarProfileRoute }}" title="{{ __('app.nav.edit_profile') }}" class="p-1 rounded-[4px] text-slate-400 hover:text-white hover:bg-white/[0.06] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </a>
                        <a href="{{ route('home') }}" target="_blank" title="{{ __('app.nav.view_website') }}" class="p-1 rounded-[4px] text-slate-400 hover:text-white hover:bg-white/[0.06] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        </a>
                        <form method="POST" action="{{ $sidebarLogoutRoute }}" class="inline">
                            @csrf
                            <button type="submit" title="{{ __('app.nav.sign_out') }}" class="p-1 rounded-[4px] text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div id="dashboard-main" class="flex-1 flex flex-col min-w-0 min-h-screen">
            <!-- Full-width Sticky Top Header (Figma Dev Mode Node 10073:144208) -->
            <header class="h-[60px] bg-white dark:bg-[#0a0a0a] border-b border-[#e2e8f0] dark:border-[#1f1f1f] px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-[0px_1px_3px_rgba(0,0,0,0.02)]">
                <!-- Search Keyword with ⌘ Badge -->
                <div class="flex items-center gap-3">
                    <button type="button" id="sidebar-toggle" class="lg:hidden p-2 rounded-[5px] text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800 focus:outline-none" aria-label="Toggle navigation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#222222] flex items-center gap-2 h-[34px] px-3 rounded-[5px] w-52 sm:w-68">
                        <input type="text" placeholder="{{ __('app.nav.search_placeholder') }}" class="flex-1 text-[13px] text-slate-700 dark:text-neutral-200 placeholder-slate-400 dark:placeholder-neutral-500 bg-transparent border-0 focus:outline-none min-w-0">
                        <span class="bg-[var(--theme-primary,#344c3d)] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-[4px] shrink-0">⌘</span>
                    </div>
                </div>

                <!-- Right Toolbar Icons (Figma Dev Mode specs) -->
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <!-- Minimus / Fullscreen -->
                    <button type="button" onclick="document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen()" title="{{ __('app.nav.toggle_fullscreen') }}" class="h-[34px] w-[34px] border border-[#e2e8f0] dark:border-[#222222] hover:bg-slate-50 dark:hover:bg-[#151515] text-slate-600 dark:text-neutral-300 rounded-[5px] transition-colors cursor-pointer hidden sm:flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                    </button>

                    <!-- Language Switcher Flag (Unified 34px height) -->
                    <div class="h-[34px] border border-[#e2e8f0] dark:border-[#222222] rounded-[5px] px-2.5 flex items-center bg-white dark:bg-[#0d0d0d] hover:bg-slate-50 dark:hover:bg-[#151515] transition-colors shrink-0">
                        <x-language-switcher :panel="$userType" class="text-xs font-semibold text-slate-700 dark:text-neutral-200 h-full flex items-center [&>button]:h-full [&>button]:py-0 [&>button]:gap-1.5" />
                    </div>

                    <!-- Notification Bell with Dropdown -->
                    <div class="relative shrink-0" id="header-notifications-container">
                        @php
                            $notifUser = match($userType) {
                                'admin' => auth('admin')->user(),
                                'instructor' => auth('instructor')->user(),
                                'student' => auth('student')->user(),
                                default => null,
                            };
                            if ($notifUser) {
                                \App\Services\NotificationService::syncInitialNotifications($notifUser, $userType);
                            }
                            $unreadNotificationsCount = $notifUser?->unreadNotifications()->count() ?? 0;
                            $recentNotifications = $notifUser?->notifications()->latest()->take(5)->get() ?? collect();
                            $readAllRoute = match($userType) {
                                'admin' => route('admin.notifications.read-all'),
                                'instructor' => route('instructor.notifications.read-all'),
                                'student' => route('student.notifications.read-all'),
                                default => '#',
                            };
                            $allNotificationsRoute = match($userType) {
                                'admin' => route('admin.notifications.index'),
                                'instructor' => route('instructor.notifications.index'),
                                'student' => route('student.notifications.index'),
                                default => '#',
                            };
                        @endphp
                        <button type="button"
                                id="header-notifications-btn"
                                aria-expanded="false"
                                aria-haspopup="true"
                                title="{{ __('app.nav.notifications') }}"
                                class="h-[34px] w-[34px] border border-[#e2e8f0] dark:border-[#222222] hover:bg-slate-50 dark:hover:bg-[#151515] text-slate-600 dark:text-neutral-300 rounded-[5px] relative transition-colors flex items-center justify-center shrink-0 cursor-pointer focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            @if ($unreadNotificationsCount > 0)
                                <span class="absolute -top-1 -right-1 px-1 min-w-[17px] h-[17px] rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white dark:ring-[#0d0d0d]">
                                    {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown Menu -->
                        <div id="header-notifications-dropdown"
                             style="width: 440px; min-width: 440px;"
                             class="hidden absolute right-0 top-full mt-2 bg-white dark:bg-[#111111] border border-slate-200 dark:border-neutral-800 rounded-xl shadow-xl z-[70] text-slate-700 dark:text-neutral-200 transform origin-top-right transition-all overflow-visible">
                            <!-- Header -->
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-900 dark:text-white text-[13px]">{{ __('app.nav.notifications') }}</span>
                                    @if ($unreadNotificationsCount > 0)
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                                            {{ $unreadNotificationsCount }} {{ __('app.nav.new') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2.5">
                                    @if ($unreadNotificationsCount > 0)
                                        <form method="POST" action="{{ $readAllRoute }}">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-medium text-slate-500 hover:text-slate-800 dark:text-neutral-400 dark:hover:text-white cursor-pointer hover:underline">
                                                {{ __('app.nav.mark_all_read') }}
                                            </button>
                                        </form>
                                    @endif
                                    <span class="text-[11px] text-slate-400 dark:text-neutral-500 font-medium">
                                        {{ ucfirst($userType) }}
                                    </span>
                                </div>
                            </div>

                            <div class="divide-y divide-slate-100 dark:divide-neutral-800/80">
                                @forelse ($recentNotifications as $notif)
                                    @php
                                        $data = $notif->data;
                                        $isUnread = is_null($notif->read_at);
                                        $cat = $data['category'] ?? 'system';
                                        $markReadUrl = match($userType) {
                                            'admin' => route('admin.notifications.read', ['id' => $notif->id, 'redirect' => 1]),
                                            'instructor' => route('instructor.notifications.read', ['id' => $notif->id, 'redirect' => 1]),
                                            'student' => route('student.notifications.read', ['id' => $notif->id, 'redirect' => 1]),
                                            default => '#',
                                        };
                                    @endphp
                                    <form method="POST" action="{{ $markReadUrl }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 flex items-start gap-3.5 hover:bg-slate-50 dark:hover:bg-neutral-800/50 transition-colors cursor-pointer group">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5
                                                {{ $cat === 'verification' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400' :
                                                   ($cat === 'support' ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400' :
                                                   ($cat === 'smtp' ? 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400' :
                                                   ($cat === 'enrollment' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' :
                                                   ($cat === 'review' ? 'bg-amber-50 text-amber-500 dark:bg-amber-950/40 dark:text-amber-400' :
                                                   'bg-slate-100 text-slate-600 dark:bg-neutral-800 dark:text-neutral-300')))) }}">
                                                @if ($cat === 'verification')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                @elseif ($cat === 'support')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                                                @elseif ($cat === 'smtp')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                                @elseif ($cat === 'enrollment')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                                @elseif ($cat === 'review')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center justify-between gap-2">
                                                    <p class="font-medium text-[13px] leading-snug {{ $isUnread ? 'text-slate-900 dark:text-white font-semibold' : 'text-slate-700 dark:text-neutral-300' }}">
                                                        {{ $data['title'] ?? 'Notification' }}
                                                    </p>
                                                    @if ($isUnread)
                                                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                                    @endif
                                                </div>
                                                <p class="text-[12px] text-slate-500 dark:text-neutral-400 mt-0.5 leading-normal line-clamp-2">
                                                    {{ $data['message'] ?? '' }}
                                                </p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-[10px] uppercase tracking-wider font-semibold text-slate-400 dark:text-neutral-500">
                                                        {{ ucfirst($cat) }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 dark:text-neutral-500">·</span>
                                                    <span class="text-[10px] text-slate-400 dark:text-neutral-500">
                                                        {{ $notif->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                @empty
                                    <div class="py-8 px-4 text-center">
                                        <svg class="w-8 h-8 mx-auto text-slate-300 dark:text-neutral-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                        <p class="text-[13px] font-medium text-slate-700 dark:text-neutral-300">{{ __('app.nav.all_caught_up') }}</p>
                                        <p class="text-[11px] text-slate-400 dark:text-neutral-500 mt-0.5">{{ __('app.nav.no_notifications') }}</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-neutral-900/60 border-t border-slate-100 dark:border-neutral-800 text-center">
                                <a href="{{ $allNotificationsRoute }}" class="text-[12px] font-medium text-[var(--theme-primary,#344c3d)] dark:text-emerald-400 hover:underline">
                                    {{ __('app.nav.view_all_notifications') }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Dark/Light Mode Toggle (independent per panel) -->
                    <button type="button" id="theme-toggle-btn" title="Toggle Theme" class="h-[34px] w-[34px] border border-[#e2e8f0] dark:border-[#222222] hover:bg-slate-50 dark:hover:bg-[#151515] text-slate-600 dark:text-neutral-300 rounded-[5px] transition-colors cursor-pointer hidden sm:flex items-center justify-center shrink-0">
                        <svg id="theme-icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                        <svg id="theme-icon-moon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    </button>

                    <!-- User Profile Dropdown Menu -->
                    <div class="relative shrink-0" id="header-user-menu-container">
                        @php
                            $u = match($userType) {
                                'admin' => auth('admin')->user(),
                                'instructor' => auth('instructor')->user(),
                                'student' => auth('student')->user(),
                                default => null,
                            };
                            $navAvatar = $u?->avatarUrl();
                            $profileRoute = match($userType) {
                                'admin' => route('admin.profile'),
                                'instructor' => route('instructor.profile'),
                                'student' => route('student.profile'),
                                default => route('home'),
                            };
                            $roleLabel = match($userType) {
                                'admin' => ($u?->isSuperAdmin() ? 'Super Admin' : ($u?->role?->name ?? 'Administrator')),
                                'instructor' => ($u?->designation ?? __('app.common.instructor')),
                                'student' => __('app.common.student') ?? 'Student',
                                default => 'User',
                            };
                            $topbarLogoutRoute = match($userType) {
                                'admin' => route('admin.logout'),
                                'instructor' => route('instructor.logout'),
                                'student' => route('logout'),
                                default => route('logout'),
                            };
                        @endphp
                        <button type="button"
                                id="header-user-menu-btn"
                                aria-expanded="false"
                                aria-haspopup="true"
                                title="{{ $u?->name ?? 'Account' }}"
                                class="flex items-center p-0.5 rounded-[6px] hover:bg-slate-100 dark:hover:bg-neutral-800 transition-colors cursor-pointer group focus:outline-none">
                            <div class="relative shrink-0">
                                @if ($navAvatar)
                                    <img src="{{ $navAvatar }}" alt="" class="w-[34px] h-[34px] rounded-[5px] object-cover ring-1 ring-slate-200 dark:ring-neutral-800" onerror="this.style.display='none'; if(this.nextElementSibling) { this.nextElementSibling.style.display='flex'; }">
                                    <div class="w-[34px] h-[34px] rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs hidden">
                                        {{ strtoupper(substr($u?->name ?? 'U', 0, 1)) }}
                                    </div>
                                @else
                                    <div class="w-[34px] h-[34px] rounded-[5px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs flex items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs">
                                        {{ strtoupper(substr($u?->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-[#0d0d0d]"></span>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="header-user-menu-dropdown"
                             class="hidden absolute right-0 top-full mt-2 w-64 bg-white dark:bg-[#0d0d0d] border border-slate-200 dark:border-[#222222] rounded-[8px] shadow-2xl py-1 z-[70] text-xs text-slate-700 dark:text-neutral-300 transform origin-top-right transition-all">
                            <!-- User Identity Header -->
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-[#1f1f1f]">
                                <div class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        @if ($navAvatar)
                                            <img src="{{ $navAvatar }}" alt="" class="w-10 h-10 rounded-[6px] object-cover ring-1 ring-slate-200 dark:ring-neutral-800" onerror="this.style.display='none'; if(this.nextElementSibling) { this.nextElementSibling.style.display='flex'; }">
                                            <div class="w-10 h-10 rounded-[6px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-sm items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs hidden">
                                                {{ strtoupper(substr($u?->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-[6px] bg-[var(--theme-primary,#344c3d)] text-white font-bold text-sm flex items-center justify-center ring-1 ring-[var(--theme-primary,#344c3d)]/40 shadow-xs">
                                                {{ strtoupper(substr($u?->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-slate-900 dark:text-white truncate text-[13px] leading-tight">{{ $u?->name }}</p>
                                        <p class="text-slate-500 dark:text-neutral-400 truncate text-[11px] mt-0.5">{{ $u?->email }}</p>
                                        <span class="inline-flex items-center mt-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] dark:text-[#86efac]">
                                            {{ $roleLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions List -->
                            <div class="py-1">
                                <a href="{{ $profileRoute }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                    <svg class="w-4 h-4 text-slate-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    <span class="font-medium">{{ __('app.nav.edit_profile') }}</span>
                                </a>

                                @if ($userType === 'admin')
                                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="font-medium">{{ __('app.nav.general_settings') }}</span>
                                    </a>
                                    @if (Route::has('admin.staff.index'))
                                        <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                            <svg class="w-4 h-4 text-slate-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zzm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                            <span class="font-medium">{{ __('app.nav.admins_staff') }}</span>
                                        </a>
                                    @endif
                                @elseif ($userType === 'student')
                                    <a href="{{ route('student.enrolled-courses') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                        <span class="font-medium">{{ __('app.nav.enrolled_courses') }}</span>
                                    </a>
                                    <a href="{{ route('student.wishlist') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                                        <span class="font-medium">{{ __('app.nav.saved_courses') }}</span>
                                    </a>
                                    <a href="{{ route('student.tickets.index') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                                        <span class="font-medium">{{ __('app.nav.help_support') }}</span>
                                    </a>
                                @endif

                                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                    <svg class="w-4 h-4 text-slate-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                    <span class="font-medium">{{ __('app.nav.view_website') }}</span>
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="border-t border-slate-100 dark:border-[#1f1f1f] pt-1">
                                <form method="POST" action="{{ $topbarLogoutRoute }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors text-start cursor-pointer font-medium">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                        <span>{{ __('app.nav.sign_out') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Full-width Main Page Content -->
            <main class="flex-1 p-5 sm:p-6 lg:p-7 min-w-0">
                @if ($userType === 'instructor' && auth('instructor')->check() && ! auth('instructor')->user()->isVerified() && ! request()->routeIs('instructor.verification.*'))
                    <div class="mb-5 p-4 rounded-xl border text-xs sm:text-sm font-medium flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs {{ auth('instructor')->user()->isVerificationRejected() ? 'bg-red-50 border-red-200 text-red-800' : (auth('instructor')->user()->isPendingVerification() ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-amber-50 border-amber-300 text-amber-900') }}">
                        <div class="flex items-center gap-2.5">
                            @if (auth('instructor')->user()->isVerificationRejected())
                                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span><strong>Verification Rejected:</strong> {{ auth('instructor')->user()->rejection_reason ?: 'Your credentials could not be verified.' }}</span>
                            @elseif (auth('instructor')->user()->isPendingVerification())
                                <svg class="w-5 h-5 text-amber-600 shrink-0 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span><strong>Verification in Progress:</strong> Your certificate and national ID are being reviewed by administrators. Course creation unlocks upon approval.</span>
                            @else
                                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span><strong>Action Required:</strong> You must verify your account with an instructor certificate and national ID before you can add courses.</span>
                            @endif
                        </div>
                        <a href="{{ route('instructor.verification.show') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-semibold text-xs whitespace-nowrap {{ auth('instructor')->user()->isVerificationRejected() ? 'bg-red-600 text-white hover:bg-red-700' : (auth('instructor')->user()->isPendingVerification() ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-amber-600 text-white hover:bg-amber-700') }}">
                            <span>{{ auth('instructor')->user()->isPendingVerification() ? 'View Status' : (auth('instructor')->user()->isVerificationRejected() ? 'Resubmit Documents' : 'Verify Account') }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                @endif

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
            <footer class="border-t border-[#e2e8f0] dark:border-[#1f1f1f] py-3.5 px-6 text-center text-xs text-[#94a3b8] dark:text-neutral-500 bg-white dark:bg-[#0a0a0a]">
                <p>&copy; {{ date('Y') }} Mono LMS. {{ __('app.nav.all_rights_reserved') }}</p>
            </footer>
        </div>

        <!-- Sidebar collapse (desktop) & mobile drawer control script -->
        <script>
            (function () {
                var html = document.documentElement;
                var key = 'mono-sidebar-collapsed';
                var collapseBtn = document.getElementById('sidebar-collapse-btn');
                if (collapseBtn) {
                    collapseBtn.addEventListener('click', function () {
                        var collapsed = html.classList.toggle('panel-sidebar-collapsed');
                        try { localStorage.setItem(key, collapsed ? '1' : '0'); } catch (e) {}
                    });
                }
            })();

            /* Collapsed flyout: groups reveal their submenus as fixed-position
               panels anchored next to the hovered icon (mono_market pattern) */
            (function () {
                var html = document.documentElement;
                var sidebar = document.getElementById('dashboard-sidebar');
                if (!sidebar) return;
                var items = sidebar.querySelectorAll('[data-accordion-item]');

                function hideAll() {
                    items.forEach(function (item) {
                        var sub = item.querySelector('.submenu-container');
                        if (sub) sub.classList.remove('panel-flyout-open');
                    });
                }

                items.forEach(function (item) {
                    var toggle = item.querySelector('[data-accordion-toggle]');
                    var sub = item.querySelector('.submenu-container');
                    if (!toggle || !sub) return;
                    toggle.addEventListener('mouseenter', function () {
                        if (!html.classList.contains('panel-sidebar-collapsed')) return;
                        if (!window.matchMedia('(min-width: 1024px)').matches) return;
                        hideAll();
                        var r = toggle.getBoundingClientRect();
                        sub.classList.add('panel-flyout-open');
                        var top = Math.min(r.top, window.innerHeight - sub.offsetHeight - 12);
                        sub.style.top = Math.max(top, 8) + 'px';
                        if (html.getAttribute('dir') === 'rtl') {
                            sub.style.left = 'auto';
                            sub.style.right = (window.innerWidth - r.left + 8) + 'px';
                        } else {
                            sub.style.right = 'auto';
                            sub.style.left = (r.right + 8) + 'px';
                        }
                    });
                    item.addEventListener('mouseleave', function () {
                        sub.classList.remove('panel-flyout-open');
                    });
                });
                html.addEventListener('click', function (e) {
                    if (!e.target.closest('#dashboard-sidebar')) hideAll();
                });
            })();

            (function () {
                var toggle = document.getElementById('sidebar-toggle');
                var closeBtn = document.getElementById('sidebar-close');
                var sidebar = document.getElementById('dashboard-sidebar');
                var backdrop = document.getElementById('sidebar-backdrop');
                if (!toggle || !sidebar) return;

                function openNav() {
                    sidebar.classList.add('sidebar-mobile-open');
                    if (backdrop) backdrop.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
                function closeNav() {
                    sidebar.classList.remove('sidebar-mobile-open');
                    if (backdrop) backdrop.classList.add('hidden');
                    document.body.style.overflow = '';
                }
                toggle.addEventListener('click', openNav);
                if (closeBtn) closeBtn.addEventListener('click', closeNav);
                if (backdrop) backdrop.addEventListener('click', closeNav);
                sidebar.addEventListener('click', function (e) {
                    if (e.target.closest('a')) closeNav();
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeNav();
                });
                window.addEventListener('resize', function () {
                    if (window.innerWidth >= 1024) closeNav();
                });
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
                        var isRtl = document.documentElement.getAttribute('dir') === 'rtl';
                        var left = isRtl ? r.left : (r.right - menu.offsetWidth);
                        var top = r.bottom + 6;
                        if (left < 8) left = 8;
                        if (left + menu.offsetWidth > window.innerWidth - 8) {
                            left = window.innerWidth - menu.offsetWidth - 8;
                        }
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

        <!-- Theme toggle controller (independent per panel: admin, instructor) -->
        <script>
            (function () {
                var panel = @json($userType);
                var toggleBtn = document.getElementById('theme-toggle-btn');
                var sunIcon = document.getElementById('theme-icon-sun');
                var moonIcon = document.getElementById('theme-icon-moon');
                var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

                function updateIcons(isDark) {
                    if (isDark) {
                        if (sunIcon) sunIcon.classList.remove('hidden');
                        if (moonIcon) moonIcon.classList.add('hidden');
                        if (toggleBtn) toggleBtn.setAttribute('title', 'Switch to Light Mode');
                    } else {
                        if (sunIcon) sunIcon.classList.add('hidden');
                        if (moonIcon) moonIcon.classList.remove('hidden');
                        if (toggleBtn) toggleBtn.setAttribute('title', 'Switch to Dark Mode');
                    }
                }

                updateIcons(document.documentElement.classList.contains('dark'));

                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function () {
                        var isDark = document.documentElement.classList.toggle('dark');
                        var nextTheme = isDark ? 'dark' : 'light';
                        updateIcons(isDark);

                        try {
                            localStorage.setItem('mono_theme_' + panel, nextTheme);
                        } catch (e) {}

                        fetch('{{ route('theme.toggle', $userType) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ theme: nextTheme })
                        }).catch(function () {});
                    });
                }
            })();

            // Header User Profile Dropdown Toggle
            (function () {
                var btn = document.getElementById('header-user-menu-btn');
                var dropdown = document.getElementById('header-user-menu-dropdown');
                var container = document.getElementById('header-user-menu-container');

                if (!btn || !dropdown || !container) return;

                function closeDropdown() {
                    dropdown.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                }

                function openDropdown() {
                    dropdown.classList.remove('hidden');
                    btn.setAttribute('aria-expanded', 'true');
                }

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var notifDropdown = document.getElementById('header-notifications-dropdown');
                    if (notifDropdown) notifDropdown.classList.add('hidden');

                    var isHidden = dropdown.classList.contains('hidden');
                    if (isHidden) {
                        openDropdown();
                    } else {
                        closeDropdown();
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!container.contains(e.target)) {
                        closeDropdown();
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeDropdown();
                    }
                });
            })();

            // Header Notifications Dropdown Toggle
            (function () {
                var btn = document.getElementById('header-notifications-btn');
                var dropdown = document.getElementById('header-notifications-dropdown');
                var container = document.getElementById('header-notifications-container');

                if (!btn || !dropdown || !container) return;

                function closeDropdown() {
                    dropdown.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                }

                function openDropdown() {
                    dropdown.classList.remove('hidden');
                    btn.setAttribute('aria-expanded', 'true');
                }

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var userMenu = document.getElementById('header-user-menu-dropdown');
                    if (userMenu) userMenu.classList.add('hidden');

                    var isHidden = dropdown.classList.contains('hidden');
                    if (isHidden) {
                        openDropdown();
                    } else {
                        closeDropdown();
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!container.contains(e.target)) {
                        closeDropdown();
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeDropdown();
                    }
                });
            })();
        </script>

        @if ($userType === 'admin')
            <x-media-picker />
        @endif

        @if ($userType === 'admin' || $userType === 'instructor')
            <script>
                window.adminTranslatorConfig = {
                    url: @json($userType === 'instructor' ? route('instructor.translate') : route('admin.translate')),
                    csrf: @json(csrf_token()),
                    defaultLocale: @json(\App\Models\Language::defaultCode()),
                    labels: {
                        working: @json(__('admin.courses.translating') ?? 'Translating…'),
                        done: @json(__('admin.courses.translate_done') ?? 'Translations filled in — review and save.'),
                        failed: @json(__('admin.languages.translate_failed') ?? 'Translation failed.')
                    }
                };
            </script>
            <script src="{{ asset('js/ai-progress.js') }}?v={{ filemtime(public_path('js/ai-progress.js')) }}" defer></script>
            <script src="{{ asset('js/admin-translator.js') }}?v={{ filemtime(public_path('js/admin-translator.js')) }}" defer></script>
        @endif
    </body>
</html>
