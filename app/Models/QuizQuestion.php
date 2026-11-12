<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'quiz_id', 'question_text', 'type', 'options', 'correct_answer',
    'explanation', 'points', 'sort_order', 'question_text_translations',
    'explanation_translations',
])]
class QuizQuestion extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'points' => 'integer',
            'sort_order' => 'integer',
            'question_text_translations' => 'array',
            'explanation_translations' => 'array',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function isCorrect(string|int|null $givenAnswer): bool
    {
        if ($givenAnswer === null) {
            return false;
        }

        return strtolower(trim((string) $givenAnswer)) === strtolower(trim((string) $this->correct_answer));
    }
}
