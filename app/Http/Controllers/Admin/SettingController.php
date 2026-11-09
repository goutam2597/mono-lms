<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'Mono LMS'),
            'site_description' => Setting::get('site_description', 'Modern, accessible Learning Management System'),
            'site_logo' => Setting::get('site_logo', ''),
            'site_favicon' => Setting::get('site_favicon', ''),
            'contact_email' => Setting::get('contact_email', 'support@mono-lms.test'),
            'contact_phone' => Setting::get('contact_phone', '+1 (555) 234-5678'),
            'currency_code' => Setting::get('currency_code', 'USD'),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
            'timezone' => Setting::get('timezone', 'UTC'),
            'footer_copyright' => Setting::get('footer_copyright', '© 2026 Mono LMS. All rights reserved.'),
            'membership_enabled' => Setting::get('membership_enabled', '1') === '1',
            'blog_enabled' => Setting::get('blog_enabled', '1') === '1',
            'forums_enabled' => Setting::get('forums_enabled', '1') === '1',
            'certificates_enabled' => Setting::get('certificates_enabled', '1') === '1',
            'seo_meta_title' => Setting::get('seo_meta_title', 'Mono LMS - Transform Your Learning Experience'),
            'seo_meta_description' => Setting::get('seo_meta_description', 'High performance enterprise LMS platform built with Laravel 12 & Tailwind CSS.'),
        ];

        return view('pages.admin.settings', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $fields = [
            'site_name',
            'site_description',
            'site_logo',
            'site_favicon',
            'contact_email',
            'contact_phone',
            'currency_code',
            'currency_symbol',
            'timezone',
            'footer_copyright',
            'seo_meta_title',
            'seo_meta_description',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, (string) $request->input($field));
            }
        }

        // Toggles
        Setting::set('membership_enabled', $request->has('membership_enabled') ? '1' : '0');
        Setting::set('blog_enabled', $request->has('blog_enabled') ? '1' : '0');
        Setting::set('forums_enabled', $request->has('forums_enabled') ? '1' : '0');
        Setting::set('certificates_enabled', $request->has('certificates_enabled') ? '1' : '0');

        return back()->with('success', 'Platform settings saved successfully.');
    }
}
