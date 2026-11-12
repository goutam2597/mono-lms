<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id', 'live_class_id', 'title', 'description',
    'time_limit_minutes', 'passing_percentage', 'max_attempts',
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

    public function canAttempt(?Student $student = null): bool
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return false;
        }

        if ($this->max_attempts === 0) {
            return true;
        }

        return $this->attemptsCount($student) < $this->max_attempts;
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
