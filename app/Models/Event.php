<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'instructor_id', 'start_time', 'duration_minutes', 'meeting_link', 'capacity', 'is_published'])]
class Event extends Model
{
    protected $casts = [
        'start_time' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }
}
