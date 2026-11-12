<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AiController;
use App\Http\Controllers\Admin\AiSettingsController;
use App\Http\Controllers\Admin\AiTranslationController;
use App\Http\Controllers\Admin\AssignmentController as AdminAssignmentController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FooterController as AdminFooterController;
use App\Http\Controllers\Admin\GoogleSettingsController;
use App\Http\Controllers\Admin\HeaderController as AdminHeaderController;
use App\Http\Controllers\Admin\HomePageController as AdminHomePageController;
use App\Http\Controllers\Admin\InstructorController as AdminInstructorController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\LiveClassController as AdminLiveClassController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PaymentGatewayController as AdminPaymentGatewayController;
use App\Http\Controllers\Admin\PayoutController as AdminPayoutController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
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
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\InstructorAuthController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Instructor\AssignmentController as InstructorAssignmentController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\LessonController;
use App\Http\Controllers\Instructor\LiveClassController as InstructorLiveClassController;
use App\Http\Controllers\Instructor\NotificationController as InstructorNotificationController;
use App\Http\Controllers\Instructor\ProfileController as InstructorProfileController;
use App\Http\Controllers\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Instructor\ReviewController as InstructorReviewController;
use App\Http\Controllers\Instructor\StudentController as InstructorStudentController;
use App\Http\Controllers\Instructor\TicketController as InstructorTicketController;
use App\Http\Controllers\Instructor\VerificationController as InstructorVerificationController;
use App\Http\Controllers\InstructorDirectoryController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PublicCertificateController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SearchSuggestionController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\LeaderboardController as StudentLeaderboardController;
use App\Http\Controllers\Student\LearnController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use App\Http\Controllers\Student\OrderController as StudentOrderController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\TicketController as StudentTicketController;
use App\Http\Controllers\Student\WishlistController;
use App\Http\Controllers\ThemeController;
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

// Search Suggestions API (mono_market pattern)
Route::get('/api/search-suggestions', [SearchSuggestionController::class, 'index'])->name('api.search.suggestions');

// Public catalog (DB-driven)
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'grid'])->name('index');
    Route::get('/grid', fn () => redirect()->route('courses.index', request()->query()));
    Route::get('/{slug}', [CourseController::class, 'show'])->name('show');
    Route::post('/{course:slug}/enroll', [CourseController::class, 'enroll'])
        ->middleware('auth:student')->name('enroll');
    Route::post('/{course}/wishlist', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/{course:slug}/reviews', [CourseReviewController::class, 'store'])
        ->middleware('auth:student')->name('reviews.store');
    Route::delete('/{course:slug}/reviews', [CourseReviewController::class, 'destroy'])
        ->middleware('auth:student')->name('reviews.destroy');
});

