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
}
