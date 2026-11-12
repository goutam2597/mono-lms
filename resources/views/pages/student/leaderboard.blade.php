<x-layouts.dashboard :title="'Cohort Leaderboard: ' . $course->trans('title') . ' - Mono LMS'" active="enrolled-courses" userType="student">
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="min-w-0">
                <a href="{{ route('student.courses.learn', $course) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Course
                </a>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1">Cohort Leaderboard</h1>
                <p class="text-xs text-neutral-400 mt-0.5">{{ $course->trans('title') }} · Live rankings based on quizzes, assignments & class participation</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-purple-800 border border-purple-200 text-xs font-bold">
                    👥 {{ $leaderboard->count() }} Enrolled Peers
                </span>
            </div>
        </div>

        <!-- Student's Personal Rank Banner -->
        @if ($myRank)
            <div class="bg-gradient-to-r from-[#344c3d] to-[#1e2e25] text-white rounded-2xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex flex-col items-center justify-center font-black">
                        <span class="text-[10px] uppercase tracking-wider text-emerald-300">RANK</span>
                        <span class="text-xl">#{{ $myRank['rank'] }}</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold">{{ $myRank['student']?->name ?? 'You' }}</h2>
                        <p class="text-xs text-white/70">Your performance in this batch cohort</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                    <div class="bg-white/10 rounded-xl px-3 py-2 border border-white/10">
                        <div class="text-[10px] uppercase text-white/70 font-semibold">Total Points</div>
                        <div class="text-lg font-bold text-emerald-300">{{ $myRank['total_score'] }}</div>
                    </div>
                    <div class="bg-white/10 rounded-xl px-3 py-2 border border-white/10">
                        <div class="text-[10px] uppercase text-white/70 font-semibold">Assignments</div>
                        <div class="text-lg font-bold">{{ $myRank['assignment_points'] }} pts</div>
                    </div>
                    <div class="bg-white/10 rounded-xl px-3 py-2 border border-white/10">
                        <div class="text-[10px] uppercase text-white/70 font-semibold">Quizzes</div>
                        <div class="text-lg font-bold">{{ $myRank['quiz_points'] }} pts</div>
                    </div>
                    <div class="bg-white/10 rounded-xl px-3 py-2 border border-white/10">
                        <div class="text-[10px] uppercase text-white/70 font-semibold">Attendance</div>
                        <div class="text-lg font-bold">{{ $myRank['attendance_points'] }} pts</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Top 3 Podium (Ostad Style Gamification) -->
        @if ($leaderboard->count() >= 3)
            @php
                $first = $leaderboard->get(0);
                $second = $leaderboard->get(1);
                $third = $leaderboard->get(2);
            @endphp
            <div class="grid grid-cols-3 gap-3 sm:gap-4 items-end pt-6">
                <!-- 2nd Place -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-4 text-center shadow-sm relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-slate-200 border-2 border-white shadow flex items-center justify-center text-sm font-bold text-slate-700">
                        🥈
                    </div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-700 mt-2">
                        {{ strtoupper(substr($second['student']?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="font-bold text-xs sm:text-sm text-slate-900 mt-2 truncate">{{ $second['student']?->name }}</div>
                    <div class="text-xs font-extrabold text-[#344c3d] mt-0.5">{{ $second['total_score'] }} pts</div>
                    <div class="text-[10px] text-neutral-400 mt-1">2nd Place</div>
                </div>

                <!-- 1st Place (Winner) -->
                <div class="bg-gradient-to-b from-amber-50 to-white border-2 border-amber-300 rounded-2xl p-5 text-center shadow-md relative -translate-y-2">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-amber-400 border-2 border-white shadow-lg flex items-center justify-center text-lg">
                        👑
                    </div>
                    <div class="w-14 h-14 mx-auto rounded-full bg-amber-100 border-2 border-amber-300 flex items-center justify-center text-base font-black text-amber-800 mt-2">
                        {{ strtoupper(substr($first['student']?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="font-bold text-sm sm:text-base text-slate-900 mt-2 truncate">{{ $first['student']?->name }}</div>
                    <div class="text-sm font-black text-amber-700 mt-0.5">{{ $first['total_score'] }} pts</div>
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-amber-200 text-amber-900 mt-1">Cohort Leader</span>
                </div>

                <!-- 3rd Place -->
                <div class="bg-white border border-[#e7e7e7] rounded-2xl p-4 text-center shadow-sm relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-amber-700/20 border-2 border-white shadow flex items-center justify-center text-sm font-bold text-amber-800">
                        🥉
                    </div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-amber-50 flex items-center justify-center text-sm font-bold text-amber-900 mt-2">
                        {{ strtoupper(substr($third['student']?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="font-bold text-xs sm:text-sm text-slate-900 mt-2 truncate">{{ $third['student']?->name }}</div>
                    <div class="text-xs font-extrabold text-[#344c3d] mt-0.5">{{ $third['total_score'] }} pts</div>
                    <div class="text-[10px] text-neutral-400 mt-1">3rd Place</div>
                </div>
            </div>
        @endif

        <!-- Full Roster Table -->
        <div class="bg-white border border-[#e7e7e7] rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-[#e7e7e7] flex items-center justify-between">
                <h3 class="font-bold text-[#191919] text-sm">Full Cohort Rankings</h3>
                <span class="text-xs text-neutral-400">Total Enrolled: {{ $leaderboard->count() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-neutral-50 border-b border-[#e7e7e7] text-neutral-500 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 font-bold text-center w-14">Rank</th>
                            <th class="py-3 px-4 font-bold">Learner</th>
                            <th class="py-3 px-4 font-bold text-center">Assignments</th>
                            <th class="py-3 px-4 font-bold text-center">Quizzes</th>
                            <th class="py-3 px-4 font-bold text-center">Live Attendance</th>
                            <th class="py-3 px-4 font-bold text-center">Progress</th>
                            <th class="py-3 px-4 font-bold text-right">Total Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0f0f0]">
                        @forelse ($leaderboard as $entry)
                            @php
                                $isMe = (auth('student')->id() === $entry['student_id']);
                            @endphp
                            <tr class="{{ $isMe ? 'bg-emerald-50/60 font-semibold' : 'hover:bg-neutral-50/50' }} transition-colors">
                                <td class="py-3 px-4 text-center">
                                    @if ($entry['rank'] === 1)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-400 text-white font-bold text-xs shadow-sm">1</span>
                                    @elseif ($entry['rank'] === 2)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-300 text-slate-800 font-bold text-xs">2</span>
                                    @elseif ($entry['rank'] === 3)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-700/40 text-amber-900 font-bold text-xs">3</span>
                                    @else
                                        <span class="text-neutral-500 font-bold">#{{ $entry['rank'] }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-neutral-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($entry['student']?->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="truncate text-slate-900 flex items-center gap-1.5">
                                                <span>{{ $entry['student']?->name }}</span>
                                                @if ($isMe)
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase bg-emerald-200 text-emerald-900">YOU</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-neutral-400">Enrolled Student</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="font-bold text-slate-800">{{ $entry['assignment_points'] }} pts</span>
                                    <span class="block text-[10px] text-neutral-400">({{ $entry['assignments_completed'] }} submitted)</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="font-bold text-slate-800">{{ $entry['quiz_points'] }} pts</span>
                                    <span class="block text-[10px] text-neutral-400">({{ $entry['quizzes_passed'] }} passed)</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="font-bold text-slate-800">{{ $entry['attendance_points'] }} pts</span>
                                    <span class="block text-[10px] text-neutral-400">({{ $entry['classes_attended'] }} sessions)</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded bg-neutral-100 text-slate-700 font-bold text-[11px]">
                                        {{ $entry['progress'] }}%
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <span class="inline-block px-3 py-1 rounded-xl bg-[#344c3d]/10 text-[#344c3d] font-black text-sm">
                                        {{ $entry['total_score'] }} pts
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-neutral-400">
                                    No students enrolled in this cohort yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
