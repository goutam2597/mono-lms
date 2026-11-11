<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonFreePreviewTest extends TestCase
{
    use RefreshDatabase;

    private Instructor $instructor;

    private Course $course;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@test.dev',
            'password' => 'password',
            'status' => 'active',
            'is_super_admin' => true,
        ]);

        $this->instructor = Instructor::create([
            'name' => 'Instructor Joe',
            'email' => 'joe@test.dev',
            'password' => 'password',
            'status' => 'active',
            'verification_status' => 'approved',
        ]);

        $this->course = Course::create([
            'instructor_id' => $this->instructor->id,
            'title' => 'Master Web Design',
            'slug' => 'master-web-design',
            'price' => 49.99,
            'level' => 'beginner',
            'type' => 'recorded',
            'is_published' => true,
        ]);
    }

    public function test_instructor_can_create_free_preview_lesson(): void
    {
        $this->actingAs($this->instructor, 'instructor');

        $response = $this->post(route('instructor.lessons.store', $this->course), [
            'title' => '01. Free Course Overview',
            'duration_minutes' => 12,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_free' => '1',
        ]);

        $response->assertSessionHas('success');

        $lesson = Lesson::first();
        $this->assertNotNull($lesson);
        $this->assertTrue($lesson->isFree());
        $this->assertTrue((bool) $lesson->is_free);
    }

    public function test_instructor_can_update_lesson_free_status(): void
    {
        $this->actingAs($this->instructor, 'instructor');

        $lesson = Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Lesson 1',
            'duration_minutes' => 10,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 1,
            'is_free' => false,
        ]);

        $this->assertFalse($lesson->isFree());

        // Toggle to free
        $this->put(route('instructor.lessons.update', $lesson), [
            'title' => 'Lesson 1 Unlocked',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_free' => '1',
        ])->assertSessionHas('success');

        $lesson->refresh();
        $this->assertTrue($lesson->isFree());

        // Toggle back to locked
        $this->put(route('instructor.lessons.update', $lesson), [
            'title' => 'Lesson 1 Locked Again',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            // No is_free sent
        ])->assertSessionHas('success');

        $lesson->refresh();
        $this->assertFalse($lesson->isFree());
    }

    public function test_admin_can_create_and_update_free_preview_lesson(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->post(route('admin.courses.lessons.store', $this->course), [
            'title' => 'Admin Free Lesson',
            'duration_minutes' => 15,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_free' => '1',
        ])->assertSessionHas('success');

        $lesson = Lesson::first();
        $this->assertTrue($lesson->isFree());

        $this->put(route('admin.courses.lessons.update', $lesson), [
            'title' => 'Admin Updated Lesson',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_free' => '0',
        ])->assertSessionHas('success');

        $lesson->refresh();
        $this->assertFalse($lesson->isFree());
    }

    public function test_lesson_is_locked_for_helper(): void
    {
        $freeLesson = Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Free Intro',
            'sort_order' => 1,
            'is_free' => true,
        ]);

        $lockedLesson = Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Paid Chapter 1',
            'sort_order' => 2,
            'is_free' => false,
        ]);

        $student = Student::create([
            'name' => 'Alice',
            'email' => 'alice@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        // Guest access: Student must be logged in to access/watch any lesson
        $this->assertTrue($freeLesson->isLockedFor(null), 'Free lesson MUST be locked for guest (requires login)');
        $this->assertTrue($lockedLesson->isLockedFor(null), 'Paid lesson MUST be locked for guest');

        // Non-enrolled student
        $this->assertFalse($freeLesson->isLockedFor($student), 'Free lesson should NOT be locked for non-enrolled student');
        $this->assertTrue($lockedLesson->isLockedFor($student), 'Paid lesson MUST be locked for non-enrolled student');

        // Enroll student
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
            'progress' => 0,
        ]);

        $this->assertFalse($freeLesson->isLockedFor($student), 'Free lesson should NOT be locked for enrolled student');
        $this->assertFalse($lockedLesson->isLockedFor($student), 'Paid lesson should NOT be locked for enrolled student');
    }

    public function test_course_detail_shows_free_preview_and_locked_badges(): void
    {
        Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Free Intro Lesson',
            'duration_minutes' => 8,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 1,
            'is_free' => true,
        ]);

        Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Advanced Secret Techniques',
            'duration_minutes' => 35,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 2,
            'is_free' => false,
        ]);

        $response = $this->get(route('courses.show', $this->course->slug));
        $response->assertOk();

        // Should show Free Preview trigger for free lesson
        $response->assertSee('Free Preview');
        $response->assertSee('data-preview-trigger', false);

        // Should show Locked indicator for paid lesson
        $response->assertSee('Locked');
    }

    public function test_free_course_auto_enrolls_logged_in_student(): void
    {
        $freeCourse = Course::create([
            'instructor_id' => $this->instructor->id,
            'title' => 'HTML Complete Free Course',
            'slug' => 'html-complete-free',
            'price' => 0,
            'level' => 'beginner',
            'type' => 'recorded',
            'is_published' => true,
        ]);

        $this->assertTrue($freeCourse->isFree());

        Lesson::create([
            'course_id' => $freeCourse->id,
            'title' => 'Lesson 1: Intro',
            'sort_order' => 1,
            'is_free' => false, // Course is free, so lesson will be unlocked automatically
        ]);

        $student = Student::create([
            'name' => 'Bob',
            'email' => 'bob@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->actingAs($student, 'student');

        // Viewing the free course auto-enrolls the student
        $response = $this->get(route('courses.show', $freeCourse->slug));
        $response->assertOk();

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $freeCourse->id,
        ]);

        // Learn route should now be fully accessible
        $lesson = $freeCourse->lessons->first();
        $learnResponse = $this->get(route('student.courses.learn', ['course' => $freeCourse, 'lesson' => $lesson->id]));
        $learnResponse->assertOk();
    }

    public function test_guest_is_prompted_with_login_modal_to_watch_video(): void
    {
        Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Sample Free Lecture',
            'duration_minutes' => 10,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 1,
            'is_free' => true,
        ]);

        $response = $this->get(route('courses.show', $this->course->slug));
        $response->assertOk();

        // Must include login modal
        $response->assertSee('course-login-modal');
        $response->assertSee('Sign In to Watch Free Lesson');
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
    }

    public function test_guest_accessing_learn_page_is_redirected_to_login(): void
    {
        $lesson = Lesson::create([
            'course_id' => $this->course->id,
            'title' => 'Sample Free Lecture',
            'duration_minutes' => 10,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 1,
            'is_free' => true,
        ]);

        // Unauthenticated guest hits learn route
        $response = $this->get(route('student.courses.learn', ['course' => $this->course, 'lesson' => $lesson->id]));
        $response->assertRedirect(route('login'));
    }
}
