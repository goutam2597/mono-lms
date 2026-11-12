<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AssignmentController extends Controller
{
    /**
     * Show assignment detail and submission form.
     */
    public function show(Course $course, Assignment $assignment): View|RedirectResponse
    {
        $student = auth('student')->user();

        $enrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if (! $enrolled && $course->isFree()) {
            Enrollment::firstOrCreate(
                ['student_id' => $student->id, 'course_id' => $course->id],
                ['status' => 'active', 'progress' => 0]
            );
            $enrolled = true;
        }

        abort_unless($enrolled, 403);
        abort_unless($assignment->course_id === $course->id, 404);

        $submission = $assignment->submissionFor($student);

        return view('pages.student.assignments.show', [
            'course' => $course,
            'assignment' => $assignment,
            'submission' => $submission,
        ]);
    }

    /**
     * Store or update a student assignment submission.
     */
    public function submit(Request $request, Course $course, Assignment $assignment): RedirectResponse
    {
        $student = auth('student')->user();

        $enrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        abort_unless($enrolled, 403);
        abort_unless($assignment->course_id === $course->id, 404);

        $request->validate([
            'submission_content' => ['nullable', 'string', 'max:10000'],
            'external_link' => ['nullable', 'url', 'max:500'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,zip,doc,docx,png,jpg,jpeg,txt'],
        ]);

        if (! $request->filled('submission_content') && ! $request->filled('external_link') && ! $request->hasFile('file')) {
            return back()->withErrors(['submission_content' => 'Please provide written notes, a project URL, or an attached file.']);
        }

        $existing = $assignment->submissionFor($student);

        $filePath = $existing?->file_path;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        $newStatus = AssignmentSubmission::STATUS_SUBMITTED;
        if ($existing && in_array($existing->status, [AssignmentSubmission::STATUS_GRADED, AssignmentSubmission::STATUS_RESUBMISSION_REQUESTED])) {
            $newStatus = AssignmentSubmission::STATUS_RESUBMITTED;
        }

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
            ],
            [
                'submission_content' => $request->input('submission_content'),
                'external_link' => $request->input('external_link'),
                'file_path' => $filePath,
                'status' => $newStatus,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Assignment submitted successfully! Your instructor will review and grade it.');
    }

    /**
     * Student request for resubmission / retest.
     */
    public function requestResubmission(Request $request, Course $course, Assignment $assignment): RedirectResponse
    {
        $student = auth('student')->user();

        $submission = $assignment->submissionFor($student);
        abort_unless($submission, 404);
        abort_unless($assignment->allow_resubmission, 403);

        $request->validate([
            'resubmission_note' => ['required', 'string', 'max:1000'],
        ]);

        $submission->update([
            'status' => AssignmentSubmission::STATUS_RESUBMISSION_REQUESTED,
            'resubmission_note' => $request->input('resubmission_note'),
        ]);

        return back()->with('success', 'Your resubmission request has been sent to the instructor.');
    }
}
