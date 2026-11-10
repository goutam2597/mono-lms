<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'course_id', 'title', 'description', 'starts_at', 'duration_minutes',
    'meeting_platform', 'meeting_url', 'recording_url', 'status', 'sort_order',
])]
class LiveClass extends Model
{
    public const PLATFORMS = ['zoom', 'google_meet', 'jitsi', 'other'];

    public const STATUSES = ['scheduled', 'live', 'completed', 'cancelled'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'scheduled' && $this->starts_at->isFuture();
    }

    public function platformLabel(): string
    {
        return match ($this->meeting_platform) {
            'google_meet' => 'Google Meet',
            'jitsi' => 'Jitsi Meet',
            'zoom' => 'Zoom',
            default => 'Live Meeting',
        };
    }
}
