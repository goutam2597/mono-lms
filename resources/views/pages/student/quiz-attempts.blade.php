<x-layouts.dashboard title="My Quiz Attempts - Mono LMS" active="quiz" userType="student">

    <div class="space-y-5">
        <!-- Header (Boxy) -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">My Quiz Attempts</h2>
                <p class="text-xs text-slate-500 mt-0.5">Overview of assessment scores, pass/fail status, and attempt history</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-[5px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Passed: 4
                </span>
                <span class="inline-flex items-center gap-1.5 text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-[5px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Failed: 1
                </span>
            </div>
        </div>

        <!-- Quiz Attempts Table (Boxy) -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                            <th class="px-5 py-3">Quiz Info</th>
                            <th class="px-5 py-3">Questions</th>
                            <th class="px-5 py-3">Pass Score</th>
                            <th class="px-5 py-3">Your Score</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-xs sm:text-sm text-slate-900">Build Responsive Real World Websites</div>
                                <div class="text-[11px] text-slate-500">HTML5, CSS3, Flexbox & Grid Assessment</div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">22 Questions</td>
                            <td class="px-5 py-3.5 font-medium">70%</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-600">85% (19/22)</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold px-2 py-0.5 rounded-[4px] text-[10px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Passed
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <button type="button" class="text-[var(--theme-primary,#344c3d)] hover:underline font-semibold cursor-pointer">View Review →</button>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-xs sm:text-sm text-slate-900">UI/UX Design Wireframing Challenge</div>
                                <div class="text-[11px] text-slate-500">User Journeys & Heuristic Analysis</div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">15 Questions</td>
                            <td class="px-5 py-3.5 font-medium">75%</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-600">93% (14/15)</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold px-2 py-0.5 rounded-[4px] text-[10px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Passed
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <button type="button" class="text-[var(--theme-primary,#344c3d)] hover:underline font-semibold cursor-pointer">View Review →</button>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-xs sm:text-sm text-slate-900">Advanced React 19 State Patterns</div>
                                <div class="text-[11px] text-slate-500">Zustand, Redux Toolkit & Context APIs</div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">20 Questions</td>
                            <td class="px-5 py-3.5 font-medium">70%</td>
                            <td class="px-5 py-3.5 font-bold text-rose-600">60% (12/20)</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 font-bold px-2 py-0.5 rounded-[4px] text-[10px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Failed
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <button type="button" class="text-[var(--theme-primary,#344c3d)] hover:underline font-semibold cursor-pointer">Retake Quiz →</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
