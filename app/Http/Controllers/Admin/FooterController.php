<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'footer_copyright' => Setting::get('footer_copyright', '© 2026 Mono LMS. All rights reserved.'),
            'footer_about_text' => Setting::get('footer_about_text', ''),
            'footer_logo' => Setting::get('footer_logo', ''),
            'footer_support_title' => Setting::get('footer_support_title', 'Support'),
            'footer_pages_title' => Setting::get('footer_pages_title', 'Pages'),
            'footer_useful_title' => Setting::get('footer_useful_title', 'Useful Links'),
            'footer_newsletter_enabled' => Setting::get('footer_newsletter_enabled', '1') === '1',
            'footer_newsletter_title' => Setting::get('footer_newsletter_title', 'Newsletter'),
            'footer_newsletter_hint' => Setting::get('footer_newsletter_hint', ''),
            'footer_payment_methods' => Setting::get('footer_payment_methods', ''),
            'footer_payment_methods_enabled' => Setting::get('footer_payment_methods_enabled', '1') === '1',
            'footer_payment_methods_width' => Setting::get('footer_payment_methods_width', '340px'),
            'app_store_url' => Setting::appStoreUrl(),
            'play_store_url' => Setting::playStoreUrl(),
            'app_store_enabled' => Setting::isAppStoreEnabled(),
            'play_store_enabled' => Setting::isPlayStoreEnabled(),
            'site_name' => Setting::get('site_name', 'Mono LMS'),
        ];

        $footerColumns = Setting::footerColumns();
        $footerBottomLinks = Setting::footerBottomLinks();
        $availablePages = Page::select('id', 'title', 'slug', 'status', 'is_system')
            ->orderByDesc('is_system')
            ->orderBy('title')
            ->get();

        return view('pages.admin.footer', [
            'settings' => $settings,
            'footerColumns' => $footerColumns,
            'footerBottomLinks' => $footerBottomLinks,
            'availablePages' => $availablePages,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if ($request->has('reset_columns')) {
            Setting::set('footer_navigation_columns', null);
            Setting::set('footer_bottom_links', null);

            return back()->with('success', 'Footer columns and links reset to default structure successfully.');
        }

        $fields = [
            'footer_copyright',
            'footer_about_text',
            'footer_logo',
            'footer_support_title',
            'footer_pages_title',
            'footer_useful_title',
            'footer_newsletter_title',
            'footer_newsletter_hint',
            'footer_payment_methods',
            'footer_payment_methods_width',
            'app_store_url',
            'play_store_url',
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
        Setting::set('footer_newsletter_enabled', $request->has('footer_newsletter_enabled') ? '1' : '0');

        // Footer Navigation Columns (JSON)
        if ($request->has('footer_navigation_columns')) {
            $rawCols = $request->input('footer_navigation_columns');
            if (is_string($rawCols)) {
                $decoded = json_decode($rawCols, true);
                if (is_array($decoded)) {
                    Setting::set('footer_navigation_columns', json_encode($decoded));
                }
            } elseif (is_array($rawCols)) {
                Setting::set('footer_navigation_columns', json_encode($rawCols));
            }
        }

        // Footer Bottom Links (JSON)
        if ($request->has('footer_bottom_links')) {
            $rawBottom = $request->input('footer_bottom_links');
            if (is_string($rawBottom)) {
                $decoded = json_decode($rawBottom, true);
                if (is_array($decoded)) {
                    Setting::set('footer_bottom_links', json_encode($decoded));
                }
            } elseif (is_array($rawBottom)) {
                Setting::set('footer_bottom_links', json_encode($rawBottom));
            }
        }

        return back()->with('success', 'Footer settings, payment methods, and navigation updated successfully.');
    }
}
