<?php

use App\Http\Middleware\HasPermission;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => HasPermission::class,
        ]);

        $middleware->web(append: [
            SetLocale::class,
        ]);

        // Send guests to the right login page for each area.
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('instructor') || $request->is('instructor/*')) {
                return route('instructor.login');
            }

            return route('login');
        });

        // Send already-authenticated users to their respective panel dashboard.
        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('instructor') || $request->is('instructor/*')) {
                return route('instructor.dashboard');
            }

            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.dashboard');
            }

            if ($request->is('student') || $request->is('student/*') || $request->routeIs('login', 'register')) {
                return route('student.dashboard');
            }

            if (auth('instructor')->check()) {
                return route('instructor.dashboard');
            }

            if (auth('admin')->check()) {
                return route('admin.dashboard');
            }

            if (auth('student')->check()) {
                return route('student.dashboard');
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
