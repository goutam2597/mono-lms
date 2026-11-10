<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_enrolled_student_can_submit_course_review(): void
    {
        $student = Student::first();
        $course = Course::published()->first();

        Enrollment::firstOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], [
            'status' => 'active',
            'progress' => 50,
        ]);

        $response = $this->actingAs($student, 'student')->post(route('courses.reviews.store', $course->slug), [
            'rating' => 5,
            'comment' => 'Outstanding course structure and very clear explanations!',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'rating' => 5,
            'comment' => 'Outstanding course structure and very clear explanations!',
            'status' => 'pending',
        ]);
    }

    public function test_non_enrolled_student_cannot_submit_review(): void
    {
        $student = Student::create([
            'name' => 'Non Enrolled Student',
            'email' => 'nonenrolled@example.com',
            'password' => 'password',
            'status' => 'active',
        ]);
        $course = Course::published()->first();

        $response = $this->actingAs($student, 'student')->post(route('courses.reviews.store', $course->slug), [
            'rating' => 4,
            'comment' => 'I have not taken this course but leaving a review.',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('reviews', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_student_can_update_their_existing_review(): void
    {
        $student = Student::first();
        $course = Course::published()->first();

        Enrollment::firstOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], [
            'status' => 'active',
            'progress' => 100,
        ]);

        Review::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'rating' => 4,
            'comment' => 'Initial review',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($student, 'student')->post(route('courses.reviews.store', $course->slug), [
            'rating' => 5,
            'comment' => 'Updated review after finishing the final project.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'rating' => 5,
            'comment' => 'Updated review after finishing the final project.',
            'status' => 'pending',
        ]);
    }

    public function test_student_can_delete_their_review(): void
    {
        $student = Student::first();
        $course = Course::published()->first();

        Review::updateOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], [
            'rating' => 5,
            'comment' => 'Temporary feedback',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($student, 'student')->delete(route('courses.reviews.destroy', $course->slug));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('reviews', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_admin_can_view_reviews_index(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.reviews.index'));
        $response->assertOk();
        $response->assertSee('Student Reviews');
        $response->assertSee('Total Reviews');
        $response->assertSee('Pending Moderation');
    }

    public function test_admin_can_approve_and_reject_reviews(): void
    {
        $admin = Admin::first();
        $student = Student::first();
        $course = Course::published()->first();

        $review = Review::updateOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], [
            'rating' => 5,
            'comment' => 'Needs moderation',
            'status' => 'pending',
        ]);

        // 1. Approve
        $approveResponse = $this->actingAs($admin, 'admin')->patch(route('admin.reviews.approve', $review));
        $approveResponse->assertSessionHas('success');
        $this->assertEquals('approved', $review->fresh()->status);

        // 2. Reject
        $rejectResponse = $this->actingAs($admin, 'admin')->patch(route('admin.reviews.reject', $review));
        $rejectResponse->assertSessionHas('success');
        $this->assertEquals('rejected', $review->fresh()->status);
    }

    public function test_admin_can_add_official_reply_to_review(): void
    {
        $admin = Admin::first();
        $student = Student::first();
        $course = Course::published()->first();

        $review = Review::updateOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], [
            'rating' => 5,
            'comment' => 'Great course!',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.reviews.reply', $review), [
            'instructor_reply' => 'Thank you for your generous feedback!',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('Thank you for your generous feedback!', $review->fresh()->instructor_reply);
        $this->assertNotNull($review->fresh()->replied_at);
    }

    public function test_admin_bulk_moderation_actions(): void
    {
        $admin = Admin::first();
        $student = Student::first();
        $courses = Course::published()->take(2)->get();

        $rev1 = Review::updateOrCreate([
            'student_id' => $student->id,
            'course_id' => $courses[0]->id,
        ], ['rating' => 4, 'status' => 'pending']);

        // Bulk Approve
        $response = $this->actingAs($admin, 'admin')->post(route('admin.reviews.bulk-approve'), [
            'ids' => [$rev1->id],
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('approved', $rev1->fresh()->status);

        // Bulk Reject
        $response = $this->actingAs($admin, 'admin')->post(route('admin.reviews.bulk-reject'), [
            'ids' => [$rev1->id],
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('rejected', $rev1->fresh()->status);

        // Bulk Delete
        $response = $this->actingAs($admin, 'admin')->post(route('admin.reviews.bulk-destroy'), [
            'ids' => [$rev1->id],
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('reviews', ['id' => $rev1->id]);
    }

    public function test_instructor_can_reply_to_course_review(): void
    {
        $course = Course::published()->whereNotNull('instructor_id')->first();
        $instructor = $course->instructor;
        $student = Student::first();

        $review = Review::updateOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], [
            'rating' => 5,
            'comment' => 'Best teacher on this subject!',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($instructor, 'instructor')->post(route('instructor.reviews.reply', $review), [
            'instructor_reply' => 'I really appreciate your kind review!',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('I really appreciate your kind review!', $review->fresh()->instructor_reply);
    }
}
