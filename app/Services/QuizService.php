<?php

namespace App\Services;

use App\Models\Course;

class QuizService
{
    /**
     * Create quizzes and questions submitted during course creation.
     *
     * @param  array<int, array<string, mixed>>  $quizzesData
     */
    public function syncFromCreation(Course $course, array $quizzesData): void
    {
        $quizSortOrder = $course->quizzes()->count() + 1;

        foreach ($quizzesData as $quizData) {
            if (! is_array($quizData)) {
                continue;
            }

            $title = trim((string) ($quizData['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $retakeDelayVal = max(0, (int) ($quizData['retake_delay_minutes'] ?? ($quizData['retake_delay'] ?? 0)));
            $retakeDelayUnit = $quizData['retake_delay_unit'] ?? 'minutes';
            if ($retakeDelayUnit === 'hours') {
                $retakeDelayVal *= 60;
            } elseif ($retakeDelayUnit === 'days') {
                $retakeDelayVal *= 1440;
            }

            $quiz = $course->quizzes()->create([
                'live_class_id' => ! empty($quizData['live_class_id']) ? (int) $quizData['live_class_id'] : null,
                'title' => $title,
                'description' => ! empty($quizData['description']) ? trim((string) $quizData['description']) : null,
                'time_limit_minutes' => max(0, (int) ($quizData['time_limit_minutes'] ?? 15)),
                'passing_percentage' => max(1, min(100, (int) ($quizData['passing_percentage'] ?? 70))),
                'max_attempts' => max(0, (int) ($quizData['max_attempts'] ?? 3)),
                'retake_delay_minutes' => $retakeDelayVal,
                'is_published' => isset($quizData['is_published']) ? (bool) $quizData['is_published'] : true,
                'sort_order' => $quizSortOrder++,
            ]);

            // Save any initial questions
            $questions = (array) ($quizData['questions'] ?? []);
            $qSortOrder = 1;

            foreach ($questions as $qData) {
                if (! is_array($qData)) {
                    continue;
                }

                $qText = trim((string) ($qData['question_text'] ?? ''));
                if ($qText === '') {
                    continue;
                }

                $rawOptions = (array) ($qData['options'] ?? []);
                $formattedOptions = [];
                $idx = 0;
                foreach ($rawOptions as $key => $optVal) {
                    $optText = trim((string) $optVal);
                    if ($optText === '') {
                        continue;
                    }
                    $optionId = is_string($key) && ! is_numeric($key) ? strtolower($key) : chr(97 + $idx);
                    $formattedOptions[] = [
                        'id' => $optionId,
                        'text' => $optText,
                    ];
                    $idx++;
                }

                if (count($formattedOptions) < 2) {
                    continue;
                }

                $correctAnswer = strtolower(trim((string) ($qData['correct_answer'] ?? 'a')));

                $quiz->questions()->create([
                    'question_text' => $qText,
                    'type' => in_array($qData['type'] ?? '', ['multiple_choice', 'true_false'], true) ? $qData['type'] : 'multiple_choice',
                    'options' => $formattedOptions,
                    'correct_answer' => $correctAnswer,
                    'explanation' => ! empty($qData['explanation']) ? trim((string) $qData['explanation']) : null,
                    'points' => max(1, (int) ($qData['points'] ?? 1)),
                    'sort_order' => $qSortOrder++,
                ]);
            }
        }
    }
}
