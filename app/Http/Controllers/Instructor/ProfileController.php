<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('pages.instructor.profile', ['instructor' => auth('instructor')->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $instructor = auth('instructor')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('instructors', 'email')->ignore($instructor->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'avatar' => ['nullable', 'image', 'max:4096', 'mimes:jpg,jpeg,png,webp,svg'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        unset($data['avatar'], $data['remove_avatar']);

        if ($request->boolean('remove_avatar')) {
            $this->deleteLocalAvatar($instructor->avatar);
            $instructor->update(['avatar' => null]);
        }

        if ($request->hasFile('avatar')) {
            $this->deleteLocalAvatar($instructor->avatar);
            $path = $request->file('avatar')->store('avatars/instructors', 'public');
            $instructor->update(['avatar' => $path]);
        }

        $instructor->update($data);

        return back()->with('success', __('instructor.profile.updated'));
    }

    /**
     * Only delete local storage files (never external URLs).
     */
    private function deleteLocalAvatar(?string $avatar): void
    {
        if ($avatar === null || $avatar === '' || str_starts_with($avatar, 'http')) {
            return;
        }

        Storage::disk('public')->delete($avatar);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:instructor'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth('instructor')->user()->update(['password' => $data['password']]);

        return back()->with('success', __('instructor.profile.password_updated'));
    }
}
