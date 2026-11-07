<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            abort(403, 'You do not have permission to access this resource.');
        }

        // Support pipe-separated permissions: admin needs ANY of them
        if (str_contains($permission, '|')) {
            $allowed = false;
            foreach (explode('|', $permission) as $perm) {
                $perm = trim($perm);
                if ($perm !== '' && $admin->hasPermission($perm)) {
                    $allowed = true;
                    break;
                }
            }
            if (! $allowed) {
                abort(403, 'You do not have permission to access this resource.');
            }
        } elseif (! $admin->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
