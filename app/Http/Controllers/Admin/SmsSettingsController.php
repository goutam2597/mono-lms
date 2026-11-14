<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SmsGatewayDevice;
use App\Services\SmsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SmsSettingsController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $activeDevice = SmsGatewayDevice::active();

        $settings = [
            'sms_enabled' => Setting::enabled('sms_enabled'),
            'sms_otp_enabled' => Setting::enabled('sms_otp_enabled'),
            'sms_notification_enabled' => Setting::enabled('sms_notification_enabled'),
            'sms_provider' => Setting::get('sms_provider', 'twilio'),
            'twilio_sid' => Setting::get('twilio_sid', ''),
            'twilio_token' => Setting::get('twilio_token', ''),
            'twilio_from' => Setting::get('twilio_from', ''),
        ];

        return view('pages.admin.settings.sms', [
            'settings' => $settings,
            'activeDevice' => $activeDevice,
            'gatewayConfigured' => $activeDevice !== null,
            'twilioConfigured' => filled($settings['twilio_sid']) && filled($settings['twilio_token']) && filled($settings['twilio_from']),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'sms_provider' => ['required', 'string', 'in:twilio,gateway'],
            'twilio_sid' => ['nullable', 'string', 'max:120'],
            'twilio_token' => ['nullable', 'string', 'max:120'],
            'twilio_from' => ['nullable', 'string', 'max:40'],
        ]);

        Setting::set('sms_enabled', $request->has('sms_enabled') ? '1' : '0');
        Setting::set('sms_otp_enabled', $request->has('sms_otp_enabled') ? '1' : '0');
        Setting::set('sms_notification_enabled', $request->has('sms_notification_enabled') ? '1' : '0');
        Setting::set('sms_provider', $data['sms_provider']);

        Setting::set('twilio_sid', $data['twilio_sid'] ?? '');
        Setting::set('twilio_token', $data['twilio_token'] ?? '');
        Setting::set('twilio_from', $data['twilio_from'] ?? '');

        return back()->with('success', 'SMS gateway settings updated successfully.');
    }

    /**
     * Send a live test SMS to verify transport connectivity.
     */
    public function test(Request $request, SmsService $smsService): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'test_phone' => ['required', 'string'],
        ]);

        $recipient = SmsService::normalizePhone($data['test_phone']);

        if (! $recipient) {
            return back()->withErrors(['test_phone' => 'Please enter a valid phone number (e.g. +8801700000000).']);
        }

        $site = Setting::get('site_name', 'Mono LMS');
        $providerName = $smsService->providerName();
        $message = "Test SMS from {$site} via {$providerName} transport. Timestamp: ".now()->format('Y-m-d H:i:s');

        $result = $smsService->send($recipient, $message, 'test');

        if ($result) {
            return back()->with('success', "Test SMS successfully dispatched to {$recipient} via {$providerName}.");
        }

        return back()->withErrors(['test_phone' => "Failed to dispatch test SMS. Please ensure {$providerName} is configured and active."]);
    }

    private function authorizeAdmin(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin && ($admin->isSuperAdmin() || $admin->hasPermission('sms.gateway.manage')), 403);
    }
}
