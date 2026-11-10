<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $rating = $request->query('rating');
        $search = $request->query('q');

        $query = Review::with(['student', 'course.instructor']);

        if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        if (in_array((int) $rating, [1, 2, 3, 4, 5], true)) {
            $query->where('rating', (int) $rating);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($s) => $s->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('course', fn ($c) => $c->where('title', 'like', "%{$search}%"));
            });
        }

        $allReviews = Review::all();
        $totalCount = $allReviews->count();
        $pendingCount = $allReviews->where('status', 'pending')->count();
        $approvedCount = $allReviews->where('status', 'approved')->count();
        $rejectedCount = $allReviews->where('status', 'rejected')->count();
        $avgRating = $approvedCount > 0 ? round($allReviews->where('status', 'approved')->avg('rating'), 1) : 0.0;

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('pages.admin.reviews', [
            'reviews' => $reviews,
            'counts' => [
                'all' => $totalCount,
                'pending' => $pendingCount,
                'approved' => $approvedCount,
                'rejected' => $rejectedCount,
                'avg_rating' => $avgRating,
            ],
            'currentStatus' => $status,
            'currentRating' => $rating,
            'searchQuery' => $search,
        ]);
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => 'approved']);

        // Notify Student that review is approved and published
        if ($review->student) {
            NotificationService::send(
                $review->student,
                title: 'Review Approved & Published',
                message: "Your {$review->rating}★ review on \"".($review->course?->title ?? 'the course').'" has been approved and published.',
                category: 'review',
                actionUrl: $review->course ? route('courses.show', $review->course->slug).'#reviews' : null,
                actionLabel: 'View Course',
                icon: 'star',
                sendMail: false,
                metadata: ['review_id' => $review->id, 'course_id' => $review->course_id]
            );
        }

        // Notify Instructor of approved review
        if ($review->course?->instructor) {
            NotificationService::send(
                $review->course->instructor,
                title: 'New Student Review Published',
                message: ($review->student?->name ?? 'A student')." left a {$review->rating}★ rating on \"{$review->course->title}\".",
                category: 'review',
                actionUrl: route('instructor.reviews'),
                actionLabel: 'View Reviews',
                icon: 'star',
                sendMail: false,
                metadata: ['review_id' => $review->id, 'course_id' => $review->course_id]
            );
        }

        return back()->with('success', __('admin.reviews.approved'));
    }

    public function reject(Review $review): RedirectResponse
    {
        $review->update(['status' => 'rejected']);

        // Notify Student that review was rejected
        if ($review->student) {
            NotificationService::send(
                $review->student,
                title: 'Review Status Update',
                message: 'Your review on "'.($review->course?->title ?? 'the course').'" was not approved by moderation.',
                category: 'review',
                actionUrl: $review->course ? route('courses.show', $review->course->slug).'#reviews' : null,
                actionLabel: 'View Course',
                icon: 'info',
                sendMail: false,
                metadata: ['review_id' => $review->id, 'course_id' => $review->course_id]
            );
        }

        return back()->with('success', __('admin.reviews.rejected'));
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
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
                title: 'Official Response to Your Review',
                message: 'An official response was posted to your review on "'.($review->course?->title ?? 'the course').'".',
                category: 'review',
                actionUrl: $review->course ? route('courses.show', $review->course->slug).'#reviews' : null,
                actionLabel: 'Read Response',
                icon: 'chat',
                sendMail: false,
                metadata: ['review_id' => $review->id, 'course_id' => $review->course_id]
            );
        }

        return back()->with('success', 'Official response added to review.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', __('admin.reviews.deleted'));
    }

    public function bulkApprove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:reviews,id'],
        ]);

        $reviews = Review::whereIn('id', $data['ids'])->get();
        foreach ($reviews as $review) {
            $review->update(['status' => 'approved']);
            if ($review->student) {
                NotificationService::send(
                    $review->student,
                    title: 'Review Approved & Published',
                    message: "Your {$review->rating}★ review on \"".($review->course?->title ?? 'the course').'" has been approved.',
                    category: 'review',
                    actionUrl: $review->course ? route('courses.show', $review->course->slug).'#reviews' : null,
                    actionLabel: 'View Course',
                    icon: 'star',
                    sendMail: false
                );
            }
        }

        return back()->with('success', 'Approved '.count($reviews).' reviews successfully.');
    }

    public function bulkReject(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:reviews,id'],
        ]);

        $count = Review::whereIn('id', $data['ids'])->update(['status' => 'rejected']);

        return back()->with('success', "Marked {$count} reviews as rejected.");
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:reviews,id'],
        ]);

        $count = Review::whereIn('id', $data['ids'])->delete();

        return back()->with('success', "Deleted {$count} reviews successfully.");
    }
}
