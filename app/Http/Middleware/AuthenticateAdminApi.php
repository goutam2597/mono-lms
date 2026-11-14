<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lightweight token authentication for admin API requests (such as device pairing from the companion app).
 */
class AuthenticateAdminApi
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) $request->bearerToken();

        if ($token === '') {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Unauthenticated.', 'code' => 'UNAUTHENTICATED'],
            ], 401);
        }

        $adminId = Cache::get('admin_api_token_'.hash('sha256', $token));

        if (! $adminId) {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Invalid or expired session.', 'code' => 'UNAUTHENTICATED'],
            ], 401);
        }

        $admin = Admin::find($adminId);

        if (! $admin || $admin->status !== 'active') {
            return response()->json([
                'success' => false,
                'error' => ['message' => 'Admin account disabled or not found.', 'code' => 'UNAUTHENTICATED'],
            ], 401);
        }

        $request->setUserResolver(fn () => $admin);

        return $next($request);
    }
}
