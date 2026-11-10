<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $instructor = auth('instructor')->user();
        $courseIds = $instructor->courses()->pluck('id');

        $query = Review::with(['student', 'course'])
            ->whereIn('course_id', $courseIds)
            ->where('status', 'approved');

        if ($courseId = $request->query('course_id')) {
            $query->where('course_id', $courseId);
        }

        if ($rating = $request->query('rating')) {
            $query->where('rating', (int) $rating);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('course', fn ($c) => $c->where('title', 'like', "%{$search}%"));
            });
        }

        $allApproved = Review::whereIn('course_id', $courseIds)->where('status', 'approved')->get();
        $totalReviews = $allApproved->count();
        $avgRating = $totalReviews > 0 ? round($allApproved->avg('rating'), 1) : 0.0;
        $fiveStarCount = $allApproved->where('rating', 5)->count();

        return view('pages.instructor.reviews', [
            'reviews' => $query->latest()->paginate(15)->withQueryString(),
            'totalReviews' => $totalReviews,
            'avgRating' => $avgRating,
            'fiveStarCount' => $fiveStarCount,
            'courses' => $instructor->courses()->select('id', 'title')->get(),
        ]);
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        $instructor = auth('instructor')->user();

        abort_unless($review->course && $review->course->instructor_id === $instructor->id, 403);

        $validated = $request->validate([
            'instructor_reply' => ['required', 'string', 'max:2000'],
        ]);

        $review->update([
            'instructor_reply' => trim($validated['instructor_reply']),
            'replied_at' => now(),
        ]);

        if ($review->student) {
            NotificationService::send(
                $review->student,
                title: 'Instructor Replied to Your Review',
                message: "{$instructor->name} responded to your review on \"{$review->course->title}\".",
                category: 'review',
                actionUrl: route('courses.show', $review->course->slug).'#reviews',
                actionLabel: 'Read Response',
                icon: 'chat',
                sendMail: false,
                metadata: ['review_id' => $review->id, 'course_id' => $review->course_id]
            );
        }

        return back()->with('success', 'Your reply has been published to this review.');
    }
}
