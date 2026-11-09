<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Independent panels supporting theme switching.
     * Storefront is intentionally excluded and remains strictly light.
     */
    public const PANELS = ['admin', 'instructor', 'student'];

    /**
     * Toggle or set the theme for the given panel independently.
     */
    public function toggle(Request $request, string $panel): JsonResponse|RedirectResponse
    {
        abort_unless(in_array($panel, self::PANELS, true), 404);

        $sessionKey = $panel.'_theme';
        $current = (string) $request->session()->get($sessionKey, 'light');
        $requested = (string) $request->input('theme', '');

        if (in_array($requested, ['light', 'dark'], true)) {
            $newTheme = $requested;
        } else {
            $newTheme = ($current === 'dark') ? 'light' : 'dark';
        }

        $request->session()->put($sessionKey, $newTheme);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'panel' => $panel,
                'theme' => $newTheme,
            ]);
        }

        return redirect()->back();
    }
}
