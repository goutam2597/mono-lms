<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Resolve the active locale (panel session → account preference → default),
     * apply it per panel context (admin / instructor / student / storefront),
     * and share direction + languages with every view.
     *
     * Mirrors mono_market's SetLocale: each panel keeps its own locale session,
     * so switching language in the admin panel never affects the storefront.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $languages = Language::active();
        $default   = Language::defaultCode();

        if ($request->is('admin') || $request->is('admin/*')) {
            $locale = $request->session()->get('admin_locale')
                ?? auth('admin')->user()?->locale
                ?? $default;
        } elseif ($request->is('instructor') || $request->is('instructor/*')) {
            $locale = $request->session()->get('instructor_locale')
                ?? auth('instructor')->user()?->locale
                ?? $default;
        } elseif ($request->is('student') || $request->is('student/*')) {
            $locale = $request->session()->get('student_locale')
                ?? auth('student')->user()?->locale
                ?? $default;
        } else {
            $locale = $request->session()->get('store_locale')
                ?? auth('student')->user()?->locale
                ?? $default;
        }

        // Guard against disabled / unknown codes.
        if (! $languages->contains('code', $locale)) {
            $locale = $default;
        }

        app()->setLocale($locale);

        $current = $languages->firstWhere('code', $locale);
        $direction = $current?->direction ?? 'ltr';

        View::share('activeLanguages', $languages);
        View::share('currentLocale', $locale);
        View::share('currentDirection', $direction);
        View::share('isRtl', $direction === 'rtl');

        return $next($request);
    }
}
