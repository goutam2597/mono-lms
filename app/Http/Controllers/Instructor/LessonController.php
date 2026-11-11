<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

        $lesson->update($this->validated($request, $lesson));

        return back()->with('success', __('instructor.lessons.updated'));
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorizeCourse($lesson->course);

        // Remove the uploaded video file along with the lesson.
        if ($lesson->video_source === Lesson::SOURCE_UPLOAD && $lesson->video_path) {
            Storage::disk('public')->delete($lesson->video_path);
        }

        $lesson->delete();

        return back()->with('success', __('instructor.lessons.deleted'));
    }

    private function authorizeCourse(Course $course): void
    {
        if ($course->instructor_id !== auth('instructor')->id()) {
            abort(403, __('instructor.courses.not_owner'));
        }
    }

    /**
     * Video source: YouTube URL or an uploaded file (two systems).
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Lesson $existing = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'video_source' => ['nullable', 'string', 'in:'.Lesson::SOURCE_YOUTUBE.','.Lesson::SOURCE_UPLOAD],
            'video_url' => ['nullable', 'string', 'max:500'],
            'video' => [
                'nullable',
                'file',
                'mimes:mp4,webm,ogg,mov,mkv',
                'max:512000', // 500 MB (php.ini upload_max_filesize must allow it)
            ],
            'is_free' => ['nullable', 'boolean'],
        ]);

        $data['is_free'] = $request->boolean('is_free');

        $data['video_source'] = match ($data['video_source'] ?? null) {
            Lesson::SOURCE_UPLOAD => Lesson::SOURCE_UPLOAD,
            Lesson::SOURCE_YOUTUBE => Lesson::SOURCE_YOUTUBE,
            default => filled($data['video_url'] ?? null) ? Lesson::SOURCE_YOUTUBE : null,
        };

        if ($data['video_source'] === Lesson::SOURCE_YOUTUBE) {
            if (blank($data['video_url'] ?? null)) {
                throw ValidationException::withMessages(['video_url' => 'Enter the YouTube URL for this lesson.']);
            }

            if (! (new Lesson($data))->youtubeId()) {
                throw ValidationException::withMessages(['video_url' => 'This is not a valid YouTube URL.']);
            }

            $data['video_path'] = null;
        }

        if ($data['video_source'] === Lesson::SOURCE_UPLOAD) {
            if (! $request->hasFile('video')) {
                // Editing without re-uploading: keep the stored file.
                if (blank($request->input('keep_video_path')) && blank($existing?->video_path)) {
                    throw ValidationException::withMessages(['video' => 'Choose a video file to upload.']);
                }

                $data['video_path'] = $request->input('keep_video_path') ?: $existing?->video_path;
            } else {
                // Replace a previously uploaded file.
                if ($existing && $existing->video_source === Lesson::SOURCE_UPLOAD && $existing->video_path) {
                    Storage::disk('public')->delete($existing->video_path);
                }

                $data['video_path'] = $request->file('video')->store('videos/lessons', 'public');
            }

            $data['video_url'] = null;
        }

        if ($data['video_source'] === null) {
            $data['video_url'] = null;
            $data['video_path'] = null;
        }

        return collect($data)->only(['title', 'duration_minutes', 'video_url', 'video_source', 'video_path', 'is_free'])->all();
    }
}
