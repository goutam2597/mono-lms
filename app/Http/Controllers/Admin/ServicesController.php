<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(): View
    {
        $mail = [
            'host' => Setting::get('mail_host', 'sandbox.smtp.mailtrap.io'),
            'port' => Setting::get('mail_port', '2525'),
            'username' => Setting::get('mail_username', 'mono_smtp_user'),
            'password' => Setting::get('mail_password', '••••••••'),
            'encryption' => Setting::get('mail_encryption', 'tls'),
            'from_address' => Setting::get('mail_from_address', 'no-reply@mono-lms.test'),
            'from_name' => Setting::get('mail_from_name', 'Mono LMS Notifications'),
        ];

        $storage = [
            'driver' => Setting::get('storage_driver', 'public'),
            's3_key' => Setting::get('storage_s3_key', ''),
            's3_secret' => Setting::get('storage_s3_secret', ''),
            's3_bucket' => Setting::get('storage_s3_bucket', 'mono-lms-assets'),
            's3_region' => Setting::get('storage_s3_region', 'us-east-1'),
        ];

        return view('pages.admin.services', [
            'mail' => $mail,
            'storage' => $storage,
            'otp_signup_enabled' => Setting::isOtpSignupEnabled(),
        ]);
    }

    public function updateMail(Request $request): RedirectResponse
    {
        $fields = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'];

        foreach ($fields as $f) {
            if ($request->filled($f)) {
                Setting::set($f, (string) $request->input($f));
            }
        }

        if ($request->has('otp_setting_present')) {
            Setting::set('otp_signup_enabled', $request->has('otp_signup_enabled') ? '1' : '0');
        }

        if ($admin = auth('admin')->user()) {
            NotificationService::send(
                notifiable: $admin,
                title: 'SMTP Mail Configuration Updated',
                message: 'Outbound SMTP mail server settings were saved and applied.',
                category: 'smtp',
                actionUrl: route('admin.services.index'),
                actionLabel: 'View Services',
                icon: 'mail',
                sendMail: false,
            );
        }

        return back()->with('success', 'SMTP mail settings saved successfully.');
    }

    public function testMail(Request $request): RedirectResponse
    {
        $email = $request->input('test_email', 'admin@learnify.test');

        if ($admin = auth('admin')->user()) {
            NotificationService::send(
                notifiable: $admin,
                title: 'SMTP Test Email Dispatched',
                message: "Test outbound email simulated and dispatched via SMTP to {$email}.",
                category: 'smtp',
                actionUrl: route('admin.services.index'),
                actionLabel: 'View Services',
                icon: 'mail',
                sendMail: true,
            );
        }

        return back()->with('success', "Test email simulated and dispatched successfully to {$email}.");
    }

    public function updateStorage(Request $request): RedirectResponse
    {
        $fields = ['storage_driver', 'storage_s3_key', 'storage_s3_secret', 'storage_s3_bucket', 'storage_s3_region'];

        foreach ($fields as $f) {
            if ($request->has($f)) {
                Setting::set($f, (string) $request->input($f));
            }
        }

        return back()->with('success', 'File storage configuration saved.');
    }
}
