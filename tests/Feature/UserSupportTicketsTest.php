<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\SupportTicket;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSupportTicketsTest extends TestCase
{
    use RefreshDatabase;

    private Student $student;

    private Instructor $instructor;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->admin = Admin::create([
            'name' => 'Support Admin',
            'email' => 'supportadmin@learnify.test',
            'password' => '12345678',
            'status' => 'active',
            'is_super_admin' => true,
        ]);

        $this->instructor = Instructor::create([
            'name' => 'Prof. Harrison',
            'email' => 'harrison@learnify.test',
            'password' => 'password123',
            'status' => 'active',
            'is_verified' => true,
        ]);

        $this->student = Student::create([
            'name' => 'Emily Rose',
            'email' => 'emily@learnify.test',
            'password' => 'password123',
            'status' => 'active',
        ]);
    }

    public function test_instructor_can_view_and_create_support_ticket(): void
    {
        $this->actingAs($this->instructor, 'instructor');

        // 1. Visit index page
        $this->get(route('instructor.tickets.index'))
            ->assertOk()
            ->assertSee('Instructor Help & Support')
            ->assertSee('Submit New Ticket');

        // 2. Submit a support ticket
        $response = $this->post(route('instructor.tickets.store'), [
            'subject' => 'Course video playback processing delay',
            'priority' => 'high',
            'message' => 'The video upload on module 4 has been processing for over two hours.',
        ]);

        $response->assertSessionHas('success');

        $ticket = SupportTicket::where('user_email', $this->instructor->email)->first();
        $this->assertNotNull($ticket);
        $this->assertStringStartsWith('TK-', $ticket->ticket_number);
        $this->assertSame('Prof. Harrison', $ticket->user_name);
        $this->assertSame('high', $ticket->priority);
        $this->assertSame('open', $ticket->status);

        // 3. View ticket in instructor list
        $this->get(route('instructor.tickets.index'))
            ->assertOk()
            ->assertSee($ticket->ticket_number)
            ->assertSee('Course video playback processing delay')
            ->assertSee('high');

        // 4. Send follow-up reply
        $replyResponse = $this->post(route('instructor.tickets.reply', $ticket), [
            'followup' => 'Update: the file format was an MP4 container with H.264 video.',
        ]);
        $replyResponse->assertSessionHas('success');

        $ticket->refresh();
        $this->assertStringContainsString('Update: the file format was an MP4 container', $ticket->message);

        // 5. Close ticket
        $closeResponse = $this->patch(route('instructor.tickets.close', $ticket));
        $closeResponse->assertSessionHas('success');

        $ticket->refresh();
        $this->assertSame('closed', $ticket->status);
    }

    public function test_student_can_view_and_create_support_ticket(): void
    {
        $this->actingAs($this->student, 'student');

        // 1. Visit index page
        $this->get(route('student.tickets.index'))
            ->assertOk()
            ->assertSee('Student Help & Support')
            ->assertSee('Submit Support Ticket');

        // 2. Submit a support ticket
        $response = $this->post(route('student.tickets.store'), [
            'subject' => 'Certificate name typo correction request',
            'priority' => 'medium',
            'message' => 'My certificate shows Emily R. instead of my full legal name Emily Rose.',
        ]);

        $response->assertSessionHas('success');

        $ticket = SupportTicket::where('user_email', $this->student->email)->first();
        $this->assertNotNull($ticket);
        $this->assertStringStartsWith('TK-', $ticket->ticket_number);
        $this->assertSame('Emily Rose', $ticket->user_name);
        $this->assertSame('medium', $ticket->priority);
        $this->assertSame('open', $ticket->status);

        // 3. View ticket in student list
        $this->get(route('student.tickets.index'))
            ->assertOk()
            ->assertSee($ticket->ticket_number)
            ->assertSee('Certificate name typo correction request');

        // 4. Send follow-up
        $replyResponse = $this->post(route('student.tickets.reply', $ticket), [
            'followup' => 'I have uploaded my student ID to my profile for verification.',
        ]);
        $replyResponse->assertSessionHas('success');

        $ticket->refresh();
        $this->assertStringContainsString('I have uploaded my student ID', $ticket->message);

        // 5. Close ticket
        $closeResponse = $this->patch(route('student.tickets.close', $ticket));
        $closeResponse->assertSessionHas('success');

        $ticket->refresh();
        $this->assertSame('closed', $ticket->status);
    }

    public function test_users_cannot_modify_other_users_tickets(): void
    {
        $otherStudent = Student::create([
            'name' => 'Other Student',
            'email' => 'otherstudent@learnify.test',
            'password' => 'password123',
            'status' => 'active',
        ]);

        $ticket = SupportTicket::create([
            'ticket_number' => 'TK-XYZ-8888',
            'user_name' => $this->student->name,
            'user_email' => $this->student->email,
            'subject' => 'Original student ticket',
            'priority' => 'low',
            'status' => 'open',
            'message' => 'Original question body',
        ]);

        $this->actingAs($otherStudent, 'student');

        $this->post(route('student.tickets.reply', $ticket), [
            'followup' => 'Malicious takeover attempt',
        ])->assertStatus(403);

        $this->patch(route('student.tickets.close', $ticket))->assertStatus(403);
    }

    public function test_admin_reply_creates_notification_with_direct_panel_link(): void
    {
        // 1. Student ticket
        $studentTicket = SupportTicket::create([
            'ticket_number' => 'TK-STU-1234',
            'user_name' => $this->student->name,
            'user_email' => $this->student->email,
            'subject' => 'Need help with enrollment refund',
            'priority' => 'high',
            'status' => 'open',
            'message' => 'Accidental duplicate charge for course.',
        ]);

        $this->actingAs($this->admin, 'admin');

        $this->patch(route('admin.tickets.reply', $studentTicket), [
            'reply' => 'Your refund of $49 has been processed back to your card.',
            'status' => 'resolved',
            'send_email' => '0',
        ])->assertSessionHas('success');

        $studentNotification = $this->student->notifications()->first();
        $this->assertNotNull($studentNotification);
        $this->assertSame('Staff response on ticket #TK-STU-1234', $studentNotification->data['title']);
        $this->assertStringContainsString('student/tickets', $studentNotification->data['action_url']);

        // 2. Instructor ticket
        $instructorTicket = SupportTicket::create([
            'ticket_number' => 'TK-INS-5678',
            'user_name' => $this->instructor->name,
            'user_email' => $this->instructor->email,
            'subject' => 'Payout bank account update',
            'priority' => 'medium',
            'status' => 'open',
            'message' => 'Please confirm my new routing number.',
        ]);

        $this->patch(route('admin.tickets.reply', $instructorTicket), [
            'reply' => 'Your routing number has been verified and updated in our banking records.',
            'status' => 'resolved',
            'send_email' => '0',
        ])->assertSessionHas('success');

        $instructorNotification = $this->instructor->notifications()->first();
        $this->assertNotNull($instructorNotification);
        $this->assertSame('Staff response on ticket #TK-INS-5678', $instructorNotification->data['title']);
        $this->assertStringContainsString('instructor/tickets', $instructorNotification->data['action_url']);
    }
}
