<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Course;
use App\Models\Event;
use App\Models\Instructor;
use App\Models\Plan;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBulkAndModalsTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin-modal@test.dev',
            'password' => 'password123',
            'is_super_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_bulk_destroy_courses(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $instructor = Instructor::create([
            'name' => 'Instructor One',
            'email' => 'instructor1@test.dev',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Web Dev',
            'slug' => 'web-dev',
        ]);

        $c1 = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'Course 1',
            'slug' => 'course-1',
            'price' => 49.99,
            'is_published' => true,
        ]);

        $c2 = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'Course 2',
            'slug' => 'course-2',
            'price' => 59.99,
            'is_published' => true,
        ]);

        $c3 = Course::create([
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'Course 3',
            'slug' => 'course-3',
            'price' => 69.99,
            'is_published' => true,
        ]);

        $response = $this->post(route('admin.courses.bulk-destroy'), [
            'ids' => [$c1->id, $c2->id],
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseMissing('courses', ['id' => $c1->id]);
        $this->assertDatabaseMissing('courses', ['id' => $c2->id]);
        $this->assertDatabaseHas('courses', ['id' => $c3->id]);
    }

    public function test_admin_can_update_event_and_bulk_destroy_events(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $e1 = Event::create([
            'title' => 'Original Live Class',
            'start_time' => now()->addDays(2),
            'duration_minutes' => 60,
            'capacity' => 50,
            'is_published' => true,
        ]);

        $e2 = Event::create([
            'title' => 'Second Webinar',
            'start_time' => now()->addDays(4),
            'duration_minutes' => 45,
            'capacity' => 100,
            'is_published' => true,
        ]);

        // Test update
        $updateResponse = $this->put(route('admin.events.update', $e1), [
            'title' => 'Updated Live Class',
            'start_time' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'duration_minutes' => 90,
            'meeting_link' => 'https://zoom.us/j/123456789',
            'capacity' => 75,
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('events', [
            'id' => $e1->id,
            'title' => 'Updated Live Class',
            'duration_minutes' => 90,
        ]);

        // Test bulk destroy
        $bulkResponse = $this->post(route('admin.events.bulk-destroy'), [
            'ids' => [$e1->id, $e2->id],
        ]);

        $bulkResponse->assertRedirect();
        $this->assertDatabaseMissing('events', ['id' => $e1->id]);
        $this->assertDatabaseMissing('events', ['id' => $e2->id]);
    }

    public function test_admin_can_bulk_destroy_plans(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $p1 = Plan::create([
            'name' => 'Monthly Starter',
            'slug' => 'monthly-starter',
            'price' => 19.00,
            'billing_period' => 'monthly',
            'is_active' => true,
        ]);

        $p2 = Plan::create([
            'name' => 'Annual Pro',
            'slug' => 'annual-pro',
            'price' => 190.00,
            'billing_period' => 'yearly',
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.plans.bulk-destroy'), [
            'ids' => [$p1->id, $p2->id],
        ]);

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseMissing('plans', ['id' => $p1->id]);
        $this->assertDatabaseMissing('plans', ['id' => $p2->id]);
    }

    public function test_admin_can_store_and_update_admin_user_via_modal(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $storeResponse = $this->post(route('admin.admins.store'), [
            'name' => 'Moderator Joe',
            'email' => 'joe@moderator.dev',
            'password' => 'SecretPassword123',
            'status' => 'active',
            'is_super_admin' => '0',
        ]);

        $storeResponse->assertRedirect(route('admin.admins.index'));

        $newAdmin = Admin::where('email', 'joe@moderator.dev')->firstOrFail();
        $this->assertTrue(Hash::check('SecretPassword123', $newAdmin->password));

        // Update without changing password
        $updateResponse = $this->put(route('admin.admins.update', $newAdmin), [
            'name' => 'Moderator Joe Updated',
            'email' => 'joe@moderator.dev',
            'status' => 'inactive',
            'is_super_admin' => '0',
        ]);

        $updateResponse->assertRedirect(route('admin.admins.index'));

        $newAdmin->refresh();
        $this->assertSame('Moderator Joe Updated', $newAdmin->name);
        $this->assertSame('inactive', $newAdmin->status);
        $this->assertTrue(Hash::check('SecretPassword123', $newAdmin->password));
    }

    public function test_admin_can_update_student_via_modal(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $student = Student::create([
            'name' => 'Student Jane',
            'email' => 'jane@student.dev',
            'password' => 'secret123',
            'phone' => '1234567890',
            'status' => 'active',
        ]);

        $response = $this->put(route('admin.students.update', $student), [
            'name' => 'Student Jane Updated',
            'email' => 'jane.updated@student.dev',
            'phone' => '0987654321',
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('admin.students.index'));

        $student->refresh();
        $this->assertSame('Student Jane Updated', $student->name);
        $this->assertSame('jane.updated@student.dev', $student->email);
        $this->assertSame('0987654321', $student->phone);
        $this->assertSame('inactive', $student->status);
    }

    public function test_admin_can_store_and_update_instructor_via_modal(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $storeResponse = $this->post(route('admin.instructors.store'), [
            'name' => 'Prof. Alex Smith',
            'email' => 'alex.smith@test.dev',
            'password' => 'AlexSecret123',
            'designation' => 'Senior AI Researcher',
            'phone' => '+1555123456',
            'bio' => 'Teaching machine learning for 10 years.',
            'status' => 'active',
        ]);

        $storeResponse->assertRedirect(route('admin.instructors.index'));
        $instructor = Instructor::where('email', 'alex.smith@test.dev')->firstOrFail();
        $this->assertSame('Prof. Alex Smith', $instructor->name);
        $this->assertSame('Senior AI Researcher', $instructor->designation);
        $this->assertTrue(Hash::check('AlexSecret123', $instructor->password));

        // Update instructor without modifying password
        $updateResponse = $this->put(route('admin.instructors.update', $instructor), [
            'name' => 'Prof. Alex Smith Jr.',
            'email' => 'alex.smith@test.dev',
            'designation' => 'Lead Data Scientist',
            'status' => 'inactive',
        ]);

        $updateResponse->assertRedirect(route('admin.instructors.index'));
        $instructor->refresh();
        $this->assertSame('Prof. Alex Smith Jr.', $instructor->name);
        $this->assertSame('Lead Data Scientist', $instructor->designation);
        $this->assertSame('inactive', $instructor->status);
        $this->assertTrue(Hash::check('AlexSecret123', $instructor->password));
    }

    public function test_admin_can_store_and_update_plan_via_modal(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $storeResponse = $this->post(route('admin.plans.store'), [
            'name' => 'Premium Plus',
            'price' => 39.99,
            'billing_period' => 'monthly',
            'features' => "Access to all courses\nDirect mentorship\nPriority certificate",
            'description' => 'Best for serious students',
            'is_active' => '1',
        ]);

        $storeResponse->assertRedirect(route('admin.plans.index'));
        $plan = Plan::where('name', 'Premium Plus')->firstOrFail();
        $this->assertEquals(39.99, (float) $plan->price);
        $this->assertSame('monthly', $plan->billing_period);
        $this->assertCount(3, $plan->features);
        $this->assertTrue($plan->is_active);

        // Update plan
        $updateResponse = $this->put(route('admin.plans.update', $plan), [
            'name' => 'Premium Elite',
            'price' => 49.99,
            'billing_period' => 'monthly',
            'features' => "All features\nVIP Support",
            'is_active' => '0',
        ]);

        $updateResponse->assertRedirect(route('admin.plans.index'));
        $plan->refresh();
        $this->assertSame('Premium Elite', $plan->name);
        $this->assertEquals(49.99, (float) $plan->price);
        $this->assertFalse($plan->is_active);
    }

    public function test_admin_can_create_edit_and_manage_courses_and_lessons(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $category = Category::create([
            'name' => 'Data Science',
            'slug' => 'data-science',
        ]);

        $instructor = Instructor::create([
            'name' => 'External Instructor',
            'email' => 'external@test.dev',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        // 1. Admin creates course assigned to external instructor
        $createPageResponse = $this->get(route('admin.courses.create'));
        $createPageResponse->assertOk();

        $storeResponse = $this->post(route('admin.courses.store'), [
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'title' => 'Deep Learning 101',
            'level' => 'intermediate',
            'price' => 79.99,
            'old_price' => 99.99,
            'description' => 'A comprehensive deep learning course.',
            'is_published' => '1',
        ]);

        $storeResponse->assertRedirect(route('admin.courses.index'));
        $course = Course::where('slug', 'deep-learning-101')->firstOrFail();
        $this->assertSame($instructor->id, $course->instructor_id);
        $this->assertTrue($course->is_published);

        // 2. Admin creates course assigned to "Myself" (admin self-assignment)
        $storeSelfResponse = $this->post(route('admin.courses.store'), [
            'instructor_id' => 'self',
            'category_id' => $category->id,
            'title' => 'Admin Special Masterclass',
            'level' => 'beginner',
            'price' => 0.00,
            'is_published' => '1',
        ]);

        $storeSelfResponse->assertRedirect(route('admin.courses.index'));
        $selfCourse = Course::where('slug', 'admin-special-masterclass')->firstOrFail();
        $adminInstructor = Instructor::where('email', $admin->email)->firstOrFail();
        $this->assertSame($adminInstructor->id, $selfCourse->instructor_id);

        // 3. Admin visits edit page and updates the course
        $editPageResponse = $this->get(route('admin.courses.edit', $course));
        $editPageResponse->assertOk();

        $updateResponse = $this->put(route('admin.courses.update', $course), [
            'instructor_id' => 'self',
            'category_id' => $category->id,
            'title' => 'Deep Learning 101 Advanced Edition',
            'level' => 'advanced',
            'price' => 89.99,
            'is_published' => '1',
        ]);

        $updateResponse->assertRedirect(route('admin.courses.index'));
        $course->refresh();
        $this->assertSame('Deep Learning 101 Advanced Edition', $course->title);
        $this->assertSame('advanced', $course->level);
        $this->assertSame($adminInstructor->id, $course->instructor_id);

        // 4. Admin manages lessons on the course
        $lessonResponse = $this->post(route('admin.courses.lessons.store', $course), [
            'title' => 'Lesson 1: Introduction to Tensors',
            'duration_minutes' => 25,
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        $lessonResponse->assertRedirect();
        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'title' => 'Lesson 1: Introduction to Tensors',
            'duration_minutes' => 25,
        ]);

        $lesson = $course->lessons()->firstOrFail();

        $deleteLessonResponse = $this->delete(route('admin.courses.lessons.destroy', $lesson));
        $deleteLessonResponse->assertRedirect();
        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
    }

    public function test_user_directory_renders_with_bulk_delete_and_modal(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $student = Student::create([
            'name' => 'Learner Doe',
            'email' => 'learner@test.dev',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        $instructor = Instructor::create([
            'name' => 'Prof Smith',
            'email' => 'prof@test.dev',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        $response = $this->get(route('admin.users.index'));
        $response->assertOk();
        $response->assertSee('User Directory');
        $response->assertSee('Learner Doe');
        $response->assertSee('Prof Smith');
        $response->assertSee('Super Admin');
        $response->assertSee('data-bulk-toolbar', false);
        $response->assertSee('id="user-modal"', false);
    }

    public function test_admin_can_bulk_destroy_users_of_mixed_types(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $s1 = Student::create([
            'name' => 'Student 1',
            'email' => 's1@test.dev',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        $i1 = Instructor::create([
            'name' => 'Instructor 1',
            'email' => 'i1@test.dev',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        $a2 = Admin::create([
            'name' => 'Sub Admin',
            'email' => 'subadmin@test.dev',
            'password' => 'secret12345',
            'status' => 'active',
        ]);

        $response = $this->post(route('admin.users.bulk-destroy'), [
            'ids' => [
                "student:{$s1->id}",
                "instructor:{$i1->id}",
                "admin:{$a2->id}",
                "admin:{$admin->id}", // Attempting self deletion should be safely skipped
            ],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('students', ['id' => $s1->id]);
        $this->assertDatabaseMissing('instructors', ['id' => $i1->id]);
        $this->assertDatabaseMissing('admins', ['id' => $a2->id]);
        $this->assertDatabaseHas('admins', ['id' => $admin->id]); // Super admin is preserved
    }

    public function test_admin_can_destroy_single_user_from_directory(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $student = Student::create([
            'name' => 'Disposable Student',
            'email' => 'disposable@test.dev',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        $response = $this->delete(route('admin.users.destroy', ['type' => 'student', 'id' => $student->id]));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_admin_can_create_student_via_modal_endpoint(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin, 'admin');

        $response = $this->post(route('admin.students.store'), [
            'name' => 'Newly Created Student',
            'email' => 'new-student@test.dev',
            'password' => 'password123',
            'phone' => '+1234567890',
            'status' => 'active',
            'redirect_to' => route('admin.users.index'),
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('students', [
            'name' => 'Newly Created Student',
            'email' => 'new-student@test.dev',
        ]);
    }
}
