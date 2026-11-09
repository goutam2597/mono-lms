<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth('student')->user();

        $enrollments = $student->enrollments()
            ->with(['course.instructor', 'course.category'])
            ->latest()
            ->get();

        $activeEnrollments = $enrollments->where('status', 'active');
        $completedEnrollments = $enrollments->where('status', 'completed');
        $continueCourse = $activeEnrollments->sortByDesc('updated_at')->first() ?? $enrollments->first();

        return view('pages.student.dashboard', [
            'student' => $student,
            'enrollments' => $enrollments,
            'continueCourse' => $continueCourse,
            'stats' => [
                'enrolled' => $enrollments->count(),
                'in_progress' => $activeEnrollments->count(),
                'completed' => $completedEnrollments->count(),
                'certificates' => $completedEnrollments->count(),
            ],
        ]);
    }
}
