<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $student = auth('student')->user();

        $enrollments = $student->enrollments()
            ->with([
                'course.instructor',
                'course.category',
                'course.reviews' => fn ($q) => $q->where('student_id', $student->id),
            ])
            ->when($request->query('filter') === 'active', fn ($q) => $q->where('status', 'active'))
            ->when($request->query('filter') === 'completed', fn ($q) => $q->where('status', 'completed'))
            ->latest()
            ->get();

        return view('pages.student.enrolled-courses', ['enrollments' => $enrollments]);
    }
}
