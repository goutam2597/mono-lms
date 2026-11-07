<?php

namespace App\Http\Controllers\Auth;

use App\Models\Student;
use App\Http\Controllers\Controller;
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
