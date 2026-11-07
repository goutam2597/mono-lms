<x-layouts.dashboard title="Student Dashboard - Dreams LMS" active="dashboard" userType="student">

    <div class="space-y-8">
        <!-- 1. Quiz Alert Banner (Figma: 5013:75559) -->
        <div class="bg-gradient-to-r from-[#ff4667] to-[#ff6b84] rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative overflow-hidden">
            <div class="relative z-10 space-y-1">
                <span class="bg-white/20 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">In Progress</span>
                <h3 class="text-lg sm:text-xl font-bold">Quiz : Build Responsive Real World Websites</h3>
                <p class="text-xs text-white/90">Questions Answered: <strong class="font-extrabold text-white">15 / 22</strong> (Estimated time remaining: 10 mins)</p>
            </div>
            <a href="{{ route('student.quiz-attempts') }}" class="relative z-10 bg-white text-[#ff4667] hover:bg-gray-100 font-bold text-xs sm:text-sm px-6 py-2.5 rounded-full transition-all shadow-sm shrink-0">
                Continue Quiz →
            </a>
        </div>

        <!-- 2. Metric Stat Cards (Figma: 5013:75572) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-purple-50 text-[#392c7d] flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-[#191919]">12</div>
                    <div class="text-xs font-medium text-[#6d6d6d]">Enrolled Courses</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-rose-50 text-[#ff4667] flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-[#191919]">02</div>
                    <div class="text-xs font-medium text-[#6d6d6d]">Active In Progress</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white border border-[#e7e7e7] rounded-xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-emerald-50 text-[#03c95a] flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-[#191919]">10</div>
                    <div class="text-xs font-medium text-[#6d6d6d]">Completed Courses</div>
                </div>
            </div>
        </div>

        <!-- 3. Recently Enrolled Courses (Figma: 5013:75592) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-lg text-[#191919]">Recently Enrolled Courses</h3>
                <a href="{{ route('student.enrolled-courses') }}" class="text-xs font-bold text-[#ff4667] hover:underline">View All →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Enrolled Course 1 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="relative h-36 rounded-lg overflow-hidden mb-3 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                        </div>
                        <span class="bg-[#f4f6f9] text-[11px] font-semibold px-2 py-0.5 rounded-full text-[#191919]">Design</span>
                        <h4 class="font-bold text-sm text-[#191919] mt-1 line-clamp-2">Information About UI/UX Design Degree</h4>
                        
                        <!-- Progress bar -->
                        <div class="mt-4 space-y-1">
                            <div class="flex items-center justify-between text-[11px] text-[#6d6d6d]">
                                <span>Progress</span>
                                <strong class="text-[#ff4667]">65%</strong>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#ff4667] rounded-full" style="width: 65%;"></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('courses.detail') }}" class="mt-4 block text-center bg-[#f4f6f9] hover:bg-[#392c7d] hover:text-white text-xs font-bold py-2 rounded-lg transition-colors">
                        Continue Learning
                    </a>
                </div>

                <!-- Enrolled Course 2 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="relative h-36 rounded-lg overflow-hidden mb-3 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                        </div>
                        <span class="bg-[#f4f6f9] text-[11px] font-semibold px-2 py-0.5 rounded-full text-[#191919]">Python</span>
                        <h4 class="font-bold text-sm text-[#191919] mt-1 line-clamp-2">Complete Python Bootcamp From Zero to Hero</h4>
                        
                        <div class="mt-4 space-y-1">
                            <div class="flex items-center justify-between text-[11px] text-[#6d6d6d]">
                                <span>Progress</span>
                                <strong class="text-[#ff4667]">25%</strong>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#ff4667] rounded-full" style="width: 25%;"></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('courses.detail') }}" class="mt-4 block text-center bg-[#f4f6f9] hover:bg-[#392c7d] hover:text-white text-xs font-bold py-2 rounded-lg transition-colors">
                        Continue Learning
                    </a>
                </div>

                <!-- Enrolled Course 3 -->
                <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="relative h-36 rounded-lg overflow-hidden mb-3 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&auto=format&fit=crop&q=80" class="w-full h-full object-cover">
                        </div>
                        <span class="bg-[#f4f6f9] text-[11px] font-semibold px-2 py-0.5 rounded-full text-[#191919]">Business</span>
                        <h4 class="font-bold text-sm text-[#191919] mt-1 line-clamp-2">The Complete Business and Management Masterclass</h4>
                        
                        <div class="mt-4 space-y-1">
                            <div class="flex items-center justify-between text-[11px] text-[#6d6d6d]">
                                <span>Progress</span>
                                <strong class="text-[#03c95a]">100% Completed</strong>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#03c95a] rounded-full" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('student.certificates') }}" class="mt-4 block text-center bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 text-xs font-bold py-2 rounded-lg transition-colors">
                        Download Certificate 🏆
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Recent Invoices Table (Figma: 5013:75602) -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs space-y-4">
            <h3 class="font-bold text-lg text-[#191919]">Recent Invoices</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-[#888888]">
                            <th class="pb-3 font-semibold">ORDER ID</th>
                            <th class="pb-3 font-semibold">COURSE</th>
                            <th class="pb-3 font-semibold">DATE</th>
                            <th class="pb-3 font-semibold">AMOUNT</th>
                            <th class="pb-3 font-semibold">STATUS</th>
                            <th class="pb-3 font-semibold text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[#191919]">
                        <tr>
                            <td class="py-3 font-mono font-medium text-[#ff4667]">#DL-9842</td>
                            <td class="py-3 font-medium">Information About UI/UX Design Degree</td>
                            <td class="py-3 text-[#6d6d6d]">Nov 02, 2026</td>
                            <td class="py-3 font-bold">$120.00</td>
                            <td class="py-3"><span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded text-[10px]">Paid</span></td>
                            <td class="py-3 text-right"><button class="text-gray-500 hover:text-[#392c7d]">Download PDF</button></td>
                        </tr>
                        <tr>
                            <td class="py-3 font-mono font-medium text-[#ff4667]">#DL-8910</td>
                            <td class="py-3 font-medium">Complete Python Bootcamp From Zero to Hero</td>
                            <td class="py-3 text-[#6d6d6d]">Oct 28, 2026</td>
                            <td class="py-3 font-bold">$150.00</td>
                            <td class="py-3"><span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded text-[10px]">Paid</span></td>
                            <td class="py-3 text-right"><button class="text-gray-500 hover:text-[#392c7d]">Download PDF</button></td>
                        </tr>
                        <tr>
                            <td class="py-3 font-mono font-medium text-[#ff4667]">#DL-7521</td>
                            <td class="py-3 font-medium">Business and Management Masterclass</td>
                            <td class="py-3 text-[#6d6d6d]">Oct 15, 2026</td>
                            <td class="py-3 font-bold">$168.00</td>
                            <td class="py-3"><span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded text-[10px]">Paid</span></td>
                            <td class="py-3 text-right"><button class="text-gray-500 hover:text-[#392c7d]">Download PDF</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
