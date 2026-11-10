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
        'firebase_enabled',
        'firebase_api_key',
        'firebase_project_id',
        'firebase_auth_domain',
        'firebase_messaging_sender_id',
        'firebase_app_id',
        'firebase_fcm_server_key',
    ];

    public function index(): View
    {
        $settings = [
            'enabled' => Setting::enabled('google_login_enabled'),
            'client_id' => (string) Setting::get('google_client_id', ''),
            'client_secret' => (string) Setting::get('google_client_secret', ''),
            'allow_student' => Setting::get('google_allow_student', '1') !== '0',
            'allow_instructor' => Setting::get('google_allow_instructor', '1') !== '0',
            // Firebase settings for web & mobile app
            'firebase_enabled' => Setting::enabled('firebase_enabled'),
            'firebase_api_key' => (string) Setting::get('firebase_api_key', ''),
            'firebase_project_id' => (string) Setting::get('firebase_project_id', ''),
            'firebase_auth_domain' => (string) Setting::get('firebase_auth_domain', ''),
            'firebase_messaging_sender_id' => (string) Setting::get('firebase_messaging_sender_id', ''),
            'firebase_app_id' => (string) Setting::get('firebase_app_id', ''),
            'firebase_fcm_server_key' => (string) Setting::get('firebase_fcm_server_key', ''),
        ];

        $callbacks = [
            'student' => route('login.google.callback', ['panel' => 'student']),
            'instructor' => route('login.google.callback', ['panel' => 'instructor']),
        ];

        return view('pages.admin.settings.google', [
            'settings' => $settings,
            'callbacks' => $callbacks,
            'consoleUrl' => 'https://console.cloud.google.com/apis/credentials',
            'firebaseConsoleUrl' => 'https://console.firebase.google.com/',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'google_client_id' => ['nullable', 'string', 'max:255'],
            'google_client_secret' => ['nullable', 'string', 'max:255'],
            'firebase_api_key' => ['nullable', 'string', 'max:255'],
            'firebase_project_id' => ['nullable', 'string', 'max:255'],
            'firebase_auth_domain' => ['nullable', 'string', 'max:255'],
            'firebase_messaging_sender_id' => ['nullable', 'string', 'max:255'],
            'firebase_app_id' => ['nullable', 'string', 'max:255'],
            'firebase_fcm_server_key' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, trim((string) $value));
        }

        Setting::set('google_login_enabled', $request->boolean('google_login_enabled') ? '1' : '0');
        Setting::set('google_allow_student', $request->boolean('google_allow_student') ? '1' : '0');
        Setting::set('google_allow_instructor', $request->boolean('google_allow_instructor') ? '1' : '0');
        Setting::set('firebase_enabled', $request->boolean('firebase_enabled') ? '1' : '0');

        return back()->with('success', 'Google & Firebase settings saved successfully.');
    }
}
