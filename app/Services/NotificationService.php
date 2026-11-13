<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Student;
use App\Models\SupportTicket;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class NotificationService
{
    /**
     * Send a notification to a specific user (Admin, Instructor, Student).
     */
    public static function send(
        $notifiable,
        string $title,
        string $message,
        string $category = 'system',
        ?string $actionUrl = null,
        ?string $actionLabel = null,
        ?string $icon = null,
        bool $sendMail = false,
        array $metadata = [],
    ): void {
        if (! $notifiable) {
            return;
        }

        // Idempotency guard for scheduled/repeated sends: skip when a notification
        // carrying the same dedup key was already delivered to this notifiable.
        $dedupKey = $metadata['dedup_key'] ?? null;
        if (is_string($dedupKey) && $dedupKey !== '') {
            try {
                $alreadySent = $notifiable->notifications()
                    ->where('data->dedup_key', $dedupKey)
                    ->exists();

                if ($alreadySent) {
                    return;
                }
            } catch (Throwable) {
                // Ignore if notifications table query fails
            }
        }

        // Deduplication guard: ignore identical notifications dispatched within a 10-second window
        try {
            if (method_exists($notifiable, 'notifications')) {
                $recentlySent = $notifiable->notifications()
                    ->where('created_at', '>=', now()->subSeconds(10))
                    ->get()
                    ->contains(fn ($n) => ($n->data['title'] ?? null) === $title && ($n->data['message'] ?? null) === $message);

                if ($recentlySent) {
                    return;
                }
            }
        } catch (Throwable) {
            // Ignore if notifications table query fails
        }

        try {
            $notification = new AppNotification(
                title: $title,
                message: $message,
                category: $category,
                actionUrl: $actionUrl,
                actionLabel: $actionLabel,
                icon: $icon,
                sendMail: $sendMail,
                metadata: $metadata,
            );

            $notifiable->notify($notification);

            // Dispatch Firebase Cloud Messaging (FCM) push notification if configured and device token present
            $deviceTokens = $metadata['fcm_token'] ?? $metadata['device_token'] ?? ($notifiable->fcm_token ?? null);
            if ($deviceTokens && FirebaseNotificationService::isConfigured()) {
                try {
                    FirebaseNotificationService::sendPushNotification(
                        deviceTokens: (array) $deviceTokens,
                        title: $title,
                        body: $message,
                        data: array_merge($metadata, [
                            'category' => $category,
                            'action_url' => $actionUrl ?? '',
                        ]),
                    );
                } catch (Throwable $fcmErr) {
                    Log::warning('Firebase push notification dispatch error: '.$fcmErr->getMessage());
                }
            }
        } catch (Throwable $e) {
            // If sending via mail fails (e.g. invalid SMTP config), the database channel already ran first.
            // Only retry if database notification was not yet stored.
            Log::warning('AppNotification mail/channel dispatch error: '.$e->getMessage());

            try {
                $alreadyStored = method_exists($notifiable, 'notifications')
                    && $notifiable->notifications()
                        ->where('created_at', '>=', now()->subSeconds(30))
                        ->get()
                        ->contains(fn ($n) => ($n->data['title'] ?? null) === $title && ($n->data['message'] ?? null) === $message);

                if (! $alreadyStored) {
                    $fallback = new AppNotification(
                        title: $title,
                        message: $message,
                        category: $category,
                        actionUrl: $actionUrl,
                        actionLabel: $actionLabel,
                        icon: $icon,
                        sendMail: false,
                        metadata: $metadata,
                    );
                    $notifiable->notify($fallback);
                }
            } catch (Throwable $e2) {
                Log::error('AppNotification fallback database error: '.$e2->getMessage());
            }
        }
    }

    /**
     * Send a notification to all active administrators.
     */
    public static function notifyAdmins(
        string $title,
        string $message,
        string $category = 'system',
        ?string $actionUrl = null,
        ?string $actionLabel = null,
        ?string $icon = null,
        bool $sendMail = false,
        array $metadata = [],
    ): void {
        $admins = Admin::where('status', 'active')->get();

        foreach ($admins as $admin) {
            self::send($admin, $title, $message, $category, $actionUrl, $actionLabel, $icon, $sendMail, $metadata);
        }
    }

    /**
     * Send a notification to every actively enrolled student of a course.
     */
    public static function notifyCourseStudents(
        Course $course,
        string $title,
        string $message,
        string $category = 'system',
        ?string $actionUrl = null,
        ?string $actionLabel = null,
        ?string $icon = null,
        bool $sendMail = false,
        array $metadata = [],
    ): void {
        $students = $course->enrollments()
            ->whereIn('status', ['active', 'completed'])
            ->with('student')
            ->get()
            ->pluck('student')
            ->filter();

        foreach ($students as $student) {
            self::send($student, $title, $message, $category, $actionUrl, $actionLabel, $icon, $sendMail, $metadata);
        }
    }

    /**
     * Send a notification to the course instructor, falling back to
     * administrators when no instructor is assigned to the course.
     */
    public static function notifyCourseInstructors(
        Course $course,
        string $title,
        string $message,
        string $category = 'system',
        ?string $actionUrl = null,
        ?string $actionLabel = null,
        ?string $icon = null,
        bool $sendMail = false,
        array $metadata = [],
        ?string $adminActionUrl = null,
    ): void {
        if ($course->instructor) {
            self::send($course->instructor, $title, $message, $category, $actionUrl, $actionLabel, $icon, $sendMail, $metadata);

            return;
        }

        self::notifyAdmins($title, $message, $category, $adminActionUrl ?? $actionUrl, $actionLabel, $icon, $sendMail, $metadata);
    }

    /**
     * Ensure the user has real notifications populated from actual platform records.
     */
    public static function syncInitialNotifications($user, string $role): void
    {
        if (! $user) {
            return;
        }

        // Only seed if user currently has zero notifications
        if ($user->notifications()->count() > 0) {
            return;
        }

        if ($role === 'instructor') {
            self::syncInstructorNotifications($user);
        } elseif ($role === 'admin') {
            self::syncAdminNotifications($user);
        } elseif ($role === 'student') {
            self::syncStudentNotifications($user);
        }
    }

    protected static function syncStudentNotifications(Student $student): void
    {
        // 1. Welcome learning notification
        self::send(
            $student,
            'Welcome to Mono LMS Learning Portal',
            'Explore courses, track ongoing lessons, complete quizzes, and earn certified credentials.',
            'system',
            route('courses.index'),
            'Browse Courses',
            'academic',
            false
        );

        // 2. Active enrollments notification if any
        $enrollment = $student->enrollments()->with('course')->latest()->first();
        if ($enrollment && $enrollment->course) {
            self::send(
                $student,
                'Course In Progress: '.$enrollment->course->title,
                'Resume where you left off. Current lesson progress is '.($enrollment->progress ?? 0).'%.',
                'enrollment',
                route('student.enrolled-courses'),
                'Resume Learning',
                'book',
                false
            );
        }

        // 3. Support & Tickets notification
        self::send(
            $student,
            'Student Helpdesk & Support Available',
            'Have a question or need assistance with your coursework? Our support team is here to help.',
            'support',
            route('student.tickets.index'),
            'Support Tickets',
            'support',
            false
        );

        // 4. SMTP / System notification
        self::send(
            $student,
            'Email Notifications Operational',
            'Your account is connected to email notifications. You will receive updates about course enrollments and ticket responses.',
            'smtp',
            route('student.profile'),
            'Account Profile',
            'mail',
            false
        );
    }

    protected static function syncInstructorNotifications(Instructor $instructor): void
    {
        // 1. Verification status
        if ($instructor->isVerified()) {
            self::send(
                $instructor,
                'Instructor Verification Approved',
                'Your credentials and national ID have been approved. Course publishing and public listing are active.',
                'verification',
                route('instructor.courses.index'),
                'View Courses',
                'shield',
                false
            );
        } elseif ($instructor->isPendingVerification()) {
            self::send(
                $instructor,
                'Verification Under Review',
                'Your certification and national ID documents are currently queued for administrator review.',
                'verification',
                route('instructor.verification.show'),
                'Review Status',
                'shield',
                false
            );
        } elseif ($instructor->isVerificationRejected()) {
            self::send(
                $instructor,
                'Verification Needs Attention',
                $instructor->rejection_reason ?: 'Your credentials could not be verified. Please inspect feedback and re-upload.',
                'verification',
                route('instructor.verification.show'),
                'Update Documents',
                'shield',
                false
            );
        } else {
            self::send(
                $instructor,
                'Action Required: Verify Account',
                'Submit your instructor certificate and national ID to unlock course creation.',
                'verification',
                route('instructor.verification.show'),
                'Complete Verification',
                'shield',
                false
            );
        }

        // 2. Recent Enrollments
        $courseIds = $instructor->courses()->pluck('id');
        $enrollments = Enrollment::with(['student', 'course'])
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->take(3)
            ->get();

        foreach ($enrollments as $enrollment) {
            self::send(
                $instructor,
                'New Student Enrolled',
                ($enrollment->student?->name ?? 'A student').' enrolled in "'.($enrollment->course?->title ?? 'your course').'".',
                'enrollment',
                route('instructor.students'),
                'View Students',
                'user',
                false
            );
        }

        // 3. Recent Reviews
        $reviews = Review::with(['student', 'course'])
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->take(2)
            ->get();

        foreach ($reviews as $review) {
            self::send(
                $instructor,
                'New Course Review Received',
                ($review->student?->name ?? 'A student').' left a '.$review->rating.'★ rating on "'.($review->course?->title ?? 'your course').'".',
                'review',
                route('instructor.reviews'),
                'Read Reviews',
                'star',
                false
            );
        }

        // 4. Support notification
        self::send(
            $instructor,
            'Instructor Support & Guidelines',
            'Welcome to the Mono LMS Instructor portal! Review course guidelines and feel free to contact administration for support.',
            'support',
            route('instructor.profile'),
            'View Profile',
            'support',
            false
        );

        // 5. SMTP / System notification
        self::send(
            $instructor,
            'SMTP Notification Service Active',
            'Email dispatch channels are operational. You will receive email alerts for enrollments and reviews.',
            'smtp',
            route('instructor.profile'),
            'Account Settings',
            'mail',
            false
        );
    }

    protected static function syncAdminNotifications(Admin $admin): void
    {
        // 1. Pending instructor verifications
        $pendingInstructors = Instructor::where('verification_status', 'pending')->latest()->take(3)->get();
        foreach ($pendingInstructors as $inst) {
            self::send(
                $admin,
                'Instructor Verification Pending: '.$inst->name,
                $inst->name.' ('.$inst->email.') submitted certificate and identity documents for approval.',
                'verification',
                route('admin.instructors.index', ['verification' => 'pending']),
                'Review Credentials',
                'shield',
                false
            );
        }

        // 2. Unread contact messages (Support)
        $unreadMessages = ContactMessage::where('is_read', false)->latest()->take(3)->get();
        foreach ($unreadMessages as $msg) {
            self::send(
                $admin,
                'Contact Inquiry: '.($msg->subject ?: 'Storefront Inquiry'),
                'From '.$msg->name.' ('.$msg->email.'): "'.Str::limit($msg->message, 80).'"',
                'support',
                route('admin.messages.index'),
                'Open Messages',
                'support',
                false
            );
        }

        // 3. Support Tickets (if any exist)
        try {
            $tickets = SupportTicket::latest()->take(2)->get();
            foreach ($tickets as $ticket) {
                self::send(
                    $admin,
                    'Support Ticket #'.$ticket->id.': '.$ticket->subject,
                    'Priority: '.ucfirst($ticket->priority ?? 'Normal').' · Status: '.ucfirst($ticket->status ?? 'Open'),
                    'support',
                    route('admin.support.tickets'),
                    'View Ticket',
                    'support',
                    false
                );
            }
        } catch (Throwable) {
            // Support tickets table might be optional
        }

        // 4. SMTP mail services status
        $host = Setting::get('mail_host', 'sandbox.smtp.mailtrap.io');
        self::send(
            $admin,
            'SMTP Mail Service Connected',
            'Mail transport is configured via '.$host.'. Outbound transaction alerts and OTP verifications are active.',
            'smtp',
            route('admin.services.index'),
            'Mail Settings',
            'mail',
            false
        );

        // 5. Recent enrollments
        $enrollment = Enrollment::with(['student', 'course'])->latest()->first();
        if ($enrollment) {
            self::send(
                $admin,
                'New Course Enrollment',
                ($enrollment->student?->name ?? 'A student').' enrolled in "'.($enrollment->course?->title ?? 'Course').'".',
                'enrollment',
                route('admin.reports.index'),
                'View Reports',
                'user',
                false
            );
        }
    }
}
