<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pages.student.profile', ['student' => auth('student')->user()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $student = auth('student')->user();
        unset($data['avatar'], $data['remove_avatar']);

        if ($request->boolean('remove_avatar')) {
            $this->deleteLocalAvatar($student->avatar);
            $student->update(['avatar' => null]);
        }

        if ($request->hasFile('avatar')) {
            $this->deleteLocalAvatar($student->avatar);

            $path = $request->file('avatar')->store('avatars/'.date('Y/m'), 'public');
            $student->update(['avatar' => $path]);
        }

        $student->update($data);

        return back()->with('success', __('student.profile.updated'));
    }

    /** Only delete files that live on our local public disk (never Google URLs). */
    private function deleteLocalAvatar(?string $avatar): void
    {
        if ($avatar === null || $avatar === '' || str_starts_with($avatar, 'http')) {
            return;
        }

        Storage::disk('public')->delete($avatar);
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
