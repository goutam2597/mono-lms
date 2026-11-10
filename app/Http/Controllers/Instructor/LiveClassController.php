<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LiveClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LiveClassController extends Controller
{
    /** Store a new live class for one of the instructor's live/combined courses. */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($course);

        $data = $this->validated($request);
        $data['course_id'] = $course->id;
        $data['sort_order'] = $course->liveClasses()->count() + 1;

        LiveClass::create($data);

        return back()->with('success', 'Live class "'.$data['title'].'" scheduled.');
    }

    public function update(Request $request, LiveClass $liveClass): RedirectResponse
    {
        $this->authorizeCourse($liveClass->course);

        $liveClass->update($this->validated($request));

        return back()->with('success', 'Live class updated.');
    }

    public function destroy(LiveClass $liveClass): RedirectResponse
    {
        $this->authorizeCourse($liveClass->course);

        $liveClass->delete();

        return back()->with('success', 'Live class removed.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'starts_at' => ['required', 'date', 'after:yesterday'],
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:600'],
            'meeting_platform' => ['required', 'string', 'in:zoom,google_meet,jitsi,other'],
            'meeting_url' => ['required', 'url', 'max:500'],
            'recording_url' => ['nullable', 'url', 'max:500'],
            'status' => ['nullable', 'string', 'in:scheduled,live,completed,cancelled'],
        ]);
    }

    private function authorizeCourse(Course $course): void
    {
        abort_unless($course->instructor_id === auth('instructor')->id(), 403);
    }
}
