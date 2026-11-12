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
            'footer_payment_methods' => Setting::get('footer_payment_methods', ''),
            'footer_payment_methods_enabled' => Setting::get('footer_payment_methods_enabled', '1') === '1',
            'footer_payment_methods_width' => Setting::get('footer_payment_methods_width', '340px'),
            'app_store_url' => Setting::appStoreUrl(),
            'play_store_url' => Setting::playStoreUrl(),
            'app_store_enabled' => Setting::isAppStoreEnabled(),
            'play_store_enabled' => Setting::isPlayStoreEnabled(),
            'otp_signup_enabled' => Setting::isOtpSignupEnabled(),
            'membership_enabled' => Setting::get('membership_enabled', '1') === '1',
            'blog_enabled' => Setting::get('blog_enabled', '1') === '1',
            'forums_enabled' => Setting::get('forums_enabled', '1') === '1',
            'certificates_enabled' => Setting::get('certificates_enabled', '1') === '1',
            'seo_meta_title' => Setting::get('seo_meta_title', 'Mono LMS - Transform Your Learning Experience'),
            'seo_meta_description' => Setting::get('seo_meta_description', 'High performance enterprise LMS platform built with Laravel 12 & Tailwind CSS.'),
            'theme_primary' => Setting::get('theme_primary', '#344c3d'),
            'theme_primary_hover' => Setting::get('theme_primary_hover', '#25372c'),
            'theme_accent' => Setting::get('theme_accent', '#738a6e'),
            'theme_dark' => Setting::get('theme_dark', '#08131e'),
            'theme_bg' => Setting::get('theme_bg', '#f4f6f9'),
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
            'footer_payment_methods',
            'footer_payment_methods_width',
            'app_store_url',
            'play_store_url',
            'seo_meta_title',
            'seo_meta_description',
            'theme_primary',
            'theme_primary_hover',
            'theme_accent',
            'theme_dark',
            'theme_bg',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, (string) $request->input($field));
            }
        }

        // Toggles
        Setting::set('footer_payment_methods_enabled', $request->has('footer_payment_methods_enabled') ? '1' : '0');
        Setting::set('app_store_enabled', $request->has('app_store_enabled') ? '1' : '0');
        Setting::set('play_store_enabled', $request->has('play_store_enabled') ? '1' : '0');
        Setting::set('otp_signup_enabled', $request->has('otp_signup_enabled') ? '1' : '0');
        Setting::set('membership_enabled', $request->has('membership_enabled') ? '1' : '0');
        Setting::set('blog_enabled', $request->has('blog_enabled') ? '1' : '0');
        Setting::set('forums_enabled', $request->has('forums_enabled') ? '1' : '0');
        Setting::set('certificates_enabled', $request->has('certificates_enabled') ? '1' : '0');

        return back()->with('success', 'Platform settings saved successfully.');
    }
}
