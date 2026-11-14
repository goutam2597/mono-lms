<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SmsGatewayDevice;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Token authentication for administrator companion clients (such as the SMS Gateway app).
 */
class AdminAuthController extends Controller
{
    public const GATEWAY_PERMISSION = 'sms.gateway.manage';

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'deviceName' => ['nullable', 'string', 'max:80'],
            'deviceId' => ['nullable', 'string', 'max:120'],
        ]);

        $admin = Admin::where('email', $data['email'])->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        if (! $admin->isSuperAdmin() && ! $admin->hasPermission(self::GATEWAY_PERMISSION)) {
            return $this->fail(
                'Your administrator account does not have permission to manage the SMS Gateway.',
                'PERMISSION_DENIED',
                403,
            );
        }

        // Generate temporary admin session token valid for 24 hours
        $sessionToken = Str::random(60);
        Cache::put('admin_api_token_'.hash('sha256', $sessionToken), $admin->id, now()->addHours(24));

        return $this->ok([
            'token' => $sessionToken,
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'isSuperAdmin' => $admin->isSuperAdmin(),
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var Admin $admin */
        $admin = $request->user();

        return $this->ok([
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'isSuperAdmin' => $admin->isSuperAdmin(),
                'canManageGateway' => $admin->isSuperAdmin() || $admin->hasPermission(self::GATEWAY_PERMISSION),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = (string) $request->bearerToken();
        if ($token !== '') {
            Cache::forget('admin_api_token_'.hash('sha256', $token));
        }

        return $this->ok(['message' => 'Signed out.']);
    }

    /**
     * Register or update the physical phone and issue a permanent device bearer token.
     */
    public function registerDevice(Request $request): JsonResponse
    {
        /** @var Admin $admin */
        $admin = $request->user();

        if (! $admin->isSuperAdmin() && ! $admin->hasPermission(self::GATEWAY_PERMISSION)) {
            return $this->fail(
                'Your administrator account does not have permission to manage the SMS Gateway.',
                'PERMISSION_DENIED',
                403,
            );
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'deviceId' => ['nullable', 'string', 'max:120'],
            'fcmToken' => ['nullable', 'string', 'max:512'],
            'appVersion' => ['nullable', 'string', 'max:40'],
            'simNumber' => ['nullable', 'string', 'max:40'],
            'simState' => ['nullable', 'string', 'max:20'],
            'carrier' => ['nullable', 'string', 'max:80'],
        ]);

        $token = 'smsgw_'.Str::random(48);
        $deviceId = trim((string) ($data['deviceId'] ?? ''));

        $device = $deviceId !== ''
            ? SmsGatewayDevice::where('device_id', $deviceId)->first()
            : null;

        $device ??= SmsGatewayDevice::where('name', $data['name'])->first();
        $device ??= new SmsGatewayDevice(['name' => $data['name']]);

        $isFirstDevice = SmsGatewayDevice::count() === 0;

        $device->forceFill([
            'name' => $data['name'],
            'token_hash' => SmsGatewayDevice::hashToken($token),
            'fcm_token' => $data['fcmToken'] ?? $device->fcm_token,
            'app_version' => $data['appVersion'] ?? $device->app_version,
            'sim_number' => SmsService::normalizePhone($data['simNumber'] ?? null) ?: $device->sim_number,
            'sim_state' => $data['simState'] ?? $device->sim_state,
            'carrier' => $data['carrier'] ?? $device->carrier,
            'is_enabled' => true,
            'is_active' => $device->is_active || $isFirstDevice,
            'last_seen_at' => now(),
        ]);

        if ($deviceId !== '') {
            $device->device_id = $deviceId;
        }

        $device->save();

        return $this->ok([
            'deviceToken' => $token,
            'deviceId' => $device->id,
            'deviceName' => $device->name,
            'isActive' => $device->is_active,
        ]);
    }

    private function ok(array $data): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data]);
    }

    private function fail(string $message, string $code, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => ['message' => $message, 'code' => $code],
        ], $status);
    }
}