// ------------------------------------------------------------- Purchase flow
// mono_market pattern: pending order with a 60-minute reservation, then a
// dedicated summary page per payment method -> gateway / manual verification.
Route::prefix('checkout')->name('checkout.')->middleware('auth:student')->group(function () {
    Route::post('/{course:slug}', [CheckoutController::class, 'start'])->name('start');
    Route::get('/pay/{order}/{method}', [CheckoutController::class, 'payPage'])
        ->whereIn('method', ['stripe', 'paypal', 'razorpay', 'offline'])->name('pay');
    Route::post('/pay/{order}/{method}/initiate', [CheckoutController::class, 'initiate'])
        ->whereIn('method', ['stripe', 'paypal', 'razorpay', 'offline'])->name('pay.initiate');
    Route::get('/payment-success', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment-cancel', [CheckoutController::class, 'paymentCancel'])->name('payment.cancel');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
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

// Inbound Contact Message Submission
Route::post('/contact/send', [PublicPageController::class, 'submitContact'])->name('contact.submit');

// Public Certificate Verification
Route::get('/certificates/verify/{code?}', [PublicCertificateController::class, 'show'])->name('certificates.verify.show');
Route::post('/certificates/verify', [PublicCertificateController::class, 'search'])->name('certificates.verify.search');

// ------------------------------------------------------------ Language switch
// Panel locale switches live OUTSIDE the guarded groups so they work from
// login pages too; each writes to its own panel session key.
Route::get('/admin/locale/{code}', [LocaleController::class, 'switchAdmin'])->name('admin.locale.switch');
Route::get('/instructor/locale/{code}', [LocaleController::class, 'switchInstructor'])->name('instructor.locale.switch');
Route::get('/student/locale/{code}', [LocaleController::class, 'switchStudent'])->name('student.locale.switch');

// ------------------------------------------------------------ Theme switch
// Independent panel theme toggles (admin, instructor, student).
// Storefront is excluded and remains strictly light.
Route::post('/theme/{panel}', [ThemeController::class, 'toggle'])
    ->whereIn('panel', ['admin', 'instructor', 'student'])
    ->name('theme.toggle');

// ----------------------------------------------------------------- Panel auth
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:login')->name('admin.login.attempt');
});
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');

Route::middleware('guest:instructor')->group(function () {
    Route::get('/instructor/login', [InstructorAuthController::class, 'showLogin'])->name('instructor.login');
    Route::post('/instructor/login', [InstructorAuthController::class, 'login'])->middleware('throttle:login')->name('instructor.login.attempt');
    Route::get('/instructor/register', [InstructorAuthController::class, 'showRegister'])->name('instructor.register');
    Route::post('/instructor/register', [InstructorAuthController::class, 'register'])->name('instructor.register.attempt');
});
Route::post('/instructor/logout', [InstructorAuthController::class, 'logout'])->middleware('auth:instructor')->name('instructor.logout');

Route::middleware('guest:student')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->middleware('throttle:login')->name('login.attempt');
    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('register.attempt');
});
Route::post('/logout', [StudentAuthController::class, 'logout'])->middleware('auth:student')->name('logout');

// Google OAuth (student + instructor panels)
Route::get('/auth/google/{panel}/redirect', [GoogleAuthController::class, 'redirect'])
    ->whereIn('panel', ['student', 'instructor'])
    ->name('login.google.redirect');
Route::get('/auth/google/{panel}/callback', [GoogleAuthController::class, 'callback'])
    ->whereIn('panel', ['student', 'instructor'])
    ->name('login.google.callback');
Route::get('/instructor/auth/google/redirect', fn () => redirect()->route('login.google.redirect', ['panel' => 'instructor']))
    ->name('instructor.login.google.redirect');
Route::get('/instructor/auth/google/callback', fn () => redirect()->route('login.google.callback', ['panel' => 'instructor']))
    ->name('instructor.login.google.callback');

// Shared password reset (?panel=student|instructor|admin) with OTP verification
Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:otp-send')->name('password.email');
Route::get('/forgot-password/otp', [PasswordResetController::class, 'showOtp'])->name('password.otp.show');
Route::post('/forgot-password/otp/verify', [PasswordResetController::class, 'verifyOtp'])->middleware('throttle:otp-verify')->name('password.otp.verify');
Route::post('/forgot-password/otp/resend', [PasswordResetController::class, 'resendOtp'])->middleware('throttle:otp-send')->name('password.otp.resend');
Route::get('/set-password', [PasswordResetController::class, 'showReset'])->name('password.set.show');
Route::get('/set-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/set-password', [PasswordResetController::class, 'reset'])->name('password.update');

// OTP sign-up verification
Route::get('/otp', [OtpController::class, 'show'])->name('otp');
Route::post('/otp/verify', [OtpController::class, 'verify'])->middleware('throttle:otp-verify')->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->middleware('throttle:otp-send')->name('otp.resend');
Route::get('/lock-screen', fn () => view('pages.auth.lock-screen'))->name('lock-screen');

