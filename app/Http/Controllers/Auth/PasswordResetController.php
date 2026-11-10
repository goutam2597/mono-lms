<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * Shared forgot/reset flow for student, instructor, and admin panels.
 * Supports email OTP verification code with fallback to token reset links.
 */
class PasswordResetController extends Controller
{
    private const PANELS = ['student', 'instructor', 'admin'];

    public function showForgot(Request $request): View
    {
        return view('pages.auth.forgot-password', [
            'panel' => $this->panel($request),
        ]);
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $panel = $this->panel($request);
        $email = (string) $request->input('email');

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10)->timestamp;

        $user = match ($panel) {
            'instructor' => Instructor::where('email', $email)->first(),
            'admin' => Admin::where('email', $email)->first(),
            default => Student::where('email', $email)->first(),
        };

        $name = $user?->name ?? 'User';

        $payload = [
            'email' => $email,
            'panel' => $panel,
            'name' => $name,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ];

        $request->session()->put('pending_password_reset', $payload);

        if ($user) {
            $this->sendResetOtpEmail($email, $name, $otp, $panel);
        }

        return redirect()->route('password.otp.show')->with('status', "A 6-digit password reset code has been sent to {$this->maskEmail($email)}.");
    }

    public function showOtp(Request $request): View
    {
        $pending = $request->session()->get('pending_password_reset');

        $expiresAt = (int) ($pending['expires_at'] ?? (now()->addMinutes(10)->timestamp));
        $remainingSeconds = max(0, $expiresAt - now()->timestamp);

        $email = (string) ($pending['email'] ?? 'user@example.com');
        $maskedEmail = $this->maskEmail($email);
        $panel = $pending['panel'] ?? $this->panel($request);

        return view('pages.auth.password-reset-otp', [
            'maskedEmail' => $maskedEmail,
            'panel' => $panel,
            'remainingSeconds' => $remainingSeconds,
            'isExpired' => $remainingSeconds <= 0,
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_password_reset');

        if (! $pending) {
            return redirect()->route('password.request')->withErrors(['email' => 'No pending password reset found. Please submit your email again.']);
        }

        $request->validate([
            'otp' => ['required', 'string'],
        ]);

        $submittedOtp = preg_replace('/\D/', '', (string) $request->input('otp'));
        $expectedOtp = (string) ($pending['otp'] ?? '');
        $expiresAt = (int) ($pending['expires_at'] ?? 0);

        if (now()->timestamp > $expiresAt) {
            return back()->withErrors(['otp' => 'This verification code has expired. Please click "Resend Code" to receive a new code.']);
        }

        if ($submittedOtp !== $expectedOtp) {
            return back()->withErrors(['otp' => 'The verification code entered is incorrect. Please try again.']);
        }

        $request->session()->forget('pending_password_reset');
        $request->session()->put('verified_password_reset', [
            'email' => $pending['email'],
            'panel' => $pending['panel'],
            'token' => 'otp_verified',
            'expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        return redirect()->route('password.set.show')->with('status', 'Code verified! Please choose your new password.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_password_reset');

        if (! $pending) {
            return redirect()->route('password.request')->withErrors(['email' => 'No pending password reset found. Please submit your email again.']);
        }

        $otp = (string) random_int(100000, 999999);
        $pending['otp'] = $otp;
        $pending['expires_at'] = now()->addMinutes(10)->timestamp;

        $request->session()->put('pending_password_reset', $pending);

        $this->sendResetOtpEmail($pending['email'], $pending['name'] ?? 'User', $otp, $pending['panel'] ?? 'student');

        return back()->with('status', 'A new 6-digit password reset code has been sent to your email.');
    }

    public function showReset(Request $request, ?string $token = null): View|RedirectResponse
    {
        $verified = $request->session()->get('verified_password_reset');

        if ($token) {
            return view('pages.auth.set-password', [
                'panel' => $this->panel($request),
                'token' => $token,
                'email' => $request->query('email'),
            ]);
        }

        if ($verified && now()->timestamp <= ($verified['expires_at'] ?? 0)) {
            return view('pages.auth.set-password', [
                'panel' => $verified['panel'] ?? 'student',
                'token' => $verified['token'] ?? 'otp_verified',
                'email' => $verified['email'] ?? null,
            ]);
        }

        // Preview state for screen tests / direct navigations
        return view('pages.auth.set-password', [
            'panel' => $this->panel($request),
            'token' => 'preview',
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $verified = $request->session()->get('verified_password_reset');
        $panel = $this->panel($request);

        // Check if resetting via verified OTP
        if ($verified && now()->timestamp <= ($verified['expires_at'] ?? 0)) {
            $request->validate([
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            $email = $verified['email'];
            $panel = $verified['panel'];

            $user = match ($panel) {
                'instructor' => Instructor::where('email', $email)->first(),
                'admin' => Admin::where('email', $email)->first(),
                default => Student::where('email', $email)->first(),
            };

            if ($user) {
                $user->forceFill(['password' => $request->input('password')])->save();
            }

            $request->session()->forget('verified_password_reset');

            return redirect()->route($panel === 'student' ? 'login' : $panel.'.login')
                ->with('status', __('auth.reset_success'));
        }

        // Standard token broker reset
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::broker($panel)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => $password])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route($panel === 'student' ? 'login' : $panel.'.login')->with('status', __('auth.reset_success'));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    protected function sendResetOtpEmail(string $email, string $name, string $otp, string $panel): void
    {
        $this->applySmtpSettings();

        try {
            Mail::to($email)->send(new PasswordResetOtpMail($otp, $name, $panel));
        } catch (\Throwable $e) {
            Log::warning("Could not dispatch password reset OTP email via SMTP to {$email}: ".$e->getMessage());
        }

        Log::info("Password reset OTP generated for {$email} ({$panel}): {$otp}");
    }

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

    private function panel(Request $request): string
    {
        $panel = $request->input('panel', $request->query('panel', 'student'));

        return in_array($panel, self::PANELS, true) ? $panel : 'student';
    }
}
