<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    public static function get(string $key, ?string $default = null): ?string
    {
        // Cache plain values only — a missing settings table (fresh test DB)
        // must never 500 a request.
        try {
            return Cache::remember('settings.'.$key, now()->addHour(), fn () => static::query()
                ->where('key', $key)
                ->value('value') ?? $default);
        } catch (\Throwable) {
            return $default;
        }
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.'.$key);
    }

    public static function enabled(string $key): bool
    {
        return self::get($key) === '1';
    }

    public static function faviconUrl(): string
    {
        $custom = self::get('site_favicon');

        if (! empty($custom)) {
            $url = PublicMedia::url($custom);
            if (! empty($url)) {
                return $url.'?v='.substr(md5($custom), 0, 8);
            }
        }

        return asset('images/favicon.png');
    }

    public static function logoUrl(?string $fallback = null): string
    {
        $custom = self::get('site_logo');
        $fallback ??= asset('images/logo.png');

        if (! empty($custom)) {
            $url = PublicMedia::url($custom);
            if (! empty($url)) {
                return $url;
            }
        }

        return $fallback;
    }

    public static function paymentMethodsUrl(?string $fallback = null): string
    {
        $custom = self::get('footer_payment_methods');
        $fallback ??= asset('images/payment-methods.png');

        if (! empty($custom)) {
            $url = PublicMedia::url($custom);
            if (! empty($url)) {
                return $url;
            }
        }

        return $fallback;
    }

    public static function paymentMethodsWidth(?string $fallback = '340px'): string
    {
        $val = trim((string) self::get('footer_payment_methods_width', $fallback));
        if ($val === '') {
            return $fallback;
        }

        if (is_numeric($val)) {
            return $val.'px';
        }

        return $val;
    }

    public static function isOtpSignupEnabled(): bool
    {
        return self::get('otp_signup_enabled', '0') === '1';
    }

    public static function appStoreUrl(?string $fallback = 'https://apps.apple.com'): ?string
    {
        return self::get('app_store_url', $fallback);
    }

    public static function playStoreUrl(?string $fallback = 'https://play.google.com/store/apps'): ?string
    {
        return self::get('play_store_url', $fallback);
    }

    public static function isAppStoreEnabled(): bool
    {
        return self::get('app_store_enabled', '1') === '1';
    }

    public static function isPlayStoreEnabled(): bool
    {
        return self::get('play_store_enabled', '1') === '1';
    }

    /**
     * Default header navigation menu items.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultHeaderMenu(): array
    {
        $homeActive = self::get('header_nav_home_enabled', '1') === '1';
        $coursesActive = self::get('header_nav_courses_enabled', '1') === '1';
        $instructorsActive = self::get('header_nav_instructors_enabled', '1') === '1';
        $blogActive = self::get('header_nav_blog_enabled', '1') === '1';
        $membershipActive = (self::get('header_nav_membership_enabled', '1') === '1') && (self::get('membership_enabled') !== '0');

        return [
            [
                'id' => 'nav_home',
                'label' => 'Home',
                'url' => '/',
                'type' => 'route',
                'target' => '_self',
                'badge' => '',
                'is_active' => $homeActive,
                'children' => [],
            ],
            [
                'id' => 'nav_courses',
                'label' => 'Courses',
                'url' => '/courses',
                'type' => 'route',
                'target' => '_self',
                'badge' => '',
                'is_active' => $coursesActive,
                'children' => [],
            ],
            [
                'id' => 'nav_instructors',
                'label' => 'Instructors',
                'url' => '/instructors',
                'type' => 'route',
                'target' => '_self',
                'badge' => '',
                'is_active' => $instructorsActive,
                'children' => [],
            ],
            [
                'id' => 'nav_blog',
                'label' => 'Blog',
                'url' => '/blog',
                'type' => 'route',
                'target' => '_self',
                'badge' => '',
                'is_active' => $blogActive,
                'children' => [],
            ],
            [
                'id' => 'nav_company',
                'label' => 'Pages',
                'url' => '#',
                'type' => 'custom',
                'target' => '_self',
                'badge' => '',
                'is_active' => true,
                'children' => [
                    [
                        'id' => 'nav_about',
                        'label' => 'About Us',
                        'url' => '/about',
                        'type' => 'page',
                        'target' => '_self',
                        'badge' => '',
                        'is_active' => true,
                    ],
                    [
                        'id' => 'nav_contact',
                        'label' => 'Contact Us',
                        'url' => '/contact',
                        'type' => 'page',
                        'target' => '_self',
                        'badge' => '',
                        'is_active' => true,
                    ],
                    [
                        'id' => 'nav_terms',
                        'label' => 'Terms & Conditions',
                        'url' => '/terms',
                        'type' => 'page',
                        'target' => '_self',
                        'badge' => '',
                        'is_active' => true,
                    ],
                    [
                        'id' => 'nav_privacy',
                        'label' => 'Privacy Policy',
                        'url' => '/privacy',
                        'type' => 'page',
                        'target' => '_self',
                        'badge' => '',
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'id' => 'nav_membership',
                'label' => 'Membership',
                'url' => '/membership',
                'type' => 'route',
                'target' => '_self',
                'badge' => 'Pro',
                'is_active' => $membershipActive,
                'children' => [],
            ],
        ];
    }

    /**
     * Get the active header navigation menu tree.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function headerMenu(): array
    {
        $raw = self::get('header_menu_items');
        if (! empty($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && ! empty($decoded)) {
                return $decoded;
            }
        }

        return self::defaultHeaderMenu();
    }

    /**
     * Default footer navigation columns.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultFooterColumns(): array
    {
        return [
            [
                'id' => 'col_support',
                'title' => self::get('footer_support_title') ?: 'Support',
                'links' => [
                    ['id' => 'link_help', 'label' => 'Help Center', 'url' => '#', 'target' => '_self'],
                    ['id' => 'link_faq', 'label' => 'FAQ', 'url' => '#', 'target' => '_self'],
                    ['id' => 'link_contact', 'label' => 'Contact Us', 'url' => '/contact', 'target' => '_self'],
                    ['id' => 'link_about', 'label' => 'About Us', 'url' => '/about', 'target' => '_self'],
                ],
            ],
            [
                'id' => 'col_pages',
                'title' => self::get('footer_pages_title') ?: 'Pages',
                'links' => [
                    ['id' => 'link_courses', 'label' => 'Courses', 'url' => '/courses', 'target' => '_self'],
                    ['id' => 'link_instructors', 'label' => 'Instructors', 'url' => '/instructors', 'target' => '_self'],
                    ['id' => 'link_blog', 'label' => 'Blog', 'url' => '/blog', 'target' => '_self'],
                    ['id' => 'link_membership', 'label' => 'Membership', 'url' => '/membership', 'target' => '_self'],
                ],
            ],
            [
                'id' => 'col_useful',
                'title' => self::get('footer_useful_title') ?: 'Useful Links',
                'links' => [
                    ['id' => 'link_become_inst', 'label' => 'Become an Instructor', 'url' => '/instructor/register', 'target' => '_self'],
                    ['id' => 'link_inst_portal', 'label' => 'Instructor Portal', 'url' => '/instructor/login', 'target' => '_self'],
                    ['id' => 'link_student_portal', 'label' => 'Student Portal', 'url' => '/login', 'target' => '_self'],
                    ['id' => 'link_register', 'label' => 'Register', 'url' => '/register', 'target' => '_self'],
                ],
            ],
        ];
    }

    /**
     * Get the active footer navigation columns.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function footerColumns(): array
    {
        $raw = self::get('footer_navigation_columns');
        if (! empty($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && ! empty($decoded)) {
                return $decoded;
            }
        }

        return self::defaultFooterColumns();
    }

    /**
     * Default footer bottom bar links.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultFooterBottomLinks(): array
    {
        return [
            ['id' => 'btm_terms', 'label' => 'Terms & Conditions', 'url' => '/terms', 'target' => '_self'],
            ['id' => 'btm_privacy', 'label' => 'Privacy Policy', 'url' => '/privacy', 'target' => '_self'],
        ];
    }

    /**
     * Get the active footer bottom bar links.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function footerBottomLinks(): array
    {
        $raw = self::get('footer_bottom_links');
        if (! empty($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && ! empty($decoded)) {
                return $decoded;
            }
        }

        return self::defaultFooterBottomLinks();
    }
}
