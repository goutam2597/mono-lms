<!-- Initial Quizzes & Assessments Builder (Course Creation) -->
<div class="p-6 sm:p-7 bg-neutral-50 dark:bg-neutral-900/40 border border-neutral-200 dark:border-neutral-800 rounded-xl space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-neutral-200 dark:border-neutral-700">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold">✓</span>
                <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 dark:text-neutral-100">Course Quizzes &amp; Assessments (Optional)</h3>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Add assessment quizzes now during course creation. Works for all courses (pre-recorded, live, and hybrid). You can also add more questions and quizzes anytime later.
            </p>
        </div>
        <button type="button" id="btn-add-creation-quiz" class="action-btn action-btn-secondary h-9 px-4 text-xs font-bold shrink-0 inline-flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Add Quiz
        </button>
    </div>

    <!-- Empty State -->
    <div id="creation-quizzes-empty" class="py-6 text-center text-xs text-neutral-400 bg-white dark:bg-neutral-800/50 border border-dashed border-neutral-300 dark:border-neutral-700 rounded-lg">
        No quizzes added yet. Click <span class="font-semibold text-neutral-700 dark:text-neutral-300">"+ Add Quiz"</span> above to attach an assessment to this course.
    </div>

    <!-- Container for dynamic quizzes -->
    <div id="creation-quizzes-container" class="space-y-6"></div>
</div>

<template id="creation-quiz-template">
    <div class="creation-quiz-card bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 rounded-xl p-5 shadow-xs space-y-4" data-quiz-index="__Q_INDEX__">
        <div class="flex items-center justify-between pb-3 border-b border-neutral-100 dark:border-neutral-700">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                    Quiz #<span class="quiz-number-label">__Q_NUM__</span>
                </span>
                <span class="text-xs text-neutral-400">Course Assessment</span>
            </div>
            <button type="button" class="btn-remove-quiz text-xs text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Remove Quiz
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Quiz Title *</label>
                <input type="text" name="quizzes[__Q_INDEX__][title]" required placeholder="e.g. Chapter 1 Quiz or Final Assessment" class="h-9 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 text-xs rounded-md focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Time Limit (Mins)</label>
                <input type="number" name="quizzes[__Q_INDEX__][time_limit_minutes]" min="0" value="15" placeholder="0 = untimed" class="h-9 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 text-xs rounded-md focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Passing Score (%) *</label>
                <input type="number" name="quizzes[__Q_INDEX__][passing_percentage]" min="1" max="100" value="70" required class="h-9 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 text-xs rounded-md focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5 sm:col-span-2">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Max Allowed Attempts</label>
                <input type="number" name="quizzes[__Q_INDEX__][max_attempts]" min="0" value="3" placeholder="0 = unlimited" class="h-9 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 text-xs rounded-md focus:outline-none focus:border-neutral-900">
            </div>

            <div class="space-y-1.5 sm:col-span-2 flex items-center pt-5">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="quizzes[__Q_INDEX__][is_published]" value="1" checked class="rounded border-neutral-300 text-[#344c3d] focus:ring-0">
                    <span class="text-xs font-semibold text-neutral-800 dark:text-neutral-200">Publish Quiz immediately with Course</span>
                </label>
            </div>

            <div class="space-y-1.5 sm:col-span-4">
                <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400">Description / Instructions (Optional)</label>
                <textarea name="quizzes[__Q_INDEX__][description]" rows="2" placeholder="Instructions or overview for students before they begin..." class="w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-2.5 text-xs rounded-md focus:outline-none focus:border-neutral-900"></textarea>
            </div>
        </div>

        <!-- Initial Questions Section for this Quiz -->
        <div class="pt-3 border-t border-neutral-100 dark:border-neutral-700/60 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">Questions</span>
                <button type="button" class="btn-add-question text-xs text-[#344c3d] hover:text-[#25372c] font-bold inline-flex items-center gap-1">
                    + Add Question
                </button>
            </div>

            <div class="questions-container space-y-3" data-quiz-index="__Q_INDEX__"></div>
        </div>
    </div>
</template>

