<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use App\Notifications\AppNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_can_view_notifications_page_with_real_database_records(): void
    {
        $instructor = Instructor::create([
            'name' => 'John Instructor',
            'email' => 'john.inst@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
            'verification_status' => 'pending',
        ]);

        $instructor->notify(new AppNotification(
            title: 'Verification In Progress',
            message: 'Your documents are being checked.',
            category: 'verification',
            actionUrl: route('instructor.verification.show'),
            actionLabel: 'Check Status',
        ));

        $this->actingAs($instructor, 'instructor');

        $response = $this->get(route('instructor.notifications.index'));
        $response->assertOk();
        $response->assertSee('Verification In Progress');
        $response->assertSee('Your documents are being checked.');
    }

    public function test_admin_can_view_notifications_page_with_real_database_records(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@mono-lms.test',
            'password' => '12345678',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $admin->notify(new AppNotification(
            title: 'SMTP Mail Connected',
            message: 'Outbound mail server is online.',
            category: 'smtp',
            actionUrl: route('admin.services.index'),
            actionLabel: 'Mail Settings',
        ));

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.notifications.index'));
        $response->assertOk();
        $response->assertSee('SMTP Mail Connected');
        $response->assertSee('Outbound mail server is online.');
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $instructor = Instructor::create([
            'name' => 'Jane Instructor',
            'email' => 'jane.inst@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $instructor->notify(new AppNotification(
            title: 'New Student Enrollment',
            message: 'Alex joined your course.',
            category: 'enrollment',
        ));

        $notification = $instructor->notifications()->first();
        $this->assertNull($notification->read_at);

        $this->actingAs($instructor, 'instructor')
            ->post(route('instructor.notifications.read', $notification->id))
            ->assertRedirect();

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $instructor = Instructor::create([
            'name' => 'Mark Instructor',
            'email' => 'mark.inst@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $instructor->notify(new AppNotification(title: 'Notice 1', message: 'Msg 1', category: 'support'));
        $instructor->notify(new AppNotification(title: 'Notice 2', message: 'Msg 2', category: 'smtp'));

        $this->assertEquals(2, $instructor->unreadNotifications()->count());

        $this->actingAs($instructor, 'instructor')
            ->post(route('instructor.notifications.read-all'))
            ->assertRedirect();

        $this->assertEquals(0, $instructor->fresh()->unreadNotifications()->count());
    }

    public function test_user_can_delete_notification(): void
    {
        $instructor = Instructor::create([
            'name' => 'Dave Instructor',
            'email' => 'dave.inst@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $instructor->notify(new AppNotification(title: 'Trash me', message: 'Delete', category: 'system'));
        $notif = $instructor->notifications()->first();

        $this->actingAs($instructor, 'instructor')
            ->delete(route('instructor.notifications.destroy', $notif->id))
            ->assertRedirect();

        $this->assertEquals(0, $instructor->notifications()->count());
    }

    public function test_admin_sidebar_only_highlights_verifications_when_filtering_pending(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'super@mono-lms.test',
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
