<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Language;
use App\Models\Role;
use App\Models\Student;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LanguageSeeder::class);
    }

    public function test_each_panel_keeps_its_own_locale_session(): void
    {
        // Storefront switches to Arabic...
        $this->get(route('locale.switch', 'ar'))->assertRedirect();
        $this->get('/')->assertOk()->assertSee('dir="rtl"', false);

        // ...but the admin login page (separate session key) stays LTR English.
        $this->get(route('admin.login'))->assertOk()->assertSee('dir="ltr"', false);
        $this->get('/')->assertOk()->assertSee('dir="rtl"', false);
    }

    public function test_arabic_renders_rtl_direction(): void
    {
        $this->get(route('admin.locale.switch', 'ar'))->assertRedirect();
        $this->get(route('admin.login'))->assertOk()->assertSee('dir="rtl"', false);
    }

    public function test_locale_persists_to_the_student_account(): void
    {
        $student = Student::create(['name' => 'S', 'email' => 's2@test.dev', 'password' => 'password', 'status' => 'active']);

        $this->post(route('login.attempt'), ['email' => $student->email, 'password' => 'password']);

        $this->get(route('student.locale.switch', 'hi'))->assertRedirect();

        $this->assertDatabaseHas('students', ['id' => $student->id, 'locale' => 'hi']);
    }

    public function test_disabled_languages_are_not_offerable(): void
    {
        Language::where('code', 'ar')->update(['is_active' => false]);
        Language::clearCache();

        $this->get(route('locale.switch', 'ar'))->assertNotFound();
    }

    public function test_admin_dashboard_renders_rtl_with_sidebar_docked_to_right(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $superRole = Role::where('slug', 'super-admin')->first();
        $admin = Admin::create([
            'role_id' => $superRole?->id,
            'name' => 'Admin User',
            'email' => 'admin_rtl@test.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $this->actingAs($admin, 'admin');

        $this->get(route('admin.locale.switch', 'ar'))->assertRedirect();

        $response = $this->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('[dir="rtl"] #dashboard-sidebar', false);
        $response->assertSee('right: 0 !important;', false);
        $response->assertSee('[dir="rtl"] #dashboard-main', false);
        $response->assertSee('padding-right: var(--dashboard-sidebar-width)', false);
        $response->assertSee(__('admin.nav.items.dashboard', [], 'ar'));
        $response->assertSee(__('admin.dashboard.student_statistics', [], 'ar'));
    }

    public function test_instructor_dashboard_renders_rtl_with_sidebar_docked_to_right(): void
    {
        $instructor = Instructor::create([
            'name' => 'Instructor User',
            'email' => 'instructor_rtl@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->actingAs($instructor, 'instructor');

        $this->get(route('instructor.locale.switch', 'ar'))->assertRedirect();

        $response = $this->get(route('instructor.dashboard'));
        $response->assertOk();
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('[dir="rtl"] #dashboard-sidebar', false);
        $response->assertSee('right: 0 !important;', false);
        $response->assertSee(__('instructor.nav.courses', [], 'ar'));
    }

    public function test_student_dashboard_renders_rtl_with_sidebar_docked_to_right(): void
    {
        $student = Student::create([
            'name' => 'Student User',
            'email' => 'student_rtl@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->actingAs($student, 'student');

        $this->get(route('student.locale.switch', 'ar'))->assertRedirect();

        $response = $this->get(route('student.dashboard'));
        $response->assertOk();
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('[dir="rtl"] #dashboard-sidebar', false);
        $response->assertSee('right: 0 !important;', false);
        $response->assertSee(__('student.nav.enrolled', [], 'ar'));
    }
}
