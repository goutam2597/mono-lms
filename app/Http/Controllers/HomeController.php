<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Post;
use App\Models\Student;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'featuredCourses' => Course::published()->featured()->with(['instructor', 'category'])->latest()->take(8)->get(),
            'categories' => Category::withCount(['courses' => fn ($q) => $q->published()])->orderBy('sort_order')->orderBy('name')->get(),
            'topInstructors' => Instructor::where('status', 'active')->withCount('courses')->take(4)->get(),
            'posts' => Post::published()->latest('published_at')->take(4)->get(),
            'stats' => [
                'enrollments' => Enrollment::count(),
                'students' => Student::count(),
                'courses' => Course::published()->count(),
                'instructors' => Instructor::where('status', 'active')->count(),
            ],
        ]);
    }
}
