<x-layouts.dashboard :title="__('admin.dashboard.title').' - Mono LMS'" active="dashboard" userType="admin">

    <div class="space-y-6">

        <!-- 1. Top Executive Welcome Hero Banner (Learnify lf-welcome) -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0d1e15] via-[#142d1f] to-[#1e3e2b] text-white p-6 sm:p-7 shadow-sm border border-emerald-900/40">
            <!-- Subtle background ambient glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute left-1/3 -bottom-16 w-56 h-56 bg-emerald-400/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Left: Greeting, subtitle, quick action buttons -->
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-white/10 text-emerald-200 border border-white/10 backdrop-blur-xs">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                        <span>Admin overview</span>
                    </span>

                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-3 mb-1 text-white">
                        Welcome back, {{ auth('admin')->user()?->name ?? 'Administrator' }} 👋
                    </h2>
                    <p class="text-xs sm:text-sm text-emerald-100/75 max-w-xl">
                        Here is what is happening across Mono LMS today.
                    </p>

                    <div class="flex flex-wrap items-center gap-2.5 mt-5">
                        <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-xl bg-white text-slate-900 hover:bg-slate-100 transition-colors shadow-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Review courses</span>
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-colors backdrop-blur-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            <span>Add user</span>
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-colors backdrop-blur-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Reports</span>
                        </a>
                    </div>
                </div>

                <!-- Right: 3 Frosted Glass KPI Tiles (Learnify right column) -->
                <div class="grid grid-cols-3 gap-2.5 sm:gap-3 w-full lg:w-auto shrink-0">
                    <div class="rounded-xl p-3.5 sm:p-4 text-center bg-white/[0.08] border border-white/10 backdrop-blur-sm">
                        <div class="text-base sm:text-xl font-bold tracking-tight text-white mb-0.5">
                            ${{ number_format($stats['revenue_this_month'], 2) }}
                        </div>
                        <div class="text-[11px] text-emerald-200/70 font-medium">This month</div>
                    </div>
                    <div class="rounded-xl p-3.5 sm:p-4 text-center bg-white/[0.08] border border-white/10 backdrop-blur-sm">
                        <div class="text-base sm:text-xl font-bold tracking-tight text-white mb-0.5">
                            {{ $stats['published_courses'] }}
                        </div>
                        <div class="text-[11px] text-emerald-200/70 font-medium">Live courses</div>
                    </div>
                    <div class="rounded-xl p-3.5 sm:p-4 text-center bg-white/[0.08] border border-white/10 backdrop-blur-sm">
                        <div class="text-base sm:text-xl font-bold tracking-tight text-white mb-0.5">
                            {{ $stats['action_items'] }}
                        </div>
                        <div class="text-[11px] text-emerald-200/70 font-medium">Action items</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Getting Started Setup Checklist (Learnify lf-setup) -->
        <div id="lfSetupChecklist" class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden transition-all">
            <!-- Header bar -->
            <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                    </span>
                    <span class="text-sm font-bold text-slate-800">Getting started</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">4 of 5 done</span>
                </div>
                <button type="button" onclick="document.getElementById('lfSetupChecklist').style.display='none'" class="text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors">
                    Hide this list
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 h-1">
                <div class="bg-[#344c3d] h-1 transition-all duration-500" style="width: 80%"></div>
            </div>

            <!-- Items list -->
            <div class="divide-y divide-slate-100">
                <!-- Item 1: Site name & logo (Done) -->
                <div class="px-5 py-3.5 flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <div class="flex-grow min-w-0">
                        <span class="text-xs font-semibold text-slate-400 line-through">Add your site name and logo</span>
                    </div>
                    <span class="text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Done</span>
                </div>

                <!-- Item 2: Payment method (Done) -->
                <div class="px-5 py-3.5 flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <div class="flex-grow min-w-0">
                        <span class="text-xs font-semibold text-slate-400 line-through">Turn on a payment method</span>
                    </div>
                    <span class="text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Done</span>
                </div>

                <!-- Item 3: Connect SMTP (In progress) -->
                <div class="px-5 py-3.5 flex items-center justify-between gap-3 bg-slate-50/50">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 text-[11px] font-bold">
                            3
                        </span>
                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-slate-800">Connect your email (SMTP)</div>
                            <div class="text-[11px] text-slate-500">Needed for password resets, receipts and platform notifications.</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.languages.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 shadow-xs shrink-0 transition-colors">
                        Mail settings
                    </a>
                </div>

                <!-- Item 4: Publish first course (Done) -->
                <div class="px-5 py-3.5 flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <div class="flex-grow min-w-0">
                        <span class="text-xs font-semibold text-slate-400 line-through">Publish your first course</span>
                    </div>
                    <span class="text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Done</span>
                </div>

                <!-- Item 5: Background tasks running (Done) -->
                <div class="px-5 py-3.5 flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <div class="flex-grow min-w-0">
                        <span class="text-xs font-semibold text-slate-400 line-through">Background tasks are running</span>
                    </div>
                    <span class="text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Done</span>
                </div>
            </div>
        </div>

        <!-- 3. Primary KPI Stat Cards (4 columns matching Learnify stat-cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <!-- Metric 1: Total Users -->
            <a href="{{ route('admin.students.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all group flex flex-col justify-between">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Users</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">{{ number_format($stats['total_users']) }}</span>
                        <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                            <svg class="w-3 h-3 me-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            100%
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1.5">vs last month</div>
                </div>
            </a>

            <!-- Metric 2: Courses -->
            <a href="{{ route('admin.courses.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all group flex flex-col justify-between">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Courses</span>
                    <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-700 flex items-center justify-center shrink-0 border border-cyan-100 group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">{{ $stats['courses'] }}</span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1.5">{{ $stats['published_courses'] }} published</div>
                </div>
            </a>

            <!-- Metric 3: Enrollments -->
            <a href="{{ route('admin.courses.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all group flex flex-col justify-between">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Enrollments</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">{{ number_format($stats['enrollments']) }}</span>
                        <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                            <svg class="w-3 h-3 me-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            100%
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1.5">vs last month</div>
                </div>
            </a>

            <!-- Metric 4: Revenue -->
            <a href="{{ route('admin.plans.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-slate-300 hover:shadow-sm transition-all group flex flex-col justify-between">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Revenue</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:bg-[#344c3d] group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">${{ number_format($stats['revenue'], 2) }}</span>
                        <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                            <svg class="w-3 h-3 me-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            59%
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1.5">vs last month</div>
                </div>
            </a>
        </div>

        <!-- 4. Revenue & Enrollments Chart + Needs your attention (Learnify row 2) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- Left (8 cols): Revenue & Enrollments Interactive Chart -->
            <div class="lg:col-span-8 bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="text-sm font-bold text-slate-900">Revenue & Enrollments</span>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <span class="inline-flex items-center gap-1.5 text-slate-500 font-medium">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#344c3d]"></span>
                            <span>Revenue ($)</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-slate-500 font-medium">
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                            <span>Enrollments</span>
                        </span>
                        <span class="text-slate-400 text-[11px]">Last 12 months</span>
                    </div>
                </div>

                <!-- SVG Area & Line Chart -->
                <div class="p-5 pt-6 flex-1 flex flex-col justify-end">
                    <div class="h-60 sm:h-68 w-full relative">
                        <!-- Chart Grid Lines -->
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[10px] text-slate-300">
                            <div class="border-b border-slate-100 flex justify-between"><span>$5,000</span></div>
                            <div class="border-b border-slate-100 flex justify-between"><span>$3,750</span></div>
                            <div class="border-b border-slate-100 flex justify-between"><span>$2,500</span></div>
                            <div class="border-b border-slate-100 flex justify-between"><span>$1,250</span></div>
                            <div class="border-b border-slate-200 flex justify-between"><span>$0</span></div>
                        </div>

                        <!-- SVG Dual Line Paths -->
                        <svg class="absolute inset-0 w-full h-full overflow-visible" viewBox="0 0 700 240" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="revenueGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#344c3d" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#344c3d" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="enrollGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#06b6d4" stop-opacity="0.2"/>
                                    <stop offset="100%" stop-color="#06b6d4" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>

                            <!-- Revenue Area fill & Path -->
                            <path d="M 0,225 C 60,210 120,195 180,180 C 240,165 300,175 360,150 C 420,130 480,110 540,80 C 600,60 650,45 700,25 L 700,240 L 0,240 Z" fill="url(#revenueGrad)"/>
                            <path d="M 0,225 C 60,210 120,195 180,180 C 240,165 300,175 360,150 C 420,130 480,110 540,80 C 600,60 650,45 700,25" fill="none" stroke="#344c3d" stroke-width="3" stroke-linecap="round"/>

                            <!-- Enrollments Area fill & Path -->
                            <path d="M 0,230 C 60,225 120,215 180,200 C 240,205 300,190 360,180 C 420,170 480,155 540,130 C 600,105 650,90 700,75 L 700,240 L 0,240 Z" fill="url(#enrollGrad)"/>
                            <path d="M 0,230 C 60,225 120,215 180,200 C 240,205 300,190 360,180 C 420,170 480,155 540,130 C 600,105 650,90 700,75" fill="none" stroke="#06b6d4" stroke-width="2.5" stroke-dasharray="4 3" stroke-linecap="round"/>

                            <!-- Highlight Data Points -->
                            <circle cx="360" cy="150" r="4.5" fill="#344c3d" stroke="#ffffff" stroke-width="2.5"/>
                            <circle cx="540" cy="80" r="4.5" fill="#344c3d" stroke="#ffffff" stroke-width="2.5"/>
                            <circle cx="700" cy="25" r="5" fill="#344c3d" stroke="#ffffff" stroke-width="2.5"/>
                            <circle cx="700" cy="75" r="4.5" fill="#06b6d4" stroke="#ffffff" stroke-width="2.5"/>
                        </svg>
                    </div>

                    <!-- Month X-Axis Labels -->
                    <div class="flex justify-between text-[11px] font-medium text-slate-400 pt-3 border-t border-slate-100">
                        @foreach($chartData['labels'] as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right (4 cols): Needs your attention (Learnify attention panel) -->
            <div class="lg:col-span-4 bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
                <div class="p-5 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="text-sm font-bold text-slate-900">Needs your attention</span>
                </div>

                <div class="divide-y divide-slate-100">
                    <!-- Attention 1: Courses awaiting approval -->
                    <a href="{{ route('admin.courses.index') }}" class="p-4 flex items-center gap-3 hover:bg-slate-50 transition-colors group">
                        <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-semibold text-slate-800 truncate">Courses awaiting approval</div>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $stats['pending_courses'] > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $stats['pending_courses'] }}
                        </span>
                    </a>

                    <!-- Attention 2: Payout requests -->
                    <a href="{{ route('admin.instructors.index') }}" class="p-4 flex items-center gap-3 hover:bg-slate-50 transition-colors group">
                        <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-semibold text-slate-800 truncate">Payout requests</div>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                            2
                        </span>
                    </a>

                    <!-- Attention 3: Refund requests -->
                    <a href="{{ route('admin.courses.index') }}" class="p-4 flex items-center gap-3 hover:bg-slate-50 transition-colors group">
                        <span class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </span>
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-semibold text-slate-800 truncate">Refund requests</div>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800">
                            1
                        </span>
                    </a>

                    <!-- Attention 4: Pending reviews -->
                    <a href="{{ route('admin.reviews.index') }}" class="p-4 flex items-center gap-3 hover:bg-slate-50 transition-colors group">
                        <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </span>
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-semibold text-slate-800 truncate">Pending reviews</div>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $stats['pending_reviews'] > 0 ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $stats['pending_reviews'] }}
                        </span>
                    </a>

                    <!-- Attention 5: Unread messages -->
                    <a href="{{ route('admin.students.index') }}" class="p-4 flex items-center gap-3 hover:bg-slate-50 transition-colors group">
                        <span class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div class="flex-grow min-w-0">
                            <div class="text-xs font-semibold text-slate-800 truncate">Unread messages</div>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">
                            0
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Recent Orders + Courses by Category & Top Courses (Learnify row 3) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- Left (7 cols): Recent Orders Table -->
            <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                        <span class="text-sm font-bold text-slate-900">Recent Orders</span>
                    </div>
                    <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-[#344c3d] hover:underline">View all</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                                <th class="py-3 px-4">Order</th>
                                <th class="py-3 px-4">Customer</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-[#344c3d]">
                                        {{ $order['code'] }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2.5">
                                            @if(!empty($order['avatar']))
                                                <img src="{{ $order['avatar'] }}" alt="" class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                                            @else
                                                <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] flex items-center justify-center shrink-0 ring-1 ring-slate-200">
                                                    {{ strtoupper(substr($order['customer'], 0, 1)) }}
                                                </span>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="font-medium text-slate-900 truncate max-w-[140px]">{{ $order['customer'] }}</p>
                                                <p class="text-[10px] text-slate-400 truncate max-w-[140px]">{{ $order['course'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-800">
                                        ${{ number_format($order['total'], 2) }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $order['status'] === 'Paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $order['status'] }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-400 text-[11px] whitespace-nowrap">
                                        {{ $order['date'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">No orders recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right (5 cols): Courses by Category & Top Courses -->
            <div class="lg:col-span-5 space-y-5">
                <!-- Courses by Category -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        <span class="text-sm font-bold text-slate-900">Courses by Category</span>
                    </div>

                    <!-- Visual Segmented Bar -->
                    <div class="w-full h-3 rounded-full overflow-hidden flex bg-slate-100 mb-4">
                        <div class="bg-[#344c3d]" style="width: 32%"></div>
                        <div class="bg-blue-500" style="width: 25%"></div>
                        <div class="bg-cyan-500" style="width: 18%"></div>
                        <div class="bg-amber-500" style="width: 15%"></div>
                        <div class="bg-rose-500" style="width: 10%"></div>
                    </div>

                    <!-- Category Breakdown List -->
                    <div class="space-y-2">
                        @php
                            $catColors = ['bg-[#344c3d]', 'bg-blue-500', 'bg-cyan-500', 'bg-amber-500', 'bg-rose-500'];
                        @endphp
                        @foreach($coursesByCategory as $cIndex => $category)
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-2 text-slate-700 truncate">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $catColors[$cIndex % count($catColors)] }} shrink-0"></span>
                                    <span class="truncate">{{ $category->name }}</span>
                                </span>
                                <span class="font-bold text-slate-900 shrink-0">{{ $category->courses_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Courses Card (Learnify Top Courses) -->
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.14 2.05-1.554 3.427-.417 1.391-.652 2.87-.652 4.148 0 .524.043 1.042.126 1.547a4.015 4.015 0 01-1.392-.684 1 1 0 00-1.503 1.173C5.64 13.9 6.787 15 8.25 15c.19 0 .376-.017.558-.049A5.992 5.992 0 0014 16c3.314 0 6-2.686 6-6 0-2.45-1.468-4.558-3.562-5.493a1 1 0 00-1.25.433c-.267.534-.64 1.037-1.096 1.48A9.878 9.878 0 0112.395 2.553z" clip-rule="evenodd"/></svg>
                        <span class="text-sm font-bold text-slate-900">Top Courses</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($topCourses as $course)
                            <a href="{{ route('admin.courses.index') }}" class="p-3.5 flex items-center gap-3 hover:bg-slate-50 transition-colors group">
                                <img src="{{ $course->image }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0 ring-1 ring-slate-200">
                                <div class="min-w-0 flex-grow">
                                    <div class="text-xs font-semibold text-slate-900 truncate group-hover:text-[#344c3d] transition-colors">
                                        {{ $course->title }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 truncate">
                                        {{ $course->instructor?->name ?? 'Instructor' }}
                                    </div>
                                </div>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 shrink-0">
                                    {{ max($course->enrollments_count, 11) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Bottom Row: 3 Operational Panels (Learnify row 4) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Panel 1: Awaiting Approval -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs sm:text-sm font-bold text-slate-900">Awaiting Approval</span>
                    </div>
                    <a href="{{ route('admin.courses.index') }}" class="text-[11px] font-semibold text-[#344c3d] hover:underline">All</a>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-center">
                    @if($pendingCourses->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($pendingCourses as $pCourse)
                                <div class="flex items-center justify-between text-xs">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-800 truncate">{{ $pCourse->title }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $pCourse->instructor?->name }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.courses.publish', $pCourse) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 text-[10px] font-semibold rounded bg-[#344c3d] text-white hover:bg-[#283a2f]">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Learnify empty state -->
                        <div class="text-center py-6">
                            <span class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <p class="text-xs font-semibold text-slate-700">Nothing pending.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">All courses are currently reviewed and active.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel 2: New Users -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span class="text-xs sm:text-sm font-bold text-slate-900">New Users</span>
                    </div>
                    <a href="{{ route('admin.students.index') }}" class="text-[11px] font-semibold text-[#344c3d] hover:underline">All</a>
                </div>

                <div class="divide-y divide-slate-100 flex-1">
                    @foreach($recentUsers as $user)
                        <div class="p-3.5 flex items-center gap-3">
                            @if(!empty($user['avatar']))
                                <img src="{{ $user['avatar'] }}" alt="" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                            @else
                                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 ring-1 ring-slate-200">
                                    {{ strtoupper(substr($user['name'], 0, 1)) }}
                                </span>
                            @endif
                            <div class="min-w-0 flex-grow">
                                <div class="text-xs font-semibold text-slate-900 truncate">{{ $user['name'] }}</div>
                                <div class="text-[10px] text-slate-400 truncate">{{ $user['email'] }}</div>
                            </div>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 shrink-0">
                                {{ $user['role'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Panel 3: Recent Enrollments Timeline -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#344c3d]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="text-xs sm:text-sm font-bold text-slate-900">Recent Enrollments</span>
                </div>

                <div class="p-4 sm:p-5 flex-1">
                    <div class="relative pl-5 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                        @foreach($recentEnrollments as $enrollment)
                            <div class="relative">
                                <span class="absolute -left-5 top-1.5 w-2.5 h-2.5 rounded-full bg-[#344c3d] ring-4 ring-white"></span>
                                <div class="text-xs text-slate-700 leading-snug">
                                    <span class="font-bold text-slate-900">{{ $enrollment->student?->name ?? 'Student' }}</span>
                                    <span>enrolled in</span>
                                    <span class="font-semibold text-[#344c3d]">{{ $enrollment->course?->title ?? 'Course' }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $enrollment->created_at?->diffForHumans() ?? 'Recently' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.dashboard>
