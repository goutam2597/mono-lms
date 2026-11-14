<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Order;
use App\Models\Student;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $paidOrders = Order::where('payment_status', 'paid')->latest()->take(8)->get();
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('amount');
        $totalEnrollments = Enrollment::count();
        $totalStudents = Student::count();
        $totalCourses = Course::count();

        $topCourses = Course::withCount('enrollments')
            ->with('instructor')
            ->orderByDesc('enrollments_count')
            ->take(6)
            ->get();

        $topInstructors = Instructor::withCount('courses')
            ->get()
            ->map(function ($inst) {
                // Real instructor earnings: 70% share of paid orders for their courses.
                $courses = Course::where('instructor_id', $inst->id)->pluck('id');
                $inst->earnings = (float) Order::whereIn('course_id', $courses)->where('payment_status', 'paid')->sum('amount') * 0.70;

                return $inst;
            })->sortByDesc('earnings')->take(5);

        return view('pages.admin.reports', [
            'stats' => [
                'revenue' => $totalRevenue,
                'enrollments' => $totalEnrollments,
                'students' => $totalStudents,
                'courses' => $totalCourses,
            ],
            'topCourses' => $topCourses,
            'topInstructors' => $topInstructors,
            'recentOrders' => $paidOrders,
        ]);
    }
}
