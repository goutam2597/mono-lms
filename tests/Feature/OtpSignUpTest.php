<?php

namespace Tests\Feature;

use App\Mail\OtpVerificationMail;
use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpSignUpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::set('otp_signup_enabled', '0');
    }

    public function test_admin_can_toggle_otp_signup_setting(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Boss',
            'email' => 'admin@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
            'status' => 'active',
        ]);

        $this->actingAs($admin, 'admin');

        // Toggle via Settings page
        $this->post(route('admin.settings.update'), [
            'otp_signup_enabled' => '1',
        ])->assertRedirect();

        $this->assertTrue(Setting::isOtpSignupEnabled());

        // Toggle via Services (SMTP) page
        $this->post(route('admin.services.mail'), [
            'otp_setting_present' => '1',
            // unchecked (no otp_signup_enabled passed)
        ])->assertRedirect();

        $this->assertFalse(Setting::isOtpSignupEnabled());
    }

    public function test_student_and_instructor_register_directly_when_otp_is_disabled(): void
    {
        Setting::set('otp_signup_enabled', '0');

        // Student registration
        $studResp = $this->post(route('register.attempt'), [
            'name' => 'Alice Student',
            'email' => 'alice@student.test',
            'password' => 'secret1234',
            'terms' => '1',
        ]);

        $studResp->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('students', ['email' => 'alice@student.test']);
        $this->assertAuthenticatedAs(Student::where('email', 'alice@student.test')->first(), 'student');

        auth('student')->logout();

        // Instructor registration
        $instResp = $this->post(route('instructor.register.attempt'), [
            'name' => 'Bob Instructor',
            'email' => 'bob@instructor.test',
            'password' => 'secret1234',
            'designation' => 'Senior Instructor',
            'terms' => '1',
        ]);

        $instResp->assertRedirect(route('instructor.dashboard'));
        $this->assertDatabaseHas('instructors', [
            'email' => 'bob@instructor.test',
            'status' => 'pending_verification',
            'verification_status' => 'unverified',
        ]);
        $this->assertAuthenticatedAs(Instructor::where('email', 'bob@instructor.test')->first(), 'instructor');
    }

    public function test_student_registration_requires_otp_when_enabled(): void
    {
        Mail::fake();
        Setting::set('otp_signup_enabled', '1');

        $response = $this->post(route('register.attempt'), [
            'name' => 'Charlie Student',
            'email' => 'charlie@student.test',
            'password' => 'secret1234',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('otp'));
        $this->assertDatabaseMissing('students', ['email' => 'charlie@student.test']);
        $this->assertGuest('student');

        $pending = session('pending_otp_registration');
        $this->assertNotNull($pending);
        $this->assertSame('student', $pending['type']);
        $this->assertSame('charlie@student.test', $pending['email']);
        $this->assertNotEmpty($pending['otp']);

        Mail::assertSent(OtpVerificationMail::class, function ($mail) {
            return $mail->hasTo('charlie@student.test') && $mail->role === 'student';
        });

        // OTP Page displays masked email
        $otpPage = $this->get(route('otp'));
        $otpPage->assertOk();
        $otpPage->assertSee('@student.test');

        // Wrong OTP fails
        $failResp = $this->post(route('otp.verify'), ['otp' => '000000']);
        $failResp->assertSessionHasErrors('otp');
        $this->assertDatabaseMissing('students', ['email' => 'charlie@student.test']);

        // Correct OTP succeeds and creates student
        $okResp = $this->post(route('otp.verify'), ['otp' => $pending['otp']]);
        $okResp->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('students', ['email' => 'charlie@student.test']);
        $this->assertAuthenticatedAs(Student::where('email', 'charlie@student.test')->first(), 'student');
        $this->assertNull(session('pending_otp_registration'));
    }

    public function test_instructor_registration_requires_otp_when_enabled(): void
    {
        Mail::fake();
        Setting::set('otp_signup_enabled', '1');

        $response = $this->post(route('instructor.register.attempt'), [
            'name' => 'Dr Donna',
            'email' => 'donna@instructor.test',
            'password' => 'secret1234',
            'designation' => 'AI Scientist',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('otp'));
        $this->assertDatabaseMissing('instructors', ['email' => 'donna@instructor.test']);
        $this->assertGuest('instructor');

        $pending = session('pending_otp_registration');
        $this->assertSame('instructor', $pending['type']);
        $this->assertSame('donna@instructor.test', $pending['email']);

        Mail::assertSent(OtpVerificationMail::class, function ($mail) {
            return $mail->hasTo('donna@instructor.test') && $mail->role === 'instructor';
        });

        // Resend OTP generates new code
        $oldOtp = $pending['otp'];
        $resend = $this->post(route('otp.resend'));
        $resend->assertSessionHas('status');

        $newPending = session('pending_otp_registration');
        $this->assertNotEmpty($newPending['otp']);

        // Verify with new OTP
        $okResp = $this->post(route('otp.verify'), ['otp' => $newPending['otp']]);
        $okResp->assertRedirect(route('instructor.dashboard'));
        $this->assertDatabaseHas('instructors', [
            'email' => 'donna@instructor.test',
            'designation' => 'AI Scientist',
            'status' => 'pending_verification',
            'verification_status' => 'unverified',
        ]);
        $this->assertAuthenticatedAs(Instructor::where('email', 'donna@instructor.test')->first(), 'instructor');
    }
}
