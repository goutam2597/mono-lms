<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['instructor', 'category'])
            ->withCount('enrollments')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->query('status') === 'published', fn ($q) => $q->where('is_published', true))
            ->when($request->query('status') === 'draft', fn ($q) => $q->where('is_published', false))
            ->when($request->query('status') === 'featured', fn ($q) => $q->where('is_featured', true))
            ->when($request->query('status') === 'trending', fn ($q) => $q->where('is_trending', true))
            ->latest()
            ->paginate(10);

        return view('pages.admin.courses', ['courses' => $courses]);
    }

    public function create()
    {
        return view('pages.admin.courses-form', [
            'course' => new Course,
            'categories' => Category::orderBy('name')->get(),
            'instructors' => Instructor::where('status', 'active')->where('verification_status', 'approved')->orderBy('name')->get(),
            'currentAdmin' => auth('admin')->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['instructor_id'] = $this->resolveInstructorId($request);

        Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', __('instructor.courses.created') ?: 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return view('pages.admin.courses-form', [
            'course' => $course->load('lessons'),
            'categories' => Category::orderBy('name')->get(),
            'instructors' => Instructor::where('status', 'active')->where('verification_status', 'approved')->orderBy('name')->get(),
            'currentAdmin' => auth('admin')->user(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = $this->validated($request, $course);
        $data['slug'] = $this->uniqueSlug($data['title'], $course->id);
        $data['instructor_id'] = $this->resolveInstructorId($request);

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('success', __('instructor.courses.updated') ?: 'Course updated successfully.');
    }

    public function storeLesson(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'video_url' => ['nullable', 'string', 'max:500'],
        ]);

        $data['sort_order'] = $course->lessons()->count() + 1;
        $course->lessons()->create($data);

        return back()->with('success', __('instructor.lessons.created') ?: 'Lesson added successfully.');
    }

    public function destroyLesson(Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return back()->with('success', __('instructor.lessons.deleted') ?: 'Lesson removed successfully.');
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

    public function toggleFeatured(Course $course): RedirectResponse
    {
        $course->update(['is_featured' => ! $course->is_featured]);

        return back()->with('success', $course->is_featured ? 'Course marked as Featured on Homepage.' : 'Course removed from Featured.');
    }

    public function toggleTrending(Course $course): RedirectResponse
    {
        $course->update(['is_trending' => ! $course->is_trending]);

        return back()->with('success', $course->is_trending ? 'Course marked as Most Trending on Homepage.' : 'Course removed from Trending.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', __('admin.courses.deleted'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $courses = Course::whereIn('id', $data['ids'])->get();
        $count = $courses->count();

        foreach ($courses as $course) {
            if ($course->thumbnail && ! str_starts_with($course->thumbnail, 'media/') && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->delete();
        }

        return redirect()->route('admin.courses.index')->with('success', "Deleted {$count} courses successfully.");
    }

    private function resolveInstructorId(Request $request): int
    {
        $val = $request->input('instructor_id');

        if ($val === 'self' || empty($val)) {
            $admin = auth('admin')->user();
            $instructor = Instructor::firstOrCreate(
                ['email' => $admin->email],
                [
                    'name' => $admin->name,
                    'password' => $admin->password,
                    'phone' => $admin->phone,
                    'avatar' => $admin->avatar,
                    'designation' => 'Administrator & Lead Instructor',
                    'bio' => 'Platform administrator and instructor.',
                    'status' => 'active',
                ]
            );

            if ($instructor->name !== $admin->name || $instructor->avatar !== $admin->avatar) {
                $instructor->update([
                    'name' => $admin->name,
                    'avatar' => $admin->avatar,
                    'status' => 'active',
                ]);
            }

            return $instructor->id;
        }

        return (int) $val;
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'instructor_id' => ['required'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:20000'],
            'image' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999'],
            'old_price' => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_trending'] = $request->boolean('is_trending');
        unset($data['instructor_id']);

        return $data;
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
