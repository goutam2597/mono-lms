<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id', 'title', 'description', 'total_points', 'pass_points',
    'due_date', 'allow_resubmission', 'status', 'sort_order',
])]
class Assignment extends Model
{
    public const STATUS_PUBLISHED = 'published';

    public const STATUS_DRAFT = 'draft';

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'total_points' => 'integer',
            'pass_points' => 'integer',
            'allow_resubmission' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function isPastDue(): bool
    {
        return $this->due_date !== null && $this->due_date->isPast();
    }

    public function submissionFor(?Student $student): ?AssignmentSubmission
    {
        if (! $student) {
            return null;
        }

        return $this->submissions()->where('student_id', $student->id)->first();
    }
}
