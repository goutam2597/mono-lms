<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_certificate_templates_and_registry(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.certificates.index'));
        $response->assertOk();
        $response->assertSee('Certificate Templates Gallery');
        $response->assertSee('Graduation Registry');
    }

    public function test_admin_can_create_new_certificate_template(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.certificates.store'), [
            'title' => 'Global AI Practitioner Certificate',
            'template_type' => 'modern',
            'signer_name' => 'Prof. Elena Rostova',
            'signature_title' => 'Chief Academic Officer',
            'bg_color' => '#f8fafc',
            'border_color' => '#0284c7',
            'accent_color' => '#38bdf8',
            'subtitle' => 'Demonstrating mastery in deep learning and AI engineering.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('certificate_templates', [
            'title' => 'Global AI Practitioner Certificate',
            'template_type' => 'modern',
            'signer_name' => 'Prof. Elena Rostova',
        ]);
    }

    public function test_admin_can_update_certificate_template(): void
    {
        $admin = Admin::first();
        $template = CertificateTemplate::first();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.certificates.update', $template), [
            'title' => 'Updated Honors Diploma',
            'template_type' => 'elegant',
            'signer_name' => 'Dr. Alexander Wright',
            'signature_title' => 'Dean of Faculty',
            'bg_color' => '#ffffff',
            'border_color' => '#344c3d',
            'accent_color' => '#738a6e',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('Updated Honors Diploma', $template->fresh()->title);
        $this->assertEquals('Dean of Faculty', $template->fresh()->signature_title);
    }

    public function test_admin_can_switch_default_certificate_template(): void
    {
        $admin = Admin::first();
        $first = CertificateTemplate::first();
        $second = CertificateTemplate::skip(1)->first();

        if ($second) {
            $response = $this->actingAs($admin, 'admin')->patch(route('admin.certificates.default', $second));
            $response->assertSessionHas('success');
            $this->assertTrue($second->fresh()->is_default);
            $this->assertFalse($first->fresh()->is_default);
        }
    }

    public function test_student_certificates_page_auto_issues_for_completed_course(): void
    {
        $student = Student::first();
        $course = Course::published()->first();

        $enrollment = Enrollment::updateOrCreate([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ], [
            'status' => 'completed',
            'progress' => 100,
        ]);

        $response = $this->actingAs($student, 'student')->get(route('student.certificates'));
        $response->assertOk();
        $response->assertSee('My Certificates');
        $response->assertSee($course->title);

        $this->assertDatabaseHas('certificates', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_student_can_view_printable_certificate(): void
    {
        $student = Student::first();
        $course = Course::published()->first();

        $certificate = Certificate::issueFor($student, $course);

        $response = $this->actingAs($student, 'student')->get(route('student.certificates.print', $certificate));
        $response->assertOk();
        $response->assertSee($certificate->certificate_number);
        $response->assertSee($student->name);
        $response->assertSee($course->title);
    }

    public function test_unauthorized_student_cannot_print_another_students_certificate(): void
    {
        $student1 = Student::first();
        $student2 = Student::create([
            'name' => 'Other Student',
            'email' => 'otherstudent@example.com',
            'password' => 'password',
            'status' => 'active',
        ]);
        $course = Course::published()->first();

        $certificate = Certificate::issueFor($student1, $course);

        $response = $this->actingAs($student2, 'student')->get(route('student.certificates.print', $certificate));
        $response->assertForbidden();
    }

    public function test_public_certificate_verification_with_valid_and_invalid_codes(): void
    {
        $student = Student::first();
        $course = Course::published()->first();

        $certificate = Certificate::issueFor($student, $course);

        // 1. Valid code verification
        $validResponse = $this->get(route('certificates.verify.show', ['code' => $certificate->certificate_number]));
        $validResponse->assertOk();
        $validResponse->assertSee('Authentic Certificate Confirmed');
        $validResponse->assertSee($certificate->certificate_number);
        $validResponse->assertSee($student->name);

        // 2. Invalid code verification
        $invalidResponse = $this->get(route('certificates.verify.show', ['code' => 'INVALID-CERT-9999']));
        $invalidResponse->assertOk();
        $invalidResponse->assertSee('Credential Not Found');
    }
}
