<?php

namespace App\Http\Middleware;

use App\Models\SmsGatewayDevice;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authenticates a gateway device by its bearer token.
 * Compared against the stored SHA-256 hash.
 */
class AuthenticateSmsGateway
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) $request->bearerToken();

        if ($token === '') {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Missing device token.', 'code' => 'GATEWAY_UNAUTHENTICATED'],
            ], 401);
        }

        $device = SmsGatewayDevice::where('token_hash', SmsGatewayDevice::hashToken($token))->first();

        if ($device === null || ! $device->is_enabled) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Invalid or revoked device token.', 'code' => 'GATEWAY_UNAUTHENTICATED'],
            ], 401);
        }

        $device->forceFill(['last_seen_at' => now()])->saveQuietly();
        $request->attributes->set('sms_gateway_device', $device);

        return $next($request);
    }
}
