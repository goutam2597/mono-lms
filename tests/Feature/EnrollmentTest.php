<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    private Course $course;

    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $instructor = Instructor::create(['name' => 'I', 'email' => 'i@test.dev', 'password' => 'password', 'status' => 'active']);
        $this->course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'price' => 49.99,
            'level' => 'beginner',
            'type' => 'recorded',
            'is_published' => true,
        ]);
        $this->student = Student::create(['name' => 'S', 'email' => 's@test.dev', 'password' => 'password', 'status' => 'active']);
    }

    public function test_student_can_enroll_free_course_directly(): void
    {
        $this->course->update(['price' => 0]);

        $this->actingAs($this->student, 'student')
            ->post(route('courses.enroll', $this->course))
            ->assertRedirect(route('student.courses.learn', $this->course));

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        // Enrolling twice does not duplicate.
        $this->post(route('courses.enroll', $this->course))
            ->assertSessionHas('info');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_paid_course_enroll_redirects_to_checkout(): void
    {
        $this->actingAs($this->student, 'student')
            ->post(route('courses.enroll', $this->course))
            ->assertRedirect(route('courses.show', $this->course->slug))
            ->assertSessionHas('info');

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_guest_cannot_enroll(): void
    {
        $this->post(route('courses.enroll', $this->course))->assertRedirect(route('login'));
        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_instructor_sees_own_course_enrollments(): void
    {
        $instructor = Instructor::where('email', 'i@test.dev')->first();
        $this->course->enrollments()->create(['student_id' => $this->student->id]);

        $this->actingAs($instructor, 'instructor');
        $this->get(route('instructor.students'))->assertOk()->assertSee($this->student->name);
    }

    public function test_instructor_cannot_edit_another_instructors_course(): void
    {
        $other = Instructor::create(['name' => 'Other', 'email' => 'other@test.dev', 'password' => 'password', 'status' => 'active']);

        $this->actingAs($other, 'instructor');
        $this->get(route('instructor.courses.edit', $this->course))->assertForbidden();
    }

    public function test_course_detail_page_shows_curriculum_and_enroll(): void
    {
        $this->course->lessons()->create(['title' => 'Intro Lesson', 'duration_minutes' => 30, 'sort_order' => 1]);

        $this->get(route('courses.show', $this->course->slug))
            ->assertOk()
            ->assertSee('Test Course')
            ->assertSee('Intro Lesson')
            ->assertSee(__('courses.guest_enroll_hint'));
    }
}
