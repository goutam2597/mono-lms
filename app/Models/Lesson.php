<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id', 'title', 'duration_minutes', 'video_url', 'video_source', 'video_path', 'sort_order',
])]
class Lesson extends Model
{
    public const SOURCE_YOUTUBE = 'youtube';

    public const SOURCE_UPLOAD = 'upload';

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
