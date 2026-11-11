<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\LiveClass;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LearnController extends Controller
{
    /** Lesson player for enrolled students (recorded lessons + live classes). */
    public function show(Request $request, Course $course): View|RedirectResponse
    {
        $student = auth('student')->user();

        $enrollment = Enrollment::where('student_id', $student->id)->where('course_id', $course->id)->first();

        // If the course is free (price = 0), auto-enroll the signed in student!
        if ((! $enrollment || $enrollment->status === 'cancelled') && $course->isFree()) {
            $enrollment = Enrollment::firstOrCreate(
                ['student_id' => $student->id, 'course_id' => $course->id],
                ['status' => 'active', 'progress' => 0]
            );
        }

        if (! $enrollment || $enrollment->status === 'cancelled') {
            // Allow watching a single unlocked free preview lesson on the learn page
            $requested = (int) $request->input('lesson');
            $targetLesson = $requested ? $course->lessons()->find($requested) : null;

            if ($targetLesson && $targetLesson->isFree()) {
                return view('pages.student.learn', [
                    'course' => $course,
                    'enrollment' => null,
                    'lessons' => $course->lessons,
                    'lesson' => $targetLesson,
                    'completedIds' => [],
                    'liveClasses' => collect(),
                    'upcomingClasses' => collect(),
                    'hasRecordedContent' => true,
                    'hasLiveContent' => false,
                    'isPreviewMode' => true,
                ]);
            }

            return redirect()
                ->route('courses.show', $course->slug)
                ->with('info', 'Please enroll in this course to access this lesson.');
        }

        $course = $enrollment->course()->with(['lessons', 'instructor', 'category'])->firstOrFail();
        $lessons = $course->lessons;
        $completedIds = LessonCompletion::where('student_id', $student->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->pluck('lesson_id')
            ->all();

        // Selected lesson: ?lesson= or the first uncompleted lesson.
        $lesson = null;
        if ($lessons->isNotEmpty()) {
            $requested = (int) $request->input('lesson');
            $lesson = $lessons->firstWhere('id', $requested)
                ?? $lessons->firstWhere(fn ($l) => ! in_array($l->id, $completedIds))
                ?? $lessons->first();
        }

        $liveClasses = $course->liveClasses;

        return view('pages.student.learn', [
            'course' => $course,
            'enrollment' => $enrollment,
            'lessons' => $lessons,
            'lesson' => $lesson,
            'completedIds' => $completedIds,
            'liveClasses' => $liveClasses,
            'upcomingClasses' => $liveClasses->filter(fn (LiveClass $c) => $c->isUpcoming())->values(),
            'hasRecordedContent' => $course->hasRecordedContent(),
            'hasLiveContent' => $course->hasLiveContent(),
        ]);
    }

    /** Mark a recorded lesson completed and recompute enrollment progress. */
    public function complete(Request $request, Lesson $lesson): RedirectResponse
    {
        $student = auth('student')->user();

        $enrollment = Enrollment::where('student_id', $student->id)->where('course_id', $lesson->course_id)->first();

        if ((! $enrollment || $enrollment->status === 'cancelled') && $lesson->course?->isFree()) {
            $enrollment = Enrollment::firstOrCreate(
                ['student_id' => $student->id, 'course_id' => $lesson->course_id],
                ['status' => 'active', 'progress' => 0]
            );
        }

        if (! $enrollment || $enrollment->status === 'cancelled') {
            abort(403);
        }

        LessonCompletion::firstOrCreate([
            'student_id' => $student->id,
            'lesson_id' => $lesson->id,
        ]);

        $progress = $enrollment->recomputeProgress();

        // Auto-issue the certificate at 100% (existing idempotent helper).
        if ($progress >= 100) {
            Certificate::issueFor($student, $lesson->course, $enrollment);
        }

        return back()->with('success', 'Lesson marked as completed.');
    }

    /** Log a live-class join click and redirect to the external meeting link. */
    public function join(Request $request, LiveClass $liveClass): RedirectResponse
    {
        $student = auth('student')->user();

        $enrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $liveClass->course_id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        abort_unless($enrolled, 403);

        return redirect()->away($liveClass->meeting_url);
    }
}
