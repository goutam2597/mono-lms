<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Review;

class NotificationController extends Controller
{
    public function index()
    {
        $pendingVerifications = Instructor::where('verification_status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $unreadMessages = ContactMessage::where('is_read', false)
            ->latest()
            ->take(10)
            ->get();

        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $recentReviews = Review::with(['student', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending_verifications' => Instructor::where('verification_status', 'pending')->count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'today_enrollments' => Enrollment::whereDate('created_at', today())->count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
        ];

        return view('pages.admin.notifications', [
            'pendingVerifications' => $pendingVerifications,
            'unreadMessages' => $unreadMessages,
            'recentEnrollments' => $recentEnrollments,
            'recentReviews' => $recentReviews,
            'stats' => $stats,
        ]);
    }
}
