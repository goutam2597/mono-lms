<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\TurnstileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Cloudflare Turnstile bot-protection settings: toggles the Turnstile widget
 * on the student/instructor login and registration forms and stores the site
 * keys. The widget itself lives in components/turnstile.blade.php; the
 * server-side gate is App\Rules\ValidTurnstile.
 */
class TurnstileSettingsController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        return view('pages.admin.settings.turnstile', [
            'settings' => Setting::orderBy('key')->pluck('value', 'key'),
            'service' => app(TurnstileService::class),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'turnstile_site_key' => ['nullable', 'string', 'max:200'],
            'turnstile_secret_key' => ['nullable', 'string', 'max:200'],
            'turnstile_theme' => ['nullable', 'in:light,dark,auto'],
        ]);

        Setting::set('turnstile_enabled', $request->boolean('turnstile_enabled') ? '1' : '0');
        Setting::set('turnstile_site_key', trim((string) ($data['turnstile_site_key'] ?? '')));
        Setting::set('turnstile_secret_key', trim((string) ($data['turnstile_secret_key'] ?? '')));
        Setting::set('turnstile_theme', $data['turnstile_theme'] ?? 'auto');

        return back()->with('success', __('admin.turnstile.saved'));
    }

    private function authorizeAdmin(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin && ($admin->isSuperAdmin() || $admin->hasPermission('settings.manage')), 403);
    }
}
