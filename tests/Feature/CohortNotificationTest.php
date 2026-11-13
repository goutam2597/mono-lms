<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\LiveClass;
use App\Models\Role;
use App\Models\Student;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CohortNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Instructor $instructor;

    protected Student $student;

    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $superRole = Role::where('slug', 'super-admin')->first();

        $this->admin = Admin::create([
            'role_id' => $superRole?->id,
            'name' => 'Admin User',
            'email' => 'admin@cohortnotify.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $this->instructor = Instructor::create([
            'name' => 'John Instructor',
            'email' => 'john@cohortnotify.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->student = Student::create([
            'name' => 'Alice Student',
            'email' => 'alice@cohortnotify.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Software Engineering',
            'slug' => 'software-engineering',
        ]);

        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'category_id' => $category->id,
            'title' => 'Full-Stack Web Development Bootcamp',
            'slug' => 'fullstack-web-bootcamp',
            'description' => 'Comprehensive cohort course',
            'type' => Course::TYPE_COMBINED,
            'price' => 100,
            'is_published' => true,
        ]);

        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress' => 20,
        ]);
    }

    public function test_publishing_assignment_notifies_enrolled_students(): void
    {
        $response = $this->actingAs($this->instructor, 'instructor')
            ->post(route('instructor.assignments.store', $this->course), [
                'title' => 'Notifications Capstone',
                'total_points' => 100,
                'pass_points' => 60,
                'due_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
                'status' => 'published',
            ]);

        $response->assertRedirect();

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'assignment')
                ->where('data->title', 'New Assignment: Notifications Capstone')
                ->exists(),
            'Enrolled student was not notified about the published assignment.'
        );
    }

    public function test_draft_assignment_does_not_notify_students(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.assignments.store', $this->course), [
                'title' => 'Hidden Draft Assignment',
                'total_points' => 50,
                'pass_points' => 25,
                'status' => 'draft',
            ]);

        $this->assertFalse(
            $this->student->notifications()
                ->where('data->title', 'like', '%Hidden Draft Assignment%')
                ->exists()
        );
    }

    public function test_publishing_draft_assignment_later_notifies_students(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Promoted Draft Assignment',
            'total_points' => 50,
            'pass_points' => 25,
            'allow_resubmission' => true,
            'status' => Assignment::STATUS_DRAFT,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->instructor, 'instructor')
            ->put(route('instructor.assignments.update', $assignment), [
                'title' => 'Promoted Draft Assignment',
                'total_points' => 50,
                'pass_points' => 25,
                'status' => 'published',
            ]);

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'assignment')
                ->where('data->title', 'New Assignment: Promoted Draft Assignment')
                ->exists()
        );
    }

    public function test_student_submission_notifies_course_instructor(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Submission Notice Assignment',
            'total_points' => 100,
            'pass_points' => 60,
            'allow_resubmission' => true,
            'status' => Assignment::STATUS_PUBLISHED,
            'sort_order' => 1,
        ]);

        $this->actingAs($this->student, 'student')
            ->post(route('student.assignments.submit', [$this->course, $assignment]), [
                'submission_content' => 'My project write-up.',
            ]);

        $this->assertTrue(
            $this->instructor->notifications()
                ->where('data->category', 'assignment')
                ->where('data->title', 'New Submission: Submission Notice Assignment')
                ->exists(),
            'Instructor was not notified about the new submission.'
        );
    }

    public function test_grading_submission_notifies_student_with_result(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Graded Assignment',
            'total_points' => 100,
            'pass_points' => 60,
            'allow_resubmission' => true,
            'status' => Assignment::STATUS_PUBLISHED,
            'sort_order' => 1,
        ]);

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'submission_content' => 'Attempt one.',
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->instructor, 'instructor')
            ->post(route('instructor.assignments.grade', [$assignment, $submission]), [
                'score' => 85,
                'instructor_feedback' => 'Excellent architecture choices.',
            ]);

        $notification = $this->student->notifications()
            ->where('data->category', 'assignment')
            ->where('data->title', 'Assignment Graded: Graded Assignment')
            ->first();

        $this->assertNotNull($notification, 'Student was not notified about the grade.');
        $this->assertStringContainsString('85/100', $notification->data['message']);
        $this->assertStringContainsString('passed', $notification->data['message']);
    }

    public function test_instructor_resubmission_request_notifies_student(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Resubmission Assignment',
            'total_points' => 100,
            'pass_points' => 60,
            'allow_resubmission' => true,
            'status' => Assignment::STATUS_PUBLISHED,
            'sort_order' => 1,
        ]);

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'submission_content' => 'First attempt.',
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->instructor, 'instructor')
            ->post(route('instructor.assignments.grade', [$assignment, $submission]), [
                'score' => 30,
                'instructor_feedback' => 'Please add integration tests.',
                'action' => 'request_resubmission',
            ]);

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'assignment')
                ->where('data->title', 'Resubmission Requested: Resubmission Assignment')
                ->exists(),
            'Student was not notified about the resubmission request.'
        );
    }

    public function test_student_resubmission_request_notifies_instructor(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Retest Assignment',
            'total_points' => 100,
            'pass_points' => 60,
            'allow_resubmission' => true,
            'status' => Assignment::STATUS_PUBLISHED,
            'sort_order' => 1,
        ]);

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'score' => 40,
            'status' => AssignmentSubmission::STATUS_GRADED,
            'submitted_at' => now(),
        ]);

        $this->actingAs($this->student, 'student')
            ->post(route('student.assignments.resubmit-request', [$this->course, $assignment]), [
                'resubmission_note' => 'My internet dropped during the demo recording.',
            ]);

        $this->assertTrue(
            $this->instructor->notifications()
                ->where('data->category', 'assignment')
                ->where('data->title', 'Resubmission Request: Retest Assignment')
                ->exists(),
            'Instructor was not notified about the resubmission request.'
        );
    }

    public function test_scheduling_live_class_notifies_enrolled_students(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.live-classes.store', $this->course), [
                'title' => 'Doubt Clinic: Query Optimization',
                'starts_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
                'duration_minutes' => 60,
                'meeting_platform' => 'google_meet',
                'class_type' => LiveClass::TYPE_CONCEPTUAL,
                'meeting_url' => 'https://meet.google.com/abc-defg-hij',
            ]);

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'live_class')
                ->where('data->title', 'Live Class Scheduled: Doubt Clinic: Query Optimization')
                ->exists(),
            'Student was not notified about the scheduled live class.'
        );
    }

    public function test_first_live_class_join_notifies_top_three_rank(): void
    {
        $liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'title' => 'Scrum Review 1',
            'starts_at' => now()->addHour(),
            'duration_minutes' => 45,
            'meeting_platform' => 'zoom',
            'class_type' => LiveClass::TYPE_SCRUM,
            'meeting_url' => 'https://zoom.us/j/998877',
            'status' => 'scheduled',
        ]);

        $this->actingAs($this->student, 'student')
            ->get(route('student.live-classes.join', $liveClass))
            ->assertRedirect('https://zoom.us/j/998877');

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'leaderboard')
                ->where('data->title', 'like', 'Leaderboard Update:%1st%')
                ->exists(),
            'Top-3 student was not notified about their leaderboard rank.'
        );

        // Joining again must not duplicate the leaderboard notification
        $this->actingAs($this->student, 'student')
            ->get(route('student.live-classes.join', $liveClass));

        $this->assertSame(
            1,
            $this->student->notifications()->where('data->category', 'leaderboard')->count()
        );
    }

    public function test_assignment_deadline_reminder_command_sends_once(): void
    {
        Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Due Tomorrow Assignment',
            'total_points' => 80,
            'pass_points' => 40,
            'due_date' => now()->addHours(12),
            'status' => Assignment::STATUS_PUBLISHED,
            'sort_order' => 1,
        ]);

        $this->artisan('notifications:assignment-deadlines')->assertSuccessful();

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'assignment')
                ->where('data->title', 'Assignment Due Soon: Due Tomorrow Assignment')
                ->exists(),
            'Deadline reminder was not sent.'
        );

        // Re-running the scheduler must not duplicate the reminder (dedup key)
        $this->artisan('notifications:assignment-deadlines')->assertSuccessful();

        $this->assertSame(
            1,
            $this->student->notifications()
                ->where('data->title', 'Assignment Due Soon: Due Tomorrow Assignment')
                ->count()
        );
    }

    public function test_deadline_reminder_skips_students_who_submitted(): void
    {
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Already Submitted Assignment',
            'total_points' => 80,
            'pass_points' => 40,
            'due_date' => now()->addHours(10),
            'status' => Assignment::STATUS_PUBLISHED,
            'sort_order' => 1,
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'submission_content' => 'Done early.',
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        $this->artisan('notifications:assignment-deadlines')->assertSuccessful();

        $this->assertFalse(
            $this->student->notifications()
                ->where('data->title', 'like', '%Already Submitted Assignment%')
                ->exists()
        );
    }

    public function test_live_class_reminder_command_sends_24h_and_1h_notices(): void
    {
        // Inside the 1-hour "starting soon" window
        LiveClass::create([
            'course_id' => $this->course->id,
            'title' => 'Starting Soon Class',
            'starts_at' => now()->addMinutes(58),
            'duration_minutes' => 60,
            'meeting_platform' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/111',
            'status' => 'scheduled',
        ]);

        // Inside the 24-hour heads-up window
        LiveClass::create([
            'course_id' => $this->course->id,
            'title' => 'Tomorrow Class',
            'starts_at' => now()->addHours(24),
            'duration_minutes' => 60,
            'meeting_platform' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/222',
            'status' => 'scheduled',
        ]);

        $this->artisan('notifications:live-class-reminders')->assertSuccessful();

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'live_class')
                ->where('data->title', 'Starting Soon: Starting Soon Class')
                ->exists(),
            '1-hour live class reminder was not sent.'
        );

        $this->assertTrue(
            $this->student->notifications()
                ->where('data->category', 'live_class')
                ->where('data->title', 'Live Class Tomorrow: Tomorrow Class')
                ->exists(),
            '24-hour live class reminder was not sent.'
        );

        // Re-running the scheduler must not duplicate reminders (dedup keys)
        $this->artisan('notifications:live-class-reminders')->assertSuccessful();

        $this->assertSame(
            1,
            $this->student->notifications()
                ->where('data->title', 'Starting Soon: Starting Soon Class')
                ->count()
        );
        $this->assertSame(
            1,
            $this->student->notifications()
                ->where('data->title', 'Live Class Tomorrow: Tomorrow Class')
                ->count()
        );
    }

    public function test_notification_pages_render_with_cohort_filters(): void
    {
        $this->actingAs($this->student, 'student')
            ->get(route('student.notifications.index', ['filter' => 'assignment']))
            ->assertOk();

        $this->actingAs($this->instructor, 'instructor')
            ->get(route('instructor.notifications.index', ['filter' => 'assignment']))
            ->assertOk();

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.notifications.index', ['filter' => 'assignment']))
            ->assertOk();
    }
}
