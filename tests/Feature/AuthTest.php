<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_panel_has_an_independent_session(): void
    {
        $admin = Admin::create(['name' => 'A', 'email' => 'a@test.dev', 'password' => 'password', 'is_super_admin' => true, 'status' => 'active']);
        $student = Student::create(['name' => 'S', 'email' => 's@test.dev', 'password' => 'password', 'status' => 'active']);

        // Log into the admin panel...
        $this->post(route('admin.login.attempt'), ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        // ...then into the student panel from the same browser session.
        $this->post(route('login.attempt'), ['email' => $student->email, 'password' => 'password'])
            ->assertRedirect(route('student.dashboard'));

        // Both panels are authenticated at once.
        $this->get('/admin')->assertOk();
        $this->get('/student')->assertOk();

        // Logging out of the student panel keeps the admin session alive.
        $this->post(route('logout'));
        $this->get('/student')->assertRedirect(route('login'));
        $this->get('/admin')->assertOk();
    }

    public function test_inactive_accounts_cannot_sign_in(): void
    {
        Student::create(['name' => 'X', 'email' => 'x@test.dev', 'password' => 'password', 'status' => 'inactive']);

        $this->post(route('login.attempt'), ['email' => 'x@test.dev', 'password' => 'password'])
            ->assertSessionHasErrors('email');
    }

    public function test_instructor_can_sign_in(): void
    {
        Instructor::create(['name' => 'I', 'email' => 'i@test.dev', 'password' => 'password', 'status' => 'active']);

        $this->post(route('instructor.login.attempt'), ['email' => 'i@test.dev', 'password' => 'password'])
            ->assertRedirect(route('instructor.dashboard'));

        $this->get('/instructor')->assertOk();
    }

    public function test_student_can_self_register(): void
    {
        $this->post(route('register.attempt'), [
            'name' => 'New Student',
            'email' => 'new@test.dev',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseHas('students', ['email' => 'new@test.dev']);
    }

    public function test_instructor_can_self_register(): void
    {
        $this->get(route('instructor.register'))->assertOk();

        $this->post(route('instructor.register.attempt'), [
            'name' => 'Jane Instructor',
            'email' => 'jane.instructor@test.dev',
            'password' => 'password123',
            'designation' => 'Lead AI Instructor',
            'terms' => '1',
        ])->assertRedirect(route('instructor.dashboard'));

        $this->assertDatabaseHas('instructors', [
            'email' => 'jane.instructor@test.dev',
            'status' => 'pending_verification',
        ]);
        $this->assertAuthenticated('instructor');
    }

    public function test_admin_can_sign_in_via_universal_login_and_see_dashboard(): void
    {
        $admin = Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@learnify.test',
            'password' => '12345678',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $this->post(route('login.attempt'), [
            'email' => 'admin@learnify.test',
            'password' => '12345678',
        ])->assertRedirect(route('admin.dashboard'));

        $response = $this->get(route('admin.dashboard'));
        $response->assertOk()
            ->assertSee('Admin overview')
            ->assertSee('Welcome back, Administrator')
            ->assertSee('Revenue & Enrollments', false)
            ->assertSee('Recent Orders')
            ->assertSee('Courses by Category')
            ->assertSee('Top Courses');
    }

    public function test_authenticated_instructor_visiting_login_or_register_redirects_to_panel_dashboard(): void
    {
        $instructor = Instructor::create([
            'name' => 'Dr. Smith',
            'email' => 'smith@test.dev',
            'password' => 'password123',
            'status' => 'active',
        ]);

        $this->actingAs($instructor, 'instructor')
            ->get(route('instructor.login'))
            ->assertRedirect(route('instructor.dashboard'));

        $this->actingAs($instructor, 'instructor')
            ->get(route('instructor.register'))
            ->assertRedirect(route('instructor.dashboard'));
    }

    public function test_authenticated_student_visiting_login_or_register_redirects_to_panel_dashboard(): void
    {
        $student = Student::create([
            'name' => 'Alice Student',
            'email' => 'alice@test.dev',
            'password' => 'password123',
            'status' => 'active',
        ]);

        $this->actingAs($student, 'student')
            ->get(route('login'))
            ->assertRedirect(route('student.dashboard'));

        $this->actingAs($student, 'student')
            ->get(route('register'))
            ->assertRedirect(route('student.dashboard'));
    }

    public function test_authenticated_admin_visiting_login_redirects_to_panel_dashboard(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'super@test.dev',
            'password' => 'password123',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
