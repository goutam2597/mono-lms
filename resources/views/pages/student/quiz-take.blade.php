<x-layouts.dashboard :title="'Quiz: ' . $quiz->trans('title') . ' - Mono LMS'" active="quiz" userType="student">

    <div class="space-y-6 max-w-4xl mx-auto pb-12">
        <!-- Top Navigation & Breadcrumb -->
        <div class="flex items-center justify-between">
            <a href="{{ route('student.courses.learn', $course) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Course
            </a>
            <div class="text-xs text-slate-400">
                Attempt {{ $attempt->attempt_number }} of {{ $quiz->max_attempts ?: 'Unlimited' }}
            </div>
        </div>

        <!-- Sticky Header with Quiz Info & Timer -->
        <div class="sticky top-16 z-20 bg-white border border-[#e2e8f0] rounded-[10px] p-4 sm:p-5 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded
                        {{ $quiz->isLiveQuiz() ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $quiz->isLiveQuiz() ? 'Live Session Quiz' : 'Assessment' }}
                    </span>
                    @if ($quiz->liveClass)
                        <span class="text-xs font-semibold text-purple-700">· {{ $quiz->liveClass->title }}</span>
                    @endif
                </div>
                <h1 class="text-base sm:text-lg font-bold text-slate-900 mt-1 truncate">{{ $quiz->trans('title') }}</h1>
                <p class="text-xs text-slate-500 truncate">Course: {{ $course->trans('title') }}</p>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <!-- Answered Progress -->
                <div class="text-right">
                    <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Answered</div>
                    <div class="text-sm font-bold text-slate-900" id="answered-counter">0 / {{ $questions->count() }}</div>
                </div>

                <!-- Timer (if timed) -->
                @if ($quiz->time_limit_minutes > 0)
                    @php
                        $endTime = $attempt->started_at->addMinutes($quiz->time_limit_minutes)->timestamp;
                        $remainingSeconds = max(0, $endTime - now()->timestamp);
                    @endphp
                    <div class="bg-slate-900 text-white px-3.5 py-2 rounded-[8px] flex items-center gap-2 font-mono" id="quiz-timer-box">
                        <svg class="w-4 h-4 text-emerald-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm font-bold" id="timer-display">--:--</span>
                    </div>
                @endif
            </div>
        </div>

        @if ($quiz->trans('description'))
            <div class="bg-slate-50 border border-slate-200 rounded-[8px] p-4 text-xs text-slate-600 leading-relaxed">
                <span class="font-bold text-slate-800 block mb-1">Instructions:</span>
                {{ $quiz->trans('description') }}
                <div class="mt-2 text-[11px] text-slate-500">Passing score required: <strong>{{ $quiz->passing_percentage }}%</strong>. Answer each question carefully before submitting.</div>
            </div>
        @endif

        <!-- Examination Form -->
        <form id="quiz-form" action="{{ route('student.quizzes.submit', [$course, $quiz, $attempt]) }}" method="POST" class="space-y-5">
            @csrf

            @forelse ($questions as $index => $q)
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 sm:p-6 shadow-xs space-y-4 question-card" data-question-id="{{ $q->id }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-[6px] bg-[#344c3d] text-white text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Question {{ $index + 1 }} of {{ $questions->count() }}</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400 font-mono">{{ $q->points }} pt{{ $q->points > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="text-sm sm:text-base font-semibold text-slate-900 leading-snug pl-1">
                        {{ $q->question_text }}
                    </div>

                    <!-- Options -->
                    <div class="space-y-2.5 pt-1">
                        @foreach ($q->options as $opt)
                            @php
                                $optId = is_array($opt) ? ($opt['id'] ?? 'a') : $opt;
                                $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                                $inputName = "answers[{$q->id}]";
                                $inputId = "q_{$q->id}_{$optId}";
                            @endphp
                            <label for="{{ $inputId }}"
                                   class="flex items-center gap-3.5 p-3 sm:p-3.5 rounded-[8px] border border-slate-200 hover:border-slate-300 hover:bg-slate-50/70 transition-all cursor-pointer group option-label">
                                <input type="radio"
                                       name="{{ $inputName }}"
                                       id="{{ $inputId }}"
                                       value="{{ $optId }}"
                                       class="w-4 h-4 text-[#344c3d] focus:ring-[#344c3d] border-slate-300 option-radio">
                                <span class="w-6 h-6 rounded-full border border-slate-300 text-slate-600 font-mono text-xs font-bold flex items-center justify-center shrink-0 uppercase group-hover:border-[#344c3d] group-hover:text-[#344c3d] transition-colors badge-label">
                                    {{ $optId }}
                                </span>
                                <span class="text-xs sm:text-sm text-slate-800 leading-normal flex-1">
                                    {{ $optText }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-10 text-center text-slate-400">
                    No questions have been added to this quiz yet. Please check back later.
                </div>
            @endforelse

            @if ($questions->isNotEmpty())
                <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500">
                        Make sure you have answered all questions. You cannot edit answers after submission.
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="submit"
                                onclick="return confirmSubmission()"
                                class="w-full sm:w-auto bg-[#344c3d] hover:bg-[#25372c] text-white text-xs sm:text-sm font-bold px-7 py-3 rounded-[8px] shadow-sm transition-colors flex items-center justify-center gap-2">
                            <span>Submit Assessment</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>

    @if ($quiz->time_limit_minutes > 0)
        <script>
            (function() {
                var remaining = {{ $remainingSeconds }};
                var timerDisplay = document.getElementById('timer-display');
                var timerBox = document.getElementById('quiz-timer-box');
                var form = document.getElementById('quiz-form');

                function updateTimer() {
                    if (remaining <= 0) {
                        timerDisplay.innerText = "00:00";
                        alert("Time limit expired! Submitting your answers automatically.");
                        form.submit();
                        return;
                    }

                    var mins = Math.floor(remaining / 60);
                    var secs = remaining % 60;
                    timerDisplay.innerText = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;

                    if (remaining < 120) {
                        timerBox.classList.remove('bg-slate-900');
                        timerBox.classList.add('bg-rose-600', 'animate-pulse');
                    }

                    remaining--;
                    setTimeout(updateTimer, 1000);
                }

                updateTimer();
            })();
        </script>
    @endif

    <script>
        // Track answered questions in real time and highlight selected choices
        (function() {
            var radios = document.querySelectorAll('.option-radio');
            var counter = document.getElementById('answered-counter');
            var total = {{ $questions->count() }};

            function refreshProgress() {
                var checkedCount = document.querySelectorAll('.option-radio:checked').length;
                if (counter) {
                    counter.innerText = checkedCount + ' / ' + total;
                }

                // Visual styling for selected labels
                document.querySelectorAll('.option-label').forEach(function(label) {
                    var input = label.querySelector('input[type="radio"]');
                    var badge = label.querySelector('.badge-label');
                    if (input && input.checked) {
                        label.classList.add('border-[#344c3d]', 'bg-emerald-50/50');
                        if (badge) {
                            badge.classList.add('bg-[#344c3d]', 'text-white', 'border-[#344c3d]');
                        }
                    } else {
                        label.classList.remove('border-[#344c3d]', 'bg-emerald-50/50');
                        if (badge) {
                            badge.classList.remove('bg-[#344c3d]', 'text-white', 'border-[#344c3d]');
                        }
                    }
                });
            }

            radios.forEach(function(radio) {
                radio.addEventListener('change', refreshProgress);
            });

            refreshProgress();

            window.confirmSubmission = function() {
                var checkedCount = document.querySelectorAll('.option-radio:checked').length;
                if (checkedCount < total) {
                    var diff = total - checkedCount;
                    return confirm("You still have " + diff + " unanswered question(s). Are you sure you want to submit?");
                }
                return confirm("Are you ready to submit your assessment? You will see your grade immediately.");
            };
        })();
    </script>

</x-layouts.dashboard>
