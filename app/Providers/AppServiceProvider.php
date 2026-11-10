<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('views/layouts'), 'layouts');

        // Bridge admin RBAC into Laravel gates: an ability is allowed when the
        // signed-in admin's role grants it (super admins bypass everything).
        Gate::before(function ($user, string $ability) {
            if ($user instanceof Admin) {
                return $user->hasPermission($ability) ? true : null;
            }

            return null;
        });

        // Rate limiters for security against brute-force attacks and abuse
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');

            return Limit::perMinute(5)->by($request->ip().'|'.$email)->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;

                return back()
                    ->withInput($request->except('password'))
                    ->withErrors([
                        'email' => "Too many login attempts. Please try again in {$retryAfter} seconds.",
                    ]);
            });
        });

        RateLimiter::for('otp-send', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip().'|'.($request->session()->getId() ?: 'guest'))->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;

                return back()->withErrors([
                    'email' => "Too many verification requests. Please wait {$retryAfter} seconds before requesting a new code.",
                    'otp' => "Too many verification requests. Please wait {$retryAfter} seconds before requesting a new code.",
                ]);
            });
        });

        RateLimiter::for('otp-verify', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip().'|'.($request->session()->getId() ?: 'guest'))->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;

                return back()->withErrors([
                    'otp' => "Too many verification attempts. Please wait {$retryAfter} seconds before trying again.",
                ]);
            });
        });
    }
}