<template id="creation-question-template">
    <div class="question-item bg-neutral-50/70 dark:bg-neutral-900/60 border border-neutral-200 dark:border-neutral-700/70 rounded-lg p-3.5 space-y-3" data-quest-index="__QUEST_INDEX__">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">
                Question #<span class="quest-number-label">__QUEST_NUM__</span>
            </span>
            <button type="button" class="btn-remove-question text-[11px] text-rose-500 hover:text-rose-700 font-semibold">
                Remove Question
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-[1fr_120px] gap-3">
            <div>
                <label class="text-[10px] font-semibold uppercase text-neutral-500 block mb-1">Question Prompt *</label>
                <input type="text" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][question_text]" placeholder="e.g. Which tag is used for the largest heading in HTML?" class="h-8 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
            </div>
            <div>
                <label class="text-[10px] font-semibold uppercase text-neutral-500 block mb-1">Points</label>
                <input type="number" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][points]" min="1" max="100" value="1" class="h-8 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
            </div>
        </div>

        <!-- Options with radio selector for correct answer -->
        <div class="space-y-1.5">
            <label class="text-[10px] font-semibold uppercase text-neutral-500 block">Options (Select radio for correct answer)</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="flex items-center gap-2 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 px-2.5 py-1.5 rounded">
                    <input type="radio" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][correct_answer]" value="a" checked class="text-[#344c3d] focus:ring-0">
                    <span class="text-xs font-bold text-neutral-500">A.</span>
                    <input type="text" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][options][0]" placeholder="Option A text" class="h-6 w-full text-xs border-0 bg-transparent focus:outline-none p-0">
                </div>
                <div class="flex items-center gap-2 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 px-2.5 py-1.5 rounded">
                    <input type="radio" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][correct_answer]" value="b" class="text-[#344c3d] focus:ring-0">
                    <span class="text-xs font-bold text-neutral-500">B.</span>
                    <input type="text" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][options][1]" placeholder="Option B text" class="h-6 w-full text-xs border-0 bg-transparent focus:outline-none p-0">
                </div>
                <div class="flex items-center gap-2 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 px-2.5 py-1.5 rounded">
                    <input type="radio" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][correct_answer]" value="c" class="text-[#344c3d] focus:ring-0">
                    <span class="text-xs font-bold text-neutral-500">C.</span>
                    <input type="text" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][options][2]" placeholder="Option C text" class="h-6 w-full text-xs border-0 bg-transparent focus:outline-none p-0">
                </div>
                <div class="flex items-center gap-2 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 px-2.5 py-1.5 rounded">
                    <input type="radio" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][correct_answer]" value="d" class="text-[#344c3d] focus:ring-0">
                    <span class="text-xs font-bold text-neutral-500">D.</span>
                    <input type="text" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][options][3]" placeholder="Option D text" class="h-6 w-full text-xs border-0 bg-transparent focus:outline-none p-0">
                </div>
            </div>
        </div>

        <div>
            <label class="text-[10px] font-semibold uppercase text-neutral-500 block mb-1">Explanation (Optional)</label>
            <input type="text" name="quizzes[__Q_INDEX__][questions][__QUEST_INDEX__][explanation]" placeholder="Explanation shown to students after answering..." class="h-8 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 text-xs rounded focus:outline-none focus:border-neutral-900">
        </div>
    </div>
</template>

<script>
    (function () {
        var quizCount = 0;
        var btnAddQuiz = document.getElementById('btn-add-creation-quiz');
        var emptyState = document.getElementById('creation-quizzes-empty');
        var container = document.getElementById('creation-quizzes-container');
        var quizTemplate = document.getElementById('creation-quiz-template');
        var questionTemplate = document.getElementById('creation-question-template');

        if (!btnAddQuiz || !container || !quizTemplate || !questionTemplate) return;

        function updateEmptyState() {
            var cards = container.querySelectorAll('.creation-quiz-card');
            if (emptyState) {
                emptyState.style.display = cards.length === 0 ? 'block' : 'none';
            }
            cards.forEach(function (card, idx) {
                var numSpan = card.querySelector('.quiz-number-label');
                if (numSpan) numSpan.textContent = idx + 1;
            });
        }

        function addQuestionToQuiz(quizCard, qIdx) {
            var questionsContainer = quizCard.querySelector('.questions-container');
            if (!questionsContainer) return;

            var existingQuestions = questionsContainer.querySelectorAll('.question-item');
            var questIdx = existingQuestions.length;
            var html = questionTemplate.innerHTML
                .replace(/__Q_INDEX__/g, qIdx)
                .replace(/__QUEST_INDEX__/g, questIdx)
                .replace(/__QUEST_NUM__/g, questIdx + 1);

            var temp = document.createElement('div');
            temp.innerHTML = html.trim();
            var node = temp.firstElementChild;
            questionsContainer.appendChild(node);

            // Hook remove question
            node.querySelector('.btn-remove-question').addEventListener('click', function () {
                node.remove();
                renumberQuestions(questionsContainer);
            });
        }

        function renumberQuestions(questionsContainer) {
            var items = questionsContainer.querySelectorAll('.question-item');
            items.forEach(function (item, idx) {
                var numSpan = item.querySelector('.quest-number-label');
                if (numSpan) numSpan.textContent = idx + 1;
            });
        }

        function addQuizCard() {
            var qIdx = quizCount++;
            var html = quizTemplate.innerHTML
                .replace(/__Q_INDEX__/g, qIdx)
                .replace(/__Q_NUM__/g, container.querySelectorAll('.creation-quiz-card').length + 1);

            var temp = document.createElement('div');
            temp.innerHTML = html.trim();
            var card = temp.firstElementChild;
            container.appendChild(card);

            // Hook remove quiz button
            card.querySelector('.btn-remove-quiz').addEventListener('click', function () {
                card.remove();
                updateEmptyState();
            });

            // Hook add question button
            card.querySelector('.btn-add-question').addEventListener('click', function () {
                addQuestionToQuiz(card, qIdx);
            });

            // Add 1 default question immediately
            addQuestionToQuiz(card, qIdx);

            updateEmptyState();
        }

        btnAddQuiz.addEventListener('click', function (e) {
            e.preventDefault();
            addQuizCard();
        });
    })();
</script>
