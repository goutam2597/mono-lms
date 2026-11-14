<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsAndLeadsTest extends TestCase
{
    use RefreshDatabase;

    /** Admin gate helper — reports and leads pages are admin-only. */
    private function actingAdmin(): void
    {
        $admin = Admin::create([
            'name' => 'Reports Admin',
            'email' => 'reports-admin@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);
        $this->actingAs($admin, 'admin');
    }

    public function test_reports_show_real_zeroes_when_platform_is_empty(): void
    {
        $this->actingAdmin();

        $this->get(route('admin.reports.index'))
            ->assertOk()
            // The old placeholder revenue must never appear again.
            ->assertDontSee('4,768.57', false)
            ->assertSee('$0.00')
            ->assertSee('No paid orders yet')
            ->assertSee('No enrollments yet');
    }

    public function test_reports_reflect_real_orders_enrollments_and_earnings(): void
    {
        $this->actingAdmin();

        $instructor = Instructor::create([
            'name' => 'Real Instructor',
            'email' => 'real-instructor@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);
        $course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Real Analytics Course',
            'slug' => 'real-analytics-course',
            'price' => 250,
            'status' => 'published',
        ]);
        $student = Student::create([
            'name' => 'Real Student',
            'email' => 'real-student@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);
        $secondStudent = Student::create([
            'name' => 'Second Student',
            'email' => 'second-student@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id, 'status' => 'active']);
        Enrollment::create(['student_id' => $secondStudent->id, 'course_id' => $course->id]);

        Order::create([
            'order_number' => 'ORD-REAL-1',
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount' => 250,
            'currency' => 'USD',
            'payment_method' => 'stripe',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('$250.00')                       // gross volume from the paid order
            ->assertSee('2 enrollments')                 // real enrollment count, no artificial minimum
            ->assertSee('$175.00');                      // instructor share: 250 * 0.70
    }

    public function test_contact_form_captures_a_new_lead(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Interested Visitor',
            'email' => 'visitor@example.com',
            'phone' => '+1 555 000 1111',
            'subject' => 'Corporate training inquiry',
            'message' => 'We need a custom cohort for 40 engineers.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', ['email' => 'visitor@example.com']);
        $this->assertDatabaseHas('leads', [
            'email' => 'visitor@example.com',
            'name' => 'Interested Visitor',
            'source' => 'contact form',
            'status' => 'new',
        ]);
    }

    public function test_returning_inquirer_keeps_pipeline_stage_and_appends_notes(): void
    {
        $existing = Lead::create([
            'name' => 'Returning Visitor',
            'email' => 'returning@example.com',
            'source' => 'contact form',
            'status' => 'enrolled',
            'notes' => '[01 Jan 2026] First inquiry.',
        ]);

        $this->post(route('contact.submit'), [
            'name' => 'Returning Visitor (updated)',
            'email' => 'returning@example.com',
            'subject' => 'Follow-up question',
            'message' => 'What are the cohort start dates?',
        ])->assertRedirect();

        // Still a single lead, stage untouched, inquiry appended to the trail.
        $this->assertEquals(1, Lead::where('email', 'returning@example.com')->count());
        $fresh = $existing->fresh();
        $this->assertEquals('enrolled', $fresh->status);
        $this->assertStringContainsString('[01 Jan 2026] First inquiry.', $fresh->notes);
        $this->assertStringContainsString('---', $fresh->notes);
        $this->assertStringContainsString('Follow-up question', $fresh->notes);
    }

    public function test_invalid_contact_submission_does_not_create_a_lead(): void
    {
        $this->post(route('contact.submit'), [
            'name' => '',
            'email' => 'not-an-email',
        ])->assertSessionHasErrors();

        $this->assertDatabaseCount('leads', 0);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_leads_page_lists_captured_leads_for_admins(): void
    {
        Lead::create([
            'name' => 'Captured Lead',
            'email' => 'captured@example.com',
            'source' => 'contact form',
            'status' => 'new',
        ]);

        $this->actingAdmin();

        $this->get(route('admin.leads.index'))
            ->assertOk()
            ->assertSee('Captured Lead')
            ->assertSee('contact form');
    }
}
