<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'header_topbar_enabled' => Setting::get('header_topbar_enabled', '1') === '1',
            'header_topbar_address' => Setting::get('header_topbar_address', '70 Washington Square South, New York, NY 10012, United States'),
            'header_topbar_phone' => Setting::get('header_topbar_phone', '+1 (555) 234-5678'),
            'header_social_facebook' => Setting::get('header_social_facebook', '#'),
            'header_social_instagram' => Setting::get('header_social_instagram', '#'),
            'header_social_twitter' => Setting::get('header_social_twitter', '#'),
            'header_social_linkedin' => Setting::get('header_social_linkedin', '#'),
            'header_social_youtube' => Setting::get('header_social_youtube', ''),
            'header_language_switcher_enabled' => Setting::get('header_language_switcher_enabled', '1') === '1',
            'header_sticky_enabled' => Setting::get('header_sticky_enabled', '1') === '1',
            'header_search_enabled' => Setting::get('header_search_enabled', '1') === '1',
            'header_nav_home_enabled' => Setting::get('header_nav_home_enabled', '1') === '1',
            'header_nav_courses_enabled' => Setting::get('header_nav_courses_enabled', '1') === '1',
            'header_nav_instructors_enabled' => Setting::get('header_nav_instructors_enabled', '1') === '1',
            'header_nav_blog_enabled' => Setting::get('header_nav_blog_enabled', '1') === '1',
            'header_nav_membership_enabled' => Setting::get('header_nav_membership_enabled', '1') === '1',
            'site_name' => Setting::get('site_name', 'Mono LMS'),
            'site_logo' => Setting::get('site_logo', ''),
        ];

        return view('pages.admin.header', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $fields = [
            'header_topbar_address',
            'header_topbar_phone',
            'header_social_facebook',
            'header_social_instagram',
            'header_social_twitter',
            'header_social_linkedin',
            'header_social_youtube',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, (string) $request->input($field));
            }
        }

        // Toggles
        Setting::set('header_topbar_enabled', $request->has('header_topbar_enabled') ? '1' : '0');
        Setting::set('header_language_switcher_enabled', $request->has('header_language_switcher_enabled') ? '1' : '0');
        Setting::set('header_sticky_enabled', $request->has('header_sticky_enabled') ? '1' : '0');
        Setting::set('header_search_enabled', $request->has('header_search_enabled') ? '1' : '0');
        Setting::set('header_nav_home_enabled', $request->has('header_nav_home_enabled') ? '1' : '0');
        Setting::set('header_nav_courses_enabled', $request->has('header_nav_courses_enabled') ? '1' : '0');
        Setting::set('header_nav_instructors_enabled', $request->has('header_nav_instructors_enabled') ? '1' : '0');
        Setting::set('header_nav_blog_enabled', $request->has('header_nav_blog_enabled') ? '1' : '0');
        Setting::set('header_nav_membership_enabled', $request->has('header_nav_membership_enabled') ? '1' : '0');

        return back()->with('success', 'Header settings and navigation updated successfully.');
    }
}
