<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pages.student.profile', ['student' => auth('student')->user()]);
    }

    public function update(Request $request)
    {
        auth('student')->user()->update(
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:30'],
                'avatar' => ['nullable', 'string', 'max:500'],
            ])
        );

        return back()->with('success', __('student.profile.updated'));
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:student'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth('student')->user()->update(['password' => $data['password']]);

        return back()->with('success', __('student.profile.password_updated'));
    }
}
