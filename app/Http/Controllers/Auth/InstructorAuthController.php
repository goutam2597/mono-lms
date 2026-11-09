<?php

namespace App\Http\Controllers\Auth;

use App\Models\Instructor;
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
        ]);

        $instructor = Instructor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'designation' => $data['designation'] ?? 'Instructor',
            'status' => 'active',
        ]);

        Auth::guard($this->guard())->login($instructor);
        $request->session()->regenerate();

        return redirect()->route($this->panelHome())->with('status', 'Welcome to Mono LMS! Your instructor account is ready.');
    }
}
