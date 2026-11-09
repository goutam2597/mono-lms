@props(['title' => 'Dashboard - Mono LMS', 'active' => 'dashboard', 'userType' => 'student'])

@if ($userType === 'admin' || $userType === 'instructor')
    <!DOCTYPE html>
    <html lang="{{ $currentLocale ?? str_replace('_', '-', app()->getLocale()) }}" dir="{{ $currentDirection ?? 'ltr' }}" class="h-full bg-[#f8fafc]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .custom-sidebar-scroll {
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.12) transparent;
            }
            .custom-sidebar-scroll::-webkit-scrollbar {
                width: 5px;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.12);
                border-radius: 9999px;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.24);
            }

            /* ---------------- Standard Modal System & Mono Market Boxy Design ---------------- */
            .action-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                vertical-align: middle;
                height: 32px;
                padding: 0 14px;
                font-size: 10px;
                font-weight: 600;
                line-height: 1.2;
                text-transform: uppercase;
                letter-spacing: .12em;
                white-space: nowrap;
                color: #ffffff;
                background: #191919;
                border: 1px solid #191919;
                cursor: pointer;
                font-family: inherit;
                border-radius: 0 !important;
                transition: color .15s, background .15s, border-color .15s;
            }
            .action-btn:hover {
                background: #344c3d;
                border-color: #344c3d;
                color: #ffffff;
            }
            .action-btn-primary {
                background: #344c3d;
                border-color: #344c3d;
                color: #ffffff;
            }
            .action-btn-primary:hover {
                background: #283a2f;
                border-color: #283a2f;
            }
            .action-btn-danger {
                background: #dc2626;
                border-color: #dc2626;
                color: #ffffff;
            }
            .action-btn-danger:hover {
                background: #b91c1c;
                border-color: #b91c1c;
            }
            .action-btn-ghost {
                background: #ffffff;
                border-color: #e2e8f0;
                color: #475569;
            }
            .action-btn-ghost:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
                color: #0f172a;
            }

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
            .modal-overlay .panel-sm { width: 440px; }
            .modal-overlay .panel-md { width: 520px; }
            .modal-overlay .panel-lg { width: 640px; }
            .modal-overlay .panel-xl { width: 920px; }

            /* Global Boxy Shape Enforcement for Admin & Dashboard */
            #admin-main .rounded-3xl,
            #admin-main .rounded-2xl,
            #admin-main .rounded-xl,
            #admin-main .rounded-lg,
            #admin-main .rounded-md,
            #admin-main .rounded,
            #dashboard-sidebar .rounded-3xl,
            #dashboard-sidebar .rounded-2xl,
            #dashboard-sidebar .rounded-xl,
            #dashboard-sidebar .rounded-lg,
            #dashboard-sidebar .rounded-md,
            #dashboard-sidebar .rounded,
            .modal-overlay .rounded-3xl,
            .modal-overlay .rounded-2xl,
            .modal-overlay .rounded-xl,
            .modal-overlay .rounded-lg,
            .modal-overlay .rounded-md,
            .modal-overlay .rounded {
                border-radius: 0 !important;
            }

            /* Boxy Form Inputs */
            #admin-main input:not([type="checkbox"]):not([type="radio"]),
            #admin-main select,
            #admin-main textarea,
            .modal-overlay input:not([type="checkbox"]):not([type="radio"]),
            .modal-overlay select,
            .modal-overlay textarea {
                border-radius: 0 !important;
            }

            /* Boxy Custom Checkboxes */
            input[type="checkbox"].control,
            input[type="checkbox"].row-check,
            #check-all {
                border-radius: 0 !important;
            }

            /* Boxy Badges & Chips */
            #admin-main span[class*="rounded-full"]:not(.status-dot):not(.avatar-dot),
            #dashboard-sidebar span[class*="rounded-full"]:not(.status-dot) {
                border-radius: 0 !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-[#0f172a] min-h-screen bg-[#f8fafc] flex">

        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/70 z-40 hidden lg:hidden transition-opacity backdrop-blur-xs"></div>

        <!-- Full-height Dedicated Standalone Sidebar (Learnify Enterprise Style) -->
        <aside id="dashboard-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#090f17] text-slate-300 flex flex-col justify-between border-r border-white/[0.07] transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none select-none">
            <!-- Sidebar Header & Scrollable Nav -->
            <div class="flex-1 flex flex-col min-h-0">
                <!-- Top Brand Header -->
                <div class="h-16 px-5 flex items-center justify-between border-b border-white/[0.07] bg-[#070b12] shrink-0">
                    <a href="{{ $userType === 'admin' ? route('admin.dashboard') : route('instructor.dashboard') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Mono LMS" class="h-7 w-auto object-contain transition-transform duration-200 group-hover:scale-105" />
                        <span class="text-[9px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-none {{ $userType === 'admin' ? 'bg-[#344c3d]/70 text-emerald-300 border border-emerald-500/30' : 'bg-emerald-950 text-emerald-200 border border-emerald-700/40' }}">
                            {{ $userType === 'admin' ? 'ADMIN CONSOLE' : 'INSTRUCTOR' }}
                        </span>
                    </a>
                    <button type="button" id="sidebar-close" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/[0.08] transition-colors" aria-label="Close sidebar">
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

            <!-- Sidebar Bottom: Polished User Profile Card -->
            <div class="p-3 border-t border-white/[0.07] bg-[#070b12] shrink-0">
                @if ($userType === 'admin')
                    @php $admin = auth('admin')->user(); @endphp
                    <div class="bg-white/[0.03] border border-white/[0.06] rounded-none p-2.5 flex items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative shrink-0">
                                <div class="w-9 h-9 rounded-none bg-gradient-to-br from-[#344c3d] to-[#1e2c23] text-emerald-200 font-bold text-xs flex items-center justify-center ring-1 ring-emerald-500/30 shadow-xs">
                                    {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-none bg-emerald-500 ring-2 ring-[#070b12]"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate leading-tight">{{ $admin->name ?? 'Administrator' }}</p>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $admin->isSuperAdmin() ? 'Super Admin' : ($admin->role?->name ?? 'Administrator') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0">
                            <a href="{{ route('home') }}" target="_blank" title="View Website" class="p-1.5 rounded-none text-slate-400 hover:text-white hover:bg-white/[0.06] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                                @csrf
                                <button type="submit" title="{{ __('app.nav.sign_out') }}" class="p-1.5 rounded-none text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @php $instructor = auth('instructor')->user(); @endphp
                    <div class="bg-white/[0.03] border border-white/[0.06] rounded-none p-2.5 flex items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative shrink-0">
                                @if ($instructor?->avatar)
                                    <img src="{{ $instructor->avatar }}" alt="{{ $instructor->name }}" class="w-9 h-9 rounded-none object-cover ring-1 ring-emerald-500/30">
                                @else
                                    <div class="w-9 h-9 rounded-none bg-gradient-to-br from-[#344c3d] to-[#1e2c23] text-emerald-200 font-bold text-xs flex items-center justify-center ring-1 ring-emerald-500/30 shadow-xs">
                                        {{ strtoupper(substr($instructor->name ?? 'I', 0, 1)) }}
                                    </div>
                                @endif
                                <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-none bg-emerald-500 ring-2 ring-[#070b12]"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate leading-tight">{{ $instructor->name ?? 'Instructor' }}</p>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ $instructor->designation ?? __('app.common.instructor') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0">
                            <a href="{{ route('home') }}" target="_blank" title="View Website" class="p-1.5 rounded-none text-slate-400 hover:text-white hover:bg-white/[0.06] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                            <form method="POST" action="{{ route('instructor.logout') }}" class="inline">
                                @csrf
                                <button type="submit" title="{{ __('instructor.nav.logout') }}" class="p-1.5 rounded-none text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </aside>

        <!-- Main Content Area -->
        <div id="admin-main" class="flex-1 flex flex-col min-w-0 lg:pl-72 min-h-screen">
            <!-- Full-width Sticky Top Header -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" id="sidebar-toggle" class="lg:hidden p-2 rounded-none text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle navigation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                            <span>{{ $userType === 'admin' ? 'Admin Console' : 'Instructor Portal' }}</span>
                            <span>/</span>
                            <span class="text-slate-700 font-semibold truncate">{{ $title }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Quick link to Live Store -->
                    <a href="{{ route('home') }}" target="_blank" class="action-btn action-btn-ghost">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span class="hidden sm:inline">View Website</span>
                    </a>

                    @if ($userType === 'instructor')
                        <a href="{{ route('instructor.courses.create') }}" class="action-btn action-btn-primary">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>{{ __('instructor.courses.create') }}</span>
                        </a>
                    @endif

                    <!-- Language Switcher -->
                    <x-language-switcher :panel="$userType" class="text-xs font-medium text-slate-700" />
                </div>
            </header>

            <!-- Full-width Main Page Content -->
            <main class="flex-1 p-5 sm:p-7 lg:p-8 min-w-0">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-none text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">&times;</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-none text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-800 cursor-pointer">&times;</button>
                    </div>
                @endif

                {{ $slot }}
            </main>
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
