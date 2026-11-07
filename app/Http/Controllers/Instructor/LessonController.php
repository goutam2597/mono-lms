<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $data = $this->validated($request);
        $data['sort_order'] = $course->lessons()->count() + 1;

        $course->lessons()->create($data);

        return back()->with('success', __('instructor.lessons.created'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $this->authorizeCourse($lesson->course);

        $lesson->update($this->validated($request));

        return back()->with('success', __('instructor.lessons.updated'));
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorizeCourse($lesson->course);

        $lesson->delete();

        return back()->with('success', __('instructor.lessons.deleted'));
    }

    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== auth('instructor')->id()) {
            abort(403, __('instructor.courses.not_owner'));
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'video_url' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
