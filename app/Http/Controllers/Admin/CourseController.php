<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['instructor', 'category'])
            ->withCount('enrollments')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->query('status') === 'published', fn ($q) => $q->where('is_published', true))
            ->when($request->query('status') === 'draft', fn ($q) => $q->where('is_published', false))
            ->latest()
            ->paginate(10);

        return view('pages.admin.courses', ['courses' => $courses]);
    }

    public function publish(Course $course)
    {
        $course->update(['is_published' => true]);

        return back()->with('success', __('admin.courses.published'));
    }

    public function unpublish(Course $course)
    {
        $course->update(['is_published' => false]);

        return back()->with('success', __('admin.courses.unpublished'));
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', __('admin.courses.deleted'));
    }
}
