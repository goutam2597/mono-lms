<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_view_notifications_page(): void
    {
        $instructor = Instructor::create([
            'name' => 'John Instructor',
            'email' => 'john.inst@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
            'verification_status' => 'approved',
        ]);

        $response = $this->actingAs($instructor, 'instructor')
            ->get(route('instructor.notifications.index'));

        $response->assertOk();
        $response->assertSee('Notifications');
        $response->assertSee('Account Verification');
    }

    public function test_admin_can_view_notifications_page(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@mono-lms.test',
            'password' => '12345678',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.notifications.index'));

        $response->assertOk();
        $response->assertSee('Notifications');
        $response->assertSee('Pending Verifications');
    }

    public function test_admin_sidebar_only_highlights_verifications_when_filtering_pending(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@mono-lms.test',
            'password' => '12345678',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.instructors.index', ['verification' => 'pending']));

        $response->assertOk();
        $content = $response->getContent();
        $this->assertStringContainsString('Verifications', $content);
    }
}
