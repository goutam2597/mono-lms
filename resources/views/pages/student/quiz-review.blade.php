<x-layouts.dashboard :title="'Quiz Review: ' . $quiz->trans('title') . ' - Mono LMS'" active="quiz" userType="student">

    <div class="space-y-6 max-w-4xl mx-auto pb-12">
        <!-- Navigation Breadcrumbs -->
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2 text-xs">
                <a href="{{ route('student.quiz-attempts') }}" class="font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                    Quiz Attempts
                </a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('student.courses.learn', $course) }}" class="font-semibold text-[#738a6e] hover:text-[#344c3d] transition-colors">
                    {{ $course->trans('title') }}
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-800 font-bold truncate max-w-xs">{{ $quiz->trans('title') }}</span>
            </div>

            <div class="flex items-center gap-2">
                @if ($quiz->canAttempt(auth('student')->user()))
                    <a href="{{ route('student.quizzes.take', [$course, $quiz]) }}"
                       class="bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-bold px-3.5 py-1.5 rounded-[6px] transition-colors">
                        Retake Quiz
                    </a>
                @endif
                <a href="{{ route('student.courses.learn', $course) }}"
                   class="border border-slate-300 hover:border-slate-400 bg-white text-slate-700 text-xs font-semibold px-3.5 py-1.5 rounded-[6px] transition-colors">
                    Back to Course
                </a>
            </div>
        </div>

        <!-- Gradebook Result Hero Card -->
        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
            <!-- Score Gauge -->
            <div class="shrink-0 w-32 h-32 rounded-full flex flex-col items-center justify-center border-4 shadow-sm {{ $attempt->is_passed ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-rose-50 border-rose-500 text-rose-700' }}">
                <span class="text-3xl font-black font-mono leading-none">{{ $attempt->formattedScore() }}</span>
                <span class="text-[10px] uppercase tracking-wider font-bold mt-1">
                    {{ $attempt->is_passed ? 'PASSED' : 'FAILED' }}
                </span>
            </div>

            <div class="space-y-3 min-w-0 flex-1 text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded
                        {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $attempt->is_passed ? 'Passed ✓' : 'Needs Improvement' }}
                    </span>
                    @if ($attempt->liveClass)
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-purple-100 text-purple-800">
                            Live Class Session Quiz
                        </span>
                    @endif
                    <span class="text-xs text-slate-400 font-medium">Attempt #{{ $attempt->attempt_number }}</span>
                </div>

                <h1 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
                    {{ $quiz->trans('title') }}
                </h1>

                <p class="text-xs text-slate-500">
                    Course: <strong class="text-slate-700">{{ $course->trans('title') }}</strong>
                    @if ($attempt->liveClass) · Session: {{ $attempt->liveClass->title }} @endif
                </p>

                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs pt-1">
                    <div class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-[6px]">
                        <span class="text-slate-400">Score:</span>
                        <strong class="text-slate-900 ml-1">{{ $attempt->correct_answers_count }} / {{ $attempt->total_questions }} ({{ $attempt->formattedScore() }})</strong>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-[6px]">
                        <span class="text-slate-400">Required:</span>
                        <strong class="text-slate-900 ml-1">{{ $quiz->passing_percentage }}%</strong>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-[6px]">
                        <span class="text-slate-400">Date:</span>
                        <strong class="text-slate-900 ml-1">{{ $attempt->completed_at?->format('M d, Y · h:i A') ?? $attempt->updated_at->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Question Breakdown -->
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">Detailed Answers Breakdown</h3>
                <span class="text-xs text-slate-400">{{ $attempt->correct_answers_count }} correct of {{ $attempt->total_questions }} total</span>
            </div>

            @php $userAnswers = $attempt->answers ?? []; @endphp

            @foreach ($questions as $index => $q)
                @php
                    $given = $userAnswers[$q->id] ?? null;
                    $isCorrect = $q->isCorrect($given);
                @endphp
                <div class="bg-white border rounded-[10px] p-5 sm:p-6 shadow-xs space-y-4 {{ $isCorrect ? 'border-emerald-200/80 bg-emerald-50/10' : 'border-rose-200/80 bg-rose-50/10' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center shrink-0
                                {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                {{ $isCorrect ? '✓' : '✗' }}
                            </span>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                Question {{ $index + 1 }}
                            </span>
                        </div>
                        <span class="text-xs font-bold {{ $isCorrect ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $isCorrect ? '+'.$q->points.' pt' : '0 / '.$q->points.' pt' }}
                        </span>
                    </div>

                    <div class="text-sm sm:text-base font-semibold text-slate-900 pl-1">
                        {{ $q->question_text }}
                    </div>

                    <!-- Options list -->
                    <div class="space-y-2 pt-1">
                        @foreach ($q->options as $opt)
                            @php
                                $optId = is_array($opt) ? ($opt['id'] ?? 'a') : $opt;
                                $optText = is_array($opt) ? ($opt['text'] ?? '') : $opt;
                                $isThisGiven = strtolower(trim((string)$given)) === strtolower(trim((string)$optId));
                                $isThisCorrect = strtolower(trim((string)$q->correct_answer)) === strtolower(trim((string)$optId));
                            @endphp

                            <div class="p-3 rounded-[8px] border text-xs sm:text-sm flex items-center justify-between gap-3 transition-colors
                                {{ $isThisCorrect
                                    ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-medium'
                                    : ($isThisGiven
                                        ? 'bg-rose-50 border-rose-300 text-rose-950 font-medium'
                                        : 'bg-white border-slate-200 text-slate-700 opacity-80') }}">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-5 h-5 rounded-full border text-xs font-mono font-bold flex items-center justify-center shrink-0 uppercase
                                        {{ $isThisCorrect
                                            ? 'bg-emerald-600 text-white border-emerald-600'
                                            : ($isThisGiven
                                                ? 'bg-rose-600 text-white border-rose-600'
                                                : 'border-slate-300 text-slate-500') }}">
                                        {{ $optId }}
                                    </span>
                                    <span class="truncate">{{ $optText }}</span>
                                </div>

                                <div class="shrink-0 text-xs font-bold">
                                    @if ($isThisCorrect && $isThisGiven)
                                        <span class="text-emerald-700 inline-flex items-center gap-1">✓ Your Correct Choice</span>
                                    @elseif ($isThisCorrect)
                                        <span class="text-emerald-700 inline-flex items-center gap-1">✓ Correct Answer</span>
                                    @elseif ($isThisGiven)
                                        <span class="text-rose-700 inline-flex items-center gap-1">✗ Your Selection</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($q->explanation)
                        <div class="bg-amber-50/80 border border-amber-200 rounded-[8px] p-3 text-xs text-amber-900 leading-relaxed flex items-start gap-2">
                            <span class="text-sm shrink-0">💡</span>
                            <div>
                                <span class="font-bold block mb-0.5">Explanation:</span>
                                {{ $q->explanation }}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('student.quiz-attempts') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to All Quiz Attempts
            </a>
            <a href="{{ route('student.courses.learn', $course) }}" class="bg-[#344c3d] hover:bg-[#25372c] text-white text-xs font-bold px-5 py-2.5 rounded-[8px] transition-colors">
                Continue Learning Course →
            </a>
        </div>
    </div>

</x-layouts.dashboard>
