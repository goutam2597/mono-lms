<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Role;
use App\Models\Student;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LanguageSeeder::class);
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_each_panel_maintains_its_own_theme_session_independently(): void
    {
        // 1. Toggle admin theme to dark
        $response = $this->postJson(route('theme.toggle', 'admin'), ['theme' => 'dark']);
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'panel' => 'admin',
                'theme' => 'dark',
            ]);
        $this->assertEquals('dark', session('admin_theme'));
        $this->assertNull(session('instructor_theme'));
        $this->assertNull(session('student_theme'));

        // 2. Toggle instructor theme to light explicitly
        $response = $this->postJson(route('theme.toggle', 'instructor'), ['theme' => 'light']);
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'panel' => 'instructor',
                'theme' => 'light',
            ]);
        $this->assertEquals('dark', session('admin_theme'));
        $this->assertEquals('light', session('instructor_theme'));
        $this->assertNull(session('student_theme'));

        // 3. Toggle student theme to dark
        $response = $this->postJson(route('theme.toggle', 'student'), ['theme' => 'dark']);
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'panel' => 'student',
                'theme' => 'dark',
            ]);
        $this->assertEquals('dark', session('admin_theme'));
        $this->assertEquals('light', session('instructor_theme'));
        $this->assertEquals('dark', session('student_theme'));

        // 4. Admin theme toggles back to light without touching instructor or student
        $response = $this->postJson(route('theme.toggle', 'admin'));
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'panel' => 'admin',
                'theme' => 'light',
            ]);
        $this->assertEquals('light', session('admin_theme'));
        $this->assertEquals('light', session('instructor_theme'));
        $this->assertEquals('dark', session('student_theme'));
    }

    public function test_invalid_panel_aborts_with_404(): void
    {
        $this->post('/theme/storefront')->assertNotFound();
        $this->post('/theme/guest')->assertNotFound();
        $this->post('/theme/random')->assertNotFound();
    }

    public function test_admin_dashboard_renders_theme_toggle_and_inline_script(): void
    {
        $role = Role::where('slug', 'super-admin')->first();
        $admin = Admin::create([
            'role_id' => $role?->id ?? 1,
            'name' => 'Super Admin',
            'email' => 'admin@test.dev',
            'password' => bcrypt('password'),
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_theme' => 'dark'])
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee("'mono_theme_' + panel", false);
        $response->assertSee('theme-toggle-btn', false);
        $response->assertSee('theme-icon-sun', false);
        $response->assertSee('theme-icon-moon', false);
    }

    public function test_instructor_dashboard_renders_theme_toggle_and_inline_script(): void
    {
        $instructor = Instructor::create([
            'name' => 'Test Instructor',
            'email' => 'inst@test.dev',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($instructor, 'instructor')
            ->withSession(['instructor_theme' => 'dark'])
            ->get(route('instructor.dashboard'));

        $response->assertOk();
        $response->assertSee("'mono_theme_' + panel", false);
        $response->assertSee('theme-toggle-btn', false);
    }

    public function test_student_portal_renders_student_theme_toggle(): void
    {
        $student = Student::create([
            'name' => 'Test Student',
            'email' => 'student@test.dev',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($student, 'student')
            ->withSession(['student_theme' => 'dark'])
            ->get(route('student.dashboard'));

        $response->assertOk();
        $response->assertSee('mono_theme_student', false);
        $response->assertSee('student-theme-toggle', false);
    }

    public function test_storefront_strictly_removes_dark_mode(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        // Public storefront has script removing .dark class unconditionally
        $response->assertSee("document.documentElement.classList.remove('dark')", false);
    }
}
