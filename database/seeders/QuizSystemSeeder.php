<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\LiveClass;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Database\Seeder;

class QuizSystemSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::find(1);
        if (! $course) {
            return;
        }

        // Enable combined (live + recorded) on Course 1 so both live classes & recorded lessons are active
        $course->update(['type' => 'combined']);

        // 1. Create or get Live Class session
        $liveClass = LiveClass::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'Live Interactive Workshop: Building Responsive Layouts'],
            [
                'description' => 'Hands-on live coding workshop covering modern responsive layouts with CSS Grid and Flexbox with live Q&A.',
                'starts_at' => now()->addDays(2)->setHour(18)->setMinute(0),
                'duration_minutes' => 90,
                'meeting_platform' => 'google_meet',
                'meeting_url' => 'https://meet.google.com/demo-lms-live',
                'status' => 'scheduled',
                'sort_order' => 1,
            ]
        );

        // 2. Course-level assessment quiz
        $courseQuiz = Quiz::firstOrCreate(
            ['course_id' => $course->id, 'title' => 'HTML5, CSS3, Flexbox & Grid Assessment'],
            [
                'description' => 'Test your understanding of semantic HTML elements, CSS Box Model, Flexbox layouts, and CSS Grid systems.',
                'time_limit_minutes' => 15,
                'passing_percentage' => 70,
                'max_attempts' => 3,
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        if ($courseQuiz->questions()->count() === 0) {
            $courseQuiz->questions()->createMany([
                [
                    'question_text' => 'Which HTML5 semantic element represents independent, self-contained content suitable for syndication?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['id' => 'a', 'text' => '<article>'],
                        ['id' => 'b', 'text' => '<section>'],
                        ['id' => 'c', 'text' => '<div>'],
                        ['id' => 'd', 'text' => '<aside>'],
                    ],
                    'correct_answer' => 'a',
                    'explanation' => '<article> is designed for self-contained, independent items such as blog posts, user comments, or news stories.',
                    'points' => 1,
                    'sort_order' => 1,
                ],
                [
                    'question_text' => 'In CSS Flexbox, which property aligns flex items along the cross axis?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['id' => 'a', 'text' => 'justify-content'],
                        ['id' => 'b', 'text' => 'align-items'],
                        ['id' => 'c', 'text' => 'flex-direction'],
                        ['id' => 'd', 'text' => 'align-content'],
                    ],
                    'correct_answer' => 'b',
                    'explanation' => 'align-items defines how flex items are aligned along the cross axis (perpendicular to main axis).',
                    'points' => 1,
                    'sort_order' => 2,
                ],
                [
                    'question_text' => 'Which CSS Grid property specifies the number and widths of columns in a grid layout?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['id' => 'a', 'text' => 'grid-template-columns'],
                        ['id' => 'b', 'text' => 'grid-columns-template'],
                        ['id' => 'c', 'text' => 'column-gap'],
                        ['id' => 'd', 'text' => 'grid-auto-flow'],
                    ],
                    'correct_answer' => 'a',
                    'explanation' => 'grid-template-columns defines the line names and track sizing functions of the grid columns.',
                    'points' => 1,
                    'sort_order' => 3,
                ],
                [
                    'question_text' => 'What is the primary role of the viewport meta tag in modern web development?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['id' => 'a', 'text' => 'It optimizes server performance for mobile requests'],
                        ['id' => 'b', 'text' => 'It tells the browser how to control the page dimensions and scaling on mobile screens'],
                        ['id' => 'c', 'text' => 'It disables pinch-to-zoom automatically'],
                        ['id' => 'd', 'text' => 'It converts desktop CSS into mobile CSS'],
                    ],
                    'correct_answer' => 'b',
                    'explanation' => 'The viewport meta tag (width=device-width, initial-scale=1.0) controls the viewport size and zoom level on mobile devices.',
                    'points' => 1,
                    'sort_order' => 4,
                ],
            ]);
        }

        // 3. Live Class Session Quiz (attached to $liveClass)
        $liveQuiz = Quiz::firstOrCreate(
            ['course_id' => $course->id, 'live_class_id' => $liveClass->id, 'title' => 'Live Workshop Concept Check & Challenge'],
            [
                'description' => 'Real-time concept check for students participating in our Live Interactive Workshop session.',
                'time_limit_minutes' => 10,
                'passing_percentage' => 75,
                'max_attempts' => 3,
                'is_published' => true,
                'sort_order' => 2,
            ]
        );

        if ($liveQuiz->questions()->count() === 0) {
            $liveQuiz->questions()->createMany([
                [
                    'question_text' => 'Which CSS viewport unit corresponds to 1% of the width of the visible browser window?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['id' => 'a', 'text' => 'vw'],
                        ['id' => 'b', 'text' => 'vh'],
                        ['id' => 'c', 'text' => 'rem'],
                        ['id' => 'd', 'text' => 'em'],
                    ],
                    'correct_answer' => 'a',
                    'explanation' => '1vw is equal to 1% of the width of the viewport (browser window).',
                    'points' => 1,
                    'sort_order' => 1,
                ],
                [
                    'question_text' => 'Which CSS media query feature is used to detect if the user prefers dark mode?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['id' => 'a', 'text' => 'prefers-color-scheme'],
                        ['id' => 'b', 'text' => 'color-mode'],
                        ['id' => 'c', 'text' => 'theme-preference'],
                        ['id' => 'd', 'text' => 'display-mode'],
                    ],
                    'correct_answer' => 'a',
                    'explanation' => 'prefers-color-scheme: dark tests whether the user has expressed a system preference for dark mode.',
                    'points' => 1,
                    'sort_order' => 2,
                ],
                [
                    'question_text' => 'In mobile-first responsive architecture, media queries typically scale up using which rule?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['id' => 'a', 'text' => 'min-width'],
                        ['id' => 'b', 'text' => 'max-width'],
                        ['id' => 'c', 'text' => 'device-width'],
                        ['id' => 'd', 'text' => 'orientation'],
                    ],
                    'correct_answer' => 'a',
                    'explanation' => 'Mobile-first design defines mobile layout by default and adds enhancements for larger displays using min-width queries.',
                    'points' => 1,
                    'sort_order' => 3,
                ],
            ]);
        }

        // 4. Sample Completed Attempt for Student 11 (or first student)
        $student = Student::find(11) ?? Student::first();
        if ($student && QuizAttempt::where('student_id', $student->id)->where('quiz_id', $courseQuiz->id)->count() === 0) {
            $questions = $courseQuiz->questions;
            $answers = [];
            if ($questions->count() >= 4) {
                $answers[$questions[0]->id] = 'a'; // correct
                $answers[$questions[1]->id] = 'b'; // correct
                $answers[$questions[2]->id] = 'a'; // correct
                $answers[$questions[3]->id] = 'a'; // incorrect (correct is b)
            }

            QuizAttempt::create([
                'quiz_id' => $courseQuiz->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'attempt_number' => 1,
                'total_questions' => 4,
                'correct_answers_count' => 3,
                'score_percentage' => 75.00,
                'is_passed' => true,
                'started_at' => now()->subHours(2),
                'completed_at' => now()->subHours(2)->addMinutes(8),
                'answers' => $answers,
            ]);
        }
    }
}
