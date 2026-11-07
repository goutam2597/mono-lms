<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\LanguageSeeder::class);
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
}
