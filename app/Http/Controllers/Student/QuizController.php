<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class QuizController extends Controller
{
    /**
     * Display student's quiz attempts history, stats, and accessible quizzes.
     */
    public function index(Request $request): View
    {
        $student = auth('student')->user();

        $enrolledCourseIds = Enrollment::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('course_id');

        $query = QuizAttempt::where('student_id', $student->id)
            ->with(['quiz.course', 'quiz.liveClass', 'course', 'liveClass'])
            ->latest('id');

        // Filter by pass/fail status
        $filter = $request->query('status', 'all');
        if ($filter === 'passed') {
            $query->where('is_passed', true);
        } elseif ($filter === 'failed') {
            $query->where('is_passed', false);
        }

        // Search by quiz title or course title
        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $query->where(function ($q) use ($term) {
                $q->whereHas('quiz', fn ($sub) => $sub->where('title', 'like', $term))
                    ->orWhereHas('course', fn ($sub) => $sub->where('title', 'like', $term));
            });
        }

        $attempts = $query->paginate(10)->withQueryString();

        // Calculate aggregate statistics
        $allStudentAttempts = QuizAttempt::where('student_id', $student->id)->get();
        $totalAttempts = $allStudentAttempts->count();
        $passedCount = $allStudentAttempts->where('is_passed', true)->count();
        $failedCount = $allStudentAttempts->where('is_passed', false)->count();
        $averageScore = $totalAttempts > 0
            ? round($allStudentAttempts->avg('score_percentage'), 1)
            : 0;

        // Quizzes available in student's enrolled courses
        $availableQuizzes = Quiz::published()
            ->whereIn('course_id', $enrolledCourseIds)
            ->with(['course', 'liveClass'])
            ->withCount('questions')
            ->orderBy('sort_order')
            ->get();

        return view('pages.student.quiz-attempts', [
            'attempts' => $attempts,
            'totalAttempts' => $totalAttempts,
            'passedCount' => $passedCount,
            'failedCount' => $failedCount,
            'averageScore' => $averageScore,
            'availableQuizzes' => $availableQuizzes,
            'activeFilter' => $filter,
        ]);
    }

    /**
     * Start or resume taking a quiz.
     */
    public function take(Request $request, Course $course, Quiz $quiz): View|RedirectResponse
    {
        $student = auth('student')->user();

        abort_unless($quiz->course_id === $course->id, 404);
        abort_unless($quiz->is_published, 404);

        // Ensure student has access to this course (enrolled or course is free)
        $isEnrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();

        if (! $isEnrolled && ! $course->isFree()) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->with('info', 'Please enroll in this course to take the quiz assessment.');
        }

        // Auto-enroll if free
        if (! $isEnrolled && $course->isFree()) {
            Enrollment::firstOrCreate(
                ['student_id' => $student->id, 'course_id' => $course->id],
                ['status' => 'active', 'progress' => 0]
            );
        }

        // Check if retake cooldown is active
        if ($quiz->isRetakeLocked($student)) {
            $remaining = $quiz->retakeCooldownRemainingHuman($student);
            $availableAt = $quiz->retakeAvailableAt($student)?->format('M d, Y · h:i A');

            return redirect()
                ->route('student.courses.learn', $course)
                ->with('error', "Retake cooldown active: You can retake this quiz in {$remaining} (available {$availableAt}).");
        }

        // Check if student has remaining attempts
        if (! $quiz->canAttempt($student)) {
            $latest = $quiz->latestAttemptFor($student);

            return redirect()
                ->route('student.courses.learn', $course)
                ->with('error', 'You have reached the maximum number of attempts ('.$quiz->max_attempts.') for this quiz.'.($latest ? ' Your best score was '.$latest->formattedScore().'.' : ''));
        }

        // Look for an in-progress attempt started within the time limit
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->whereNull('completed_at')
            ->latest('id')
            ->first();

        // Check if time expired on in-progress attempt
        if ($attempt && $quiz->time_limit_minutes > 0) {
            $deadline = $attempt->started_at->addMinutes($quiz->time_limit_minutes);
            if (now()->gt($deadline)) {
                // Auto-complete expired attempt as failed with 0 score
                $attempt->update([
                    'completed_at' => $deadline,
                    'is_passed' => false,
                    'score_percentage' => 0,
                    'total_questions' => $quiz->questions()->count(),
                    'correct_answers_count' => 0,
                ]);
                $attempt = null;
            }
        }

        if (! $attempt) {
            $attemptNumber = $quiz->attemptsCount($student) + 1;
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'live_class_id' => $quiz->live_class_id,
                'attempt_number' => $attemptNumber,
                'total_questions' => $quiz->questions()->count(),
                'correct_answers_count' => 0,
                'score_percentage' => 0.00,
                'is_passed' => false,
                'started_at' => now(),
            ]);
        }

        $questions = $quiz->questions()->get();

        return view('pages.student.quiz-take', [
            'course' => $course,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'questions' => $questions,
        ]);
    }

    /**
     * Submit and grade the quiz attempt.
     */
    public function submit(Request $request, Course $course, Quiz $quiz, QuizAttempt $attempt): RedirectResponse
    {
        $student = auth('student')->user();

        abort_unless($attempt->student_id === $student->id, 403);
        abort_unless($attempt->quiz_id === $quiz->id, 404);

        if ($attempt->isCompleted()) {
            return redirect()->route('student.quizzes.review', $attempt);
        }

        $submittedAnswers = (array) $request->input('answers', []);
        $questions = $quiz->questions()->get();
        $totalQuestions = $questions->count();
        $correctCount = 0;

        foreach ($questions as $q) {
            $given = $submittedAnswers[$q->id] ?? null;
            if ($q->isCorrect($given)) {
                $correctCount++;
            }
        }

        $scorePercentage = $totalQuestions > 0
            ? round(($correctCount / $totalQuestions) * 100, 2)
            : 100.00;

        $isPassed = $scorePercentage >= $quiz->passing_percentage;

        $attempt->update([
            'total_questions' => $totalQuestions,
            'correct_answers_count' => $correctCount,
            'score_percentage' => $scorePercentage,
            'is_passed' => $isPassed,
            'completed_at' => now(),
            'answers' => $submittedAnswers,
        ]);

        NotificationService::send(
            $student,
            $isPassed ? 'Quiz Assessment Passed! 🎉' : 'Quiz Completed',
            'You scored '.number_format($scorePercentage, 0).'% on "'.$quiz->title.'" for "'.$course->trans('title').'".'.($isPassed ? ' Great job!' : ' Passing score: '.$quiz->passing_percentage.'%.'),
            category: 'quiz',
            actionUrl: route('student.quizzes.review', $attempt),
            actionLabel: 'Review Answers',
            sendMail: false,
            metadata: [
                'quiz_id' => $quiz->id,
                'attempt_id' => $attempt->id,
                'course_id' => $course->id,
                'score' => $scorePercentage,
                'is_passed' => $isPassed,
            ],
        );

        $flashType = $isPassed ? 'success' : 'info';
        $flashMsg = $isPassed
            ? 'Congratulations! You passed "'.$quiz->title.'" with a score of '.number_format($scorePercentage, 0).'%.'
            : 'Quiz completed. Your score is '.number_format($scorePercentage, 0).'%. Passing threshold is '.$quiz->passing_percentage.'%.';

        return redirect()
            ->route('student.quizzes.review', $attempt)
            ->with($flashType, $flashMsg);
    }

    /**
     * Review the questions, user answers, and correct answers for an attempt.
     */
    public function review(QuizAttempt $attempt): View
    {
        $student = auth('student')->user();

        abort_unless($attempt->student_id === $student->id, 403);

        $attempt->load(['quiz.questions', 'quiz.course.instructor', 'quiz.liveClass', 'course']);

        return view('pages.student.quiz-review', [
            'attempt' => $attempt,
            'quiz' => $attempt->quiz,
            'course' => $attempt->course,
            'questions' => $attempt->quiz->questions,
        ]);
    }
}
