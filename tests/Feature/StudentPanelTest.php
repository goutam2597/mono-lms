<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPanelTest extends TestCase
{
    use RefreshDatabase;

    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = Student::create([
            'name' => 'John Learner',
            'email' => 'johnlearner@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);
    }

    public function test_student_dashboard_renders_with_full_width_dashboard_shell(): void
    {
        $response = $this->actingAs($this->student, 'student')->get(route('student.dashboard'));

        $response->assertOk();
        // Standalone dark sidebar & badge
        $response->assertSee('id="dashboard-sidebar"', false);
        $response->assertSee('STUDENT');
        // Topbar header and notification button
        $response->assertSee('id="header-notifications-btn"', false);
        $response->assertSee('id="header-user-menu-btn"', false);
        // Student navigation links
        $response->assertSee(route('student.dashboard'));
        $response->assertSee(route('student.enrolled-courses'));
        $response->assertSee(route('student.wishlist'));
        $response->assertSee(route('student.certificates'));
        $response->assertSee(route('student.tickets.index'));
        $response->assertSee(route('student.profile'));
        // User greeting
        $response->assertSee('Welcome back, John Learner!');
    }

    public function test_student_panel_pages_render_successfully(): void
    {
        $pages = [
            route('student.dashboard'),
            route('student.enrolled-courses'),
            route('student.wishlist'),
            route('student.certificates'),
            route('student.quiz-attempts'),
            route('student.profile'),
            route('student.tickets.index'),
            route('student.notifications.index'),
        ];

        foreach ($pages as $url) {
            $response = $this->actingAs($this->student, 'student')->get($url);
            $response->assertOk();
            $response->assertSee('id="dashboard-sidebar"', false);
            $response->assertSee('John Learner');
        }
    }

    public function test_student_notifications_index_and_actions(): void
    {
        // 1. Visit notifications index (seeds initial notifications)
        $response = $this->actingAs($this->student, 'student')->get(route('student.notifications.index'));
        $response->assertOk();
        $response->assertSee('Notifications');
        $response->assertSee('Welcome to Mono LMS Learning Portal');

        $notification = $this->student->notifications()->first();
        $this->assertNotNull($notification);

        // 2. Mark as read
        $readResp = $this->actingAs($this->student, 'student')->post(route('student.notifications.read', ['id' => $notification->id]));
        $readResp->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);

        // 3. Mark all as read
        $markAllResp = $this->actingAs($this->student, 'student')->post(route('student.notifications.read-all'));
        $markAllResp->assertRedirect();

        // 4. Delete notification
        $deleteResp = $this->actingAs($this->student, 'student')->delete(route('student.notifications.destroy', ['id' => $notification->id]));
        $deleteResp->assertRedirect();
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }
}
