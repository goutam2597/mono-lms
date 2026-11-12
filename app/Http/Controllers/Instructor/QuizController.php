<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class QuizController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        $data = $this->validatedQuiz($request, $course);
        $data['course_id'] = $course->id;
        $data['sort_order'] = $course->quizzes()->count() + 1;
        $data['is_published'] = $request->boolean('is_published', true);

        $quiz = Quiz::create($data);

        $this->syncQuestions($request, $quiz);

        return back()->with('success', 'Quiz "'.$quiz->title.'" created successfully.');
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorizeCourse($quiz->course);

        $data = $this->validatedQuiz($request, $quiz->course);
        $data['is_published'] = $request->boolean('is_published', true);

        $quiz->update($data);

        return back()->with('success', 'Quiz "'.$quiz->title.'" updated.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->authorizeCourse($quiz->course);

        $title = $quiz->title;
        $quiz->delete();

        return back()->with('success', 'Quiz "'.$title.'" removed.');
    }

    public function storeQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorizeCourse($quiz->course);

        $data = $request->validate([
            'question_text' => ['required', 'string', 'max:2000'],
            'type' => ['nullable', 'string', 'in:multiple_choice,true_false'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string', 'max:500'],
            'correct_answer' => ['required', 'string', 'max:50'],
            'explanation' => ['nullable', 'string', 'max:2000'],
            'points' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $formattedOptions = [];
        foreach ($data['options'] as $idx => $optText) {
            $key = chr(97 + $idx);
            $formattedOptions[] = [
                'id' => $key,
                'text' => trim($optText),
            ];
        }

        $quiz->questions()->create([
            'question_text' => $data['question_text'],
            'type' => $data['type'] ?? 'multiple_choice',
            'options' => $formattedOptions,
            'correct_answer' => strtolower(trim($data['correct_answer'])),
            'explanation' => $data['explanation'] ?? null,
            'points' => $data['points'] ?? 1,
            'sort_order' => $quiz->questions()->count() + 1,
        ]);

        return back()->with('success', 'Question added to "'.$quiz->title.'".');
    }

    public function destroyQuestion(QuizQuestion $question): RedirectResponse
    {
        $this->authorizeCourse($question->quiz->course);

        $quiz = $question->quiz;
        $question->delete();

        return back()->with('success', 'Question deleted from "'.$quiz->title.'".');
    }

    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== auth('instructor')->id()) {
            abort(403, 'You do not have permission to manage quizzes for this course.');
        }
    }

    /** @return array<string, mixed> */
    private function validatedQuiz(Request $request, Course $course): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'live_class_id' => [
                'nullable',
                'integer',
                'exists:live_classes,id',
            ],
            'time_limit_minutes' => ['nullable', 'integer', 'min:0', 'max:300'],
            'passing_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'max_attempts' => ['nullable', 'integer', 'min:0', 'max:50'],
        ]);
    }

    private function syncQuestions(Request $request, Quiz $quiz): void
    {
        $questionsInput = $request->input('new_questions', []);
        if (! is_array($questionsInput)) {
            return;
        }

        foreach ($questionsInput as $qData) {
            if (empty($qData['text']) || empty($qData['options']) || empty($qData['correct'])) {
                continue;
            }

            $opts = [];
            foreach ((array) $qData['options'] as $idx => $opt) {
                if (filled($opt)) {
                    $opts[] = ['id' => chr(97 + $idx), 'text' => trim($opt)];
                }
            }

            if (count($opts) >= 2) {
                $quiz->questions()->create([
                    'question_text' => trim($qData['text']),
                    'options' => $opts,
                    'correct_answer' => strtolower(trim($qData['correct'])),
                    'explanation' => $qData['explanation'] ?? null,
                    'sort_order' => $quiz->questions()->count() + 1,
                ]);
            }
        }
    }
}
