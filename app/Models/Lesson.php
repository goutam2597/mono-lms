<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id', 'title', 'duration_minutes', 'video_url', 'video_source', 'video_path', 'sort_order', 'is_free',
])]
class Lesson extends Model
{
    public const SOURCE_YOUTUBE = 'youtube';

    public const SOURCE_UPLOAD = 'upload';

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
            'duration_minutes' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    /** Whether the lesson has playable video content attached. */
    public function hasVideo(): bool
    {
        return $this->playerUrl() !== null;
    }

    /** Whether this lesson is unlocked as a free preview for prospective students. */
    public function isFree(): bool
    {
        return (bool) $this->is_free;
    }

    /**
     * Check whether this lesson is locked for a given student (or guest).
     * Watching videos requires the student to be logged in.
     * When logged in:
     * - If marked free preview, it is unlocked.
     * - If the course is free (price = 0), all lessons are unlocked.
     * - If the student is enrolled, it is unlocked.
     * Otherwise locked.
     */
    public function isLockedFor(?Student $student = null): bool
    {
        if (! $student) {
            return true;
        }

        if ($this->isFree()) {
            return false;
        }

        if ($this->course && $this->course->isFree()) {
            return false;
        }

        return ! $student->enrollments()->where('course_id', $this->course_id)->exists();
    }

    /** Whether the lesson should render as an uploaded file (HTML5) rather than a YouTube embed. */
    public function isUploadedVideo(): bool
    {
        return $this->video_source === self::SOURCE_UPLOAD && ! empty($this->video_path);
    }

    /**
     * Extract the YouTube video id from any supported YouTube URL shape.
     * (watch?v=, youtu.be/, /embed/, /shorts/, /live/)
     */
    public function youtubeId(): ?string
    {
        $url = trim((string) $this->video_url);

        if ($url === '') {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([A-Za-z0-9_-]{6,20})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /** The URL the student-facing player should use, or null when no video is attached. */
    public function playerUrl(): ?string
    {
        if ($this->isUploadedVideo()) {
            return PublicMedia::url($this->video_path);
        }

        $id = $this->youtubeId();

        return $id ? 'https://www.youtube.com/embed/'.$id.'?rel=0&modestbranding=1&enablejsapi=1' : null;
    }
}
