<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = auth('student')->user()->enrollments()
            ->with('course')
            ->when($request->query('filter') === 'active', fn ($q) => $q->where('status', 'active'))
            ->when($request->query('filter') === 'completed', fn ($q) => $q->where('status', 'completed'))
            ->latest()
            ->get();

        return view('pages.student.enrolled-courses', ['enrollments' => $enrollments]);
    }
}
