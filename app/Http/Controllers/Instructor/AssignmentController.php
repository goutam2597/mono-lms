<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AssignmentController extends Controller
{
    /**
     * List assignments and submissions overview for a course.
     */
    public function index(Course $course): View
    {
        $this->authorizeCourse($course);

        $assignments = $course->assignments()
            ->withCount([
                'submissions as total_submissions',
                'submissions as graded_submissions' => fn ($q) => $q->where('status', AssignmentSubmission::STATUS_GRADED),
                'submissions as pending_submissions' => fn ($q) => $q->whereIn('status', [
                    AssignmentSubmission::STATUS_SUBMITTED,
                    AssignmentSubmission::STATUS_RESUBMITTED,
                    AssignmentSubmission::STATUS_RESUBMISSION_REQUESTED,
                ]),
            ])
            ->get();

        $liveClasses = $course->liveClasses()->withCount('attendances')->get();

        return view('pages.instructor.assignments.index', [
            'course' => $course,
            'assignments' => $assignments,
            'liveClasses' => $liveClasses,
        ]);
    }

    /**
     * Store a new assignment.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'total_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'pass_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'due_date' => ['nullable', 'date'],
            'allow_resubmission' => ['nullable', 'boolean'],
            'status' => ['required', 'string', 'in:published,draft'],
        ]);

        $validated['course_id'] = $course->id;
        $validated['allow_resubmission'] = $request->boolean('allow_resubmission', true);
        $validated['sort_order'] = $course->assignments()->count() + 1;

        Assignment::create($validated);

        return back()->with('success', 'Assignment created successfully.');
    }

    /**
     * Update an assignment.
     */
    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->authorizeCourse($assignment->course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'total_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'pass_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'due_date' => ['nullable', 'date'],
            'allow_resubmission' => ['nullable', 'boolean'],
            'status' => ['required', 'string', 'in:published,draft'],
        ]);

        $validated['allow_resubmission'] = $request->boolean('allow_resubmission', true);

        $assignment->update($validated);

        return back()->with('success', 'Assignment updated successfully.');
    }

    /**
     * Delete an assignment.
     */
    public function destroy(Assignment $assignment): RedirectResponse
    {
        $this->authorizeCourse($assignment->course);

        $assignment->delete();

        return back()->with('success', 'Assignment removed.');
    }

    /**
     * View all student submissions for an assignment.
     */
    public function submissions(Assignment $assignment): View
    {
        $this->authorizeCourse($assignment->course);

        $submissions = $assignment->submissions()
            ->with('student')
            ->orderByDesc('submitted_at')
            ->paginate(20);

        return view('pages.instructor.assignments.submissions', [
            'assignment' => $assignment,
            'course' => $assignment->course,
            'submissions' => $submissions,
        ]);
    }

    /**
     * Grade a student submission.
     */
    public function grade(Request $request, Assignment $assignment, AssignmentSubmission $submission): RedirectResponse
    {
        $this->authorizeCourse($assignment->course);
        abort_unless($submission->assignment_id === $assignment->id, 404);

        $validated = $request->validate([
            'score' => ['required', 'integer', 'min:0', 'max:'.$assignment->total_points],
            'instructor_feedback' => ['nullable', 'string', 'max:5000'],
            'action' => ['nullable', 'string', 'in:grade,request_resubmission'],
        ]);

        $status = ($request->input('action') === 'request_resubmission')
            ? AssignmentSubmission::STATUS_RESUBMISSION_REQUESTED
            : AssignmentSubmission::STATUS_GRADED;

        $submission->update([
            'score' => (int) $validated['score'],
            'instructor_feedback' => $validated['instructor_feedback'] ?? null,
            'status' => $status,
            'graded_by' => auth('instructor')->id(),
            'graded_by_type' => 'instructor',
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Submission graded successfully.');
    }

    private function authorizeCourse(Course $course): void
    {
        $instructorId = auth('instructor')->id();
        abort_unless($instructorId && (int) $course->instructor_id === (int) $instructorId, 403);
    }
}
