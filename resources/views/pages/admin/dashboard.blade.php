<x-layouts.dashboard :title="__('admin.dashboard.title').' - Mono LMS'" active="dashboard" userType="admin">

    <div class="space-y-6">

        <!-- Top Welcome & Date Filter Header (Figma Dev Mode Node 10060:83402) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-[11px] font-semibold text-[var(--theme-primary,#344c3d)] uppercase tracking-wider mb-0.5">{{ __('admin.dashboard.overview') }}</div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#0f172a] tracking-tight">
                    {{ __('admin.dashboard.welcome_back', ['name' => auth('admin')->user()?->name ?? 'Administrator']) }}
                </h1>
                <p class="text-xs sm:text-[13px] text-[#64748b] mt-0.5">
                    {{ __('admin.dashboard.today_summary', ['actions' => $stats['action_items'] ?? 0, 'reviews' => $stats['reviews'] ?? 0]) }}
                    <a href="{{ route('admin.courses.index') }}" class="text-[var(--theme-primary,#344c3d)] font-semibold hover:underline">{{ __('admin.dashboard.view_details') }}</a>
                </p>
            </div>
            <!-- Date Range Filter Pill -->
            <div class="inline-flex items-center gap-2 bg-white border border-[#e2e8f0] px-3.5 py-2 rounded-[5px] text-xs font-medium text-[#334155] shadow-2xs self-start sm:self-auto">
                <svg class="w-3.5 h-3.5 text-[var(--theme-primary,#344c3d)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                </svg>
                <span>{{ now()->subDays(6)->format('d M Y') }} - {{ now()->format('d M Y') }}</span>
            </div>
        </div>

        @php
            $pendingVerificationsCount = \App\Models\Instructor::where('verification_status', 'pending')->count();
        @endphp
        @if ($pendingVerificationsCount > 0)
            <div class="p-4 rounded-[6px] bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm text-amber-950">
                            {{ __('admin.dashboard.waiting_verification', ['count' => $pendingVerificationsCount]) }}
                        </p>
                        <p class="text-xs text-amber-800 mt-0.5">
                            {{ __('admin.dashboard.waiting_verification_hint') }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.instructors.index', ['verification' => 'pending']) }}" class="action-btn bg-amber-600 border-amber-600 hover:bg-amber-700 text-white text-[11px] h-8 px-4 rounded-xs shrink-0 font-semibold uppercase tracking-wider">
                    {{ __('admin.dashboard.review_pending', ['count' => $pendingVerificationsCount]) }}
                </a>
            </div>
        @endif

        <!-- 1. Four Metric Cards with Wave Sparklines (Figma Dev Mode Node 10060:83410) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: All Students (Primary Brand) -->
            <div class="bg-white border border-[#e2e8f0] rounded-[5px] shadow-[0px_4px_7px_rgba(238,238,238,0.25)] p-5 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[var(--theme-primary,#344c3d)] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] text-[#64748b] font-medium leading-tight">{{ __('admin.dashboard.total_students') }}</p>
                            <p class="text-[20px] font-bold text-[#0f172a] mt-1">{{ number_format($stats['total_students'] ?? $stats['students'] ?? 0) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#eef9f1] text-[#137547] shrink-0">
                        +20%
                    </span>
                </div>
                <!-- Sparkline Wave SVG (Primary Brand) -->
                <div class="mt-4 -mx-5 -mb-5 h-11 overflow-hidden">
                    <svg class="w-full h-full" viewBox="0 0 260 50" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="gradBlue" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="var(--theme-primary,#344c3d)" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="var(--theme-primary,#344c3d)" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,35 Q30,15 65,30 T130,20 T195,35 T260,15 L260,50 L0,50 Z" fill="url(#gradBlue)"/>
                        <path d="M0,35 Q30,15 65,30 T130,20 T195,35 T260,15" fill="none" stroke="var(--theme-primary,#344c3d)" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            <!-- Card 2: Total Courses (Orange) -->
            <div class="bg-white border border-[#e2e8f0] rounded-[5px] shadow-[0px_4px_7px_rgba(238,238,238,0.25)] p-5 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#e65c00] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18-3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] text-[#64748b] font-medium leading-tight">{{ __('admin.dashboard.total_courses') }}</p>
                            <p class="text-[20px] font-bold text-[#0f172a] mt-1">{{ number_format($stats['total_courses'] ?? $stats['courses'] ?? 0) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#eef9f1] text-[#137547] shrink-0">
                        +15%
                    </span>
                </div>
                <!-- Sparkline Wave SVG (Orange) -->
                <div class="mt-4 -mx-5 -mb-5 h-11 overflow-hidden">
                    <svg class="w-full h-full" viewBox="0 0 260 50" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="gradOrange" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#e65c00" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#e65c00" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,25 Q35,40 70,25 T140,35 T200,18 T260,30 L260,50 L0,50 Z" fill="url(#gradOrange)"/>
                        <path d="M0,25 Q35,40 70,25 T140,35 T200,18 T260,30" fill="none" stroke="#e65c00" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            <!-- Card 3: Total Instructors (Purple) -->
            <div class="bg-white border border-[#e2e8f0] rounded-[5px] shadow-[0px_4px_7px_rgba(238,238,238,0.25)] p-5 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#7c3aed] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] text-[#64748b] font-medium leading-tight">{{ __('admin.dashboard.total_instructors') }}</p>
                            <p class="text-[20px] font-bold text-[#0f172a] mt-1">{{ number_format($stats['total_instructors'] ?? $stats['instructors'] ?? 0) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#eef9f1] text-[#137547] shrink-0">
                        +18%
                    </span>
                </div>
                <!-- Sparkline Wave SVG (Purple) -->
                <div class="mt-4 -mx-5 -mb-5 h-11 overflow-hidden">
                    <svg class="w-full h-full" viewBox="0 0 260 50" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="gradPurple" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#7c3aed" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#7c3aed" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,38 Q40,15 80,30 T160,18 T220,32 T260,15 L260,50 L0,50 Z" fill="url(#gradPurple)"/>
                        <path d="M0,38 Q40,15 80,30 T160,18 T220,32 T260,15" fill="none" stroke="#7c3aed" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            <!-- Card 4: Total Revenue (Magenta) -->
            <div class="bg-white border border-[#e2e8f0] rounded-[5px] shadow-[0px_4px_7px_rgba(238,238,238,0.25)] p-5 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#db2777] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] text-[#64748b] font-medium leading-tight">{{ __('admin.dashboard.total_revenue') }}</p>
                            <p class="text-[20px] font-bold text-[#0f172a] mt-1">${{ number_format($stats['total_revenue'] ?? $stats['revenue'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#eef9f1] text-[#137547] shrink-0">
                        +12%
                    </span>
                </div>
                <!-- Sparkline Wave SVG (Magenta) -->
                <div class="mt-4 -mx-5 -mb-5 h-11 overflow-hidden">
                    <svg class="w-full h-full" viewBox="0 0 260 50" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="gradMagenta" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#db2777" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#db2777" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,32 Q35,45 75,20 T150,38 T215,22 T260,15 L260,50 L0,50 Z" fill="url(#gradMagenta)"/>
                        <path d="M0,32 Q35,45 75,20 T150,38 T215,22 T260,15" fill="none" stroke="#db2777" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>

        @if(($pendingCourses && $pendingCourses->isNotEmpty()) || ($pendingReviews && $pendingReviews->isNotEmpty()))
            <!-- Action Items: Pending Approvals & Moderation Banner -->
            <div class="bg-amber-50/70 border border-amber-200 rounded-[5px] p-4.5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-amber-200/60">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('admin.dashboard.action_required') }}</h2>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-[4px] bg-amber-100 text-amber-800 self-start sm:self-auto">
                        {{ __('admin.dashboard.items_pending', ['count' => ($pendingCourses ? $pendingCourses->count() : 0) + ($pendingReviews ? $pendingReviews->count() : 0)]) }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    @if($pendingCourses && $pendingCourses->isNotEmpty())
                        <div class="bg-white border border-amber-200/60 rounded-[5px] p-3.5 space-y-2.5">
                            <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                    {{ __('admin.dashboard.courses_awaiting_pub') }}
                                </span>
                                <a href="{{ route('admin.courses.index') }}" class="text-[var(--theme-primary,#344c3d)] hover:underline">{{ __('app.common.view_all') }}</a>
                            </div>
                            @foreach($pendingCourses->take(3) as $pCourse)
                                <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100 gap-2">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 truncate">{{ $pCourse->trans('title') }}</p>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $pCourse->instructor?->name ?? 'Instructor' }} &bull; {{ $pCourse->category?->trans('name') ?? 'Uncategorized' }}</p>
                                    </div>
                                    <form action="{{ route('admin.courses.publish', $pCourse) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 rounded-[4px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] transition-colors whitespace-nowrap cursor-pointer">
                                            {{ __('admin.dashboard.publish') }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($pendingReviews && $pendingReviews->isNotEmpty())
                        <div class="bg-white border border-amber-200/60 rounded-[5px] p-3.5 space-y-2.5">
                            <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                    {{ __('admin.dashboard.reviews_to_moderate') }}
                                </span>
                                <a href="{{ route('admin.reviews.index') }}" class="text-[var(--theme-primary,#344c3d)] hover:underline">{{ __('app.common.view_all') }}</a>
                            </div>
                            @foreach($pendingReviews->take(3) as $pRev)
                                <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100 gap-2">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 truncate">&ldquo;{{ Str::limit($pRev->comment, 40) }}&rdquo;</p>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $pRev->student?->name ?? 'Student' }} on {{ $pRev->course?->trans('title') ?? 'Course' }}</p>
                                    </div>
                                    <form action="{{ route('admin.reviews.approve', $pRev) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 rounded-[4px] bg-[var(--theme-primary,#344c3d)] hover:bg-[var(--theme-primary-hover,#25372c)] text-white font-semibold text-[11px] transition-colors whitespace-nowrap cursor-pointer">
                                            {{ __('admin.dashboard.approve') }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- 2. Dual Grid: Recent Requests & Student Statistics Bar Chart (Figma Dev Mode Node 10060:83458) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <!-- Left: Recent Enrollment Requests (5 cols) -->
            <div class="lg:col-span-5 bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-[#0f172a]">{{ __('admin.dashboard.recent_enrollments') }}</h2>
                        <a href="{{ route('admin.orders.index') }}" class="border border-[#e2e8f0] bg-white hover:bg-slate-50 px-2.5 py-1 text-xs rounded-[5px] font-semibold text-[#0f172a] transition-colors">
                            {{ __('admin.dashboard.all_enrollments') }}
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100 mt-2">
                        @forelse($recentOrders->take(5) as $order)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($order['customer'], 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-semibold text-[#0f172a] truncate">{{ $order['customer'] }}</p>
                                        <p class="text-[11px] text-[#64748b] truncate">{{ $order['course'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-semibold {{ $order['total'] > 0 ? 'bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)]' : 'bg-[#eef9f1] text-[#137547]' }}">
                                        {{ $order['status'] }}
                                    </span>
                                    <a href="{{ route('admin.orders.index') }}" title="View" class="w-7 h-7 rounded-[4px] border border-[#e2e8f0] text-slate-500 hover:text-[var(--theme-primary,#344c3d)] hover:border-[var(--theme-primary,#344c3d)] flex items-center justify-center transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-xs text-slate-400 text-center">{{ __('admin.dashboard.no_recent_orders') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">
                        {{ __('admin.dashboard.view_all_orders') }} &rarr;
                    </a>
                </div>
            </div>

            <!-- Right: Student Statistics Bar Chart (7 cols) (Figma Dev Mode Node 10060:83576) -->
            <div class="lg:col-span-7 bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)] flex flex-col justify-between">
                <div>
                    <!-- Header with Title and View All Button -->
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-[#0f172a]">{!! __('admin.dashboard.student_statistics') !!}</h2>
                        <a href="{{ route('admin.reports.index') }}" class="border border-[#e2e8f0] bg-white hover:bg-slate-50 px-2.5 py-1 text-xs rounded-[5px] font-semibold text-[#0f172a] transition-colors">
                            {{ __('app.common.view_all') }}
                        </a>
                    </div>

                    <!-- Subheader: Total Count & Color Legends (Figma Node 10060:83581) -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-3 pb-2 text-xs">
                        <p class="font-semibold text-sm text-[#0f172a]">
                            {{ __('admin.dashboard.total_no_students') }} <span class="font-bold text-[var(--theme-primary,#344c3d)]">{{ number_format($stats['total_students'] ?? $stats['students'] ?? 0) }}</span>
                        </p>
                        <div class="flex items-center gap-4 text-xs font-medium">
                            <span class="flex items-center gap-1.5 text-[#334155]">
                                <span class="w-2.5 h-2.5 rounded-full bg-[var(--theme-primary,#344c3d)]"></span>
                                {{ __('admin.dashboard.new_students') }}
                            </span>
                            <span class="flex items-center gap-1.5 text-[#334155]">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#bfcfbb]"></span>
                                {{ __('admin.dashboard.total_enrollments') }}
                            </span>
                        </div>
                    </div>

                    <!-- Column Chart Canvas with Y-Axis & Gridlines (Figma Dev Mode Node 10060:83589) -->
                    <div class="mt-2">
                        <!-- Chart Plot Area (Fixed 200px Height) -->
                        <div class="relative h-[200px]">
                            <!-- Y-Axis Labels & Horizontal Dashed Gridlines (100, 80, 60, 40, 20, 0) -->
                            <div class="h-full flex flex-col justify-between select-none">
                                @foreach(['100', '80', '60', '40', '20', '0'] as $yVal)
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-6 text-[11px] font-medium text-[#64748b] text-right shrink-0">{{ $yVal }}</span>
                                        <div class="flex-1 border-b {{ $yVal === '0' ? 'border-[#cbd5e1]' : 'border-dashed border-gray-200' }}"></div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Bar Columns Layer (Overlay scoped to the 200px plot area only, resting on 0 baseline) -->
                            @php
                                $days = [
                                    ['day' => '25 May', 'new' => 30, 'total' => 60],
                                    ['day' => '26 May', 'new' => 38, 'total' => 70],
                                    ['day' => '27 May', 'new' => 75, 'total' => 90],
                                    ['day' => '28 May', 'new' => 32, 'total' => 100],
                                    ['day' => '29 May', 'new' => 28, 'total' => 85],
                                    ['day' => '30 May', 'new' => 52, 'total' => 78],
                                    ['day' => '31 May', 'new' => 45, 'total' => 65],
                                ];
                            @endphp

                            <div class="absolute inset-0 left-8 flex items-end justify-between gap-2 px-2 pb-[1px]">
                                @foreach($days as $bar)
                                    <div class="flex-1 flex flex-col items-center h-full justify-end group cursor-pointer relative">
                                        <!-- Bar Graphic -->
                                        <div class="w-full max-w-[28px] sm:max-w-[34px] h-full flex items-end justify-center relative">
                                            <!-- Sage Mint Background Bar (Total) -->
                                            <div class="w-full bg-[#bfcfbb] rounded-t-[5px] transition-all duration-300 group-hover:bg-[#aebd9d] relative flex items-end justify-center" style="height: {{ $bar['total'] }}%;">
                                                <!-- Vibrant Evergreen Foreground Bar (New) -->
                                                <div class="w-full bg-[var(--theme-primary,#344c3d)] rounded-t-[5px] transition-all duration-300 group-hover:bg-[var(--theme-primary-hover,#25372c)]" style="height: {{ min(100, round(($bar['new'] / $bar['total']) * 100)) }}%;"></div>
                                            </div>
                                        </div>

                                        <!-- Interactive Tooltip -->
                                        <div class="absolute -top-7 bg-slate-900 text-white text-[10px] font-semibold px-2 py-0.5 rounded shadow-md opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-20">
                                            New: {{ $bar['new'] }} | Total: {{ $bar['total'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- X-Axis Date Labels (Outside and below the 200px plot area) -->
                        <div class="flex justify-between pl-8 px-2 pt-2.5 text-[11px] sm:text-[12px] text-[#64748b] dark:text-neutral-400 font-medium select-none">
                            @foreach($days as $bar)
                                <span class="flex-1 text-center truncate">{{ $bar['day'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Mini Metric Badges (Fills vertical void, aligns card height with left column) -->
                <div class="grid grid-cols-3 gap-3 mt-4 pt-3.5 border-t border-gray-100 dark:border-[#1f1f1f]">
                    <div class="bg-[#f8fafc] dark:bg-[#121212] border border-[#e2e8f0] dark:border-[#222222] rounded-[5px] p-2.5 text-center">
                        <p class="text-[11px] text-[#64748b] dark:text-neutral-400 font-medium truncate">{{ __('admin.dashboard.weekly_growth') }}</p>
                        <p class="text-[14px] font-bold text-[#09800f] dark:text-emerald-400 mt-0.5 flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                            +14.6%
                        </p>
                    </div>
                    <div class="bg-[#f8fafc] dark:bg-[#121212] border border-[#e2e8f0] dark:border-[#222222] rounded-[5px] p-2.5 text-center">
                        <p class="text-[11px] text-[#64748b] dark:text-neutral-400 font-medium truncate">{{ __('admin.dashboard.daily_active') }}</p>
                        <p class="text-[14px] font-bold text-[#0f172a] dark:text-white mt-0.5">
                            412 <span class="text-[10px] font-normal text-[#64748b] dark:text-neutral-400">avg</span>
                        </p>
                    </div>
                    <div class="bg-[#f8fafc] dark:bg-[#121212] border border-[#e2e8f0] dark:border-[#222222] rounded-[5px] p-2.5 text-center">
                        <p class="text-[11px] text-[#64748b] dark:text-neutral-400 font-medium truncate">{{ __('admin.dashboard.completion_rate') }}</p>
                        <p class="text-[14px] font-bold text-[var(--theme-primary,#344c3d)] dark:text-[#86efac] mt-0.5">
                            88.4%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Quick Action Hub Cards (Figma Dev Mode style + Media Library) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3.5">
            <!-- 1. All Students -->
            <a href="{{ route('admin.students.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[var(--theme-primary,#344c3d)]/10 dark:bg-[var(--theme-primary,#344c3d)]/25 w-full py-3 rounded-[5px] text-[var(--theme-primary,#344c3d)] dark:text-[#86efac] flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[var(--theme-primary,#344c3d)] dark:group-hover:text-[#86efac] mt-2.5 truncate w-full">{{ __('admin.nav.items.students') }}</p>
            </a>

            <!-- 2. Instructors -->
            <a href="{{ route('admin.instructors.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[#eef9f1] dark:bg-emerald-950/30 w-full py-3 rounded-[5px] text-[#137547] dark:text-emerald-400 flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[#137547] dark:group-hover:text-emerald-400 mt-2.5 truncate w-full">{{ __('admin.nav.items.instructors') }}</p>
            </a>

            <!-- 3. All Courses -->
            <a href="{{ route('admin.courses.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[#fff8ef] dark:bg-amber-950/30 w-full py-3 rounded-[5px] text-[#d97706] dark:text-amber-400 flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[#d97706] dark:group-hover:text-amber-400 mt-2.5 truncate w-full">{{ __('admin.nav.items.courses') }}</p>
            </a>

            <!-- 4. Categories -->
            <a href="{{ route('admin.categories.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[#f1ebf7] dark:bg-purple-950/30 w-full py-3 rounded-[5px] text-[#7c3aed] dark:text-purple-400 flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[#7c3aed] dark:group-hover:text-purple-400 mt-2.5 truncate w-full">{{ __('admin.nav.items.categories') }}</p>
            </a>

            <!-- 5. Media Library (User requested) -->
            <a href="{{ route('admin.media.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[#e0f2fe] dark:bg-sky-950/30 w-full py-3 rounded-[5px] text-[#0284c7] dark:text-sky-400 flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[#0284c7] dark:group-hover:text-sky-400 mt-2.5 truncate w-full">{{ __('admin.nav.items.media') }}</p>
            </a>

            <!-- 6. All Orders -->
            <a href="{{ route('admin.orders.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[#fbecea] dark:bg-rose-950/30 w-full py-3 rounded-[5px] text-[#dc2626] dark:text-rose-400 flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[#dc2626] dark:group-hover:text-rose-400 mt-2.5 truncate w-full">{{ __('admin.nav.items.orders') }}</p>
            </a>

            <!-- 7. Live Events -->
            <a href="{{ route('admin.events.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[#fdf4ff] dark:bg-fuchsia-950/30 w-full py-3 rounded-[5px] text-[#c026d3] dark:text-fuchsia-400 flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[#c026d3] dark:group-hover:text-fuchsia-400 mt-2.5 truncate w-full">{{ __('admin.nav.items.events') }}</p>
            </a>

            <!-- 8. Certificates -->
            <a href="{{ route('admin.certificates.index') }}" class="bg-white dark:bg-[#0d0d0d] border border-[#e2e8f0] dark:border-[#1f1f1f] rounded-[5px] p-3.5 text-center shadow-[0px_4px_7px_rgba(238,238,238,0.25)] dark:shadow-none hover:shadow-md transition-all group flex flex-col items-center">
                <div class="bg-[#ebf2f1] dark:bg-teal-950/30 w-full py-3 rounded-[5px] text-[#0d9488] dark:text-teal-400 flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <p class="font-semibold text-xs text-[#0f172a] dark:text-neutral-200 group-hover:text-[#0d9488] dark:group-hover:text-teal-400 mt-2.5 truncate w-full">{{ __('admin.nav.items.certificates') }}</p>
            </a>
        </div>

        <!-- 4. Three Activity Columns: Reports, Visits Radial Gauge, Top Instructors (Figma Dev Mode Node 10060:83691) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <!-- Col 1: Patient / Course Reports (4 cols) -->
            <div class="lg:col-span-4 bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-[#0f172a]">{{ __('admin.dashboard.course_reports') }}</h2>
                        <a href="{{ route('admin.reports.index') }}" class="text-[11px] font-semibold text-[#64748b] hover:text-[var(--theme-primary,#344c3d)]">{{ __('app.common.view_all') }}</a>
                    </div>
                    <div class="divide-y divide-gray-100 mt-2">
                        @foreach($coursesByCategory->take(5) as $cat)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-semibold text-[#0f172a] truncate">{{ $cat->name }}</p>
                                        <p class="text-[11px] text-[#64748b]">{{ __('admin.dashboard.active_courses', ['count' => $cat->courses_count]) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.courses.index', ['category' => $cat->slug]) }}" class="w-7 h-7 rounded-[4px] border border-[#e2e8f0] text-slate-500 hover:text-[var(--theme-primary,#344c3d)] hover:border-[var(--theme-primary,#344c3d)] flex items-center justify-center transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.categories.index') }}" class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">{{ __('admin.dashboard.manage_categories') }} &rarr;</a>
                </div>
            </div>

            <!-- Col 2: Student Visits Radial Gauge (4 cols) (Figma Dev Mode Node 10060:83759) -->
            <div class="lg:col-span-4 bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-[#0f172a]">{{ __('admin.dashboard.student_visits') }}</h2>
                        <a href="{{ route('admin.reports.index') }}" class="border border-[#e2e8f0] bg-white hover:bg-slate-50 px-2.5 py-1 text-xs rounded-[5px] font-semibold text-[#0f172a] transition-colors">{{ __('app.common.view_all') }}</a>
                    </div>

                    <!-- Dual-Track Segmented Radial Gauge Chart (Figma Dev Mode Node 10060:83765) -->
                    <div class="pt-4 pb-1 flex flex-col items-center justify-center">
                        <div class="relative w-[220px] h-[175px] mx-auto flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/chart-patient-visits.svg') }}" alt="Student Visits Radial Gauge" class="w-full h-full object-contain pointer-events-none select-none">
                            <!-- Center Label Inside the Dual-Track Arch (Figma Node 10060:83894) -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none pt-1">
                                <span class="text-[13px] text-[#334155] font-normal leading-tight">{{ __('admin.dashboard.all_students') }}</span>
                                <span class="text-[25px] font-bold text-[#0f172a] leading-none mt-1">80%</span>
                            </div>
                        </div>

                        <!-- Demographic Breakdown (Figma Dev Mode Node 10060:83868) -->
                        <div class="flex flex-col gap-3.5 w-full mt-4 pt-3.5 border-t border-gray-100">
                            <!-- Male (Primary Outer Track: 69%) -->
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-[var(--theme-primary,#344c3d)] flex items-center justify-center p-2 text-white shrink-0 shadow-xs">
                                        <img src="{{ asset('images/icon-male.svg') }}" alt="Male" class="w-4 h-4 pointer-events-none">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-sm text-[#0f172a] leading-tight">{{ __('admin.dashboard.male') }}</p>
                                        <p class="text-[12px] leading-tight mt-0.5">
                                            <span class="text-[#09800f] font-semibold">-15%</span>
                                            <span class="text-[#334155]">{{ __('admin.dashboard.since_last_week') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-[16px] text-[#0f172a] shrink-0">69%</span>
                            </div>

                            <!-- Female (Purple Inner Track: 56%) -->
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-[#6a1b9a] flex items-center justify-center p-2 text-white shrink-0 shadow-xs">
                                        <img src="{{ asset('images/icon-female.svg') }}" alt="Female" class="w-4 h-4 pointer-events-none">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-sm text-[#0f172a] leading-tight">{{ __('admin.dashboard.female') }}</p>
                                        <p class="text-[12px] leading-tight mt-0.5">
                                            <span class="text-[#09800f] font-semibold">-15%</span>
                                            <span class="text-[#334155]">{{ __('admin.dashboard.since_last_week') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-[16px] text-[#0f172a] shrink-0">56%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-3 text-center text-xs text-[#64748b]">
                    <span>Learner retention rate remains high across demographics</span>
                </div>
            </div>

            <!-- Col 3: Top Instructors (4 cols) -->
            <div class="lg:col-span-4 bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-[#0f172a]">{{ __('admin.dashboard.top_instructors') }}</h2>
                        <a href="{{ route('admin.instructors.index') }}" class="text-[11px] font-semibold text-[#64748b] hover:text-[var(--theme-primary,#344c3d)]">{{ __('app.common.view_all') }}</a>
                    </div>
                    <div class="divide-y divide-gray-100 mt-2">
                        @foreach(\App\Models\Instructor::take(5)->get() as $inst)
                            <div class="py-2.5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    @if($inst->avatarUrl())
                                        <img src="{{ $inst->avatarUrl() }}" alt="{{ $inst->name }}" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-gray-200" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                        <div class="w-8 h-8 rounded-full bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs items-center justify-center shrink-0 hidden">
                                            {{ strtoupper(substr($inst->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-[var(--theme-primary,#344c3d)] text-white font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($inst->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-semibold text-[#0f172a] truncate">{{ $inst->name }}</p>
                                        <p class="text-[11px] text-[#64748b] truncate">{{ $inst->designation ?? 'Senior Instructor' }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[5px] text-[10px] font-semibold bg-[#eef9f1] text-[#137547] shrink-0">
                                    Active
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.instructors.index') }}" class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">{{ __('admin.dashboard.manage_instructors') }} &rarr;</a>
                </div>
            </div>
        </div>

        <!-- 5. Two Columns: Category Donut Breakdown & Course Records (Figma Dev Mode Node 10060:83958) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <!-- Left: Top Categories Donut Chart (4 cols) -->
            <div class="lg:col-span-4 bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-[#0f172a]">{{ __('admin.dashboard.courses_by_category') }}</h2>
                        <a href="{{ route('admin.categories.index') }}" class="text-[11px] font-semibold text-[#64748b] hover:text-[var(--theme-primary,#344c3d)]">{{ __('admin.dashboard.manage_categories') }}</a>
                    </div>

                    <!-- Donut Chart with Center Text -->
                    <div class="py-4 flex flex-col items-center justify-center">
                        <div class="relative w-36 h-36 flex items-center justify-center">
                            <svg class="w-36 h-36 -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="38" stroke="var(--theme-primary,#344c3d)" stroke-width="12" fill="transparent" stroke-dasharray="70 240"/>
                                <circle cx="50" cy="50" r="38" stroke="#0d9488" stroke-width="12" fill="transparent" stroke-dasharray="50 240" stroke-dashoffset="-70"/>
                                <circle cx="50" cy="50" r="38" stroke="#7c3aed" stroke-width="12" fill="transparent" stroke-dasharray="45 240" stroke-dashoffset="-120"/>
                                <circle cx="50" cy="50" r="38" stroke="#f97316" stroke-width="12" fill="transparent" stroke-dasharray="40 240" stroke-dashoffset="-165"/>
                                <circle cx="50" cy="50" r="38" stroke="#eab308" stroke-width="12" fill="transparent" stroke-dasharray="35 240" stroke-dashoffset="-205"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-[10px] text-[#64748b] font-medium leading-none">{{ __('admin.dashboard.total_courses') }}</span>
                                <span class="text-lg font-bold text-[#0f172a] mt-0.5">{{ $stats['total_courses'] ?? $stats['courses'] ?? 0 }}</span>
                            </div>
                        </div>

                        <!-- Legend Items (Figma style) -->
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 w-full mt-4 text-[11px] text-[#334155]">
                            @php
                                $colors = [\App\Models\Setting::get('theme_primary', '#344c3d'), '#0d9488', '#7c3aed', '#f97316', '#eab308'];
                            @endphp
                            @foreach($coursesByCategory->take(5) as $idx => $cat)
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $colors[$idx % count($colors)] }}"></span>
                                    <span class="truncate">{{ $cat->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Bottom Stats Ribbon -->
                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100 text-xs">
                    <div>
                        <p class="text-base font-bold text-[#0f172a]">${{ number_format($stats['revenue_this_month'] ?? 0, 2) }}</p>
                        <p class="text-[10px] text-[#64748b]">{{ __('admin.dashboard.revenue_generated') }}</p>
                    </div>
                    <div>
                        <p class="text-base font-bold text-[#0f172a]">{{ $stats['enrollments_count'] ?? $stats['enrollments'] ?? 3125 }}+</p>
                        <p class="text-[10px] text-[#64748b]">{{ __('admin.dashboard.total_enrollments') }}</p>
                    </div>
                </div>
            </div>

            <!-- Right: Course Performance Table (8 cols) -->
            <div class="lg:col-span-8 bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-[#0f172a]">{{ __('admin.dashboard.top_courses_perf') }}</h2>
                        <a href="{{ route('admin.courses.index') }}" class="text-[11px] font-semibold text-[#64748b] hover:text-[var(--theme-primary,#344c3d)]">{{ __('app.common.view_all') }}</a>
                    </div>

                    <div class="overflow-x-auto mt-2">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#f8fafc] text-[#64748b] uppercase text-[10px] font-bold tracking-wider border-b border-[#e2e8f0]">
                                <tr>
                                    <th class="py-2.5 px-3">{{ __('admin.dashboard.course_name') }}</th>
                                    <th class="py-2.5 px-3">{{ __('admin.dashboard.instructor') }}</th>
                                    <th class="py-2.5 px-3">{{ __('admin.dashboard.category') }}</th>
                                    <th class="py-2.5 px-3">{{ __('admin.dashboard.price') }}</th>
                                    <th class="py-2.5 px-3 text-right">{{ __('admin.dashboard.students') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($topCourses->take(5) as $course)
                                    <tr class="hover:bg-[#f8fafc]/80 transition-colors">
                                        <td class="py-3 px-3 font-semibold text-[#0f172a] max-w-[200px] truncate">
                                            {{ $course->trans('title') }}
                                        </td>
                                        <td class="py-3 px-3 text-[#64748b]">
                                            {{ $course->instructor?->name ?? 'Instructor' }}
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-semibold bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)]">
                                                {{ $course->category?->trans('name') ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 font-semibold text-[#0f172a]">
                                            ${{ number_format($course->price, 2) }}
                                        </td>
                                        <td class="py-3 px-3 text-right font-bold text-[var(--theme-primary,#344c3d)]">
                                            {{ $course->enrollments_count }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-slate-400">{{ __('admin.dashboard.no_course_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-[var(--theme-primary,#344c3d)] hover:underline">{{ __('admin.dashboard.manage_all_courses') }} &rarr;</a>
                </div>
            </div>
        </div>

        <!-- 6. Full-Width Latest Enrollments & Orders Table (Figma Dev Mode Node 10060:84086) -->
        <div class="bg-white border border-[#e2e8f0] rounded-[5px] p-5 shadow-[0px_4px_7px_rgba(238,238,238,0.25)]">
            <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                <h2 class="text-sm font-bold text-[#0f172a]">{{ __('admin.dashboard.recent_orders_enrollments') }}</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-semibold text-[#64748b] hover:text-[var(--theme-primary,#344c3d)]">{{ __('app.common.view_all') }}</a>
            </div>

            <div class="overflow-x-auto mt-3">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f8fafc] text-[#64748b] uppercase text-[10px] font-bold tracking-wider border-b border-[#e2e8f0]">
                        <tr>
                            <th class="py-3 px-3.5">{{ __('admin.dashboard.order_id') }}</th>
                            <th class="py-3 px-3.5">{{ __('admin.dashboard.student_name') }}</th>
                            <th class="py-3 px-3.5">{{ __('admin.dashboard.course_program') }}</th>
                            <th class="py-3 px-3.5">{{ __('admin.dashboard.amount') }}</th>
                            <th class="py-3 px-3.5">{{ __('admin.dashboard.status') }}</th>
                            <th class="py-3 px-3.5">{{ __('admin.dashboard.date_time') }}</th>
                            <th class="py-3 px-3.5 text-right">{{ __('admin.dashboard.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-[#f8fafc]/80 transition-colors">
                                <td class="py-3 px-3.5 font-bold text-[var(--theme-primary,#344c3d)]">
                                    {{ $order['code'] }}
                                </td>
                                <td class="py-3 px-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)] font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($order['customer'], 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-[#0f172a]">{{ $order['customer'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3.5 text-[#334155] max-w-[220px] truncate">
                                    {{ $order['course'] }}
                                </td>
                                <td class="py-3 px-3.5 font-semibold text-[#0f172a]">
                                    ${{ number_format($order['total'], 2) }}
                                </td>
                                <td class="py-3 px-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[4px] text-[10px] font-semibold {{ $order['total'] > 0 ? 'bg-[#eef9f1] text-[#137547]' : 'bg-[var(--theme-primary,#344c3d)]/10 text-[var(--theme-primary,#344c3d)]' }}">
                                        {{ $order['status'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3.5 text-[#64748b]">
                                    {{ $order['date'] }}
                                </td>
                                <td class="py-3 px-3.5 text-right">
                                    <a href="{{ route('admin.orders.index') }}" title="Details" class="inline-flex p-1 rounded hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-slate-400">{{ __('admin.dashboard.no_recent_orders') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-layouts.dashboard>
