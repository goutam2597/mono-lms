<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $courseIds = auth('instructor')->user()->courses()->pluck('id');

        return view('pages.instructor.reviews', [
            'reviews' => Review::with(['student', 'course'])
                ->whereIn('course_id', $courseIds)
                ->where('status', 'approved')
                ->latest()
                ->paginate(15),
        ]);
    }
}
