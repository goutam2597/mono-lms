<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchSuggestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'courses' => [],
                'categories' => [],
                'instructors' => [],
                'see_all_url' => route('courses.index'),
            ]);
        }

        $currency = Setting::get('currency_symbol', '$');

        // 1. Courses matching title, subtitle, category or instructor name
        $courses = Course::published()
            ->with(['instructor', 'category'])
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('subtitle', 'like', "%{$q}%")
                    ->orWhereHas('category', fn ($cq) => $cq->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('instructor', fn ($iq) => $iq->where('name', 'like', "%{$q}%"));
            })
            ->take(5)
            ->get()
            ->map(function (Course $course) use ($currency) {
                $priceDisplay = ($course->price !== null && (float) $course->price > 0)
                    ? $currency.number_format((float) $course->price, 2)
                    : 'Free';

                $oldPriceDisplay = ($course->old_price && (float) $course->old_price > (float) $course->price)
                    ? $currency.number_format((float) $course->old_price, 2)
                    : null;

                return [
                    'id' => $course->id,
                    'name' => $course->title,
                    'url' => route('courses.show', $course->slug),
                    'image' => $course->imageUrl(),
                    'price' => $priceDisplay,
                    'old_price' => $oldPriceDisplay,
                    'category' => $course->category?->name ?? '',
                    'instructor' => $course->instructor?->name ?? '',
                ];
            });

        // 2. Categories matching name
        $categories = Category::where('name', 'like', "%{$q}%")
            ->withCount(['courses' => fn ($cq) => $cq->published()])
            ->take(4)
            ->get()
            ->map(function (Category $category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'count' => (int) $category->courses_count,
                    'url' => route('courses.index', ['category' => $category->slug]),
                ];
            });

        // 3. Instructors matching name or designation
        $instructors = Instructor::where('status', 'active')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('designation', 'like', "%{$q}%");
            })
            ->withCount(['courses' => fn ($cq) => $cq->published()])
            ->take(3)
            ->get()
            ->map(function (Instructor $instructor) {
                return [
                    'name' => $instructor->name,
                    'slug' => Str::slug($instructor->name),
                    'avatar' => $instructor->avatarUrl(),
                    'designation' => $instructor->designation ?: 'Instructor',
                    'count' => (int) $instructor->courses_count,
                    'url' => route('instructors.show', Str::slug($instructor->name)),
                ];
            });

        return response()->json([
            'courses' => $courses,
            'categories' => $categories,
            'instructors' => $instructors,
            'see_all_url' => route('courses.index', ['q' => $q]),
        ]);
    }
}
