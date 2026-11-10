<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function grid(Request $request)
    {
        return view('pages.courses.grid', $this->catalog($request, 'grid'));
    }

    public function show(string $slug)
    {
        $course = Course::published()
            ->with(['instructor', 'category', 'lessons', 'liveClasses', 'reviews' => fn ($q) => $q->where('status', 'approved')->with('student')])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Course::published()
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->with(['instructor', 'category'])
            ->take(3)
            ->get();

        $student = auth('student')->user();
        $isEnrolled = $student
            && Enrollment::where('student_id', $student->id)->where('course_id', $course->id)->exists();

        $userReview = $student
            ? Review::where('student_id', $student->id)->where('course_id', $course->id)->first()
            : null;

        return view('pages.courses.detail', [
            'course' => $course,
            'related' => $related,
            'isEnrolled' => $isEnrolled,
            'userReview' => $userReview,
            'ratingBreakdown' => $course->ratingBreakdown(),
            'paymentMethods' => PaymentMethodService::available(),
            'upcomingClasses' => $course->liveClasses->filter(fn ($c) => $c->isUpcoming())->take(3),
        ]);
    }

    /**
     * Enroll the signed-in student into a published course.
     * Free courses enroll directly; paid courses go through the checkout flow.
     */
    public function enroll(Request $request, Course $course)
    {
        abort_unless($course->is_published, 404);

        $student = auth('student')->user();

        $existing = Enrollment::where('student_id', $student->id)->where('course_id', $course->id)->first();

        if ($existing) {
            return redirect()
                ->route('student.courses.learn', $course)
                ->with('info', __('courses.already_enrolled'));
        }

        // Paid courses must go through checkout (60-minute reservation +
        // payment method + verification). Free courses enroll directly.
        if ($course->price > 0) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->with('info', 'This course requires a purchase — choose a payment method below.');
        }

        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'progress' => 0,
        ]);

        return redirect()
            ->route('student.courses.learn', $course)
            ->with('success', __('courses.enrolled_success'));
    }

    private function catalog(Request $request, string $view): array
    {
        $courses = Course::published()
            ->with(['instructor', 'category'])
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', '%'.$request->string('q').'%')
                ->orWhere('subtitle', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->query('category'))))
            ->when(in_array($request->query('level'), ['beginner', 'intermediate', 'advanced']), fn ($q) => $q->where('level', $request->query('level')))
            ->when($request->query('sort') === 'price_low', fn ($q) => $q->orderBy('price'))
            ->when($request->query('sort') === 'price_high', fn ($q) => $q->orderByDesc('price'))
            ->when($request->query('sort') === 'popular' || $request->query('sort') === null, fn ($q) => $q->withCount('enrollments')->orderByDesc('enrollments_count'))
            ->when($request->query('sort') === 'latest', fn ($q) => $q->latest())
            ->paginate(9)
            ->withQueryString();

        return [
            'courses' => $courses,
            'categories' => Category::withCount(['courses' => fn ($q) => $q->published()])->orderBy('sort_order')->orderBy('name')->get(),
            'levelCounts' => Course::published()->selectRaw('level, COUNT(*) as total')->groupBy('level')->pluck('total', 'level'),
            'view' => $view,
        ];
    }
}
