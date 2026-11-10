<?php

namespace App\Http\Controllers\Auth;

use App\Models\Instructor;
use App\Models\Setting;
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
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:instructors,email'],
            'password' => ['required', 'string', Password::min(8)],
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
