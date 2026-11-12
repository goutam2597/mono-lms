<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\LiveClass;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Role;
use App\Models\Student;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizSystemTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Instructor $instructor;

    protected Student $student;

    protected Course $course;

    protected LiveClass $liveClass;

    protected Quiz $quiz;

    protected QuizQuestion $question;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $superRole = Role::where('slug', 'super-admin')->first();

        $this->admin = Admin::create([
            'role_id' => $superRole?->id,
            'name' => 'Admin User',
            'email' => 'admin@quiztest.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $this->instructor = Instructor::create([
            'name' => 'Sarah Instructor',
            'email' => 'sarah@quiztest.dev',
            'password' => 'password',
            'status' => 'active',
            'verification_status' => 'approved',
        ]);

        $this->student = Student::create([
            'name' => 'Alex Student',
            'email' => 'alex@quiztest.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Web Development',
            'slug' => 'web-development',
            'status' => 'active',
        ]);

        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title' => 'Fullstack Mastery',
            'slug' => 'fullstack-mastery',
            'type' => Course::TYPE_COMBINED,
            'price' => 50.00,
            'level' => 'intermediate',
            'status' => 'published',
        ]);

        $this->liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'title' => 'Live Architecture Workshop',
            'starts_at' => now()->addDays(2),
            'duration_minutes' => 60,
            'meeting_platform' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/123456789',
            'status' => 'scheduled',
        ]);

        // Create an enrollment
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress' => 20,
        ]);

        // Create a published quiz
        $this->quiz = Quiz::create([
            'course_id' => $this->course->id,
            'live_class_id' => $this->liveClass->id,
            'title' => 'Architecture Knowledge Check',
            'passing_percentage' => 70,
            'time_limit_minutes' => 15,
            'max_attempts' => 3,
            'is_published' => true,
        ]);

        $this->question = $this->quiz->questions()->create([
            'question_text' => 'What is the primary role of an API gateway?',
            'type' => 'multiple_choice',
            'options' => [
                ['id' => 'a', 'text' => 'Render HTML on the client'],
                ['id' => 'b', 'text' => 'Route requests, handle auth and rate limiting'],
                ['id' => 'c', 'text' => 'Store persistent relational database records'],
            ],
            'correct_answer' => 'b',
            'explanation' => 'API Gateways act as a reverse proxy routing requests and handling cross-cutting concerns.',
            'points' => 1,
            'sort_order' => 1,
        ]);
    }

    public function test_student_can_view_quiz_attempts_page_with_stats_and_available_quizzes(): void
    {
        // Create an existing completed attempt
        QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'live_class_id' => $this->liveClass->id,
            'attempt_number' => 1,
            'total_questions' => 1,
            'correct_answers_count' => 1,
            'score_percentage' => 100.00,
            'is_passed' => true,
            'started_at' => now()->subMinutes(10),
            'completed_at' => now()->subMinutes(5),
            'answers' => [$this->question->id => 'b'],
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.quiz-attempts'));

        $response->assertOk();
        $response->assertSee('My Quiz Attempts');
        $response->assertSee('Architecture Knowledge Check');
        $response->assertSee('Fullstack Mastery');
        $response->assertSee('100%');
        $response->assertSee('Passed');
        $response->assertSee('Total Attempts');
        $response->assertSee('Avg Score: 100%'); // Average score
        $response->assertSee('Available Quizzes to Take');
    }

    public function test_student_can_filter_quiz_attempts(): void
    {
        QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'attempt_number' => 1,
            'total_questions' => 1,
            'correct_answers_count' => 0,
            'score_percentage' => 0.00,
            'is_passed' => false,
            'started_at' => now()->subMinutes(15),
            'completed_at' => now()->subMinutes(10),
            'answers' => [$this->question->id => 'a'],
        ]);

        // Filter by passed
        $respPassed = $this->actingAs($this->student, 'student')
            ->get(route('student.quiz-attempts', ['status' => 'passed']));
        $respPassed->assertOk();
        $respPassed->assertSee('No quiz attempts found');

        // Filter by failed
        $respFailed = $this->actingAs($this->student, 'student')
            ->get(route('student.quiz-attempts', ['status' => 'failed']));
        $respFailed->assertOk();
        $respFailed->assertSee('Architecture Knowledge Check');
        $respFailed->assertSee('Failed');
    }

    public function test_student_can_take_quiz_and_in_progress_attempt_is_created(): void
    {
        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.quizzes.take', [$this->course, $this->quiz]));

        $response->assertOk();
        $response->assertSee('Architecture Knowledge Check');
        $response->assertSee('What is the primary role of an API gateway?');
        $response->assertSee('Route requests, handle auth and rate limiting');

        $this->assertDatabaseHas('quiz_attempts', [
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'completed_at' => null,
        ]);
    }

    public function test_student_can_submit_quiz_answers_and_get_graded(): void
    {
        $attempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'attempt_number' => 1,
            'total_questions' => 1,
            'correct_answers_count' => 0,
            'score_percentage' => 0.00,
            'is_passed' => false,
            'started_at' => now(),
        ]);

        // Submit correct answer
        $response = $this->actingAs($this->student, 'student')
            ->post(route('student.quizzes.submit', [$this->course, $this->quiz, $attempt]), [
                'answers' => [
                    $this->question->id => 'b',
                ],
            ]);

        $response->assertRedirect(route('student.quizzes.review', $attempt));

        $attempt->refresh();
        $this->assertNotNull($attempt->completed_at);
        $this->assertTrue((bool) $attempt->is_passed);
        $this->assertEquals(100.0, (float) $attempt->score_percentage);
        $this->assertEquals(1, $attempt->correct_answers_count);

        // Verification of student notification
        $latestNotification = $this->student->notifications()->first();
        $this->assertNotNull($latestNotification);
        $this->assertEquals('quiz', $latestNotification->data['category'] ?? null);
    }

    public function test_student_can_review_completed_quiz_attempt(): void
    {
        $attempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'attempt_number' => 1,
            'total_questions' => 1,
            'correct_answers_count' => 1,
            'score_percentage' => 100.00,
            'is_passed' => true,
            'started_at' => now()->subMinutes(10),
            'completed_at' => now()->subMinutes(5),
            'answers' => [$this->question->id => 'b'],
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.quizzes.review', $attempt));

        $response->assertOk();
        $response->assertSee('Architecture Knowledge Check');
        $response->assertSee('PASSED');
        $response->assertSee('Passed ✓');
        $response->assertSee('100%');
        $response->assertSee('API Gateways act as a reverse proxy');
    }

    public function test_student_cannot_take_quiz_if_max_attempts_reached(): void
    {
        $this->quiz->update(['max_attempts' => 1]);

        QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'attempt_number' => 1,
            'total_questions' => 1,
            'correct_answers_count' => 1,
            'score_percentage' => 100.00,
            'is_passed' => true,
            'started_at' => now()->subMinutes(10),
            'completed_at' => now()->subMinutes(5),
            'answers' => [$this->question->id => 'b'],
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.quizzes.take', [$this->course, $this->quiz]));

        $response->assertRedirect(route('student.courses.learn', $this->course));
        $response->assertSessionHas('error');
    }

    public function test_admin_can_create_update_and_delete_course_quizzes_and_questions(): void
    {
        $this->actingAs($this->admin, 'admin');

        // 1. Create a quiz
        $createQuizResp = $this->post(route('admin.courses.quizzes.store', $this->course), [
            'title' => 'Admin Created Quiz',
            'passing_percentage' => 80,
            'time_limit_minutes' => 20,
            'max_attempts' => 2,
            'is_published' => '1',
        ]);
        $createQuizResp->assertRedirect();

        $newQuiz = Quiz::where('title', 'Admin Created Quiz')->first();
        $this->assertNotNull($newQuiz);
        $this->assertEquals(80, $newQuiz->passing_percentage);

        // 2. Add question to the quiz
        $addQuestionResp = $this->post(route('admin.quizzes.questions.store', $newQuiz), [
            'question_text' => 'Is PHP 8.4 supported?',
            'type' => 'true_false',
            'options' => ['True', 'False'],
            'correct_answer' => 'a',
            'explanation' => 'PHP 8.4 is the current runtime.',
            'points' => 2,
        ]);
        $addQuestionResp->assertRedirect();

        $this->assertDatabaseHas('quiz_questions', [
            'quiz_id' => $newQuiz->id,
            'question_text' => 'Is PHP 8.4 supported?',
            'correct_answer' => 'a',
        ]);

        // 3. Update the quiz
        $updateResp = $this->put(route('admin.quizzes.update', $newQuiz), [
            'title' => 'Admin Updated Quiz Title',
            'passing_percentage' => 85,
            'time_limit_minutes' => 30,
            'max_attempts' => 5,
        ]);
        $updateResp->assertRedirect();
        $this->assertEquals('Admin Updated Quiz Title', $newQuiz->fresh()->title);

        // 4. Delete the quiz
        $deleteResp = $this->delete(route('admin.quizzes.destroy', $newQuiz));
        $deleteResp->assertRedirect();
        $this->assertDatabaseMissing('quizzes', ['id' => $newQuiz->id]);
    }

    public function test_instructor_can_manage_quizzes_for_their_own_courses_only(): void
    {
        $this->actingAs($this->instructor, 'instructor');

        // Create quiz for own course
        $resp = $this->post(route('instructor.courses.quizzes.store', $this->course), [
            'title' => 'Instructor Quiz',
            'passing_percentage' => 75,
            'time_limit_minutes' => 10,
            'max_attempts' => 2,
            'is_published' => '1',
        ]);
        $resp->assertRedirect();
        $this->assertDatabaseHas('quizzes', ['title' => 'Instructor Quiz']);

        // Another instructor's course
        $otherInstructor = Instructor::create([
            'name' => 'Other Instructor',
            'email' => 'other@quiztest.dev',
            'password' => 'password',
            'status' => 'active',
        ]);
        $otherCourse = Course::create([
            'instructor_id' => $otherInstructor->id,
            'category_id' => $this->course->category_id,
            'title' => 'Other Course',
            'slug' => 'other-course',
            'status' => 'published',
        ]);

        // Attempt to create quiz for another instructor's course should be forbidden
        $forbiddenResp = $this->post(route('instructor.courses.quizzes.store', $otherCourse), [
            'title' => 'Hacked Quiz',
            'passing_percentage' => 50,
        ]);
        $forbiddenResp->assertForbidden();
    }

    public function test_course_learn_page_displays_quizzes_and_live_session_quizzes(): void
    {
        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.courses.learn', $this->course));

        $response->assertOk();
        $response->assertSee('Quizzes & Live Assessments');
        $response->assertSee('Architecture Knowledge Check');
        $response->assertSee('Live Class: Live Architecture Workshop');
        $response->assertSee('Take Quiz');
    }

    public function test_admin_can_add_quizzes_during_course_creation_for_prerecorded_course(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.courses.store'), [
            'title' => 'Pre-recorded Modern PHP Course',
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->course->category_id,
            'price' => 29.99,
            'level' => 'intermediate',
            'type' => Course::TYPE_RECORDED,
            'is_published' => '1',
            'quizzes' => [
                [
                    'title' => 'Modern PHP Fundamentals Assessment',
                    'time_limit_minutes' => 20,
                    'passing_percentage' => 75,
                    'max_attempts' => 2,
                    'is_published' => '1',
                    'description' => 'Test your knowledge on PHP 8.4 features and strict types.',
                    'questions' => [
                        [
                            'question_text' => 'What does constructor property promotion do in PHP 8?',
                            'type' => 'multiple_choice',
                            'options' => [
                                'Declares and assigns class properties directly in the constructor signature',
                                'Increases memory consumption automatically',
                                'Converts PHP scripts into binary executables',
                            ],
                            'correct_answer' => 'a',
                            'explanation' => 'Constructor property promotion reduces boilerplate by combining property declaration and assignment.',
                            'points' => 2,
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.courses.index'));

        $course = Course::where('slug', 'pre-recorded-modern-php-course')->first();
        $this->assertNotNull($course);
        $this->assertEquals(Course::TYPE_RECORDED, $course->type);

        $quiz = $course->quizzes()->first();
        $this->assertNotNull($quiz);
        $this->assertEquals('Modern PHP Fundamentals Assessment', $quiz->title);
        $this->assertEquals(75, $quiz->passing_percentage);
        $this->assertEquals(1, $quiz->questions()->count());

        $question = $quiz->questions()->first();
        $this->assertEquals('What does constructor property promotion do in PHP 8?', $question->question_text);
        $this->assertEquals('a', $question->correct_answer);
        $this->assertEquals(2, $question->points);
    }

    public function test_instructor_can_add_quizzes_during_course_creation(): void
    {
        $this->actingAs($this->instructor, 'instructor');

        $response = $this->post(route('instructor.courses.store'), [
            'title' => 'Instructor Authored Pre-recorded Course',
            'category_id' => $this->course->category_id,
            'price' => 19.99,
            'level' => 'beginner',
            'type' => Course::TYPE_RECORDED,
            'is_published' => '1',
            'quizzes' => [
                [
                    'title' => 'Beginner Checkpoint',
                    'time_limit_minutes' => 10,
                    'passing_percentage' => 80,
                    'max_attempts' => 3,
                    'is_published' => '1',
                    'questions' => [
                        [
                            'question_text' => 'Is CSS used for styling web documents?',
                            'type' => 'multiple_choice',
                            'options' => ['Yes', 'No'],
                            'correct_answer' => 'a',
                            'points' => 1,
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertRedirect(route('instructor.courses.index'));

        $course = Course::where('slug', 'instructor-authored-pre-recorded-course')->first();
        $this->assertNotNull($course);
        $this->assertEquals($this->instructor->id, $course->instructor_id);

        $quiz = $course->quizzes()->first();
        $this->assertNotNull($quiz);
        $this->assertEquals('Beginner Checkpoint', $quiz->title);
        $this->assertEquals(1, $quiz->questions()->count());
    }

    public function test_prerecorded_course_learn_page_displays_course_assessments(): void
    {
        $preRecordedCourse = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $this->course->category_id,
            'title' => 'Pure Recorded Course',
            'slug' => 'pure-recorded-course',
            'type' => Course::TYPE_RECORDED,
            'price' => 0,
            'level' => 'beginner',
            'status' => 'published',
        ]);

        $quiz = $preRecordedCourse->quizzes()->create([
            'title' => 'Recorded Module 1 Quiz',
            'passing_percentage' => 60,
            'time_limit_minutes' => 10,
            'is_published' => true,
        ]);

        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $preRecordedCourse->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.courses.learn', $preRecordedCourse));

        $response->assertOk();
        $response->assertSee('Quizzes & Course Assessments');
        $response->assertSee('Recorded Module 1 Quiz');
        $response->assertSee('Course Assessment');
    }
}
