<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Setting;
use App\Models\Student;
use App\Services\TurnstileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TurnstileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fake Cloudflare siteverify with a per-test payload. Registered inside
     * each test that needs it — Http::fake stubs accumulate and the first
     * matching stub wins, so a test-level rejection fake must be the only one.
     */
    private function fakeSiteverify(bool $success): void
    {
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => $success,
                ...($success ? [] : ['error-codes' => ['invalid-input-response']]),
            ]),
        ]);
    }

    private function enableTurnstile(): void
    {
        Setting::set('turnstile_enabled', '1');
        Setting::set('turnstile_site_key', '0x4AAAAAAASITEKEY');
        Setting::set('turnstile_secret_key', '0x4AAAAAAASECRETKEY');
        Setting::set('turnstile_theme', 'light');
    }

    private function createStudent(): Student
    {
        return Student::create([
            'name' => 'Captcha Student',
            'email' => 'captcha@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);
    }

    public function test_login_passes_untouched_when_turnstile_disabled(): void
    {
        $student = $this->createStudent();

        $this->post(route('login.attempt'), [
            'email' => $student->email,
            'password' => 'password',
        ])->assertRedirect(route('student.dashboard'));

        // No Cloudflare request was made at all.
        Http::assertNothingSent();
    }

    public function test_login_fails_without_token_when_turnstile_enabled(): void
    {
        $this->enableTurnstile();
        $student = $this->createStudent();

        $this->post(route('login.attempt'), [
            'email' => $student->email,
            'password' => 'password',
        ])
            ->assertSessionHasErrors('cf-turnstile-response');

        $this->assertGuest('student');
    }

    public function test_login_succeeds_with_verified_token(): void
    {
        $this->fakeSiteverify(true);
        $this->enableTurnstile();
        $student = $this->createStudent();

        $this->post(route('login.attempt'), [
            'email' => $student->email,
            'password' => 'password',
            'cf-turnstile-response' => 'valid-token',
        ])->assertRedirect(route('student.dashboard'));

        $this->assertAuthenticatedAs($student, 'student');
    }

    public function test_login_fails_when_cloudflare_rejects_the_token(): void
    {
        $this->fakeSiteverify(false);
        $this->enableTurnstile();
        $student = $this->createStudent();

        $this->post(route('login.attempt'), [
            'email' => $student->email,
            'password' => 'password',
            'cf-turnstile-response' => 'tampered-token',
        ])->assertSessionHasErrors('cf-turnstile-response');

        $this->assertGuest('student');
    }

    public function test_instructor_login_is_also_gated(): void
    {
        $this->enableTurnstile();
        Instructor::create([
            'name' => 'Captcha Instructor',
            'email' => 'captcha-instructor@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->post(route('instructor.login.attempt'), [
            'email' => 'captcha-instructor@mono-lms.test',
            'password' => 'password',
        ])->assertSessionHasErrors('cf-turnstile-response');

        $this->assertGuest('instructor');
    }

    public function test_registration_is_gated_when_turnstile_enabled(): void
    {
        $this->fakeSiteverify(true);
        $this->enableTurnstile();

        // Missing token is rejected before any account is created.
        $this->post(route('register.attempt'), [
            'name' => 'New Student',
            'email' => 'new-student@mono-lms.test',
            'password' => 'password123',
            'terms' => '1',
        ])->assertSessionHasErrors('cf-turnstile-response');
        $this->assertDatabaseMissing('students', ['email' => 'new-student@mono-lms.test']);

        // A verified token lets the registration through.
        $this->post(route('register.attempt'), [
            'name' => 'New Student',
            'email' => 'new-student@mono-lms.test',
            'password' => 'password123',
            'terms' => '1',
            'cf-turnstile-response' => 'valid-token',
        ])->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseHas('students', ['email' => 'new-student@mono-lms.test']);
    }

    public function test_widget_renders_on_all_four_auth_forms_when_enabled(): void
    {
        $this->enableTurnstile();

        foreach ([
            '/login' => 'data-action="login"',
            '/instructor/login' => 'data-action="login"',
            '/register' => 'data-action="register"',
            '/instructor/register' => 'data-action="register"',
        ] as $url => $expectedAction) {
            $this->get($url)
                ->assertOk()
                ->assertSee('cf-turnstile', false)
                ->assertSee('0x4AAAAAAASITEKEY', false)
                ->assertSee('data-theme="light"', false)
                ->assertSee($expectedAction, false);
        }
    }

    public function test_widget_is_absent_when_disabled(): void
    {
        $this->get('/login')->assertOk()->assertDontSee('cf-turnstile', false);
    }

    public function test_super_admin_can_update_turnstile_settings(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin-turnstile@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.settings.turnstile'))
            ->assertOk()
            ->assertSee('Bot Protection');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.turnstile.update'), [
                'turnstile_enabled' => '1',
                'turnstile_site_key' => 'site-key-123',
                'turnstile_secret_key' => 'secret-key-456',
                'turnstile_theme' => 'dark',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('1', Setting::get('turnstile_enabled'));
        $this->assertEquals('site-key-123', Setting::get('turnstile_site_key'));
        $this->assertEquals('dark', Setting::get('turnstile_theme'));
        $this->assertTrue(app(TurnstileService::class)->enabled());
    }

    public function test_invalid_theme_is_rejected(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin-turnstile2@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.turnstile.update'), [
                'turnstile_theme' => 'neon',
            ])
            ->assertSessionHasErrors('turnstile_theme');
    }

    public function test_admin_without_permission_cannot_access_turnstile_settings(): void
    {
        $admin = Admin::create([
            'name' => 'Plain Admin',
            'email' => 'plain-turnstile@mono-lms.test',
            'password' => 'password',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.settings.turnstile'))
            ->assertForbidden();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.turnstile.update'), ['turnstile_theme' => 'light'])
            ->assertForbidden();
    }
}
