<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\Instructor;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function show(Request $request): View
    {
        $pending = $request->session()->get('pending_otp_registration');

        $expiresAt = (int) ($pending['expires_at'] ?? (now()->addMinutes(10)->timestamp));
        $remainingSeconds = max(0, $expiresAt - now()->timestamp);

        $email = (string) ($pending['email'] ?? 'student@example.com');
        $maskedEmail = $this->maskEmail($email);
        $role = $pending['type'] ?? 'student';

        return view('pages.auth.otp', [
            'maskedEmail' => $maskedEmail,
            'role' => $role,
            'remainingSeconds' => $remainingSeconds,
            'isExpired' => $remainingSeconds <= 0,
        ]);
    }

    /**
     * Verify the submitted OTP and activate the account.
     */
    public function verify(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_otp_registration');

        if (! $pending) {
            return redirect()->route('register')->withErrors(['email' => 'No pending registration found. Please register again.']);
        }

        $request->validate([
            'otp' => ['required', 'string'],
        ]);

        $submittedOtp = preg_replace('/\D/', '', (string) $request->input('otp'));
        $expectedOtp = (string) ($pending['otp'] ?? '');
        $expiresAt = (int) ($pending['expires_at'] ?? 0);

        if (now()->timestamp > $expiresAt) {
            return back()->withErrors(['otp' => 'This verification code has expired. Please click "Resend OTP" below to receive a new code.']);
        }

        if ($submittedOtp !== $expectedOtp) {
            return back()->withErrors(['otp' => 'The verification code entered is incorrect. Please try again.']);
        }

        $type = $pending['type'] ?? 'student';

        if ($type === 'instructor') {
            if (Instructor::where('email', $pending['email'])->exists()) {
                $request->session()->forget('pending_otp_registration');

                return redirect()->route('instructor.login')->with('info', 'An account with this email already exists. Please sign in.');
            }

            $instructor = Instructor::create([
                'name' => $pending['name'],
                'email' => $pending['email'],
                'password' => $pending['password'],
                'designation' => $pending['designation'] ?? 'Instructor',
                'status' => 'pending_verification',
                'verification_status' => 'unverified',
            ]);

            $request->session()->forget('pending_otp_registration');
            Auth::guard('instructor')->login($instructor);
            $request->session()->regenerate();

            return redirect()->route('instructor.dashboard')->with('status', 'Email verified successfully! Please submit your Instructor Certificate and National ID to complete instructor verification.');
        }

        // Student
        if (Student::where('email', $pending['email'])->exists()) {
            $request->session()->forget('pending_otp_registration');

            return redirect()->route('login')->with('info', 'An account with this email already exists. Please sign in.');
        }

        $student = Student::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'status' => 'active',
        ]);

        $request->session()->forget('pending_otp_registration');
        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')->with('success', 'Email verified successfully! Welcome to Mono LMS.');
    }

    /**
     * Resend a fresh 6-digit OTP code.
     */
    public function resend(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_otp_registration');

        if (! $pending) {
            return redirect()->route('register')->withErrors(['email' => 'No pending registration found. Please register again.']);
        }

        $otp = (string) random_int(100000, 999999);
        $pending['otp'] = $otp;
        $pending['expires_at'] = now()->addMinutes(10)->timestamp;

        $request->session()->put('pending_otp_registration', $pending);

        $this->sendOtpEmail($pending['email'], $pending['name'], $otp, $pending['type'] ?? 'student');

        return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
    }

    /**
     * Initiate an OTP sign-up flow from a registration controller.
     *
     * @param  array<string, mixed>  $data
     */
    public function initiateRegistration(Request $request, array $data): RedirectResponse
    {
        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10)->timestamp;

        $payload = [
            'type' => $data['type'] ?? 'student',
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'designation' => $data['designation'] ?? null,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ];

        $request->session()->put('pending_otp_registration', $payload);

        $this->sendOtpEmail($payload['email'], $payload['name'], $otp, $payload['type']);

        return redirect()->route('otp')->with('status', "Verification code sent! Please check your email: {$this->maskEmail($payload['email'])}");
    }

    /**
     * Dispatch OTP email and gracefully log on local/mock environments.
     */
    protected function sendOtpEmail(string $email, string $name, string $otp, string $type): void
    {
        $this->applySmtpSettings();

        try {
            Mail::to($email)->send(new OtpVerificationMail($otp, $name, $type));
        } catch (\Throwable $e) {
            Log::warning("Could not dispatch OTP email via configured SMTP to {$email}: ".$e->getMessage());
        }

        Log::info("OTP generated for {$email} ({$type}): {$otp}");
    }

    /**
     * Apply SMTP settings dynamically from admin panel configuration.
     */
    protected function applySmtpSettings(): void
    {
        $host = Setting::get('mail_host');

        if (! empty($host)) {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => (int) Setting::get('mail_port', 587),
                'mail.mailers.smtp.username' => Setting::get('mail_username'),
                'mail.mailers.smtp.password' => Setting::get('mail_password'),
                'mail.mailers.smtp.encryption' => Setting::get('mail_encryption', 'tls') ?: null,
                'mail.from.address' => Setting::get('mail_from_address', config('mail.from.address')),
                'mail.from.name' => Setting::get('mail_from_name', config('mail.from.name')),
            ]);
        }
    }

    /**
     * Mask an email address for privacy (e.g. jo***oe@example.com).
     */
    protected function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return $email;
        }

        [$user, $domain] = explode('@', $email, 2);
        $len = strlen($user);

        if ($len <= 2) {
            $maskedUser = substr($user, 0, 1).'*';
        } else {
            $maskedUser = substr($user, 0, 2).str_repeat('*', max(3, $len - 3)).substr($user, -1);
        }

        return $maskedUser.'@'.$domain;
    }
}
