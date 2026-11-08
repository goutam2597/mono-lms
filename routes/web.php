<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\InstructorController as AdminInstructorController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentGatewayController as AdminPaymentGatewayController;
use App\Http\Controllers\Admin\PayoutController as AdminPayoutController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServicesController as AdminServicesController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Http\Controllers\Admin\SystemController as AdminSystemController;
use App\Http\Controllers\Admin\UserDirectoryController as AdminUserDirectoryController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\InstructorAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\LessonController;
use App\Http\Controllers\Instructor\ProfileController as InstructorProfileController;
use App\Http\Controllers\Instructor\ReviewController as InstructorReviewController;
use App\Http\Controllers\Instructor\StudentController as InstructorStudentController;
use App\Http\Controllers\InstructorDirectoryController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Mono LMS
|--------------------------------------------------------------------------
|
| Panels and their guards (independent sessions per panel):
|  - /admin/*       guard: admin       (super admin / manager, RBAC)
|  - /instructor/*  guard: instructor  (accounts created by admins)
|  - /student/*     guard: student     (self-registered)
|  - everything else is the public storefront.
|
| Each panel has its own language switcher stored in its own locale session
| (admin_locale / instructor_locale / student_locale / store_locale).
|
*/

// ---------------------------------------------------------------- Storefront
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/locale/{code}', [LocaleController::class, 'switchStore'])->name('locale.switch');

// Public catalog (DB-driven)
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'grid'])->name('index');
    Route::get('/grid', fn () => redirect()->route('courses.index', request()->query()));
    Route::get('/{slug}', [CourseController::class, 'show'])->name('show');
    Route::post('/{course:slug}/enroll', [CourseController::class, 'enroll'])
        ->middleware('auth:student')->name('enroll');
});

Route::prefix('instructors')->name('instructors.')->group(function () {
    Route::get('/', [InstructorDirectoryController::class, 'grid'])->name('index');
    Route::get('/grid', fn () => redirect()->route('instructors.index', request()->query()));
    Route::get('/{name}', [InstructorDirectoryController::class, 'show'])->name('show');
});

// Screens Showcase Hub
Route::get('/screens', function () {
    return view('screens.index');
})->name('screens');
Route::get('/screens-index', fn () => redirect()->route('screens'))->name('screens.index');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Membership (admin-dependent: 404 when disabled in settings)
Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
Route::post('/membership/{plan:slug}/subscribe', [MembershipController::class, 'subscribe'])
    ->middleware('auth:student')->name('membership.subscribe');

// ------------------------------------------------------------ Language switch
// Panel locale switches live OUTSIDE the guarded groups so they work from
// login pages too; each writes to its own panel session key.
Route::get('/admin/locale/{code}', [LocaleController::class, 'switchAdmin'])->name('admin.locale.switch');
Route::get('/instructor/locale/{code}', [LocaleController::class, 'switchInstructor'])->name('instructor.locale.switch');
Route::get('/student/locale/{code}', [LocaleController::class, 'switchStudent'])->name('student.locale.switch');

// ----------------------------------------------------------------- Panel auth
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.attempt');
});
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');

Route::middleware('guest:instructor')->group(function () {
    Route::get('/instructor/login', [InstructorAuthController::class, 'showLogin'])->name('instructor.login');
    Route::post('/instructor/login', [InstructorAuthController::class, 'login'])->name('instructor.login.attempt');
});
Route::post('/instructor/logout', [InstructorAuthController::class, 'logout'])->middleware('auth:instructor')->name('instructor.logout');

Route::middleware('guest:student')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('register.attempt');
});
Route::post('/logout', [StudentAuthController::class, 'logout'])->middleware('auth:student')->name('logout');

// Shared password reset (?panel=student|instructor|admin)
Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/set-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/set-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Static UI-kit screens (kept for the screens hub)
Route::get('/otp', fn () => view('pages.auth.otp'))->name('otp');
Route::get('/lock-screen', fn () => view('pages.auth.lock-screen'))->name('lock-screen');

