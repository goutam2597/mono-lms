<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        return view('pages.instructor.courses', [
            'courses' => $this->myCourses()->withCount(['enrollments', 'lessons'])->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('pages.instructor.courses-form', [
            'course' => new Course,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);

        $this->myCourses()->create($data);

        return redirect()->route('instructor.courses.index')->with('success', __('instructor.courses.created'));
    }

    public function edit(Course $course)
    {
        $this->authorizeCourse($course);

        return view('pages.instructor.courses-form', [
            'course' => $course->load('lessons'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $data = $this->validated($request, $course);
        $data['slug'] = $this->uniqueSlug($data['title'], $course->id);

        $course->update($data);

        return redirect()->route('instructor.courses.index')->with('success', __('instructor.courses.updated'));
    }

    public function destroy(Course $course)
    {
        $this->authorizeCourse($course);

        $course->delete();

        return redirect()->route('instructor.courses.index')->with('success', __('instructor.courses.deleted'));
    }

    private function myCourses()
    {
        return auth('instructor')->user()->courses();
    }

    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== auth('instructor')->id()) {
            abort(403, __('instructor.courses.not_owner'));
        }
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:20000'],
            'image' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999'],
            'old_price' => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'course';
        $slug = $base;
        $i = 2;

        while (Course::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
