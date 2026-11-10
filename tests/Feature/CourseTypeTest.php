<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTypeTest extends TestCase
{
    use RefreshDatabase;

    private Instructor $instructor;

    private Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instructor = Instructor::create([
            'name' => 'I', 'email' => 'i@test.dev', 'password' => 'password',
            'status' => 'active', 'verification_status' => 'approved',
        ]);
        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'title' => 'Type Course',
            'slug' => 'type-course',
            'price' => 50.00,
            'level' => 'beginner',
            'type' => 'combined',
            'is_published' => true,
        ]);
    }

    private function actingInstructor(): Instructor
    {
        $instructor = Instructor::where('email', 'i@test.dev')->first();
        $this->actingAs($instructor, 'instructor');

        return $instructor;
    }

    public function test_instructor_can_create_course_with_type(): void
    {
        $this->actingInstructor();

        $this->post(route('instructor.courses.store'), [
            'title' => 'Live Cohort',
            'price' => 200,
            'level' => 'beginner',
            'type' => 'live',
        ])->assertRedirect(route('instructor.courses.index'));

        $this->assertDatabaseHas('courses', ['title' => 'Live Cohort', 'type' => 'live']);
    }

    public function test_instructor_can_schedule_live_classes(): void
    {
        $this->actingInstructor();

        $this->post(route('instructor.live-classes.store', $this->course), [
            'title' => 'Kickoff Session',
            'starts_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'duration_minutes' => 90,
            'meeting_platform' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/123456',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('live_classes', [
            'course_id' => $this->course->id,
            'title' => 'Kickoff Session',
            'meeting_platform' => 'zoom',
        ]);
    }

    public function test_instructor_cannot_manage_live_classes_of_others(): void
    {
        $other = Instructor::create(['name' => 'O', 'email' => 'o@test.dev', 'password' => 'password', 'status' => 'active']);

        $live = $this->course->liveClasses()->create([
            'title' => 'X', 'starts_at' => now()->addDay(), 'meeting_platform' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/1',
        ]);

        $this->actingAs($other, 'instructor')
            ->put(route('instructor.live-classes.update', $live), [
                'title' => 'Hacked', 'starts_at' => now()->addDay(), 'meeting_platform' => 'zoom', 'meeting_url' => 'https://zoom.us/j/2',
            ])
            ->assertForbidden();

        $this->actingAs($other, 'instructor')
            ->delete(route('instructor.live-classes.destroy', $live))
            ->assertForbidden();

        $this->assertDatabaseHas('live_classes', ['id' => $live->id, 'title' => 'X']);
    }

    public function test_detail_page_shows_schedule_and_hides_meeting_link_until_enrolled(): void
    {
        $this->course->liveClasses()->create([
            'title' => 'Orientation',
            'starts_at' => now()->addDays(2),
            'meeting_platform' => 'google_meet',
            'meeting_url' => 'https://meet.google.com/abc-def',
        ]);

        $guest = $this->get(route('courses.show', $this->course->slug))
            ->assertOk()
            ->assertSee('Live Class Schedule')
            ->assertSee('Orientation');

        // Meeting link must not leak to guests.
        $this->assertStringNotContainsString('meet.google.com/abc-def', $guest->getContent());

        $student = Student::create(['name' => 'S', 'email' => 's@test.dev', 'password' => 'password', 'status' => 'active']);
        $this->course->enrollments()->create(['student_id' => $student->id, 'status' => 'active']);

        // Enrolled student sees the Join button; the raw link is only served
        // through the guarded join redirect.
        $this->actingAs($student, 'student')
            ->get(route('courses.show', $this->course->slug))
            ->assertOk()
            ->assertSee('Join Class');

        $live = $this->course->liveClasses()->first();
        $this->actingAs($student, 'student')
            ->get(route('student.live-classes.join', $live))
            ->assertRedirect('https://meet.google.com/abc-def');
    }

    public function test_admin_course_form_stores_type_and_live_classes(): void
    {
        $admin = Admin::create(['name' => 'A', 'email' => 'a@test.dev', 'password' => 'password', 'status' => 'active', 'is_super_admin' => true]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.courses.store'), [
                'title' => 'Admin Made Live',
                'instructor_id' => $this->instructor->id,
                'price' => 10,
                'level' => 'beginner',
                'type' => 'live',
            ])->assertRedirect(route('admin.courses.index'));

        $course = Course::where('title', 'Admin Made Live')->first();
        $this->assertSame('live', $course->type);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.live-classes.store', $course), [
                'title' => 'Session 1',
                'starts_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
                'meeting_platform' => 'jitsi',
                'meeting_url' => 'https://meet.jit.si/lms1',
            ])->assertSessionHas('success');

        $this->assertDatabaseHas('live_classes', ['course_id' => $course->id, 'title' => 'Session 1']);
    }
}