// -------------------------------------------------------------- Admin panel
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:admins.manage')->group(function () {
        Route::resource('admins', AdminUserController::class)->except('show');
    });

    Route::middleware('permission:roles.manage')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
    });

    Route::middleware('permission:instructors.manage')->group(function () {
        Route::resource('instructors', AdminInstructorController::class)->except('show');
    });

    Route::middleware('permission:students.manage')->group(function () {
        Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}/edit', [AdminStudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
    });

    Route::middleware('permission:categories.manage')->group(function () {
        Route::resource('categories', AdminCategoryController::class)->except('show');
    });

    Route::get('/courses', [AdminCourseController::class, 'index'])
        ->middleware('permission:courses.manage')->name('courses.index');
    Route::patch('/courses/{course}/publish', [AdminCourseController::class, 'publish'])
        ->middleware('permission:courses.publish')->name('courses.publish');
    Route::patch('/courses/{course}/unpublish', [AdminCourseController::class, 'unpublish'])
        ->middleware('permission:courses.publish')->name('courses.unpublish');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])
        ->middleware('permission:courses.manage')->name('courses.destroy');

    Route::get('/reviews', [AdminReviewController::class, 'index'])
        ->middleware('permission:reviews.moderate')->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])
        ->middleware('permission:reviews.moderate')->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])
        ->middleware('permission:reviews.moderate')->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])
        ->middleware('permission:reviews.moderate')->name('reviews.destroy');

    Route::get('/languages', [LanguageController::class, 'index'])
        ->middleware('permission:languages.manage')->name('languages.index');
    Route::patch('/languages/{language}/toggle', [LanguageController::class, 'toggle'])
        ->middleware('permission:languages.manage')->name('languages.toggle');
    Route::patch('/languages/{language}/default', [LanguageController::class, 'makeDefault'])
        ->middleware('permission:languages.manage')->name('languages.default');

    // Blog
    Route::middleware('permission:blog.manage')->group(function () {
        Route::get('/blog', [AdminPostController::class, 'index'])->name('blog.index');
        Route::get('/blog/create', [AdminPostController::class, 'create'])->name('blog.create');
        Route::post('/blog', [AdminPostController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit', [AdminPostController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}', [AdminPostController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [AdminPostController::class, 'destroy'])->name('blog.destroy');
    });

    // Membership plans + the master enable/disable switch
    Route::middleware('permission:plans.manage')->group(function () {
        Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
        Route::patch('/plans/membership/toggle', [AdminPlanController::class, 'toggleMembership'])->name('plans.membership-toggle');
        Route::get('/plans/create', [AdminPlanController::class, 'create'])->name('plans.create');
        Route::post('/plans', [AdminPlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [AdminPlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [AdminPlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [AdminPlanController::class, 'destroy'])->name('plans.destroy');
    });

    // Unified All Users Directory
    Route::get('/users', [AdminUserDirectoryController::class, 'index'])->name('users.index');

    // Orders & Sales Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Coupons
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::patch('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');
    Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    // Payouts
    Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts.index');
    Route::patch('/payouts/{payout}', [AdminPayoutController::class, 'update'])->name('payouts.update');

    // Certificate Templates
    Route::get('/certificate-templates', [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::post('/certificate-templates', [AdminCertificateController::class, 'store'])->name('certificates.store');
    Route::patch('/certificate-templates/{template}/default', [AdminCertificateController::class, 'makeDefault'])->name('certificates.default');

    // Support Tickets & Contact Messages
    Route::get('/tickets', [AdminSupportController::class, 'tickets'])->name('tickets.index');
    Route::patch('/tickets/{ticket}/reply', [AdminSupportController::class, 'replyTicket'])->name('tickets.reply');
    Route::get('/messages', [AdminSupportController::class, 'messages'])->name('messages.index');
    Route::patch('/messages/{message}/toggle', [AdminSupportController::class, 'toggleMessageRead'])->name('messages.toggle');
    Route::delete('/messages/{message}', [AdminSupportController::class, 'destroyMessage'])->name('messages.destroy');

    // Payment Gateways
    Route::get('/payment-gateways', [AdminPaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::post('/payment-gateways', [AdminPaymentGatewayController::class, 'update'])->name('payment-gateways.update');

    // Reports & Analytics
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

    // General Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Mail (SMTP) & Storage Services
    Route::get('/services', [AdminServicesController::class, 'index'])->name('services.index');
    Route::post('/services/mail', [AdminServicesController::class, 'updateMail'])->name('services.mail');
    Route::post('/services/mail/test', [AdminServicesController::class, 'testMail'])->name('services.mail.test');
    Route::post('/services/storage', [AdminServicesController::class, 'updateStorage'])->name('services.storage');

    // Leads (CRM)
    Route::get('/leads', [AdminLeadController::class, 'index'])->name('leads.index');
    Route::patch('/leads/{lead}', [AdminLeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [AdminLeadController::class, 'destroy'])->name('leads.destroy');

    // Events / Webinars
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    // System Status & Updates
    Route::get('/system', [AdminSystemController::class, 'index'])->name('system.index');
    Route::post('/system/clear-cache', [AdminSystemController::class, 'clearCache'])->name('system.clear-cache');
});

// ---------------------------------------------------------- Instructor panel
Route::prefix('instructor')->name('instructor.')->middleware('auth:instructor')->group(function () {
    Route::get('/', [InstructorDashboardController::class, 'index'])->name('dashboard');

    Route::get('/courses', [InstructorCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [InstructorCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [InstructorCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [InstructorCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [InstructorCourseController::class, 'destroy'])->name('courses.destroy');

    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

    Route::get('/students', [InstructorStudentController::class, 'index'])->name('students');
    Route::get('/reviews', [InstructorReviewController::class, 'index'])->name('reviews');
    Route::get('/profile', [InstructorProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [InstructorProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [InstructorProfileController::class, 'updatePassword'])->name('profile.password');
});

// ------------------------------------------------------------ Student panel
Route::prefix('student')->name('student.')->middleware('auth:student')->group(function () {
    Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/enrolled-courses', [EnrollmentController::class, 'index'])->name('enrolled-courses');
    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password');

    // Static UI-kit placeholders (no quiz/certificate engine yet)
    Route::get('/certificates', fn () => view('pages.student.certificates'))->name('certificates');
    Route::get('/quiz-attempts', fn () => view('pages.student.quiz-attempts'))->name('quiz-attempts');
});
