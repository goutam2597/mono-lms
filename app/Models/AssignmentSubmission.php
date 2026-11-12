<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'assignment_id', 'student_id', 'submission_content', 'external_link',
    'file_path', 'score', 'status', 'instructor_feedback', 'resubmission_note',
    'graded_by', 'graded_by_type', 'graded_at', 'submitted_at',
])]
class AssignmentSubmission extends Model
{
    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_GRADED = 'graded';

    public const STATUS_RESUBMISSION_REQUESTED = 'resubmission_requested';

    public const STATUS_RESUBMITTED = 'resubmitted';

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'graded_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function isGraded(): bool
    {
        return $this->status === self::STATUS_GRADED && $this->score !== null;
    }

    public function isPassed(): bool
    {
        if (! $this->isGraded()) {
            return false;
        }

        return $this->score >= ($this->assignment?->pass_points ?? 0);
    }

    public function canRequestResubmission(): bool
    {
        return (bool) $this->assignment?->allow_resubmission && $this->status !== self::STATUS_RESUBMISSION_REQUESTED;
    }
}
