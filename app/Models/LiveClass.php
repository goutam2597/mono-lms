<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id', 'title', 'description', 'starts_at', 'duration_minutes',
    'meeting_platform', 'class_type', 'meeting_url', 'recording_url', 'status', 'sort_order',
])]
class LiveClass extends Model
{
    public const PLATFORMS = ['zoom', 'google_meet', 'jitsi', 'other'];

    public const STATUSES = ['scheduled', 'live', 'completed', 'cancelled'];

    public const TYPE_MAIN = 'main';

    public const TYPE_CONCEPTUAL = 'conceptual';

    public const TYPE_SCRUM = 'scrum';

    public const TYPES = [self::TYPE_MAIN, self::TYPE_CONCEPTUAL, self::TYPE_SCRUM];

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

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)->orderBy('sort_order');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(LiveClassAttendance::class);
    }

    public function attendedBy(?Student $student): bool
    {
        if (! $student) {
            return false;
        }

        return $this->attendances()->where('student_id', $student->id)->exists();
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

    public function classTypeLabel(): string
    {
        return match ($this->class_type) {
            self::TYPE_CONCEPTUAL => 'Conceptual / Doubt Clearing',
            self::TYPE_SCRUM => 'Scrum & Progress Review',
            default => 'Live Main Class',
        };
    }

    public function classTypeBadgeClasses(): string
    {
        return match ($this->class_type) {
            self::TYPE_CONCEPTUAL => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 border-purple-200 dark:border-purple-800',
            self::TYPE_SCRUM => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border-amber-200 dark:border-amber-800',
            default => 'bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300 border-primary-200 dark:border-primary-800',
        };
    }
}
