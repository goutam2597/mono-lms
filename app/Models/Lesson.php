<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'course_id', 'title', 'duration_minutes', 'video_url', 'sort_order',
])]
class Lesson extends Model
{
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
