<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated administrator's profile edit page.
     */
    public function edit(): View
    {
        return view('pages.admin.profile', [
            'admin' => auth('admin')->user(),
        ]);
    }

    /**
     * Update the administrator's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = auth('admin')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'locale' => ['nullable', 'string', 'max:10'],
        ]);

        $admin->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the administrator's account password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth('admin')->user()->update([
            'password' => $data['password'],
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
