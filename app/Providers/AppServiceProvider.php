<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
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
    }
}
