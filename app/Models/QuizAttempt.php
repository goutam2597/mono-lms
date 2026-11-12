<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'quiz_id', 'student_id', 'course_id', 'live_class_id',
    'attempt_number', 'total_questions', 'correct_answers_count',
    'score_percentage', 'is_passed', 'started_at', 'completed_at',
    'answers',
])]
class QuizAttempt extends Model
{
    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'total_questions' => 'integer',
            'correct_answers_count' => 'integer',
            'score_percentage' => 'float',
            'is_passed' => 'boolean',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'answers' => 'array',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function liveClass(): BelongsTo
    {
        return $this->belongsTo(LiveClass::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function formattedScore(): string
    {
        return number_format($this->score_percentage, 0).'%';
    }

    public function scoreSummary(): string
    {
        return $this->formattedScore().' ('.$this->correct_answers_count.'/'.$this->total_questions.')';
    }
}
