<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
        'firebase_service_account_path',
        'firebase_service_account_filename',
        'firebase_service_account_client_email',
        'firebase_service_account_project_id',
        'firebase_service_account_private_key_id',
        'firebase_service_account_uploaded_at',
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

        $serviceAccountPath = (string) Setting::get('firebase_service_account_path', '');
        $hasServiceAccount = $serviceAccountPath !== '' && Storage::disk('local')->exists($serviceAccountPath);

        $serviceAccount = [
            'has_file' => $hasServiceAccount,
            'filename' => (string) Setting::get('firebase_service_account_filename', ''),
            'client_email' => (string) Setting::get('firebase_service_account_client_email', ''),
            'project_id' => (string) Setting::get('firebase_service_account_project_id', ''),
            'uploaded_at' => (string) Setting::get('firebase_service_account_uploaded_at', ''),
        ];

        $callbacks = [
            'student' => route('login.google.callback', ['panel' => 'student']),
            'instructor' => route('login.google.callback', ['panel' => 'instructor']),
        ];

        return view('pages.admin.settings.google', [
            'settings' => $settings,
            'serviceAccount' => $serviceAccount,
            'callbacks' => $callbacks,
            'consoleUrl' => 'https://console.cloud.google.com/apis/credentials',
            'firebaseConsoleUrl' => 'https://console.firebase.google.com/',
            'firebaseServiceAccountsUrl' => 'https://console.firebase.google.com/project/_/settings/serviceaccounts/adminsdk',
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
            'firebase_service_account_file' => ['nullable', 'file', 'max:2048'],
        ]);

        // Process uploaded service account file if present
        if ($request->hasFile('firebase_service_account_file')) {
            $file = $request->file('firebase_service_account_file');
            $content = (string) file_get_contents($file->getRealPath());
            $json = json_decode($content, true);

            if (! is_array($json) || empty($json['project_id']) || empty($json['private_key']) || empty($json['client_email'])) {
                return back()->withInput()->withErrors([
                    'firebase_service_account_file' => 'The uploaded file is not a valid Firebase Admin SDK private key JSON file. Please ensure it contains client_email, project_id, and private_key.',
                ]);
            }

            $storedPath = 'firebase/service-account.json';
            Storage::disk('local')->put($storedPath, $content);

            Setting::set('firebase_service_account_path', $storedPath);
            Setting::set('firebase_service_account_filename', $file->getClientOriginalName());
            Setting::set('firebase_service_account_client_email', (string) ($json['client_email'] ?? ''));
            Setting::set('firebase_service_account_project_id', (string) ($json['project_id'] ?? ''));
            Setting::set('firebase_service_account_private_key_id', (string) ($json['private_key_id'] ?? ''));
            Setting::set('firebase_service_account_uploaded_at', now()->toIso8601String());

            if (empty($data['firebase_project_id']) && ! empty($json['project_id'])) {
                $data['firebase_project_id'] = (string) $json['project_id'];
            }

            Cache::forget('firebase_fcm_oauth_token_'.md5((string) ($json['client_email'] ?? '')));
        }

        foreach ($data as $key => $value) {
            if ($key !== 'firebase_service_account_file') {
                Setting::set($key, trim((string) $value));
            }
        }

        Setting::set('google_login_enabled', $request->boolean('google_login_enabled') ? '1' : '0');
        Setting::set('google_allow_student', $request->boolean('google_allow_student') ? '1' : '0');
        Setting::set('google_allow_instructor', $request->boolean('google_allow_instructor') ? '1' : '0');
        Setting::set('firebase_enabled', $request->boolean('firebase_enabled') ? '1' : '0');

        return back()->with('success', 'Google & Firebase settings saved successfully.');
    }

    public function destroyServiceAccount(): RedirectResponse
    {
        $path = (string) Setting::get('firebase_service_account_path', '');
        if ($path !== '' && Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }

        Setting::set('firebase_service_account_path', '');
        Setting::set('firebase_service_account_filename', '');
        Setting::set('firebase_service_account_client_email', '');
        Setting::set('firebase_service_account_project_id', '');
        Setting::set('firebase_service_account_private_key_id', '');
        Setting::set('firebase_service_account_uploaded_at', '');

        return back()->with('success', 'Firebase Admin SDK service account private key removed successfully.');
    }

    public function testFirebaseNotification(): RedirectResponse
    {
        $result = FirebaseNotificationService::testConnection();

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->withErrors(['firebase_test' => $result['message']]);
    }
}
