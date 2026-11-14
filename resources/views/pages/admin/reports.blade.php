<x-layouts.dashboard title="Reports & Analytics - Mono LMS" active="reports" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Reports & Analytics</h1>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.12em] bg-emerald-50 text-emerald-800 border border-emerald-200">
                        Live Metrics
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Cross-platform revenue performance, instructor payouts, and course enrollment velocity.</p>
            </div>
        </div>

        <!-- 4 KPI Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200 p-4 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Gross Platform Volume</span>
                <p class="text-2xl font-extrabold text-[#344c3d] font-mono mt-1">${{ number_format($stats['revenue'], 2) }}</p>
                <span class="text-[11px] text-emerald-600 font-medium">All-time transactions</span>
            </div>
            <div class="bg-white border border-slate-200 p-4 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Total Enrollments</span>
                <p class="text-2xl font-extrabold text-blue-600 font-mono mt-1">{{ number_format($stats['enrollments']) }}</p>
                <span class="text-[11px] text-slate-400">Course completions</span>
            </div>
            <div class="bg-white border border-slate-200 p-4 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Active Learners</span>
                <p class="text-2xl font-extrabold text-indigo-600 font-mono mt-1">{{ number_format($stats['students']) }}</p>
                <span class="text-[11px] text-slate-400">Registered students</span>
            </div>
            <div class="bg-white border border-slate-200 p-4 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Curriculum Catalog</span>
                <p class="text-2xl font-extrabold text-slate-800 font-mono mt-1">{{ $stats['courses'] }}</p>
                <span class="text-[11px] text-slate-400">Available courses</span>
            </div>
        </div>

        <!-- Dual Column Reports -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Courses by Enrollments -->
            <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Top Courses by Enrollments</span>
                    <a href="{{ route('admin.courses.index') }}" class="action-btn action-btn-ghost text-xs">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($topCourses as $idx => $course)
                        <div class="p-4 flex items-center justify-between gap-3 text-xs hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 h-6 bg-slate-100 text-slate-600 font-bold font-mono text-[11px] flex items-center justify-center shrink-0 border border-slate-200">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $course->trans('title') }}</p>
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $course->instructor?->name ?? 'Staff Instructor' }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-[#344c3d] px-2.5 py-1 bg-emerald-50 border border-emerald-200 shrink-0 font-mono">
                                {{ $course->enrollments_count }} enrollments
                            </span>
                        </div>
                    @endforeach
                    @if($topCourses->isEmpty())
                        <div class="p-6 text-center text-xs text-slate-400">No enrollments yet — course rankings appear as students enroll.</div>
                    @endif
                </div>
            </div>

            <!-- Top Instructors by Revenue -->
            <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Top Instructors by Earnings</span>
                    <a href="{{ route('admin.instructors.index') }}" class="action-btn action-btn-ghost text-xs">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($topInstructors as $idx => $inst)
                        <div class="p-4 flex items-center justify-between gap-3 text-xs hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 h-6 bg-slate-100 text-slate-600 font-bold font-mono text-[11px] flex items-center justify-center shrink-0 border border-slate-200">
                                    {{ $idx + 1 }}
                                </span>
                                <img src="{{ $inst->avatarUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($inst->name).'&background=344C3D&color=FFFFFF' }}" alt="{{ $inst->name }}" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-slate-200" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($inst->name) }}&background=344C3D&color=FFFFFF';">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $inst->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $inst->courses_count }} courses published</p>
                                </div>
                            </div>
                            <span class="font-bold text-slate-900 px-2.5 py-1 bg-slate-100 border border-slate-200 shrink-0 font-mono">
                                ${{ number_format($inst->earnings, 2) }}
                            </span>
                        </div>
                    @endforeach
                    @if($topInstructors->isEmpty())
                        <div class="p-6 text-center text-xs text-slate-400">No instructors yet — earnings appear once instructors publish courses.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Paid Transactions -->
        <div class="bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Recent Paid Orders</span>
                <a href="{{ route('admin.orders.index') }}" class="action-btn action-btn-ghost text-xs">All Orders</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-[0.14em] border-b border-slate-200">
                            <th class="py-3 px-4">Order #</th>
                            <th class="py-3 px-4">Student</th>
                            <th class="py-3 px-4">Course</th>
                            <th class="py-3 px-4">Method</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-[#344c3d]">#{{ $order->order_number }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $order->student?->name ?? 'Guest' }}</td>
                                <td class="py-3 px-4 text-slate-600 truncate max-w-xs">{{ $order->course?->title ?? 'Curriculum Program' }}</td>
                                <td class="py-3 px-4 uppercase text-[10px] font-mono text-slate-500">{{ $order->payment_method }}</td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">${{ number_format($order->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        @if($recentOrders->isEmpty())
                            <tr><td colspan="5" class="py-10 text-center text-xs text-slate-400">No paid orders yet — transactions appear here after the first successful checkout.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>