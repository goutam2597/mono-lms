<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Setting;
use App\Models\Student;
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

    public function test_footer_renders_payment_methods(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('payment-methods.png');
        $response->assertSee('Terms & Conditions', false);
    }

    public function test_storefront_search_icon_and_auth_avatar_visibility(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Boss',
            'email' => 'adminboss@test.dev',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);
        $instructor = Instructor::create([
            'name' => 'Prof Xavier',
            'email' => 'profxavier@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);
        $student = Student::create([
            'name' => 'Jane Student',
            'email' => 'janestudent@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        // 1. Guest visits home: see new search icon, guest auth buttons, no admin login URL
        $guestResp = $this->get('/');
        $guestResp->assertOk();
        $guestResp->assertSee('M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68', false);
        $guestResp->assertSee(route('login'));
        $guestResp->assertSee(route('register'));
        $guestResp->assertDontSee('/admin/login');

        // 2. Admin is logged in: storefront should NOT show admin avatar or admin URLs
        $adminResp = $this->actingAs($admin, 'admin')->get('/');
        $adminResp->assertOk();
        $adminResp->assertDontSee('Admin Boss');
        $adminResp->assertDontSee(route('admin.dashboard'));
        $adminResp->assertDontSee('/admin/login');
        $adminResp->assertSee(route('login')); // still shows student Sign In

        // 3. Instructor is logged in: storefront header should NOT show instructor account/logout
        $instResp = $this->actingAs($instructor, 'instructor')->get('/');
        $instResp->assertOk();
        $instResp->assertDontSee(route('instructor.logout'));
        // But footer instructor links point directly to instructor dashboard
        $instResp->assertSee(route('instructor.dashboard'));

        // 4. Student is logged in: storefront DOES show student avatar & name
        $studResp = $this->actingAs($student, 'student')->get('/');
        $studResp->assertOk();
        $studResp->assertSee('Jane Student');
        $studResp->assertSee(route('student.dashboard'));
    }
}
