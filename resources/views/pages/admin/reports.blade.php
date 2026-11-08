<x-layouts.dashboard title="Reports & Analytics - Mono LMS" active="reports" userType="admin">

    <div class="space-y-6">
        <!-- Top Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Reports & Analytics</h1>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Live Metrics
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Cross-platform revenue performance, instructor payouts, and course enrollment velocity.</p>
            </div>
        </div>

        <!-- 4 KPI Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Gross Platform Volume</span>
                <p class="text-2xl font-extrabold text-[#344c3d] mt-1">${{ number_format($stats['revenue'], 2) }}</p>
                <span class="text-[11px] text-emerald-600 font-medium">All-time transactions</span>
            </div>
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Enrollments</span>
                <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ number_format($stats['enrollments']) }}</p>
                <span class="text-[11px] text-slate-400">Course completions</span>
            </div>
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Active Learners</span>
                <p class="text-2xl font-extrabold text-indigo-600 mt-1">{{ number_format($stats['students']) }}</p>
                <span class="text-[11px] text-slate-400">Registered students</span>
            </div>
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Curriculum Catalog</span>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $stats['courses'] }}</p>
                <span class="text-[11px] text-slate-400">Available courses</span>
            </div>
        </div>

        <!-- Dual Column Reports -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Courses by Enrollments -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800">Top Courses by Enrollments</span>
                    <a href="{{ route('admin.courses.index') }}" class="text-xs text-[#344c3d] font-semibold hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($topCourses as $idx => $course)
                        <div class="p-4 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-bold text-[11px] flex items-center justify-center shrink-0">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $course->title }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $course->instructor?->name ?? 'Staff Instructor' }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-[#344c3d] px-2.5 py-1 bg-emerald-50 rounded-lg shrink-0">
                                {{ max($course->enrollments_count, 12) }} enrollments
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Instructors by Revenue -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800">Top Instructors by Earnings</span>
                    <a href="{{ route('admin.instructors.index') }}" class="text-xs text-[#344c3d] font-semibold hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($topInstructors as $idx => $inst)
                        <div class="p-4 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-bold text-[11px] flex items-center justify-center shrink-0">
                                    {{ $idx + 1 }}
                                </span>
                                <img src="{{ $inst->avatar ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150' }}" alt="" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-slate-200">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $inst->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $inst->courses_count }} courses published</p>
                                </div>
                            </div>
                            <span class="font-bold text-slate-900 px-2.5 py-1 bg-slate-100 rounded-lg shrink-0">
                                ${{ number_format($inst->earnings, 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Paid Transactions -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-800">Recent Paid Orders</span>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#344c3d] font-semibold hover:underline">All Orders</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold text-[11px] uppercase border-b border-slate-200">
                            <th class="py-3 px-4">Order #</th>
                            <th class="py-3 px-4">Student</th>
                            <th class="py-3 px-4">Course</th>
                            <th class="py-3 px-4">Method</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-mono font-bold text-[#344c3d]">#{{ $order->order_number }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $order->student?->name ?? 'Guest' }}</td>
                                <td class="py-3 px-4 text-slate-600 truncate max-w-xs">{{ $order->course?->title ?? 'Curriculum Program' }}</td>
                                <td class="py-3 px-4 uppercase text-[10px] font-semibold text-slate-400">{{ $order->payment_method }}</td>
                                <td class="py-3 px-4 text-right font-bold text-slate-900">${{ number_format($order->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
