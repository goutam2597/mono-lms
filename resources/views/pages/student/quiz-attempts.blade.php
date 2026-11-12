<x-layouts.dashboard title="My Quiz Attempts - Mono LMS" active="quiz" userType="student">

    <div class="space-y-6">
        <!-- Header & Stats Cards -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">My Quiz Attempts</h2>
                <p class="text-xs text-slate-500 mt-0.5">Overview of assessment scores, pass/fail status, and attempt history</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-[5px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                    Total Attempts: {{ $totalAttempts }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-[5px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Passed: {{ $passedCount }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-[5px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Failed: {{ $failedCount }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-[#344c3d] bg-emerald-50/70 border border-emerald-200 px-3 py-1.5 rounded-[5px]">
                    Avg Score: {{ $averageScore }}%
                </span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white border border-[#e2e8f0] p-3 rounded-[10px] shadow-xs">
            <div class="flex items-center gap-1.5">
                <a href="{{ route('student.quiz-attempts', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-[5px] transition-colors {{ ($activeFilter === 'all') ? 'bg-[#344c3d] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    All ({{ $totalAttempts }})
                </a>
                <a href="{{ route('student.quiz-attempts', array_merge(request()->except(['status', 'page']), ['status' => 'passed'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-[5px] transition-colors {{ ($activeFilter === 'passed') ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Passed ({{ $passedCount }})
                </a>
                <a href="{{ route('student.quiz-attempts', array_merge(request()->except(['status', 'page']), ['status' => 'failed'])) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-[5px] transition-colors {{ ($activeFilter === 'failed') ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Failed ({{ $failedCount }})
                </a>
            </div>

            <form method="GET" action="{{ route('student.quiz-attempts') }}" class="flex items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search quizzes or courses..."
                           class="h-8 pl-8 pr-3 text-xs border border-slate-200 rounded-[5px] focus:outline-none focus:border-[#344c3d] w-48 sm:w-64">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if(request('q'))
                    <a href="{{ route('student.quiz-attempts', ['status' => request('status', 'all')]) }}" class="text-xs text-slate-400 hover:text-slate-600">Clear</a>
                @endif
            </form>
        </div>

        <!-- Quiz Attempts Table -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                            <th class="px-5 py-3.5">Quiz & Course Info</th>
                            <th class="px-5 py-3.5">Questions</th>
                            <th class="px-5 py-3.5">Pass Score</th>
                            <th class="px-5 py-3.5">Your Score</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Attempt Date</th>
                            <th class="px-5 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($attempts as $attempt)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-xs sm:text-sm text-slate-900 flex items-center gap-2 flex-wrap">
                                        <span>{{ $attempt->quiz?->trans('title') ?? 'Quiz Assessment' }}</span>
                                        @if ($attempt->liveClass)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                                                Live Class: {{ $attempt->liveClass->title }}
                                            </span>
                                        @elseif ($attempt->course?->isLive())
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                                Live Course
                                            </span>
                                        @elseif ($attempt->course?->isCombined())
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                                                Live + Rec
                                            </span>
                                        @endif
                                    </div>
                                    @if ($attempt->course)
                                        <a href="{{ route('student.courses.learn', $attempt->course) }}" class="text-[11px] text-slate-500 hover:text-[#344c3d] hover:underline mt-0.5 inline-block">
                                            Course: {{ $attempt->course->trans('title') }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-500">
                                    {{ $attempt->total_questions }} Questions
                                </td>
                                <td class="px-5 py-4 font-medium">
                                    {{ $attempt->quiz?->passing_percentage ?? 70 }}%
                                </td>
                                <td class="px-5 py-4 font-bold {{ $attempt->is_passed ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $attempt->scoreSummary() }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($attempt->is_passed)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold px-2 py-0.5 rounded-[4px] text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Passed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 font-bold px-2 py-0.5 rounded-[4px] text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-500 text-[11px]">
                                    <div>Attempt #{{ $attempt->attempt_number }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $attempt->completed_at?->format('M d, Y · h:i A') ?? $attempt->started_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <a href="{{ route('student.quizzes.review', $attempt) }}"
                                           class="text-[var(--theme-primary,#344c3d)] hover:underline font-semibold text-xs">
                                            View Review →
                                        </a>
                                        @if ($attempt->quiz && $attempt->course)
                                            @if ($attempt->quiz->isRetakeLocked(auth('student')->user()))
                                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2 py-0.5 rounded text-[10px] font-semibold" title="Unlock time decided by instructor">
                                                    ⏳ Retake in {{ $attempt->quiz->retakeCooldownRemainingHuman(auth('student')->user()) }}
                                                </span>
                                            @elseif ($attempt->quiz->canAttempt(auth('student')->user()))
                                                <a href="{{ route('student.quizzes.take', [$attempt->course, $attempt->quiz]) }}"
                                                   class="bg-[#344c3d] hover:bg-[#25372c] text-white px-2.5 py-1 rounded text-[10px] font-bold transition-colors">
                                                    Retake Quiz
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                    <div class="max-w-sm mx-auto space-y-2">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="font-medium text-slate-700">No quiz attempts found</p>
                                        <p class="text-xs text-slate-400">You haven't completed any quizzes under this filter yet. Enroll in courses and take your first assessment!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attempts->hasPages())
                <div class="px-5 py-3 border-t border-slate-100">
                    {{ $attempts->links() }}
                </div>
            @endif
        </div>

        <!-- Available Quizzes in Your Enrolled Courses Section -->
        @if ($availableQuizzes->isNotEmpty())
            <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Available Quizzes to Take</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Assessment quizzes from courses you are currently enrolled in</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200">
                        {{ $availableQuizzes->count() }} Available
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($availableQuizzes as $q)
                        @php
                            $userBest = $q->bestAttemptFor(auth('student')->user());
                            $userAttemptsCount = $q->attemptsCount(auth('student')->user());
                            $canTake = $q->canAttempt(auth('student')->user());
                        @endphp
                        <div class="border border-slate-200 rounded-[8px] p-4 bg-slate-50/50 flex flex-col justify-between hover:border-slate-300 transition-colors">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded
                                        {{ $q->isLiveQuiz() ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $q->isLiveQuiz() ? 'Live Session Quiz' : 'Course Quiz' }}
                                    </span>
                                    @if ($userBest && $userBest->is_passed)
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded">Passed ✓</span>
                                    @elseif ($userAttemptsCount > 0)
                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">Attempted ({{ $userBest->formattedScore() }})</span>
                                    @endif
                                </div>

                                <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ $q->trans('title') }}</h4>
                                <p class="text-xs text-slate-500 truncate">{{ $q->course?->trans('title') }}</p>

                                <div class="flex items-center gap-3 text-[11px] text-slate-500 pt-1">
                                    <span>📝 {{ $q->questions_count }} Questions</span>
                                    <span>⏱️ {{ $q->time_limit_minutes > 0 ? $q->time_limit_minutes.'m' : 'Untimed' }}</span>
                                    <span>🎯 {{ $q->passing_percentage }}% Pass</span>
                                </div>
                            </div>

                            <div class="pt-4 mt-3 border-t border-slate-200/80 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400">
                                    Attempts: {{ $userAttemptsCount }}/{{ $q->max_attempts ?: '∞' }}
                                </span>
                                @if ($q->isRetakeLocked(auth('student')->user()))
                                    @php
                                        $cooldownSecs = $q->retakeCooldownRemainingSeconds(auth('student')->user());
                                        $cooldownHuman = $q->retakeCooldownRemainingHuman(auth('student')->user());
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-[5px] bg-amber-50 text-amber-800 border border-amber-200 text-xs font-semibold retake-cooldown-timer"
                                          data-remaining="{{ $cooldownSecs }}"
                                          data-take-url="{{ route('student.quizzes.take', [$q->course, $q]) }}"
                                          title="Retake wait period decided by instructor">
                                        ⏳ Retake in <span class="cooldown-countdown font-bold font-mono">{{ $cooldownHuman }}</span>
                                    </span>
                                @elseif ($canTake)
                                    <a href="{{ route('student.quizzes.take', [$q->course, $q]) }}"
                                       class="bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-bold px-3.5 py-1.5 rounded transition-colors">
                                        {{ $userAttemptsCount > 0 ? 'Retake Quiz' : 'Take Quiz' }}
                                    </a>
                                @elseif ($userBest)
                                    <a href="{{ route('student.quizzes.review', $userBest) }}"
                                       class="border border-[#344c3d] text-[#344c3d] hover:bg-[#344c3d] hover:text-white text-xs font-bold px-3 py-1.5 rounded transition-colors">
                                        Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Live Retake Cooldown Countdown Script -->
    <script>
        (function() {
            var cooldownElements = document.querySelectorAll('.retake-cooldown-timer');
            if (!cooldownElements.length) return;

            function formatCooldown(secs) {
                var hrs = Math.floor(secs / 3600);
                var mins = Math.floor((secs % 3600) / 60);
                var s = secs % 60;
                if (hrs > 0) return hrs + 'h ' + mins + 'm';
                if (mins > 0) return mins + 'm ' + s + 's';
                return s + 's';
            }

            setInterval(function() {
                cooldownElements.forEach(function(el) {
                    var remaining = parseInt(el.getAttribute('data-remaining') || '0', 10);
                    if (remaining > 1) {
                        remaining--;
                        el.setAttribute('data-remaining', remaining);
                        var span = el.querySelector('.cooldown-countdown');
                        if (span) span.innerText = formatCooldown(remaining);
                    } else if (remaining === 1) {
                        // Cooldown ended, convert to active Retake button
                        var url = el.getAttribute('data-take-url');
                        var parent = el.parentNode;
                        if (parent && url) {
                            var btn = document.createElement('a');
                            btn.href = url;
                            btn.className = "bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-bold px-3.5 py-1.5 rounded transition-colors animate-bounce";
                            btn.innerText = "Retake Quiz Now";
                            parent.replaceChild(btn, el);
                        }
                    }
                });
            }, 1000);
        })();
    </script>

</x-layouts.dashboard>
