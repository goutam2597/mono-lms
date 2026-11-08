<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class SystemController extends Controller
{
    public function index(): View
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Development Server',
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
            'timezone' => config('app.timezone'),
        ];

        return view('pages.admin.system', [
            'info' => $info,
        ]);
    }

    public function clearCache(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');

        return back()->with('success', 'Application cache, compiled views, and config cleared successfully.');
    }
}
