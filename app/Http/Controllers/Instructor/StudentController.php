<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;

class StudentController extends Controller
{
    public function index()
    {
        $courseIds = auth('instructor')->user()->courses()->pluck('id');

        return view('pages.instructor.students', [
            'enrollments' => Enrollment::with(['student', 'course'])
                ->whereIn('course_id', $courseIds)
                ->latest()
                ->paginate(15),
        ]);
    }
}
