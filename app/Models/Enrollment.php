<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['student_id', 'course_id', 'progress', 'status'])]
class Enrollment extends Model
{
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    /**
     * Recalculate progress from completed lessons and persist it.
     * Live classes do not count toward recorded-content progress.
     */
    public function recomputeProgress(): int
    {
        $lessonIds = $this->course->lessons()->pluck('id');

        if ($lessonIds->isEmpty()) {
            if ((int) $this->progress !== 0) {
                $this->update(['progress' => 0]);
            }

            return 0;
        }

        $completed = LessonCompletion::where('student_id', $this->student_id)
            ->whereIn('lesson_id', $lessonIds)
            ->count();

        $progress = (int) min(100, floor(($completed / $lessonIds->count()) * 100));

        if ((int) $this->progress !== $progress) {
            $this->update([
                'progress' => $progress,
                'status' => $progress >= 100 ? 'completed' : ($this->status === 'completed' ? 'active' : $this->status),
            ]);
        }

        return $progress;
    }
}
