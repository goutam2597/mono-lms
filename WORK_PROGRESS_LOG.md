# Mono LMS — Comprehensive Work Progress Log

> **Target Audience:** Future AI Agents & Engineering Pair Programmers  
> **Repository:** `mono-lms`  
> **Active Branch:** `main`  
> **Last Updated:** November 2026  
> **Framework Stack:** Laravel 12 (PHP 8.4), Tailwind CSS v4, Vite 8, Alpine.js / Vanilla JS components  
> **Local Base URL:** `http://localhost/mono-lms/public/` (Always respect subfolder routing!)

---

## 1. Executive Summary

This document captures the end-to-end architecture, recent changes, bug fixes, database schema modifications, UI/UX polish, and conventions established across the **Mono LMS** platform. It is designed to give incoming agents instant context so no duplicate effort or architectural regressions occur.

---

## 2. Key Modules & Implementations

### 2.1 Storefront Homepage (`/`) & Section Engine

The homepage is modularized into 13 independently configurable sections powered by [`App\Services\HomePageContent`](file:///c:/laragon/www/mono-lms/app/Services/HomePageContent.php) and rendered via [`pages/home.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/home.blade.php).

#### Section 04: Featured Courses Grid
- **Category Filter Tabs:**
  - Includes interactive filter tabs: `All`, `Most popular`, `Business`, `Design`, `Music`, `Programming`, `Database`.
  - Driven by [`resources/js/featured-tabs.js`](file:///c:/laragon/www/mono-lms/resources/js/featured-tabs.js), loaded through `app.js`.
  - Every course card carries dynamic tags (`data-tags`) evaluated in Blade based on category slugs, category names, titles, enrollment counts, and trending status.
  - Active button states are visually distinct (`bg-[#dce7da]/40 text-[#344c3d] border-[#344c3d]`).
  - Includes a fallback empty state (`data-featured-empty`) with a 1-click "Show All Featured Courses" reset button if a filter yields no immediate matches.
- **Admin Management:**
  - Controlled in `admin/courses` via quick action toggle `POST /admin/courses/{course}/toggle-featured`.
  - Filter courses in admin panel by `?status=featured`.
  - Editable limit in Homepage Editor (default: 8).

#### Section 08: Most Trending Courses
- **Database Column & Scope:**
  - Added `is_trending` boolean column (default: `false`) via migration `2026_11_09_102639_add_is_trending_to_courses_table.php`.
  - Cast as `boolean` and scoped via `scopeTrending()` in [`App\Models\Course`](file:///c:/laragon/www/mono-lms/app/Models/Course.php).
- **Responsive Slider Engine:**
  - When `$trendingCourses->count() <= 4`: Displays as a standard 4-column responsive grid.
  - When `$trendingCourses->count() > 4`: Automatically converts into a full infinite carousel slider matching the top categories slider.
  - Upgraded [`resources/js/category-slider.js`](file:///c:/laragon/www/mono-lms/resources/js/category-slider.js) into a reusable multi-slider engine (`[data-category-slider]`, `[data-trending-slider]`):
    - Desktop: 4 items for trending courses, 5 items for categories.
    - Tablet / Mobile: Responsive card width calculation with gap compensation.
    - Left/Right dual navigation arrow buttons.
    - Infinite looping with cloned prefix/suffix buffer items.
    - Touch-swipe and mouse-drag gestures.
    - Dynamic pagination dots scoped to their parent slider section.
- **Admin Management:**
  - Controlled in `admin/courses` via `POST /admin/courses/{course}/toggle-trending`.
  - Filter courses in admin panel by `?status=trending`.
  - Configurable limit in Homepage Editor (default: 8).

#### Section 09: Courses Taught by Real Instructors
- **Layout Architecture:**
  - 3-column masonry grid: Left column (4 photos in 2x2 grid), Center column (featured tall card), Right column (4 photos in 2x2 grid).
- **Bug Fixed (Empty Right Column):**
  - Previously, `HomeController@index` retrieved instructors with a hardcoded `limit` of 4.
  - The left column called `take(4)`, consuming all 4 items, while the right column called `skip(4)`, receiving 0 items and rendering as an empty white block.
  - **Fix:** Updated [`HomeController.php`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/HomeController.php) to fetch `max(8, (int) ($home['instructors']['limit'] ?? 8))`.
  - Updated [`HomePageContent.php`](file:///c:/laragon/www/mono-lms/app/Services/HomePageContent.php) and stored database settings to default limit 8.
  - Made [`pages/home.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/home.blade.php) resilient by splitting available instructors between left and right slices (`$leftInstructors` and `$rightInstructors`).

---

### 2.2 Payment Gateway Architecture & Dashboard

- **Location:** Dedicated route `/admin/payment-gateways` ([`AdminPaymentGatewayController`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/Admin/PaymentGatewayController.php)).
- **Navigation:** Accessible under the **Settings** sidebar dropdown menu (`active="settings-gateways"`).
- **Layout:** Full-width design (`max-w-7xl` or full dashboard container), eliminating narrow boxed card constraints.
- **Visuals:** Replaced generic placeholder text with verified SVG branding icons for all supported gateways:
  - Stripe, PayPal, Razorpay, Flutterwave, Paystack, Mollie, Bank Transfer, Offline Cash.
- **Functionality:**
  - Per-gateway toggle (`is_active`).
  - Environment mode switcher (`sandbox` vs. `live`).
  - Key management (Public Key, Secret Key, Merchant ID, Webhook Secret).
  - Currency selector and transaction fee markup inputs.

---

### 2.3 Storefront Footer & App Download Badges

- **Location:** [`resources/views/components/footer.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/components/footer.blade.php).
- **Badges:** Integrated official vectorized SVGs:
  - Google Play badge (`google-play-badge-logo-svgrepo-com.svg`).
  - Apple App Store badge (`download-on-the-app-store-apple-logo-svgrepo-com.svg`).
- **Destination URLs:** Configurable, wrapped with `url(...)` or outbound links.

---

### 2.4 Instructor Authentication & Subfolder URL Resilience

- **Problem:** When running under a subfolder path like `http://localhost/mono-lms/public/`, relative root links (e.g., `/instructor/login`) broke by redirecting to `http://localhost/instructor/login`.
- **Solution:**
  - All dynamic homepage and navigation links are wrapped in `url(...)` or `route(...)`.
  - Registered `/instructor/register` and `/instructor/login` routes with respective views and controllers.
  - Created automated test coverage in [`AuthTest.php`](file:///c:/laragon/www/mono-lms/tests/Feature/AuthTest.php).

---

### 2.5 Admin Panel Dark Mode & Palette Standards

- **Core Rule:** True neutral black dark mode palette.
- **Theme Tokens:**
  - Dark background: `bg-[#08131e]`, `bg-neutral-900`, `bg-neutral-950`, or `bg-black`.
  - Dark borders: `border-neutral-800` or `border-neutral-700`.
  - Dark text: `text-neutral-100`, `text-neutral-200`, `text-neutral-400`.
  - Brand accents: Sage Evergreen `#344C3D`, Mint `#DCE7DA`, Moss `#738A6E`.
- **Fixed Issues:**
  - Fixed white-on-white and inverted card bleeds in `admin/profile`, `admin/settings`, and language switcher dropdowns.
  - Ensured floating language dropdowns and profile menus utilize proper z-index and fixed portal positioning outside table overflow boundaries.

---

## 3. Database Modifications Summary

| Migration File | Table | Modifications |
|---|---|---|
| `2026_11_09_102639_add_is_trending_to_courses_table.php` | `courses` | Added `is_trending` (`boolean`, default: `false`) |
| Seeders: `DatabaseSeeder.php` | `courses` | Seeded demo courses with `is_featured` and `is_trending` |

---

## 4. Key Routes Reference

### Public / Storefront
- `GET /`: Storefront Homepage ([`HomeController@index`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/HomeController.php))
- `GET /courses`: Course Catalog
- `GET /courses/{slug}`: Course Detail View
- `GET /instructor/login`: Instructor Sign In
- `GET /instructor/register`: Instructor Registration

### Admin Panel (`/admin/*`)
- `GET /admin/dashboard`: Dashboard Overview
- `GET /admin/homepage`: Homepage Section Editor ([`Admin\HomePageController@edit`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/Admin/HomepageController.php))
- `POST /admin/homepage`: Save Homepage Sections ([`Admin\HomePageController@update`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/Admin/HomepageController.php))
- `GET /admin/courses`: Course Manager ([`Admin\CourseController@index`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/Admin/CourseController.php))
  - Supported filters: `?status=published`, `?status=draft`, `?status=featured`, `?status=trending`
- `POST /admin/courses/{course}/toggle-featured`: Instant toggle featured state
- `POST /admin/courses/{course}/toggle-trending`: Instant toggle trending state
- `GET /admin/payment-gateways`: Payment Gateways Dashboard ([`Admin\PaymentGatewayController@index`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/Admin/PaymentGatewayController.php))
- `GET /admin/settings`: Platform & Theme Settings

---

## 5. Automated Testing Suite

All tests use PHPUnit and can be run via:
```bash
php artisan test --compact
```
Targeted test execution:
```bash
php artisan test --filter=HomePageEditorTest
php artisan test --filter=AdminPanelPagesTest
php artisan test --filter=AuthTest
```

### Current Test Status
- **Total Tests:** 81
- **Passed:** 81 (100%)
- **Assertions:** 488
- **Code Style:** Verified via `vendor/bin/pint --format agent`

---

## 6. Guidelines for Future AI Agents

1. **Subfolder URL Awareness:**  
   The application runs in Laragon at `http://localhost/mono-lms/public/`. NEVER hardcode leading-slash URLs like `href="/admin/..."` in Blade templates. Always use `route('...')` or `url('...')`.

2. **Frontend Assets Compilation:**  
   If you modify files in `resources/js/` or `resources/css/`, always execute:
   ```bash
   npm run build
   ```
   Do not leave uncompiled Vite assets.

3. **Code Style (Pint):**  
   After modifying any PHP files, run:
   ```bash
   vendor/bin/pint --format agent
   ```

4. **Testing Requirement:**  
   Always run `php artisan test --compact` before finalizing work and commit changes to `main`.
