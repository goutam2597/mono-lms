# Mono LMS – Learning Management System

> High-fidelity, pixel-perfect Laravel 13 & Tailwind CSS v4 implementation of the **Mono LMS design system**.

[![Laravel 13](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS 4](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![SQLite](https://img.shields.io/badge/SQLite-Database-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://sqlite.org)
[![PHPUnit Tests](https://img.shields.io/badge/Tests-Passed-success?style=for-the-badge&logo=githubactions&logoColor=white)](#tests)

---

## 📸 Visual Demos & Screenshots

All screens are designed in a structured design process and mapped to structured Blade components.

### 1. Home (`/`)
![Home](demos/home.png)

### 2. Courses Catalog (`/courses`)
![Course Grid](demos/course_grid.png)

### 3. Student Dashboard (`/student`)
![Student Dashboard](demos/student_dashboard.png)

### 4. Instructor Dashboard (`/instructor`)
![Instructor Dashboard](demos/instructor_dashboard.png)

### 5. Authentication Screen (`/login`)
![Login Screen](demos/login.png)

---

## 🧭 Complete Screens & Route Directory

All 22 screens can be browsed and accessed from the interactive **Screens Showcase Hub** at [`/screens`](http://localhost/lms/public/screens):

| Category | Screen Name | Route | Node ID |
| :--- | :--- | :--- | :--- |
| **Home** | Home *(Main and Only Home)* | `/` | `2355:29907` |
| **Hub** | Screens Showcase Hub | `/screens` | `1:4659` |
| **Courses** | Course Grid | `/courses/grid` | `200:18274` |
| | Course List | `/courses/list` | `200:19012` |
| | Course Details | `/courses/detail` | `200:19760` |
| **Instructors** | Instructor Grid | `/instructors/grid` | `235:21516` |
| | Instructor List | `/instructors/list` | `235:21980` |
| | Instructor Profile | `/instructors/detail` | `235:22430` |
| **Student Portal** | Student Dashboard | `/student/dashboard` | `235:23080` |
| | Student Profile | `/student/profile` | `235:23450` |
| | Enrolled Courses | `/student/enrolled-courses`| `235:23890` |
| | Certificates | `/student/certificates` | `235:24210` |
| | Quiz Attempts | `/student/quiz-attempts` | `235:24550` |
| **Instructor Portal**| Instructor Dashboard | `/instructor/dashboard` | `375:48500` |
| | Manage Courses | `/instructor/courses` | `375:48920` |
| | Student Reviews | `/instructor/reviews` | `375:49310` |
| **Authentication** | Sign In | `/login` | `375:49416` |
| | Register | `/register` | `375:49512` |
| | Forgot Password | `/forgot-password` | `375:49601` |
| | Enter OTP | `/otp` | `375:49627` |
| | Set New Password | `/set-password` | `375:50135` |
| | Lock Screen | `/lock-screen` | `375:50392` |

---

## 🛠️ Tech Stack & Architecture

- **Backend Framework**: [Laravel 13](https://laravel.com) on **PHP 8.3+**
- **Styling**: [Tailwind CSS v4](https://tailwindcss.com) with `@theme` design tokens
- **Font Foundations**: Inter & Montserrat typography
- **Frontend Build**: [Vite](https://vitejs.dev) with production pre-compiled assets in `public/build/`
- **Database**: **MySQL** (`mono_lms`, utf8mb4)
- **Auth**: 3 independent guards — `admin`, `instructor`, `student` — each with its own table, panel, login page and session
- **RBAC**: roles & permissions for the admin panel, curated by the super admin (`permission:` middleware + gates)
- **i18n**: key-based translations (`lang/en`, `lang/hi`, `lang/ar`) with a per-panel language switcher; each panel keeps its own locale session (Arabic renders RTL)
- **Blade Architecture**:
  - `<x-layouts.app>`: Standard customer-facing header, main content, and footer
  - `<x-layouts.dashboard>`: Shared panel layout for Admin, Instructor and Student (`userType` prop)
  - `<x-layouts.auth>`: Split-screen branding layout for authentication flows
  - Reusable components: `<x-header>`, `<x-footer>`, `<x-language-switcher>`, `<x-admin-sidebar>`, `<x-instructor-sidebar>`, `<x-student-sidebar>`

---

## 🔐 Seeded Panel Logins

All seeded accounts use the password **`password`**:

| Panel | URL | Email | Role |
| :--- | :--- | :--- | :--- |
| Super Admin | `/admin/login` | `superadmin@mono-lms.test` | Super Admin (all permissions) |
| Manager | `/admin/login` | `manager@mono-lms.test` | Manager (limited — try `/admin/roles` → 403) |
| Instructor | `/instructor/login` | `instructor@mono-lms.test` | — |
| Student | `/login` | `student@mono-lms.test` | — |

Students can also self-register at `/register`. Instructor accounts are created by admins only.

---

## 🚀 Quick Start Guide

### Prerequisites
- PHP 8.4 or higher with `pdo_sqlite` and `sqlite3` extensions enabled
- Composer
- Node.js & npm (optional, as pre-compiled build assets are included)

### 1. Clone the Repository
```bash
git clone https://github.com/goutam2597/mono-lms.git
cd mono-lms
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
The SQLite database `database/database.sqlite` is already tracked and migrated with required session and cache tables.
If needed, you can run:
```bash
php artisan migrate
```

### 5. Build Assets (Optional)
Production assets are already included in `public/build/`. To start Vite in dev mode:
```bash
npm install
npm run dev
# or for production build:
npm run build
```

### 6. Start the Application
Run via Laravel Artisan:
```bash
php artisan serve
```
Visit: [http://127.0.0.1:8000](http://127.0.0.1:8000) or browse all screens at [http://127.0.0.1:8000/screens](http://127.0.0.1:8000/screens).

Or if using **Laragon / Apache**:
Visit: `http://localhost/lms/public` or `http://localhost/lms/public/screens`.

---

## 🧪 Testing

Run the feature test suite covering all 22 screens:
```bash
php artisan test --compact
```

---

## 🚧 Roadmap — Shared cPanel Hosting Deployment (planned)

> **TODO:** make the app installable on shared cPanel hosting **without terminal/SSH access**. Not implemented yet — pending until the core project is complete.

Key facts already handled:

- **Vite never runs on the server.** It is a build-time tool only — the compiled assets (`public/build/` + `manifest.json`) are **committed to the repo**, and Laravel's `@vite` directive just reads the manifest at runtime (plain PHP). Uploading the repo gives a working UI, no Node/npm needed on the host.
- After changing CSS/JS: rebuild locally (`npm run build`) and commit/upload `public/build/`.

Remaining blockers & planned solutions:

1. **Composer dependencies** — `/vendor` is not committed. Plan: build a release ZIP locally (`composer install --no-dev`) that includes `vendor/`, `public/build/`, and `.env.example`; upload via cPanel File Manager.
2. **Database migration + seeding without SSH** — Plan: a **one-time web installer** (`/install` wizard route) that checks server requirements, tests the DB connection from a web form, runs `migrate` + seed, creates the super admin, writes a lock file, and disables itself.
3. **`php artisan storage:link`** — not required yet (no public disk usage). If needed later: auto-create in the installer or fallback route.

Alternative stopgap: export a schema + seed SQL dump for phpMyAdmin import (static — breaks when the schema changes; the web installer is preferred).

---

## 📄 License
The MIT License (MIT).