// -------------------------------------------------------------- Admin panel
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin profile & password edit (accessible by any authenticated admin)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::middleware('permission:admins.manage')->group(function () {
        Route::resource('admins', AdminUserController::class)->except('show');
        Route::post('/admins/bulk-destroy', [AdminUserController::class, 'bulkDestroy'])->name('admins.bulk-destroy');
    });

    Route::middleware('permission:roles.manage')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
    });

    Route::middleware('permission:instructors.manage')->group(function () {
        Route::resource('instructors', AdminInstructorController::class)->except('show');
        Route::post('/instructors/bulk-destroy', [AdminInstructorController::class, 'bulkDestroy'])->name('instructors.bulk-destroy');
        Route::post('/instructors/{instructor}/approve-verification', [AdminInstructorController::class, 'approveVerification'])->name('instructors.approve-verification');
        Route::post('/instructors/{instructor}/reject-verification', [AdminInstructorController::class, 'rejectVerification'])->name('instructors.reject-verification');
    });

    Route::middleware('permission:students.manage')->group(function () {
        Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
        Route::post('/students', [AdminStudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [AdminStudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
        Route::post('/students/bulk-destroy', [AdminStudentController::class, 'bulkDestroy'])->name('students.bulk-destroy');
    });

    Route::middleware('permission:categories.manage')->group(function () {
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::post('/categories/bulk-destroy', [AdminCategoryController::class, 'bulkDestroy'])->name('categories.bulk-destroy');
    });

    Route::get('/courses', [AdminCourseController::class, 'index'])
        ->middleware('permission:courses.manage')->name('courses.index');
    Route::get('/courses/create', [AdminCourseController::class, 'create'])
        ->middleware('permission:courses.manage')->name('courses.create');
    Route::post('/courses', [AdminCourseController::class, 'store'])
        ->middleware('permission:courses.manage')->name('courses.store');
    Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])
        ->middleware('permission:courses.manage')->name('courses.edit');
    Route::put('/courses/{course}', [AdminCourseController::class, 'update'])
        ->middleware('permission:courses.manage')->name('courses.update');
    Route::post('/courses/{course}/lessons', [AdminCourseController::class, 'storeLesson'])
        ->middleware('permission:courses.manage')->name('courses.lessons.store');
    Route::put('/lessons/{lesson}', [AdminCourseController::class, 'updateLesson'])
        ->middleware('permission:courses.manage')->name('courses.lessons.update');
    Route::delete('/lessons/{lesson}', [AdminCourseController::class, 'destroyLesson'])
        ->middleware('permission:courses.manage')->name('courses.lessons.destroy');
    Route::post('/courses/{course}/live-classes', [AdminLiveClassController::class, 'store'])
        ->middleware('permission:courses.manage')->name('live-classes.store');
    Route::put('/live-classes/{liveClass}', [AdminLiveClassController::class, 'update'])
        ->middleware('permission:courses.manage')->name('live-classes.update');
    Route::delete('/live-classes/{liveClass}', [AdminLiveClassController::class, 'destroy'])
        ->middleware('permission:courses.manage')->name('live-classes.destroy');
    Route::post('/courses/{course}/quizzes', [AdminQuizController::class, 'store'])
        ->middleware('permission:courses.manage')->name('courses.quizzes.store');
    Route::put('/quizzes/{quiz}', [AdminQuizController::class, 'update'])
        ->middleware('permission:courses.manage')->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [AdminQuizController::class, 'destroy'])
        ->middleware('permission:courses.manage')->name('quizzes.destroy');
    Route::post('/quizzes/{quiz}/questions', [AdminQuizController::class, 'storeQuestion'])
        ->middleware('permission:courses.manage')->name('quizzes.questions.store');
    Route::delete('/quiz-questions/{question}', [AdminQuizController::class, 'destroyQuestion'])
        ->middleware('permission:courses.manage')->name('quizzes.questions.destroy');

    // Assignments (Ostad cohort-based learning)
    Route::get('/courses/{course}/assignments', [AdminAssignmentController::class, 'index'])
        ->middleware('permission:courses.manage')->name('courses.assignments.index');
    Route::post('/courses/{course}/assignments', [AdminAssignmentController::class, 'store'])
        ->middleware('permission:courses.manage')->name('assignments.store');
    Route::put('/assignments/{assignment}', [AdminAssignmentController::class, 'update'])
        ->middleware('permission:courses.manage')->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AdminAssignmentController::class, 'destroy'])
        ->middleware('permission:courses.manage')->name('assignments.destroy');
    Route::get('/assignments/{assignment}/submissions', [AdminAssignmentController::class, 'submissions'])
        ->middleware('permission:courses.manage')->name('assignments.submissions');
    Route::post('/assignments/{assignment}/submissions/{submission}/grade', [AdminAssignmentController::class, 'grade'])
        ->middleware('permission:courses.manage')->name('assignments.grade');
    Route::post('/courses/bulk-destroy', [AdminCourseController::class, 'bulkDestroy'])
        ->middleware('permission:courses.manage')->name('courses.bulk-destroy');
    Route::match(['post', 'patch'], '/courses/{course}/publish', [AdminCourseController::class, 'publish'])
        ->middleware('permission:courses.publish')->name('courses.publish');
    Route::match(['post', 'patch'], '/courses/{course}/unpublish', [AdminCourseController::class, 'unpublish'])
        ->middleware('permission:courses.publish')->name('courses.unpublish');
    Route::match(['post', 'patch'], '/courses/{course}/toggle-featured', [AdminCourseController::class, 'toggleFeatured'])
        ->middleware('permission:courses.manage')->name('courses.toggle-featured');
    Route::match(['post', 'patch'], '/courses/{course}/toggle-trending', [AdminCourseController::class, 'toggleTrending'])
        ->middleware('permission:courses.manage')->name('courses.toggle-trending');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])
        ->middleware('permission:courses.manage')->name('courses.destroy');

    Route::get('/reviews', [AdminReviewController::class, 'index'])
        ->middleware('permission:reviews.moderate')->name('reviews.index');
    Route::match(['post', 'patch'], '/reviews/{review}/approve', [AdminReviewController::class, 'approve'])
        ->middleware('permission:reviews.moderate')->name('reviews.approve');
    Route::match(['post', 'patch'], '/reviews/{review}/reject', [AdminReviewController::class, 'reject'])
        ->middleware('permission:reviews.moderate')->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])
        ->middleware('permission:reviews.moderate')->name('reviews.destroy');
    Route::post('/reviews/bulk-approve', [AdminReviewController::class, 'bulkApprove'])
        ->middleware('permission:reviews.moderate')->name('reviews.bulk-approve');
    Route::post('/reviews/bulk-reject', [AdminReviewController::class, 'bulkReject'])
        ->middleware('permission:reviews.moderate')->name('reviews.bulk-reject');
    Route::post('/reviews/bulk-destroy', [AdminReviewController::class, 'bulkDestroy'])
        ->middleware('permission:reviews.moderate')->name('reviews.bulk-destroy');
    Route::post('/reviews/{review}/reply', [AdminReviewController::class, 'reply'])
        ->middleware('permission:reviews.moderate')->name('reviews.reply');

    // Languages & Translations
    Route::middleware('permission:languages.manage')->group(function () {
        Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
        Route::post('/languages', [LanguageController::class, 'store'])->name('languages.store');
        Route::put('/languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
        Route::match(['post', 'patch'], '/languages/{language}/toggle', [LanguageController::class, 'toggle'])->name('languages.toggle');
        Route::match(['post', 'patch'], '/languages/{language}/default', [LanguageController::class, 'makeDefault'])->name('languages.default');
        Route::delete('/languages/{language}', [LanguageController::class, 'destroy'])->name('languages.destroy');
        Route::get('/languages/{language}/translations', [LanguageController::class, 'translations'])->name('languages.translations');
        Route::match(['post', 'put'], '/languages/{language}/translations', [LanguageController::class, 'updateTranslations'])->name('languages.translations.update');
        Route::post('/languages/{language}/translate', [LanguageController::class, 'translate'])->name('languages.translate');
    });

    // AI Providers & Suite Settings
    Route::middleware('permission:languages.manage')->group(function () {
        Route::get('/settings/ai', [AiSettingsController::class, 'index'])->name('settings.ai');
        Route::post('/settings/ai', [AiSettingsController::class, 'store'])->name('settings.ai.store');
        Route::post('/settings/ai/models', [AiSettingsController::class, 'fetchModels'])->name('settings.ai.models');
        Route::post('/settings/ai/playground', [AiSettingsController::class, 'playground'])->name('settings.ai.playground');
        Route::put('/settings/ai/translator', [AiSettingsController::class, 'updateTranslator'])->name('settings.ai.translator');
        Route::put('/settings/ai/generator', [AiSettingsController::class, 'updateGenerator'])->name('settings.ai.generator');
        Route::put('/settings/ai/image', [AiSettingsController::class, 'updateImage'])->name('settings.ai.image');
        Route::put('/settings/ai/{aiProvider}', [AiSettingsController::class, 'update'])->name('settings.ai.update');
        Route::delete('/settings/ai/{aiProvider}', [AiSettingsController::class, 'destroy'])->name('settings.ai.destroy');
    });

    // AI Tools (Translation & Image Generation)
    Route::post('/translate', AiTranslationController::class)
        ->middleware('throttle:30,1')
        ->name('translate');
    Route::post('/translate/taxonomy', [AiController::class, 'bulkTranslateTaxonomy'])
        ->middleware('throttle:10,1')
        ->name('translate.taxonomy');
    Route::post('/generate-image', [AiController::class, 'generateImage'])
        ->name('generate.image');
    Route::post('/generate', [AiController::class, 'generateContent'])
        ->middleware('throttle:30,1')
        ->name('generate');

    // Blog
    Route::middleware('permission:blog.manage')->group(function () {
        Route::get('/blog', [AdminPostController::class, 'index'])->name('blog.index');
        Route::get('/blog/create', [AdminPostController::class, 'create'])->name('blog.create');
        Route::post('/blog', [AdminPostController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit', [AdminPostController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}', [AdminPostController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [AdminPostController::class, 'destroy'])->name('blog.destroy');
        Route::post('/blog/bulk-destroy', [AdminPostController::class, 'bulkDestroy'])->name('blog.bulk-destroy');
    });

    // Media Library
    Route::middleware('permission:media.manage')->group(function () {
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::get('/media.json', [AdminMediaController::class, 'json'])->name('media.json');
        Route::post('/media/upload', [AdminMediaController::class, 'quickUpload'])->name('media.upload');
        Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{media}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
        Route::post('/media/bulk-destroy', [AdminMediaController::class, 'bulkDestroy'])->name('media.bulk-destroy');
    });

    // Membership plans + the master enable/disable switch
    Route::middleware('permission:plans.manage')->group(function () {
        Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
        Route::match(['post', 'patch'], '/plans/membership/toggle', [AdminPlanController::class, 'toggleMembership'])->name('plans.membership-toggle');
        Route::get('/plans/create', [AdminPlanController::class, 'create'])->name('plans.create');
        Route::post('/plans', [AdminPlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [AdminPlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [AdminPlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [AdminPlanController::class, 'destroy'])->name('plans.destroy');
        Route::post('/plans/bulk-destroy', [AdminPlanController::class, 'bulkDestroy'])->name('plans.bulk-destroy');
    });

    // Unified All Users Directory
    Route::get('/users', [AdminUserDirectoryController::class, 'index'])->name('users.index');
    Route::post('/users/bulk-destroy', [AdminUserDirectoryController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::delete('/users/{type}/{id}', [AdminUserDirectoryController::class, 'destroy'])->name('users.destroy');

    // Orders & Sales Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/verify', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify');

    // Coupons
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::patch('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');
    Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
    Route::post('/coupons/bulk-destroy', [AdminCouponController::class, 'bulkDestroy'])->name('coupons.bulk-destroy');

    // Payouts
    Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts.index');
    Route::patch('/payouts/{payout}', [AdminPayoutController::class, 'update'])->name('payouts.update');

    // Certificate Templates
    Route::get('/certificate-templates', [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::post('/certificate-templates', [AdminCertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificate-templates/{template}/preview', [AdminCertificateController::class, 'preview'])->name('certificates.preview');
    Route::put('/certificate-templates/{template}', [AdminCertificateController::class, 'update'])->name('certificates.update');
    Route::delete('/certificate-templates/{template}', [AdminCertificateController::class, 'destroy'])->name('certificates.destroy');
    Route::patch('/certificate-templates/{template}/default', [AdminCertificateController::class, 'makeDefault'])->name('certificates.default');

    // Support Tickets & Contact Messages
    Route::get('/tickets', [AdminSupportController::class, 'tickets'])->name('tickets.index');
    Route::post('/tickets', [AdminSupportController::class, 'storeTicket'])->name('tickets.store');
    Route::patch('/tickets/{ticket}', [AdminSupportController::class, 'updateTicket'])->name('tickets.update');
    Route::patch('/tickets/{ticket}/reply', [AdminSupportController::class, 'replyTicket'])->name('tickets.reply');
    Route::delete('/tickets/{ticket}', [AdminSupportController::class, 'destroyTicket'])->name('tickets.destroy');
    Route::post('/tickets/bulk-action', [AdminSupportController::class, 'bulkTickets'])->name('tickets.bulk-action');

    Route::get('/messages', [AdminSupportController::class, 'messages'])->name('messages.index');
    Route::get('/messages/export', [AdminSupportController::class, 'exportMessages'])->name('messages.export');
    Route::post('/messages/{message}/reply', [AdminSupportController::class, 'replyMessage'])->name('messages.reply');
    Route::patch('/messages/{message}/toggle', [AdminSupportController::class, 'toggleMessageRead'])->name('messages.toggle');
    Route::delete('/messages/{message}', [AdminSupportController::class, 'destroyMessage'])->name('messages.destroy');
    Route::post('/messages/bulk-action', [AdminSupportController::class, 'bulkMessages'])->name('messages.bulk-action');
    Route::post('/messages/bulk-destroy', [AdminSupportController::class, 'bulkDestroyMessages'])->name('messages.bulk-destroy');

    // Payment Gateways
    Route::get('/payment-gateways', [AdminPaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::post('/payment-gateways', [AdminPaymentGatewayController::class, 'update'])->name('payment-gateways.update');

    // Reports & Analytics
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

    // Google & Firebase Settings
    Route::middleware('permission:settings.manage')->group(function () {
        Route::get('/settings/google', [GoogleSettingsController::class, 'index'])->name('settings.google');
        Route::post('/settings/google', [GoogleSettingsController::class, 'store'])->name('settings.google.store');
        Route::delete('/settings/google/firebase-service-account', [GoogleSettingsController::class, 'destroyServiceAccount'])->name('settings.google.firebase_service_account.destroy');
        Route::post('/settings/google/firebase-test', [GoogleSettingsController::class, 'testFirebaseNotification'])->name('settings.google.firebase_test');
    });

    // General Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Home Page Editor
    Route::get('/homepage', [AdminHomePageController::class, 'edit'])->name('homepage.index');
    Route::put('/homepage', [AdminHomePageController::class, 'update'])->name('homepage.update');
    Route::post('/homepage', [AdminHomePageController::class, 'update']);

    // Site Editor: Static & Custom Pages, Header, Footer
    Route::middleware('permission:pages.manage')->group(function () {
        Route::get('/header', [AdminHeaderController::class, 'edit'])->name('header.index');
        Route::post('/header', [AdminHeaderController::class, 'update'])->name('header.update');
        Route::put('/header', [AdminHeaderController::class, 'update']);

        Route::get('/footer', [AdminFooterController::class, 'edit'])->name('footer.index');
        Route::post('/footer', [AdminFooterController::class, 'update'])->name('footer.update');
        Route::put('/footer', [AdminFooterController::class, 'update']);

        Route::resource('pages', AdminPageController::class)->except('show');
        Route::get('/pages/edit-by-slug/{slug}', [AdminPageController::class, 'editBySlug'])->name('pages.edit-by-slug');
        Route::post('/pages/{page}/duplicate', [AdminPageController::class, 'duplicate'])->name('pages.duplicate');
    });

    // Mail (SMTP) & Storage Services
    Route::get('/services', [AdminServicesController::class, 'index'])->name('services.index');
    Route::post('/services/mail', [AdminServicesController::class, 'updateMail'])->name('services.mail');
    Route::post('/services/mail/test', [AdminServicesController::class, 'testMail'])->name('services.mail.test');
    Route::post('/services/storage', [AdminServicesController::class, 'updateStorage'])->name('services.storage');

    // Leads (CRM)
    Route::get('/leads', [AdminLeadController::class, 'index'])->name('leads.index');
    Route::patch('/leads/{lead}', [AdminLeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [AdminLeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/bulk-destroy', [AdminLeadController::class, 'bulkDestroy'])->name('leads.bulk-destroy');

    // Events / Webinars
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/bulk-destroy', [AdminEventController::class, 'bulkDestroy'])->name('events.bulk-destroy');

    // System Status & Updates
    Route::get('/system', [AdminSystemController::class, 'index'])->name('system.index');
    Route::post('/system/clear-cache', [AdminSystemController::class, 'clearCache'])->name('system.clear-cache');
});

// ---------------------------------------------------------- Instructor panel
Route::prefix('instructor')->name('instructor.')->middleware('auth:instructor')->group(function () {
    Route::get('/', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [InstructorNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [InstructorNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [InstructorNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [InstructorNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/verify', [InstructorVerificationController::class, 'show'])->name('verification.show');
    Route::post('/verify', [InstructorVerificationController::class, 'submit'])->name('verification.submit');

    Route::get('/courses', [InstructorCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [InstructorCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [InstructorCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [InstructorCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [InstructorCourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/translate', AiTranslationController::class)->middleware('throttle:30,1')->name('translate');
    Route::post('/generate-image', [AiController::class, 'generateImage'])->name('generate.image');
    Route::post('/generate', [AiController::class, 'generateContent'])->middleware('throttle:30,1')->name('generate');

    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

    // Live classes (live / combined courses)
    Route::post('/courses/{course}/live-classes', [InstructorLiveClassController::class, 'store'])->name('live-classes.store');
    Route::put('/live-classes/{liveClass}', [InstructorLiveClassController::class, 'update'])->name('live-classes.update');
    Route::delete('/live-classes/{liveClass}', [InstructorLiveClassController::class, 'destroy'])->name('live-classes.destroy');

    // Quizzes (recorded / live / combined)
    Route::post('/courses/{course}/quizzes', [InstructorQuizController::class, 'store'])->name('courses.quizzes.store');
    Route::put('/quizzes/{quiz}', [InstructorQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [InstructorQuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::post('/quizzes/{quiz}/questions', [InstructorQuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::delete('/quiz-questions/{question}', [InstructorQuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

    // Assignments (Ostad cohort-based learning)
    Route::get('/courses/{course}/assignments', [InstructorAssignmentController::class, 'index'])->name('courses.assignments.index');
    Route::post('/courses/{course}/assignments', [InstructorAssignmentController::class, 'store'])->name('assignments.store');
    Route::put('/assignments/{assignment}', [InstructorAssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [InstructorAssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('/assignments/{assignment}/submissions', [InstructorAssignmentController::class, 'submissions'])->name('assignments.submissions');
    Route::post('/assignments/{assignment}/submissions/{submission}/grade', [InstructorAssignmentController::class, 'grade'])->name('assignments.grade');

    Route::get('/students', [InstructorStudentController::class, 'index'])->name('students');
    Route::get('/reviews', [InstructorReviewController::class, 'index'])->name('reviews');
    Route::post('/reviews/{review}/reply', [InstructorReviewController::class, 'reply'])->name('reviews.reply');
    Route::get('/profile', [InstructorProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [InstructorProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [InstructorProfileController::class, 'updatePassword'])->name('profile.password');

    // Support Tickets
    Route::get('/tickets', [InstructorTicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [InstructorTicketController::class, 'store'])->name('tickets.store');
    Route::post('/tickets/{ticket}/reply', [InstructorTicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/close', [InstructorTicketController::class, 'close'])->name('tickets.close');
});

// ------------------------------------------------------------ Student panel
Route::prefix('student')->name('student.')->middleware('auth:student')->group(function () {
    Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/enrolled-courses', [EnrollmentController::class, 'index'])->name('enrolled-courses');
    Route::get('/saved-courses', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/wishlist', fn () => redirect()->route('student.wishlist'))->name('wishlist.alias');
    Route::delete('/saved-courses/{course}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // My Orders (purchase history + payment retry)
    Route::get('/orders', [StudentOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [StudentOrderController::class, 'show'])->name('orders.show');

    // Learning: lesson player, live classes, completion tracking
    Route::get('/courses/{course}/learn', [LearnController::class, 'show'])->name('courses.learn');
    Route::post('/lessons/{lesson}/complete', [LearnController::class, 'complete'])->name('lessons.complete');
    Route::get('/live-classes/{liveClass}/join', [LearnController::class, 'join'])
        ->name('live-classes.join');
    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password');

    // Support Tickets
    Route::get('/tickets', [StudentTicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [StudentTicketController::class, 'store'])->name('tickets.store');
    Route::post('/tickets/{ticket}/reply', [StudentTicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/close', [StudentTicketController::class, 'close'])->name('tickets.close');

    // Notifications
    Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [StudentNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [StudentNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [StudentNotificationController::class, 'destroy'])->name('notifications.destroy');

    // Certificates
    Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates');
    Route::get('/certificates/{certificate}/print', [StudentCertificateController::class, 'printCertificate'])->name('certificates.print');
    Route::post('/certificates/claim/{course}', [StudentCertificateController::class, 'claim'])->name('certificates.claim');

    // Quizzes & Assessments
    Route::get('/quiz-attempts', [StudentQuizController::class, 'index'])->name('quiz-attempts');
    Route::get('/courses/{course}/quizzes/{quiz}/take', [StudentQuizController::class, 'take'])->name('quizzes.take');
    Route::post('/courses/{course}/quizzes/{quiz}/attempts/{attempt}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quiz-attempts/{attempt}/review', [StudentQuizController::class, 'review'])->name('quizzes.review');

    // Assignments & Homework (Ostad cohort-based learning)
    Route::get('/courses/{course}/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/courses/{course}/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');
    Route::post('/courses/{course}/assignments/{assignment}/resubmit-request', [StudentAssignmentController::class, 'requestResubmission'])->name('assignments.resubmit-request');

    // Cohort Leaderboard (Ostad gamification & rank)
    Route::get('/courses/{course}/leaderboard', [StudentLeaderboardController::class, 'show'])->name('courses.leaderboard');
});

// ------------------------------------------------------------- Dynamic Public Pages (Catch-all permalinks)
Route::get('/{slug}', [PublicPageController::class, 'show'])
    ->where('slug', '^[a-zA-Z0-9\-_]+$')
    ->name('pages.show');
