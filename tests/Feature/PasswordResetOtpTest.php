<?php

namespace Tests\Feature;

use App\Mail\PasswordResetOtpMail;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_generates_and_emails_otp(): void
    {
        Mail::fake();

        $student = Student::create([
            'name' => 'John Learner',
            'email' => 'john@learner.test',
            'password' => Hash::make('oldpassword'),
            'status' => 'active',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'john@learner.test',
            'panel' => 'student',
        ]);

        $response->assertRedirect(route('password.otp.show'));
        $response->assertSessionHas('status');

        $pending = session('pending_password_reset');
        $this->assertNotNull($pending);
        $this->assertSame('john@learner.test', $pending['email']);
        $this->assertSame('student', $pending['panel']);
        $this->assertNotEmpty($pending['otp']);

        Mail::assertSent(PasswordResetOtpMail::class, function ($mail) {
            return $mail->hasTo('john@learner.test');
        });

        // OTP Page displays masked email
        $page = $this->get(route('password.otp.show'));
        $page->assertOk();
        $page->assertSee('@learner.test');

        // Incorrect OTP fails
        $fail = $this->post(route('password.otp.verify'), ['otp' => '000000']);
        $fail->assertSessionHasErrors('otp');

        // Correct OTP passes and allows reset
        $ok = $this->post(route('password.otp.verify'), ['otp' => $pending['otp']]);
        $ok->assertRedirect(route('password.set.show'));
        $this->assertNotNull(session('verified_password_reset'));

        // Reset password
        $resetResp = $this->post(route('password.update'), [
            'password' => 'newsecretpassword123',
            'password_confirmation' => 'newsecretpassword123',
        ]);

        $resetResp->assertRedirect(route('login'));

        // Confirm user can log in with new password
        $student->refresh();
        $this->assertTrue(Hash::check('newsecretpassword123', $student->password));
    }

    public function test_resend_password_reset_otp_works(): void
    {
        Mail::fake();

        $student = Student::create([
            'name' => 'Sarah Learner',
            'email' => 'sarah@learner.test',
            'password' => Hash::make('oldpassword'),
            'status' => 'active',
        ]);

        $this->post(route('password.email'), [
            'email' => 'sarah@learner.test',
            'panel' => 'student',
        ]);

        $initialPending = session('pending_password_reset');
        $initialOtp = $initialPending['otp'];

        $resendResp = $this->post(route('password.otp.resend'));
        $resendResp->assertSessionHas('status');

        $updatedPending = session('pending_password_reset');
        $this->assertNotEmpty($updatedPending['otp']);
    }

    public function test_login_rate_limiting_throttles_after_five_attempts(): void
    {
        Student::create([
            'name' => 'Spam Tester',
            'email' => 'spam@test.dev',
            'password' => Hash::make('correctpassword'),
            'status' => 'active',
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.attempt'), [
                'email' => 'spam@test.dev',
                'password' => 'wrongpass',
            ]);
        }

        // 6th attempt should be throttled
        $throttled = $this->post(route('login.attempt'), [
            'email' => 'spam@test.dev',
            'password' => 'wrongpass',
        ]);

        $throttled->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
    }
}
