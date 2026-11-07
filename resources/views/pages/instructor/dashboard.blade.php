<x-layouts.dashboard title="Instructor Dashboard - Dreams LMS" active="dashboard" userType="instructor">

    <div class="space-y-8">
        <!-- 1. Metric Stat Cards (Figma: 5013:76334) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#6d6d6d]">Total Revenue</span>
                    <span class="p-2 rounded-lg bg-emerald-50 text-[#03c95a]">💵</span>
                </div>
                <div class="text-2xl font-extrabold text-[#191919]">$18,420</div>
                <div class="text-[11px] text-emerald-600 mt-1 font-medium">+14.2% from last month</div>
            </div>

            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#6d6d6d]">Enrolled Students</span>
                    <span class="p-2 rounded-lg bg-purple-50 text-[#392c7d]">👥</span>
                </div>
                <div class="text-2xl font-extrabold text-[#191919]">12,450</div>
                <div class="text-[11px] text-[#392c7d] mt-1 font-medium">+320 new this week</div>
            </div>

            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#6d6d6d]">Active Courses</span>
                    <span class="p-2 rounded-lg bg-rose-50 text-[#ff4667]">📚</span>
                </div>
                <div class="text-2xl font-extrabold text-[#191919]">12</div>
                <div class="text-[11px] text-[#ff4667] mt-1 font-medium">All published & live</div>
            </div>

            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#6d6d6d]">Course Rating</span>
                    <span class="p-2 rounded-lg bg-amber-50 text-[#ffc107]">★</span>
                </div>
                <div class="text-2xl font-extrabold text-[#191919]">4.9 / 5.0</div>
                <div class="text-[11px] text-gray-500 mt-1">Based on 1,840 reviews</div>
            </div>
        </div>

        <!-- 2. Revenue Graph Simulation & Upcoming Sessions -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-base text-[#191919]">Earnings Overview</h3>
                    <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1 text-gray-600 focus:outline-none">
                        <option>This Year (2026)</option>
                        <option>Previous Year</option>
                    </select>
                </div>
                <!-- CSS Simulated Bar Graph -->
                <div class="h-56 flex items-end justify-between gap-3 pt-8 pb-2 px-4 border-b border-gray-100">
                    @php
                        $bars = [
                            ['m' => 'Jan', 'h' => 45],
                            ['m' => 'Feb', 'h' => 60],
                            ['m' => 'Mar', 'h' => 55],
                            ['m' => 'Apr', 'h' => 75],
                            ['m' => 'May', 'h' => 90],
                            ['m' => 'Jun', 'h' => 85],
                            ['m' => 'Jul', 'h' => 70],
                            ['m' => 'Aug', 'h' => 95],
                            ['m' => 'Sep', 'h' => 80],
                            ['m' => 'Oct', 'h' => 100],
                            ['m' => 'Nov', 'h' => 88],
                            ['m' => 'Dec', 'h' => 65],
                        ];
                    @endphp
                    @foreach($bars as $bar)
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                            <div class="w-full max-w-[28px] bg-gradient-to-t from-[#392c7d] to-[#ff4667] rounded-t-md transition-all hover:opacity-80" style="height: {{ $bar['h'] }}%;"></div>
                            <span class="text-[10px] text-gray-400">{{ $bar['m'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-4 bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-4">
                <h3 class="font-bold text-base text-[#191919]">Upcoming Live Q&A</h3>
                <div class="space-y-3">
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold text-[#ff4667] uppercase">Tomorrow • 14:00 GMT</span>
                        <div class="font-bold text-xs text-[#191919]">Python Asyncio Deep Dive</div>
                        <div class="text-[11px] text-gray-500">45 Students Registered</div>
                    </div>
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold text-[#392c7d] uppercase">Friday • 17:30 GMT</span>
                        <div class="font-bold text-xs text-[#191919]">Laravel 12 Microservices Architecture</div>
                        <div class="text-[11px] text-gray-500">82 Students Registered</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Top Performing Courses Table -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-base text-[#191919]">Top Performing Courses</h3>
                <a href="{{ route('instructor.courses') }}" class="text-xs font-bold text-[#ff4667] hover:underline">Manage All Courses →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-[#888888]">
                            <th class="pb-3 font-semibold">COURSE</th>
                            <th class="pb-3 font-semibold">PRICE</th>
                            <th class="pb-3 font-semibold">STUDENTS</th>
                            <th class="pb-3 font-semibold">REVENUE</th>
                            <th class="pb-3 font-semibold">RATING</th>
                            <th class="pb-3 font-semibold text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[#191919]">
                        <tr>
                            <td class="py-3 font-semibold">Complete Python Bootcamp From Zero to Hero</td>
                            <td class="py-3 font-bold">$150</td>
                            <td class="py-3 text-gray-600">4,280</td>
                            <td class="py-3 font-bold text-emerald-600">$8,540</td>
                            <td class="py-3 font-bold text-[#ffc107]">★ 4.8</td>
                            <td class="py-3 text-right"><a href="{{ route('courses.detail') }}" class="text-[#392c7d] font-semibold hover:underline">View</a></td>
                        </tr>
                        <tr>
                            <td class="py-3 font-semibold">Laravel 12 API Development and Testing Masterclass</td>
                            <td class="py-3 font-bold">$145</td>
                            <td class="py-3 text-gray-600">3,120</td>
                            <td class="py-3 font-bold text-emerald-600">$6,210</td>
                            <td class="py-3 font-bold text-[#ffc107]">★ 4.9</td>
                            <td class="py-3 text-right"><a href="{{ route('courses.detail') }}" class="text-[#392c7d] font-semibold hover:underline">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
