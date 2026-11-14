<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsGatewayDevice;
use App\Models\SmsInboundMessage;
use App\Models\SmsMessage;
use App\Services\Sms\GatewaySmsProvider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Admin management for self-hosted Android SMS Gateway phones, device pairing, and logs.
 */
class SmsGatewayController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        return view('pages.admin.sms-gateway.index', [
            'devices' => SmsGatewayDevice::withCount(['messages', 'inboundMessages'])->orderByDesc('id')->get(),
            'active' => SmsGatewayDevice::active(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
        ]);

        [$device, $token] = SmsGatewayDevice::register($data['name']);

        if (SmsGatewayDevice::count() === 1) {
            $device->forceFill(['is_active' => true])->save();
        }

        return back()
            ->with('gateway_token', $token)
            ->with('gateway_token_device', $device->name)
            ->with('success', "Device '{$device->name}' registered successfully. Copy the device token now.");
    }

    public function setActive(Request $request, SmsGatewayDevice $device): RedirectResponse
    {
        $this->authorizeAdmin();

        // Make this device the only active sender
        SmsGatewayDevice::where('id', '!=', $device->id)->update(['is_active' => false]);
        $device->forceFill(['is_active' => ! $device->is_active, 'is_enabled' => true])->save();

        return back()->with('success', "Active gateway device set to '{$device->name}'.");
    }

    public function toggle(Request $request, SmsGatewayDevice $device): RedirectResponse
    {
        $this->authorizeAdmin();

        $device->forceFill([
            'is_enabled' => ! $device->is_enabled,
            'is_active' => $device->is_enabled ? false : $device->is_active,
        ])->save();

        return back()->with('success', "Device '{$device->name}' status updated.");
    }

    public function revoke(Request $request, SmsGatewayDevice $device): RedirectResponse
    {
        $this->authorizeAdmin();

        $token = 'smsgw_'.Str::random(48);

        $device->forceFill([
            'token_hash' => SmsGatewayDevice::hashToken($token),
            'fcm_token' => null,
            'is_enabled' => false,
            'is_active' => false,
        ])->save();

        return back()
            ->with('gateway_token', $token)
            ->with('gateway_token_device', $device->name)
            ->with('success', "Device '{$device->name}' token rotated. Previous token is now invalid.");
    }

    public function destroy(Request $request, SmsGatewayDevice $device): RedirectResponse
    {
        $this->authorizeAdmin();

        $name = $device->name;
        $device->delete();

        return back()->with('success', "Device '{$name}' deleted successfully.");
    }

    public function log(Request $request): View
    {
        $this->authorizeAdmin();

        $status = $request->query('status');
        $kind = $request->query('kind');

        $messages = SmsMessage::query()
            ->with('device')
            ->when(in_array($status, ['pending', 'processing', 'sent', 'failed'], true),
                fn ($q) => $q->where('status', $status))
            ->when(in_array($kind, ['otp', 'notice', 'test', 'other'], true),
                fn ($q) => $q->where('kind', $kind))
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('pages.admin.sms-gateway.log', [
            'messages' => $messages,
            'inbound' => SmsInboundMessage::with('device')->orderByDesc('id')->limit(25)->get(),
            'status' => $status ?: 'all',
            'kind' => $kind ?: 'all',
            'counts' => [
                'pending' => SmsMessage::where('status', SmsMessage::STATUS_PENDING)->count(),
                'processing' => SmsMessage::where('status', SmsMessage::STATUS_PROCESSING)->count(),
                'sent' => SmsMessage::where('status', SmsMessage::STATUS_SENT)->count(),
                'failed' => SmsMessage::where('status', SmsMessage::STATUS_FAILED)->count(),
            ],
        ]);
    }

    public function nudge(Request $request, GatewaySmsProvider $provider): RedirectResponse
    {
        $this->authorizeAdmin();

        $device = SmsGatewayDevice::active();

        if ($device === null) {
            return back()->withErrors(['nudge' => 'No active gateway device is currently selected.']);
        }

        $provider->wake($device);

        return back()->with('success', "FCM wake-up signal dispatched to device '{$device->name}'.");
    }

    private function authorizeAdmin(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin && ($admin->isSuperAdmin() || $admin->hasPermission('sms.gateway.manage')), 403);
    }
}
