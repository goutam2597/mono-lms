<?php

namespace App\Http\Controllers\Auth;

use App\Models\Setting;
use App\Models\Student;
use App\Rules\ValidTurnstile;
use App\Services\PhoneNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends PanelAuthController
{
    protected function guard(): string
    {
        return 'student';
    }

    protected function loginView(): string
    {
        return 'pages.auth.login';
    }

    protected function panelHome(): string
    {
        return 'student.dashboard';
    }

    protected function panelLabelKey(): string
    {
        return 'student.panel';
    }

    protected function loginRouteName(): string
    {
        // The student login route is named `login`, not `student.login`.
        return 'login';
    }

    public function login(Request $request)
    {
        // Web-only bot gate on the shared multi-guard login form (this
        // override handles student, instructor and admin credentials). The
        // field is merged in so the rule also runs when the widget never
        // rendered (Laravel skips non-implicit rules for absent fields).
        if ($this->turnstileEnabled()) {
            $request->merge(['cf-turnstile-response' => $request->input('cf-turnstile-response')]);
            $request->validate(['cf-turnstile-response' => [new ValidTurnstile('login')]]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // 1. Try student
        if (Auth::guard('student')->attempt($credentials, $remember)) {
            $user = Auth::guard('student')->user();
            if ($user->status !== 'active') {
                Auth::guard('student')->logout();
                throw ValidationException::withMessages([
                    'email' => __('auth.account_inactive'),
                ]);
            }
            $request->session()->regenerate();

            return redirect()->intended(route('student.dashboard'));
        }

        // 2. Fallback to admin (in case admin logs in via main /login)
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $user = Auth::guard('admin')->user();
            if ($user->status !== 'active') {
                Auth::guard('admin')->logout();
                throw ValidationException::withMessages([
                    'email' => __('auth.account_inactive'),
                ]);
            }
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // 3. Fallback to instructor
        if (Auth::guard('instructor')->attempt($credentials, $remember)) {
            $user = Auth::guard('instructor')->user();
            if ($user->status !== 'active') {
                Auth::guard('instructor')->logout();
                throw ValidationException::withMessages([
                    'email' => __('auth.account_inactive'),
                ]);
            }
            $request->session()->regenerate();

            return redirect()->intended(route('instructor.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.invalid_credentials'),
        ]);
    }

    public function showRegister()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route($this->panelHome());
        }

        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        // Web-only bot gate. Passes untouched while Turnstile is disabled;
        // when the OTP signup flow is on, this also shields it from spam. The
        // field is merged in so the rule also runs when the widget never
        // rendered (Laravel skips non-implicit rules for absent fields).
        $request->merge(['cf-turnstile-response' => $request->input('cf-turnstile-response')]);
        $request->validate(['cf-turnstile-response' => [new ValidTurnstile('register')]]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students,email'],
            'password' => ['required', 'string', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:40', PhoneNumberService::rule()],
            'prefer_sms' => ['nullable', 'boolean'],
            'terms' => ['accepted'],
        ]);

        if (Setting::isOtpSignupEnabled()) {
            return app(OtpController::class)->initiateRegistration($request, [
                'type' => 'student',
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'phone' => $data['phone'] ?? null,
                'prefer_sms' => (bool) ($data['prefer_sms'] ?? false),
            ]);
        }

        $student = Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            ...app(PhoneNumberService::class)->attributesFor($data['phone'] ?? null),
            'status' => 'active',
        ]);

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')->with('status', __('auth.registered'));
    }
}
