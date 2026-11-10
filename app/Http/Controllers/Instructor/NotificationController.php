<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $instructor = auth('instructor')->user();
        $courseIds = $instructor->courses()->pluck('id');

        // Verification status
        $verification = [
            'status' => $instructor->verification_status ?? 'unverified',
            'rejection_reason' => $instructor->rejection_reason,
        ];

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->take(10)
            ->get();

        // Recent reviews
        $recentReviews = Review::with(['student', 'course'])
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->take(10)
            ->get();

        // Stats
        $stats = [
            'total_students' => Enrollment::whereIn('course_id', $courseIds)->count(),
            'total_reviews' => Review::whereIn('course_id', $courseIds)->count(),
            'avg_rating' => round((float) DB::table('reviews')->whereIn('course_id', $courseIds)->where('status', 'approved')->avg('rating'), 1),
        ];

        return view('pages.instructor.notifications', [
            'verification' => $verification,
            'recentEnrollments' => $recentEnrollments,
            'recentReviews' => $recentReviews,
            'stats' => $stats,
        ]);
    }
}
