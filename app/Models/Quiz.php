<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id', 'live_class_id', 'title', 'description',
    'time_limit_minutes', 'passing_percentage', 'max_attempts', 'retake_delay_minutes',
    'is_published', 'sort_order', 'title_translations', 'description_translations',
])]
class Quiz extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'time_limit_minutes' => 'integer',
            'passing_percentage' => 'integer',
            'max_attempts' => 'integer',
            'retake_delay_minutes' => 'integer',
            'sort_order' => 'integer',
            'title_translations' => 'array',
            'description_translations' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function liveClass(): BelongsTo
    {
        return $this->belongsTo(LiveClass::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class)->latest();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function isLiveQuiz(): bool
    {
        return $this->live_class_id !== null;
    }

    public function latestAttemptFor(?Student $student = null): ?QuizAttempt
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return null;
        }

        return $this->attempts()->where('student_id', $student->id)->latest('id')->first();
    }

    public function bestAttemptFor(?Student $student = null): ?QuizAttempt
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return null;
        }

        return $this->attempts()->where('student_id', $student->id)->orderByDesc('score_percentage')->first();
    }

    public function hasPassed(?Student $student = null): bool
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return false;
        }

        return $this->attempts()->where('student_id', $student->id)->where('is_passed', true)->exists();
    }

    public function attemptsCount(?Student $student = null): int
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return 0;
        }

        return $this->attempts()->where('student_id', $student->id)->count();
    }

    public function retakeDelayFormatted(): string
    {
        if ($this->retake_delay_minutes <= 0) {
            return 'Immediate';
        }

        if ($this->retake_delay_minutes >= 1440 && $this->retake_delay_minutes % 1440 === 0) {
            $days = (int) ($this->retake_delay_minutes / 1440);

            return $days.' '.($days === 1 ? 'day' : 'days');
        }

        if ($this->retake_delay_minutes >= 60 && $this->retake_delay_minutes % 60 === 0) {
            $hours = (int) ($this->retake_delay_minutes / 60);

            return $hours.' '.($hours === 1 ? 'hour' : 'hours');
        }

        if ($this->retake_delay_minutes >= 60) {
            $hours = floor($this->retake_delay_minutes / 60);
            $mins = $this->retake_delay_minutes % 60;

            return $hours.'h '.$mins.'m';
        }

        return $this->retake_delay_minutes.' mins';
    }

    public function latestCompletedAttemptFor(?Student $student = null): ?QuizAttempt
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return null;
        }

        return $this->attempts()
            ->where('student_id', $student->id)
            ->whereNotNull('completed_at')
            ->latest('id')
            ->first();
    }

    public function retakeAvailableAt(?Student $student = null): ?Carbon
    {
        if ($this->retake_delay_minutes <= 0) {
            return null;
        }

        $latestCompleted = $this->latestCompletedAttemptFor($student);
        if (! $latestCompleted || ! $latestCompleted->completed_at) {
            return null;
        }

        return $latestCompleted->completed_at->copy()->addMinutes($this->retake_delay_minutes);
    }

    public function isRetakeLocked(?Student $student = null): bool
    {
        $availableAt = $this->retakeAvailableAt($student);
        if (! $availableAt) {
            return false;
        }

        return now()->lt($availableAt);
    }

    public function retakeCooldownRemainingSeconds(?Student $student = null): int
    {
        $availableAt = $this->retakeAvailableAt($student);
        if (! $availableAt) {
            return 0;
        }

        return max(0, $availableAt->diffInSeconds(now()));
    }

    public function retakeCooldownRemainingHuman(?Student $student = null): ?string
    {
        $availableAt = $this->retakeAvailableAt($student);
        if (! $availableAt || now()->gte($availableAt)) {
            return null;
        }

        $totalSeconds = $availableAt->diffInSeconds(now());
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        if ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        }

        return "{$seconds}s";
    }

    public function canAttempt(?Student $student = null): bool
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return false;
        }

        if ($this->max_attempts > 0 && $this->attemptsCount($student) >= $this->max_attempts) {
            return false;
        }

        if ($this->isRetakeLocked($student)) {
            return false;
        }

        return true;
    }

    public function attemptsRemaining(?Student $student = null): int
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return 0;
        }

        if ($this->max_attempts === 0) {
            return 999;
        }

        return max(0, $this->max_attempts - $this->attemptsCount($student));
    }
}
