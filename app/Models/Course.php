<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[Fillable([
    'instructor_id', 'category_id', 'title', 'slug', 'subtitle', 'description',
    'title_translations', 'subtitle_translations', 'description_translations',
    'image', 'price', 'old_price', 'level', 'duration_minutes', 'type',
    'is_published', 'is_featured', 'is_trending',
])]
class Course extends Model
{
    use HasTranslations;

    public const TYPE_RECORDED = 'recorded';

    public const TYPE_LIVE = 'live';

    public const TYPE_COMBINED = 'combined';

    public const TYPES = [self::TYPE_RECORDED, self::TYPE_LIVE, self::TYPE_COMBINED];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'old_price' => 'float',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'title_translations' => 'array',
            'subtitle_translations' => 'array',
            'description_translations' => 'array',
        ];
    }

    public function isFree(): bool
    {
        return (float) $this->price <= 0;
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    public function liveClasses(): HasMany
    {
        return $this->hasMany(LiveClass::class)->orderBy('starts_at');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)->orderBy('sort_order');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->orderBy('sort_order');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isWishlistedBy(?Student $student = null): bool
    {
        $student ??= auth('student')->user();

        if (! $student) {
            return false;
        }

        return $this->wishlists()->where('student_id', $student->id)->exists();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function isLive(): bool
    {
        return $this->type === 'live';
    }

    public function isRecorded(): bool
    {
        return $this->type === 'recorded';
    }

    public function isCombined(): bool
    {
        return $this->type === 'combined';
    }

    /** Whether the course includes pre-recorded video lessons. */
    public function hasRecordedContent(): bool
    {
        return in_array($this->type, ['recorded', 'combined'], true);
    }

    /** Whether the course includes scheduled live classes. */
    public function hasLiveContent(): bool
    {
        return in_array($this->type, ['live', 'combined'], true);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'live' => 'Live',
            'combined' => 'Combined',
            default => 'Recorded',
        };
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where('is_trending', true);
    }

    public function averageRating(): float
    {
        return round((float) $this->reviews()->where('status', 'approved')->avg('rating'), 1);
    }

    public function reviewCount(): int
    {
        return (int) $this->reviews()->where('status', 'approved')->count();
    }

    /**
     * Get percentage and count distribution of approved ratings (1-5).
     *
     * @return array<int, array{count: int, percent: int}>
     */
    public function ratingBreakdown(): array
    {
        $reviews = $this->reviews()->where('status', 'approved')->pluck('rating');
        $total = $reviews->count();

        $breakdown = [
            5 => ['count' => 0, 'percent' => 0],
            4 => ['count' => 0, 'percent' => 0],
            3 => ['count' => 0, 'percent' => 0],
            2 => ['count' => 0, 'percent' => 0],
            1 => ['count' => 0, 'percent' => 0],
        ];

        if ($total === 0) {
            return $breakdown;
        }

        foreach ($reviews as $rating) {
            $r = (int) $rating;
            if (isset($breakdown[$r])) {
                $breakdown[$r]['count']++;
            }
        }

        foreach ($breakdown as $star => $data) {
            $breakdown[$star]['percent'] = (int) round(($data['count'] / $total) * 100);
        }

        return $breakdown;
    }

    public function enrolledCount(): int
    {
        return (int) $this->enrollments()->whereIn('status', ['active', 'completed'])->count();
    }

    public function imageUrl(?string $fallback = null): string
    {
        $fallback ??= 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&auto=format&fit=crop&q=80';

        return PublicMedia::url($this->image, $fallback);
    }

    /**
     * Compute the cohort leaderboard ranking for enrolled students.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function cohortLeaderboard(): Collection
    {
        $enrollments = $this->enrollments()
            ->whereIn('status', ['active', 'completed'])
            ->with('student')
            ->get();

        if ($enrollments->isEmpty()) {
            return collect();
        }

        $studentIds = $enrollments->pluck('student_id')->all();
        $assignmentIds = $this->assignments()->pluck('id')->all();
        $liveClassIds = $this->liveClasses()->pluck('id')->all();

        // Assignment points
        $submissionsByStudent = empty($assignmentIds) ? collect() : AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->whereIn('student_id', $studentIds)
            ->where('status', AssignmentSubmission::STATUS_GRADED)
            ->get()
            ->groupBy('student_id');

        // Quiz points (highest passed attempt score per quiz)
        $attemptsByStudent = QuizAttempt::where('course_id', $this->id)
            ->whereIn('student_id', $studentIds)
            ->where('is_passed', true)
            ->get()
            ->groupBy('student_id');

        // Live class attendance points (10 points per attended session)
        $attendancesByStudent = empty($liveClassIds) ? collect() : LiveClassAttendance::whereIn('live_class_id', $liveClassIds)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->groupBy('student_id');

        $rows = $enrollments->map(function ($enrollment) use ($submissionsByStudent, $attemptsByStudent, $attendancesByStudent) {
            $student = $enrollment->student;
            $sId = $enrollment->student_id;

            $subs = $submissionsByStudent->get($sId, collect());
            $assignmentScore = (int) $subs->sum('score');
            $assignmentsCompleted = (int) $subs->count();

            $attempts = $attemptsByStudent->get($sId, collect());
            // Unique by quiz_id taking highest score
            $quizScore = (int) $attempts->groupBy('quiz_id')->map(fn ($qAttempts) => $qAttempts->max('score'))->sum();
            $quizzesPassed = (int) $attempts->groupBy('quiz_id')->count();

            $att = $attendancesByStudent->get($sId, collect());
            $classesAttended = (int) $att->count();
            $attendanceScore = $classesAttended * 10;

            $totalScore = $assignmentScore + $quizScore + $attendanceScore;

            return [
                'student_id' => $sId,
                'student' => $student,
                'progress' => (int) $enrollment->progress,
                'assignment_points' => $assignmentScore,
                'assignments_completed' => $assignmentsCompleted,
                'quiz_points' => $quizScore,
                'quizzes_passed' => $quizzesPassed,
                'attendance_points' => $attendanceScore,
                'classes_attended' => $classesAttended,
                'total_score' => $totalScore,
            ];
        });

        // Sort by total score descending, then progress descending, then student id
        $sorted = $rows->sortByDesc('total_score')->values();

        return $sorted->map(function ($row, $index) {
            $row['rank'] = $index + 1;

            return $row;
        });
    }

    /**
     * Retrieve the model for a bound value (supports both numeric ID and slug).
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return Model
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? (is_numeric($value) ? 'id' : 'slug'), $value)->firstOrFail();
    }
}
