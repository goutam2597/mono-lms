<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    private Course $course;

    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $instructor = Instructor::create([
            'name' => 'Instructor Test',
            'email' => 'inst@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Mastering Laravel',
            'slug' => 'mastering-laravel',
            'price' => 79.00,
            'level' => 'intermediate',
            'is_published' => true,
        ]);

        $this->student = Student::create([
            'name' => 'Jane Student',
            'email' => 'jane@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);
    }

    public function test_guest_is_redirected_when_accessing_saved_courses_page(): void
    {
        $this->get(route('student.wishlist'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_gets_unauthorized_json_when_toggling_wishlist_via_ajax(): void
    {
        $this->postJson(route('courses.wishlist.toggle', $this->course))
            ->assertStatus(401)
            ->assertJson([
                'authenticated' => false,
                'redirect' => route('login'),
            ]);
    }

    public function test_student_can_view_saved_courses_page(): void
    {
        $this->actingAs($this->student, 'student');

        Wishlist::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $response = $this->get(route('student.wishlist'));

        $response->assertOk();
        $response->assertSee('Saved Courses');
        $response->assertSee('Mastering Laravel');
    }

    public function test_student_can_toggle_course_into_wishlist(): void
    {
        $this->actingAs($this->student, 'student');

        $response = $this->postJson(route('courses.wishlist.toggle', $this->course));

        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'saved' => true,
            'count' => 1,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $this->assertTrue($this->student->hasWishlisted($this->course));
    }

    public function test_student_can_toggle_course_into_wishlist_by_slug(): void
    {
        $this->actingAs($this->student, 'student');

        $response = $this->postJson(route('courses.wishlist.toggle', $this->course->slug));

        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'saved' => true,
            'count' => 1,
        ]);

        $this->assertTrue($this->student->hasWishlisted($this->course));
    }

    public function test_student_can_untoggle_course_from_wishlist(): void
    {
        $this->actingAs($this->student, 'student');

        Wishlist::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $this->assertTrue($this->student->hasWishlisted($this->course));

        $response = $this->postJson(route('courses.wishlist.toggle', $this->course));

        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'saved' => false,
            'count' => 0,
        ]);

        $this->assertDatabaseMissing('wishlists', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $this->assertFalse($this->student->hasWishlisted($this->course));
    }

    public function test_student_can_destroy_saved_course(): void
    {
        $this->actingAs($this->student, 'student');

        Wishlist::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $this->delete(route('student.wishlist.destroy', $this->course))
            ->assertRedirect();

        $this->assertDatabaseMissing('wishlists', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);
    }

    public function test_student_sidebar_displays_saved_courses_item(): void
    {
        $this->actingAs($this->student, 'student');

        Wishlist::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $response = $this->get(route('student.dashboard'));

        $response->assertOk();
        $response->assertSee(route('student.wishlist'));
        $response->assertSee('Saved Courses');
    }

    public function test_course_detail_shows_wishlist_button(): void
    {
        $this->actingAs($this->student, 'student');

        $response = $this->get(route('courses.show', $this->course->slug));

        $response->assertOk();
        $response->assertSee('data-wishlist-toggle', false);
        $response->assertSee('Save to Wishlist');
    }
}
