<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class LeaderboardController extends Controller
{
    /**
     * Show cohort leaderboard for the enrolled course.
     */
    public function show(Course $course): View|RedirectResponse
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

        $leaderboard = $course->cohortLeaderboard();
        $myRank = $leaderboard->firstWhere('student_id', $student->id);

        return view('pages.student.leaderboard', [
            'course' => $course,
            'leaderboard' => $leaderboard,
            'myRank' => $myRank,
        ]);
    }
}
