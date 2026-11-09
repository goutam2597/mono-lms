<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Post;
use App\Models\Student;
use App\Services\HomePageContent;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(HomePageContent $homePageContent): View
    {
        $home = $homePageContent->all();

        return view('pages.home', [
            'home' => $home,
            'featuredCourses' => Course::published()->featured()->with(['instructor', 'category'])->latest()->take((int) ($home['featured_courses']['limit'] ?? 8))->get(),
            'categories' => Category::withCount(['courses' => fn ($q) => $q->published()])->orderBy('sort_order')->orderBy('name')->take((int) ($home['categories']['limit'] ?? 8))->get(),
            'topInstructors' => Instructor::where('status', 'active')->withCount('courses')->take((int) ($home['instructors']['limit'] ?? 4))->get(),
            'posts' => Post::published()->latest('published_at')->take((int) ($home['blog']['limit'] ?? 4))->get(),
            'stats' => [
                'enrollments' => Enrollment::count(),
                'students' => Student::count(),
                'courses' => Course::published()->count(),
                'instructors' => Instructor::where('status', 'active')->count(),
            ],
        ]);
    }
}
