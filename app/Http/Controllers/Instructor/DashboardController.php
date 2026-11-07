<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $instructor = auth('instructor')->user();

        $courses = $instructor->courses();
        $courseIds = $courses->pluck('id');

        $stats = [
            'revenue' => (float) Enrollment::whereIn('course_id', $courseIds)
                ->join('courses', 'courses.id', '=', 'enrollments.course_id')
                ->sum('courses.price'),
            'students' => Enrollment::whereIn('course_id', $courseIds)->count(),
            'courses' => $courses->count(),
            'rating' => round((float) DB::table('reviews')->whereIn('course_id', $courseIds)->where('status', 'approved')->avg('rating'), 1),
        ];

        // Enrollments per month for the last 12 months (chart).
        // Grouped in PHP for cross-database compatibility (MySQL / SQLite tests).
        $monthly = Enrollment::whereIn('course_id', $courseIds)
            ->where('enrollments.created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->get(['created_at'])
            ->groupBy(fn ($e) => $e->created_at->format('Y-m'))
            ->map(fn ($group) => $group->count());

        $bars = collect(range(0, 11))->map(function ($i) use ($monthly) {
            $month = now()->subMonths(11 - $i)->format('Y-m');

            return [
                'label' => now()->subMonths(11 - $i)->translatedFormat('M'),
                'total' => (int) ($monthly[$month] ?? 0),
            ];
        });

        return view('pages.instructor.dashboard', [
            'stats' => $stats,
            'bars' => $bars,
            'topCourses' => $courses->withCount('enrollments')->orderByDesc('enrollments_count')->take(5)->get(),
            'recentEnrollments' => Enrollment::with(['student', 'course'])
                ->whereIn('course_id', $courseIds)
                ->latest()->take(5)->get(),
        ]);
    }
}
