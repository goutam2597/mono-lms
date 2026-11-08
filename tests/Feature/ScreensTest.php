<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScreensTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Membership is admin-dependent; enable it for the public screens test.
        Setting::set('membership_enabled', '1');
    }

    /**
     * Public screens return 200 for guests.
     */
    public function test_public_screens_return_successful_response(): void
    {
        $routes = [
            '/',
            '/screens',
            '/courses',
            '/instructors',
            '/blog',
            '/membership',
            '/login',
            '/register',
            '/forgot-password',
            '/admin/login',
            '/instructor/login',
            '/otp',
            '/lock-screen',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200, "Route [{$route}] failed to return 200 OK.");
        }
    }

    /**
     * Panel screens redirect guests to the right login page.
     */
    public function test_panel_screens_redirect_guests_to_panel_logins(): void
    {
        $this->get('/student')->assertRedirect(route('login'));
        $this->get('/instructor')->assertRedirect(route('instructor.login'));
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }
}
