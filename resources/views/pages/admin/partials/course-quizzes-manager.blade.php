@php
    $userType = $userType ?? 'admin';
    $quizzes = $course->quizzes()->with(['questions', 'liveClass'])->withCount('attempts')->get();
@endphp

<!-- Quizzes & Assessments Manager -->
<div class="bg-white border border-[#e7e7e7] p-6 sm:p-8 space-y-6">
    <div class="flex items-center justify-between pb-3 border-b border-neutral-200">
        <div>
            <h3 class="font-bold text-[#191919] text-base">Quizzes & Live Assessments</h3>
            <p class="text-xs text-neutral-400 mt-0.5">{{ $quizzes->count() }} quizzes · comprehensive assessments for live sessions, recorded modules, and certificates</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-purple-50 text-purple-700 border border-purple-200 text-xs font-semibold">
            Interactive Gradebook
        </span>
    </div>

    <!-- Add Quiz Form -->
    <form action="{{ route($userType . '.courses.quizzes.store', $course) }}" method="POST" class="space-y-4 bg-neutral-50 dark:bg-neutral-900/50 p-6 sm:p-7 border border-neutral-200 dark:border-neutral-800 rounded-2xl">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Quiz Title *</label>
                <input type="text" name="title" required placeholder="e.g. Live Session 1 Concept Check or Module 1 Assessment" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Time Limit (Mins)</label>
                <input type="number" name="time_limit_minutes" min="0" value="15" placeholder="0 for untimed" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Pass Score (%) *</label>
                <input type="number" name="passing_percentage" min="1" max="100" value="70" required class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Max Attempts</label>
                <input type="number" name="max_attempts" min="0" value="3" placeholder="0 for unlimited" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
            </div>

            <!-- Associate with Live Class (for live / combined courses) or Scope for Pre-recorded -->
            @if ($course->hasLiveContent() && $course->liveClasses->isNotEmpty())
                <div class="space-y-1.5 sm:col-span-3">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Session Assignment (Live / Hybrid)</label>
                    <select name="live_class_id" class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3.5 text-sm rounded-lg focus:outline-none focus:border-neutral-900">
                        <option value="">General Course Assessment (All lessons & sessions)</option>
                        @foreach ($course->liveClasses as $live)
                            <option value="{{ $live->id }}">Live Class: {{ $live->title }} ({{ $live->starts_at->format('M d, Y') }})</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-neutral-400">Assigning to a Live Class session automatically highlights this quiz to students joining that live session.</p>
                </div>
            @else
                <div class="space-y-1.5 sm:col-span-3">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Assessment Scope</label>
                    <div class="h-10 w-full border border-neutral-200 dark:border-neutral-700 bg-neutral-100/70 dark:bg-neutral-800/50 px-3.5 text-xs text-neutral-600 dark:text-neutral-300 rounded-lg flex items-center">
                        Course Assessment (All pre-recorded video lessons &amp; curriculum)
                    </div>
                </div>
            @endif

            <div class="space-y-1.5 sm:col-span-4">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Description / Instructions (Optional)</label>
                <textarea name="description" rows="2" placeholder="Brief instructions for students taking this quiz..." class="w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-3 text-xs rounded-lg focus:outline-none focus:border-neutral-900"></textarea>
            </div>
        </div>

        <!-- First Question (Optional Initial) -->
        <div class="pt-3 border-t border-neutral-200 dark:border-neutral-800 space-y-3">
            <div class="text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">+ Add Initial Question (Optional)</div>
            <div class="space-y-2">
                <input type="text" name="new_questions[0][text]" placeholder="Question 1 text: e.g. What is the difference between flexbox and CSS grid?" class="h-9 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <input type="text" name="new_questions[0][options][0]" placeholder="Option A" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                    <input type="text" name="new_questions[0][options][1]" placeholder="Option B" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                    <input type="text" name="new_questions[0][options][2]" placeholder="Option C" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                    <input type="text" name="new_questions[0][options][3]" placeholder="Option D" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                    <select name="new_questions[0][correct]" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-2 text-xs rounded focus:outline-none focus:border-neutral-900">
                        <option value="a">Correct Option: A</option>
                        <option value="b">Correct Option: B</option>
                        <option value="c">Correct Option: C</option>
                        <option value="d">Correct Option: D</option>
                    </select>
                    <input type="text" name="new_questions[0][explanation]" placeholder="Explanation (shown to student after quiz)" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="action-btn action-btn-primary h-10 px-6 text-[11px] font-semibold uppercase tracking-[0.12em] whitespace-nowrap">
                + Create Quiz Assessment
            </button>
        </div>
    </form>

    <!-- Quizzes List -->
    <div class="space-y-4">
        @forelse ($quizzes as $quiz)
            <div class="border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden bg-white dark:bg-neutral-900">
                <!-- Quiz Summary Header -->
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-neutral-50/60 dark:bg-neutral-800/40 border-b border-neutral-200 dark:border-neutral-800">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-bold text-sm text-[#191919] dark:text-neutral-100">{{ $quiz->title }}</h4>
                            @if ($quiz->liveClass)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-100 text-purple-800">
                                    Live Class: {{ $quiz->liveClass->title }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700 dark:bg-neutral-800 dark:text-neutral-300">
                                    Course Assessment
                                </span>
                            @endif
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                                Pass: {{ $quiz->passing_percentage }}%
                            </span>
                        </div>
                        <p class="text-xs text-neutral-400 mt-1">
                            {{ $quiz->questions->count() }} Questions · {{ $quiz->time_limit_minutes > 0 ? $quiz->time_limit_minutes.' mins' : 'Untimed' }} · Max Attempts: {{ $quiz->max_attempts ?: 'Unlimited' }} · {{ $quiz->attempts_count }} Student Attempts
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button"
                                onclick="document.getElementById('quiz-questions-{{ $quiz->id }}').classList.toggle('hidden')"
                                class="action-btn action-btn-ghost h-8 px-3 text-[10px] font-semibold uppercase tracking-wider">
                            Manage Questions ({{ $quiz->questions->count() }})
                        </button>
                        <form action="{{ route($userType . '.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete this quiz and all its questions and student attempts?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn action-btn-danger h-8 px-3 text-[10px] font-semibold uppercase tracking-wider">
                                Delete Quiz
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Questions Manager Accordion Body -->
                <div id="quiz-questions-{{ $quiz->id }}" class="hidden p-4 sm:p-6 space-y-5 bg-white dark:bg-neutral-900">
                    <!-- Add Question Form -->
                    <form action="{{ route($userType . '.quizzes.questions.store', $quiz) }}" method="POST" class="p-4 border border-dashed border-neutral-300 dark:border-neutral-700 rounded-xl space-y-3 bg-neutral-50/50 dark:bg-neutral-800/30">
                        @csrf
                        <div class="text-xs font-bold text-neutral-800 dark:text-neutral-200 uppercase tracking-wider">+ Add New Question</div>
                        <div class="space-y-2">
                            <input type="text" name="question_text" required placeholder="Question text: e.g. Which HTML tag is used for the largest heading?" class="h-9 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" name="options[0]" required placeholder="Option A text (e.g. <h1>)" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                                <input type="text" name="options[1]" required placeholder="Option B text (e.g. <h6>)" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                                <input type="text" name="options[2]" placeholder="Option C text (e.g. <head>)" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                                <input type="text" name="options[3]" placeholder="Option D text (e.g. <heading>)" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                                <select name="correct_answer" required class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-2 text-xs rounded focus:outline-none focus:border-neutral-900">
                                    <option value="a">Correct Answer: Option A</option>
                                    <option value="b">Correct Answer: Option B</option>
                                    <option value="c">Correct Answer: Option C</option>
                                    <option value="d">Correct Answer: Option D</option>
                                </select>
                                <input type="text" name="explanation" placeholder="Explanation for students (optional)" class="h-8 border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
                            </div>
                        </div>
                        <div class="flex justify-end pt-1">
                            <button type="submit" class="action-btn action-btn-primary h-8 px-4 text-[10px] font-bold uppercase tracking-wider">
                                Add Question
                            </button>
                        </div>
                    </form>

                    <!-- Questions List -->
                    <div class="divide-y divide-neutral-200 dark:divide-neutral-800 border border-neutral-200 dark:border-neutral-800 rounded-xl overflow-hidden">
                        @forelse ($quiz->questions as $idx => $q)
                            <div class="p-3.5 flex items-start justify-between gap-3 bg-white dark:bg-neutral-900 text-xs">
                                <div class="space-y-1.5 min-w-0">
                                    <div class="font-semibold text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-neutral-100 text-neutral-600 text-[10px] font-bold flex items-center justify-center shrink-0">
                                            {{ $idx + 1 }}
                                        </span>
                                        <span>{{ $q->question_text }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 text-[11px] text-neutral-500 pl-7">
                                        @foreach ($q->options as $opt)
                                            @php
                                                $oId = $opt['id'] ?? 'a';
                                                $oTxt = $opt['text'] ?? '';
                                                $isCor = strtolower($oId) === strtolower($q->correct_answer);
                                            @endphp
                                            <span class="px-2 py-0.5 rounded border {{ $isCor ? 'bg-emerald-50 text-emerald-700 border-emerald-300 font-bold' : 'bg-neutral-50 text-neutral-600 border-neutral-200' }}">
                                                {{ strtoupper($oId) }}: {{ $oTxt }} @if($isCor) ✓ @endif
                                            </span>
                                        @endforeach
                                    </div>
                                    @if ($q->explanation)
                                        <p class="text-[11px] text-amber-700 pl-7 italic">💡 {{ $q->explanation }}</p>
                                    @endif
                                </div>
                                <form action="{{ route($userType . '.quizzes.questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger h-7 px-2.5 text-[10px]">Delete</button>
                                </form>
                            </div>
                        @empty
                            <div class="p-4 text-center text-xs text-neutral-400">
                                No questions added to this quiz yet. Use the form above to add questions.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="px-4 py-8 text-center text-xs text-neutral-400 border border-neutral-200 rounded-xl">
                No quizzes created for this course yet. Use the form above to add your first quiz or live assessment.
            </div>
        @endforelse
    </div>
</div>
