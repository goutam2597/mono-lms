<?php

namespace App\Http\Controllers\Auth;

use App\Models\Instructor;
use App\Models\Setting;
use App\Rules\ValidTurnstile;
use App\Services\PhoneNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class InstructorAuthController extends PanelAuthController
{
    protected function guard(): string
    {
        return 'instructor';
    }

    protected function loginView(): string
    {
        return 'pages.auth.instructor-login';
    }

    protected function panelHome(): string
    {
        return 'instructor.dashboard';
    }

    protected function panelLabelKey(): string
    {
        return 'instructor.panel';
    }

    public function showRegister()
    {
        if (Auth::guard($this->guard())->check()) {
            return redirect()->route($this->panelHome());
        }

        return view('pages.auth.instructor-register');
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:instructors,email'],
            'password' => ['required', 'string', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:40', PhoneNumberService::rule()],
            'prefer_sms' => ['nullable', 'boolean'],
            'designation' => ['nullable', 'string', 'max:150'],
            'terms' => ['accepted'],
            'certification_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'national_id_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        if (Setting::isOtpSignupEnabled()) {
            return app(OtpController::class)->initiateRegistration($request, [
                'type' => 'instructor',
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'phone' => $data['phone'] ?? null,
                'prefer_sms' => (bool) ($data['prefer_sms'] ?? false),
                'designation' => $data['designation'] ?? 'Instructor',
            ]);
        }

        $certPath = $request->hasFile('certification_file')
            ? $request->file('certification_file')->store('verifications/certificates', 'public')
            : null;
        $idPath = $request->hasFile('national_id_file')
            ? $request->file('national_id_file')->store('verifications/national_ids', 'public')
            : null;
        $hasDocs = ($certPath !== null || $idPath !== null);

        $instructor = Instructor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            ...app(PhoneNumberService::class)->attributesFor($data['phone'] ?? null),
            'designation' => $data['designation'] ?? 'Instructor',
            'status' => 'pending_verification',
            'verification_status' => $hasDocs ? 'pending' : 'unverified',
            'certification_file' => $certPath,
            'national_id_file' => $idPath,
        ]);

        Auth::guard($this->guard())->login($instructor);
        $request->session()->regenerate();

        return redirect()->route($this->panelHome())->with('status', 'Welcome to Mono LMS! Please submit your Instructor Certificate and National ID for verification.');
    }
}
