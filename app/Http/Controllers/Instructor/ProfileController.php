<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pages.instructor.profile', ['instructor' => auth('instructor')->user()]);
    }

    public function update(Request $request)
    {
        auth('instructor')->user()->update(
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:30'],
                'designation' => ['nullable', 'string', 'max:100'],
                'bio' => ['nullable', 'string', 'max:5000'],
                'avatar' => ['nullable', 'string', 'max:500'],
            ])
        );

        return back()->with('success', __('instructor.profile.updated'));
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:instructor'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth('instructor')->user()->update(['password' => $data['password']]);

        return back()->with('success', __('instructor.profile.password_updated'));
    }
}
