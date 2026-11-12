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
use App\Models\LiveClassAttendance;
use App\Models\Role;
use App\Models\Student;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CohortAssignmentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Instructor $instructor;

    protected Student $student;

    protected Course $course;

    protected LiveClass $liveClass;

    protected Assignment $assignment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $superRole = Role::where('slug', 'super-admin')->first();

        $this->admin = Admin::create([
            'role_id' => $superRole?->id,
            'name' => 'Admin User',
            'email' => 'admin@cohorttest.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $this->instructor = Instructor::create([
            'name' => 'John Instructor',
            'email' => 'john@cohorttest.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->student = Student::create([
            'name' => 'Alice Student',
            'email' => 'alice@cohorttest.dev',
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

        $this->liveClass = LiveClass::create([
            'course_id' => $this->course->id,
            'title' => 'Live Session 1: Modern Backend Architectures',
            'starts_at' => now()->addDays(2),
            'duration_minutes' => 90,
            'meeting_platform' => 'zoom',
            'class_type' => LiveClass::TYPE_MAIN,
            'meeting_url' => 'https://zoom.us/j/123456789',
            'status' => 'scheduled',
        ]);

        $this->assignment = Assignment::create([
            'course_id' => $this->course->id,
            'title' => 'Project Milestone 1: RESTful API Design',
            'description' => 'Build a robust API with validation and tests.',
            'total_points' => 100,
            'pass_points' => 60,
            'due_date' => now()->addDays(5),
            'allow_resubmission' => true,
            'status' => Assignment::STATUS_PUBLISHED,
            'sort_order' => 1,
        ]);

        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress' => 20,
        ]);
    }

    public function test_instructor_can_create_assignment(): void
    {
        $response = $this->actingAs($this->instructor, 'instructor')
            ->post(route('instructor.assignments.store', $this->course), [
                'title' => 'Final Capstone Project',
                'description' => 'Deploy a full-stack platform with Docker.',
                'total_points' => 100,
                'pass_points' => 70,
                'due_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
                'allow_resubmission' => 1,
                'status' => 'published',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assignments', [
            'course_id' => $this->course->id,
            'title' => 'Final Capstone Project',
            'pass_points' => 70,
            'allow_resubmission' => true,
        ]);
    }

    public function test_admin_can_update_and_delete_assignment(): void
    {
        $updateResponse = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.assignments.update', $this->assignment), [
                'title' => 'Project Milestone 1: Updated Title',
                'description' => 'Updated requirements',
                'total_points' => 120,
                'pass_points' => 75,
                'allow_resubmission' => 1,
                'status' => 'published',
            ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('assignments', [
            'id' => $this->assignment->id,
            'title' => 'Project Milestone 1: Updated Title',
            'total_points' => 120,
        ]);

        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.assignments.destroy', $this->assignment));

        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('assignments', [
            'id' => $this->assignment->id,
        ]);
    }

    public function test_student_can_view_assignment_and_submit_solution(): void
    {
        $viewResponse = $this->actingAs($this->student, 'student')
            ->get(route('student.assignments.show', [$this->course, $this->assignment]));

        $viewResponse->assertOk()
            ->assertSee('Project Milestone 1: RESTful API Design')
            ->assertSee('100 Total Points');

        $submitResponse = $this->actingAs($this->student, 'student')
            ->post(route('student.assignments.submit', [$this->course, $this->assignment]), [
                'external_link' => 'https://github.com/alice/mono-api-project',
                'submission_content' => 'Here is my repository with full test coverage.',
            ]);

        $submitResponse->assertRedirect();
        $this->assertDatabaseHas('assignment_submissions', [
            'assignment_id' => $this->assignment->id,
            'student_id' => $this->student->id,
            'external_link' => 'https://github.com/alice/mono-api-project',
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
        ]);
    }

    public function test_instructor_can_grade_submission_and_provide_feedback(): void
    {
        $submission = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $this->student->id,
            'external_link' => 'https://github.com/alice/project',
            'submission_content' => 'Completed all endpoints.',
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
        ]);

        $gradeResponse = $this->actingAs($this->instructor, 'instructor')
            ->post(route('instructor.assignments.grade', [$this->assignment, $submission]), [
                'score' => 85,
                'instructor_feedback' => 'Excellent architecture and clean tests! Passed.',
                'action' => 'grade',
            ]);

        $gradeResponse->assertRedirect();
        $submission->refresh();

        $this->assertEquals(AssignmentSubmission::STATUS_GRADED, $submission->status);
        $this->assertEquals(85, $submission->score);
        $this->assertTrue($submission->isPassed());
        $this->assertStringContainsString('Excellent architecture', $submission->instructor_feedback);
    }

    public function test_student_can_request_resubmission(): void
    {
        $submission = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $this->student->id,
            'external_link' => 'https://github.com/alice/project',
            'score' => 45,
            'status' => AssignmentSubmission::STATUS_GRADED,
        ]);

        $requestResponse = $this->actingAs($this->student, 'student')
            ->post(route('student.assignments.resubmit-request', [$this->course, $this->assignment]), [
                'resubmission_note' => 'I fixed the authentication bug and updated my tests.',
            ]);

        $requestResponse->assertRedirect();
        $submission->refresh();

        $this->assertEquals(AssignmentSubmission::STATUS_RESUBMISSION_REQUESTED, $submission->status);
        $this->assertStringContainsString('fixed the authentication bug', $submission->resubmission_note);
    }

    public function test_student_joining_live_class_records_attendance(): void
    {
        $this->assertDatabaseMissing('live_class_attendances', [
            'live_class_id' => $this->liveClass->id,
            'student_id' => $this->student->id,
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('student.live-classes.join', $this->liveClass));

        $response->assertRedirect($this->liveClass->meeting_url);

        $this->assertDatabaseHas('live_class_attendances', [
            'live_class_id' => $this->liveClass->id,
            'student_id' => $this->student->id,
        ]);

        $this->assertTrue($this->liveClass->fresh()->attendedBy($this->student));
    }

    public function test_cohort_leaderboard_computes_ranks_and_renders_page(): void
    {
        $studentBob = Student::create([
            'name' => 'Bob Learner',
            'email' => 'bob@cohorttest.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        Enrollment::create([
            'student_id' => $studentBob->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress' => 50,
        ]);

        // Alice: 85 pts from assignment + 10 pts from live attendance = 95 pts
        AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $this->student->id,
            'score' => 85,
            'status' => AssignmentSubmission::STATUS_GRADED,
        ]);
        LiveClassAttendance::create([
            'live_class_id' => $this->liveClass->id,
            'student_id' => $this->student->id,
        ]);

        // Bob: 50 pts from assignment = 50 pts
        AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $studentBob->id,
            'score' => 50,
            'status' => AssignmentSubmission::STATUS_GRADED,
        ]);

        $leaderboard = $this->course->cohortLeaderboard();

        $this->assertCount(2, $leaderboard);
        $this->assertEquals($this->student->id, $leaderboard[0]['student_id']);
        $this->assertEquals(1, $leaderboard[0]['rank']);
        $this->assertEquals(95, $leaderboard[0]['total_score']);

        $this->assertEquals($studentBob->id, $leaderboard[1]['student_id']);
        $this->assertEquals(2, $leaderboard[1]['rank']);
        $this->assertEquals(50, $leaderboard[1]['total_score']);

        // View leaderboard page as student
        $viewResponse = $this->actingAs($this->student, 'student')
            ->get(route('student.courses.leaderboard', $this->course));

        $viewResponse->assertOk()
            ->assertSee('Cohort Leaderboard')
            ->assertSee('Alice Student')
            ->assertSee('Bob Learner')
            ->assertSee('95 pts');
    }
}
