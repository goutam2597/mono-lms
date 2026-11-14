<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Cookie consent settings: toggles the storefront consent banner and its
 * copy/style. The banner itself lives in components/cookie-consent.blade.php
 * and renders on the storefront + auth layouts only.
 */
class CookieSettingsController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        return view('pages.admin.settings.cookie', [
            'settings' => Setting::orderBy('key')->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'cookie_consent_layout' => ['required', 'in:bar,card,modal'],
            'cookie_consent_title' => ['nullable', 'string', 'max:120'],
            'cookie_consent_message' => ['nullable', 'string', 'max:600'],
            'cookie_consent_accept_text' => ['nullable', 'string', 'max:60'],
            'cookie_consent_decline_text' => ['nullable', 'string', 'max:60'],
            'cookie_consent_policy_text' => ['nullable', 'string', 'max:60'],
            'cookie_consent_policy_url' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::set('cookie_consent_enabled', $request->boolean('cookie_consent_enabled') ? '1' : '0');
        Setting::set('cookie_consent_show_decline', $request->boolean('cookie_consent_show_decline') ? '1' : '0');
        Setting::set('cookie_consent_layout', $data['cookie_consent_layout']);

        foreach ([
            'cookie_consent_title',
            'cookie_consent_message',
            'cookie_consent_accept_text',
            'cookie_consent_decline_text',
            'cookie_consent_policy_text',
            'cookie_consent_policy_url',
        ] as $key) {
            Setting::set($key, trim((string) ($data[$key] ?? '')));
        }

        return back()->with('success', __('admin.cookie.saved'));
    }

    private function authorizeAdmin(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin && ($admin->isSuperAdmin() || $admin->hasPermission('settings.manage')), 403);
    }
}
