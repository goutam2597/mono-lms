<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseReviewController extends Controller
{
    /**
     * Submit or update a course review from an enrolled student.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $student = auth('student')->user();

        if (! $student) {
            return redirect()->route('login')->with('error', 'Please log in to submit a review.');
        }

        $isEnrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        if (! $isEnrolled) {
            return back()->with('error', 'You must be enrolled in this course to leave a review.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $existing = Review::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        $review = Review::updateOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course->id,
            ],
            [
                'rating' => (int) $validated['rating'],
                'comment' => ! empty($validated['comment']) ? trim($validated['comment']) : null,
                'status' => 'pending', // Submits for admin moderation
            ]
        );

        // Notify admins of new or updated review awaiting moderation
        NotificationService::notifyAdmins(
            title: $existing ? 'Course Review Updated (Pending)' : 'New Course Review Pending Moderation',
            message: "{$student->name} submitted a {$review->rating}★ review for \"{$course->title}\".",
            category: 'review',
            actionUrl: route('admin.reviews.index', ['status' => 'pending']),
            actionLabel: 'Moderate Review',
            icon: 'star',
            sendMail: false,
            metadata: ['review_id' => $review->id, 'course_id' => $course->id]
        );

        // Notify the course instructor
        if ($course->instructor) {
            NotificationService::send(
                $course->instructor,
                title: 'New Student Feedback Submitted',
                message: "{$student->name} reviewed \"{$course->title}\" ({$review->rating}★). It is currently queued for administrator approval.",
                category: 'review',
                actionUrl: route('instructor.reviews'),
                actionLabel: 'View Reviews',
                icon: 'star',
                sendMail: false,
                metadata: ['review_id' => $review->id, 'course_id' => $course->id]
            );
        }

        $msg = $existing
            ? 'Your review has been updated and sent for administrator moderation.'
            : 'Thank you for your feedback! Your review has been submitted and is pending administrator moderation.';

        return back()->with('success', $msg);
    }

    /**
     * Delete the student's review for this course.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $student = auth('student')->user();

        if (! $student) {
            return redirect()->route('login');
        }

        $review = Review::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($review) {
            $review->delete();

            return back()->with('success', 'Your review has been removed.');
        }

        return back();
    }
}
