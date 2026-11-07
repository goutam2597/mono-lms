<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Review;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'admins' => \App\Models\Admin::count(),
            'instructors' => Instructor::count(),
            'students' => Student::count(),
            'courses' => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'enrollments' => Enrollment::count(),
            'reviews' => Review::count(),
            'categories' => Category::count(),
        ];

        return view('pages.admin.dashboard', [
            'stats' => $stats,
            'recentStudents' => Student::latest()->take(5)->get(),
            'recentCourses' => Course::with(['instructor', 'category'])->latest()->take(5)->get(),
            'pendingReviews' => Review::with(['student', 'course'])->where('status', 'pending')->latest()->take(5)->get(),
        ]);
    }
}
