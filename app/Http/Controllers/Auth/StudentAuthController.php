<?php

namespace App\Http\Controllers\Auth;

use App\Models\Setting;
use App\Models\Student;
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

    public function login(Request $request)
    {
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
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students,email'],
            'password' => ['required', 'string', Password::min(8)],
            'terms' => ['accepted'],
        ]);

        if (Setting::isOtpSignupEnabled()) {
            return app(OtpController::class)->initiateRegistration($request, [
                'type' => 'student',
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        }

        $student = Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => 'active',
        ]);

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')->with('status', __('auth.registered'));
    }
}
