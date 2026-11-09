<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display student's saved courses.
     */
    public function index(Request $request): View
    {
        $student = auth('student')->user();

        $query = $student->wishlistCourses()
            ->with(['instructor', 'category'])
            ->withCount('lessons')
            ->latest('wishlists.created_at');

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('subtitle', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        $courses = $query->paginate(9)->withQueryString();

        return view('pages.student.saved-courses', [
            'student' => $student,
            'courses' => $courses,
        ]);
    }

    /**
     * Toggle a course in the student's wishlist.
     */
    public function toggle(Request $request, Course $course): JsonResponse|RedirectResponse
    {
        $student = auth('student')->user();

        if (! $student) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'authenticated' => false,
                    'redirect' => route('login'),
                    'message' => __('Please sign in to save courses.'),
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

        $existing = Wishlist::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
            $message = __('Course removed from saved courses.');
        } else {
            Wishlist::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
            ]);
            $saved = true;
            $message = __('Course saved to your list.');
        }

        $count = $student->wishlists()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'saved' => $saved,
                'count' => $count,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove a course from the student's wishlist.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $student = auth('student')->user();

        Wishlist::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->delete();

        return back()->with('success', __('Course removed from saved courses.'));
    }
}
