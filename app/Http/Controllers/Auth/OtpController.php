<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Student;
use App\Services\OtpService;
use App\Services\PhoneNumberService;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
        protected PhoneNumberService $phoneService,
        protected SmsService $smsService,
    ) {}

    /**
     * Show the OTP verification form.
     */
    public function show(Request $request): View
    {
        $pending = $request->session()->get('pending_otp_registration');

        $expiresAt = (int) ($pending['expires_at'] ?? (now()->addMinutes(10)->timestamp));
        $remainingSeconds = max(0, $expiresAt - now()->timestamp);

        $channel = $pending['channel'] ?? OtpService::CHANNEL_EMAIL;
        $email = (string) ($pending['email'] ?? '');
        $phone = (string) ($pending['phone'] ?? '');
        $maskedEmail = $this->maskEmail($email);
        $maskedPhone = $this->phoneService->mask($phone);
        $role = $pending['type'] ?? 'student';

        return view('pages.auth.otp', [
            'channel' => $channel,
            'maskedEmail' => $maskedEmail,
            'maskedPhone' => $maskedPhone,
            'displayTarget' => $channel === OtpService::CHANNEL_SMS ? $maskedPhone : $maskedEmail,
            'smsOtpAvailable' => $this->smsService->otpEnabled() && filled($phone),
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
        $channel = $pending['channel'] ?? OtpService::CHANNEL_EMAIL;
        $identifier = $channel === OtpService::CHANNEL_SMS ? ($pending['phone'] ?? '') : ($pending['email'] ?? '');

        // Verify using OtpService (hashed verification with brute-force lockout)
        $verified = $this->otpService->verifyFor($channel, $identifier, OtpService::PURPOSE_SIGNUP, $submittedOtp);

        // Fallback check against session for backward compatibility
        if (! $verified) {
            $expectedOtp = (string) ($pending['otp'] ?? '');
            $expiresAt = (int) ($pending['expires_at'] ?? 0);

            if (now()->timestamp > $expiresAt) {
                return back()->withErrors(['otp' => 'This verification code has expired. Please click "Resend OTP" below to receive a new code.']);
            }

            if ($submittedOtp !== $expectedOtp) {
                return back()->withErrors(['otp' => 'The verification code entered is incorrect or exceeded maximum attempts. Please try again.']);
            }
        }

        $type = $pending['type'] ?? 'student';

        if ($type === 'instructor') {
            if (Instructor::where('email', $pending['email'])->exists()) {
                $request->session()->forget('pending_otp_registration');
                $this->otpService->clear($channel, $identifier, OtpService::PURPOSE_SIGNUP);

                return redirect()->route('instructor.login')->with('info', 'An account with this email already exists. Please sign in.');
            }

            $instructor = Instructor::create([
                'name' => $pending['name'],
                'email' => $pending['email'],
                ...$this->phoneService->attributesFor($pending['phone'] ?? null),
                'phone_verified_at' => $channel === OtpService::CHANNEL_SMS ? now() : null,
                'password' => $pending['password'],
                'designation' => $pending['designation'] ?? 'Instructor',
                'status' => 'pending_verification',
                'verification_status' => 'unverified',
            ]);

            $request->session()->forget('pending_otp_registration');
            $this->otpService->clear($channel, $identifier, OtpService::PURPOSE_SIGNUP);

            Auth::guard('instructor')->login($instructor);
            $request->session()->regenerate();

            return redirect()->route('instructor.dashboard')->with('status', 'Verification successful! Please submit your Instructor Certificate to complete onboarding.');
        }

        // Student
        if (Student::where('email', $pending['email'])->exists()) {
            $request->session()->forget('pending_otp_registration');
            $this->otpService->clear($channel, $identifier, OtpService::PURPOSE_SIGNUP);

            return redirect()->route('login')->with('info', 'An account with this email already exists. Please sign in.');
        }

        $student = Student::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            ...$this->phoneService->attributesFor($pending['phone'] ?? null),
            'phone_verified_at' => $channel === OtpService::CHANNEL_SMS ? now() : null,
            'password' => $pending['password'],
            'status' => 'active',
        ]);

        $request->session()->forget('pending_otp_registration');
        $this->otpService->clear($channel, $identifier, OtpService::PURPOSE_SIGNUP);

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')->with('success', 'Verification successful! Welcome to Mono LMS.');
    }

    /**
     * Resend a fresh 6-digit OTP code (can also switch channel between email and SMS).
     */
    public function resend(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_otp_registration');

        if (! $pending) {
            return redirect()->route('register')->withErrors(['email' => 'No pending registration found. Please register again.']);
        }

        $channel = $request->input('channel', $pending['channel'] ?? OtpService::CHANNEL_EMAIL);
        if ($channel === OtpService::CHANNEL_SMS && (! $this->smsService->otpEnabled() || empty($pending['phone']))) {
            $channel = OtpService::CHANNEL_EMAIL;
        }

        $identifier = $channel === OtpService::CHANNEL_SMS ? $pending['phone'] : $pending['email'];

        [$otpModel, $code] = $this->otpService->issueFor(
            $channel,
            $identifier,
            OtpService::PURPOSE_SIGNUP,
            $pending['name'],
            $pending['type'] ?? 'student',
        );

        $pending['channel'] = $channel;
        $pending['otp'] = $code;
        $pending['expires_at'] = now()->addMinutes(OtpService::EXPIRY_MINUTES)->timestamp;
        $request->session()->put('pending_otp_registration', $pending);

        $targetText = $channel === OtpService::CHANNEL_SMS ? 'your phone ('.$this->phoneService->mask($identifier).')' : 'your email ('.$this->maskEmail($identifier).')';

        return back()->with('status', "A new 6-digit verification code has been dispatched to {$targetText}.");
    }

    /**
     * Initiate an OTP sign-up flow from registration controller.
     *
     * @param  array<string, mixed>  $data
     */
    public function initiateRegistration(Request $request, array $data): RedirectResponse
    {
        // Choose channel: if user provided phone and SMS OTP is enabled, or default to email
        $channel = (! empty($data['phone']) && $this->smsService->otpEnabled() && ($data['prefer_sms'] ?? false))
            ? OtpService::CHANNEL_SMS
            : OtpService::CHANNEL_EMAIL;

        $identifier = $channel === OtpService::CHANNEL_SMS ? $data['phone'] : $data['email'];

        [$otpModel, $code] = $this->otpService->issueFor(
            $channel,
            $identifier,
            OtpService::PURPOSE_SIGNUP,
            $data['name'],
            $data['type'] ?? 'student',
        );

        $payload = [
            'type' => $data['type'] ?? 'student',
            'channel' => $channel,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'designation' => $data['designation'] ?? null,
            'otp' => $code,
            'expires_at' => now()->addMinutes(OtpService::EXPIRY_MINUTES)->timestamp,
        ];

        $request->session()->put('pending_otp_registration', $payload);

        $dest = $channel === OtpService::CHANNEL_SMS ? $this->phoneService->mask($identifier) : $this->maskEmail($identifier);

        return redirect()->route('otp')->with('status', "Verification code sent to {$dest}!");
    }

    /**
     * Mask an email address for privacy.
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
