<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth('student')->user();

        $enrollments = $student->enrollments()->with('course')->latest()->get();

        return view('pages.student.dashboard', [
            'enrollments' => $enrollments,
            'stats' => [
                'enrolled' => $enrollments->count(),
                'in_progress' => $enrollments->where('status', 'active')->count(),
                'completed' => $enrollments->where('status', 'completed')->count(),
                'certificates' => $enrollments->where('status', 'completed')->count(),
            ],
        ]);
    }
}
