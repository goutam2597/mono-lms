<x-layouts.dashboard title="My Quiz Attempts - Mono LMS" active="quiz" userType="student">

    <div class="space-y-6">
        <!-- Header (Figma: 5013:76280) -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-4 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#191919]">My Quiz Attempts</h2>
                <p class="text-xs text-[#6d6d6d] mt-0.5">Overview of assessment scores, pass/fail status, and attempt history.</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <span class="text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Pass: 4</span>
                <span class="text-rose-600 bg-rose-50 px-3 py-1 rounded-full">Fail: 1</span>
            </div>
        </div>

        <!-- Quiz Attempts Table -->
        <div class="bg-white border border-[#e7e7e7] rounded-xl p-6 shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-[#888888]">
                            <th class="pb-3 font-semibold">QUIZ INFO</th>
                            <th class="pb-3 font-semibold">QUESTIONS</th>
                            <th class="pb-3 font-semibold">PASS SCORE</th>
                            <th class="pb-3 font-semibold">YOUR SCORE</th>
                            <th class="pb-3 font-semibold">STATUS</th>
                            <th class="pb-3 font-semibold text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[#191919]">
                        <tr>
                            <td class="py-4">
                                <div class="font-bold text-sm text-[#191919]">Build Responsive Real World Websites</div>
                                <div class="text-[11px] text-[#6d6d6d]">HTML5, CSS3, Flexbox & Grid Exam</div>
                            </td>
                            <td class="py-4 text-[#6d6d6d]">22 Questions</td>
                            <td class="py-4">70%</td>
                            <td class="py-4 font-bold text-[#03c95a]">85% (19/22)</td>
                            <td class="py-4">
                                <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full text-[10px]">Pass</span>
                            </td>
                            <td class="py-4 text-right">
                                <button class="text-[#392c7d] hover:text-[#ff4667] font-semibold">View Answers →</button>
                            </td>
                        </tr>

                        <tr>
                            <td class="py-4">
                                <div class="font-bold text-sm text-[#191919]">UI/UX Design Wireframing Challenge</div>
                                <div class="text-[11px] text-[#6d6d6d]">User Journeys & Heuristic Analysis</div>
                            </td>
                            <td class="py-4 text-[#6d6d6d]">15 Questions</td>
                            <td class="py-4">75%</td>
                            <td class="py-4 font-bold text-[#03c95a]">93% (14/15)</td>
                            <td class="py-4">
                                <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full text-[10px]">Pass</span>
                            </td>
                            <td class="py-4 text-right">
                                <button class="text-[#392c7d] hover:text-[#ff4667] font-semibold">View Answers →</button>
                            </td>
                        </tr>

                        <tr>
                            <td class="py-4">
                                <div class="font-bold text-sm text-[#191919]">Python Data Structures & Algorithms</div>
                                <div class="text-[11px] text-[#6d6d6d]">Lists, Dictionaries, Recursion & Trees</div>
                            </td>
                            <td class="py-4 text-[#6d6d6d]">20 Questions</td>
                            <td class="py-4">80%</td>
                            <td class="py-4 font-bold text-rose-600">65% (13/20)</td>
                            <td class="py-4">
                                <span class="bg-rose-100 text-rose-700 font-bold px-2.5 py-1 rounded-full text-[10px]">Fail</span>
                            </td>
                            <td class="py-4 text-right">
                                <button class="text-[#ff4667] hover:underline font-semibold">Retake Quiz</button>
                            </td>
                        </tr>

                        <tr>
                            <td class="py-4">
                                <div class="font-bold text-sm text-[#191919]">Agile Scrum Master Principles</div>
                                <div class="text-[11px] text-[#6d6d6d]">Sprints, Standups & Retrospectives</div>
                            </td>
                            <td class="py-4 text-[#6d6d6d]">25 Questions</td>
                            <td class="py-4">70%</td>
                            <td class="py-4 font-bold text-[#03c95a]">88% (22/25)</td>
                            <td class="py-4">
                                <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full text-[10px]">Pass</span>
                            </td>
                            <td class="py-4 text-right">
                                <button class="text-[#392c7d] hover:text-[#ff4667] font-semibold">View Answers →</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.dashboard>
