<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GoogleSettingsController extends Controller
{
    public const FIELDS = [
        'google_login_enabled',
        'google_client_id',
        'google_client_secret',
        'google_allow_student',
        'google_allow_instructor',
    ];

    public function index(): View
    {
        $settings = [
            'enabled' => Setting::enabled('google_login_enabled'),
            'client_id' => (string) Setting::get('google_client_id', ''),
            'client_secret' => (string) Setting::get('google_client_secret', ''),
            'allow_student' => Setting::get('google_allow_student', '1') !== '0',
            'allow_instructor' => Setting::get('google_allow_instructor', '1') !== '0',
        ];

        $callbacks = [
            'student' => route('login.google.callback', ['panel' => 'student']),
            'instructor' => route('login.google.callback', ['panel' => 'instructor']),
        ];

        return view('pages.admin.settings.google', [
            'settings' => $settings,
            'callbacks' => $callbacks,
            'consoleUrl' => 'https://console.cloud.google.com/apis/credentials',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'google_client_id' => ['nullable', 'string', 'max:255'],
            'google_client_secret' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            if ($request->filled($key)) {
                Setting::set($key, trim((string) $value));
            }
        }

        Setting::set('google_login_enabled', $request->boolean('google_login_enabled') ? '1' : '0');
        Setting::set('google_allow_student', $request->boolean('google_allow_student') ? '1' : '0');
        Setting::set('google_allow_instructor', $request->boolean('google_allow_instructor') ? '1' : '0');

        return back()->with('success', 'Google login settings saved successfully.');
    }
}
