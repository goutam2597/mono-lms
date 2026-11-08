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
    </head>
    <body class="font-sans antialiased text-[#0f172a] min-h-screen bg-[#f8fafc] flex">

        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/60 z-40 hidden lg:hidden transition-opacity backdrop-blur-xs"></div>

        <!-- Full-height Dedicated Standalone Sidebar -->
        <aside id="dashboard-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 sm:w-68 bg-[#0c141d] text-slate-300 flex flex-col justify-between border-r border-slate-800/80 transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-xl lg:shadow-none">
            <!-- Sidebar Header & Nav -->
            <div class="flex-1 flex flex-col min-h-0">
                <!-- Top Brand Header -->
                <div class="h-16 px-5 flex items-center justify-between border-b border-slate-800/80 bg-[#090f17] shrink-0">
                    <a href="{{ $userType === 'admin' ? route('admin.dashboard') : route('instructor.dashboard') }}" class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Mono LMS" class="h-7 w-auto object-contain" />
                        <span class="text-[9px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full {{ $userType === 'admin' ? 'bg-[#344c3d] text-[#dce7da] border border-[#738a6e]/40' : 'bg-emerald-950 text-emerald-200 border border-emerald-700/40' }}">
                            {{ $userType === 'admin' ? 'Admin' : 'Instructor' }}
                        </span>
                    </a>
                    <button type="button" id="sidebar-close" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800" aria-label="Close sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Navigation Links List (Scrollable) -->
                <div class="flex-1 p-3.5 overflow-y-auto">
                    @if ($userType === 'admin')
                        <x-admin-sidebar :active="$active" />
                    @else
                        <x-instructor-sidebar :active="$active" />
                    @endif
                </div>
            </div>

            <!-- Sidebar Bottom: User Profile & Quick Sign Out -->
            <div class="p-3.5 border-t border-slate-800/80 bg-[#090f17]/90 shrink-0">
                @if ($userType === 'admin')
                    @php $admin = auth('admin')->user(); @endphp
                    <div class="flex items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative">
                                <span class="w-8.5 h-8.5 rounded-full bg-[#344c3d] text-white font-bold text-xs flex items-center justify-center shrink-0 ring-2 ring-slate-800">
                                    {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                                </span>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-[#0c141d]"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate">{{ $admin->name ?? 'Admin' }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $admin->isSuperAdmin() ? __('admin.admins.super_admin') : ($admin->role?->name ?? 'Admin') }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}" class="shrink-0">
                            @csrf
                            <button type="submit" title="{{ __('app.nav.sign_out') }}" class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800/80 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                @else
                    @php $instructor = auth('instructor')->user(); @endphp
                    <div class="flex items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="relative">
                                @if ($instructor?->avatar)
                                    <img src="{{ $instructor->avatar }}" alt="{{ $instructor->name }}" class="w-8.5 h-8.5 rounded-full object-cover shrink-0 ring-2 ring-slate-800">
                                @else
                                    <span class="w-8.5 h-8.5 rounded-full bg-[#344c3d] text-white font-bold text-xs flex items-center justify-center shrink-0 ring-2 ring-slate-800">
                                        {{ strtoupper(substr($instructor->name ?? 'I', 0, 1)) }}
                                    </span>
                                @endif
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-[#0c141d]"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate">{{ $instructor->name ?? 'Instructor' }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $instructor->designation ?? __('app.common.instructor') }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('instructor.logout') }}" class="shrink-0">
                            @csrf
                            <button type="submit" title="{{ __('instructor.nav.logout') }}" class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800/80 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64 sm:lg:pl-68 min-h-screen">
            <!-- Full-width Sticky Top Header -->
            <header class="h-16 bg-white border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" id="sidebar-toggle" class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle navigation">
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
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-[#344c3d] bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span class="hidden sm:inline">View Website</span>
                    </a>

                    @if ($userType === 'instructor')
                        <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold bg-[#344c3d] hover:bg-[#283a2f] text-white px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-lg transition-colors shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
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
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">&times;</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
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
