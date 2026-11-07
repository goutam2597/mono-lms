<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'instructor_id', 'category_id', 'title', 'slug', 'subtitle', 'description',
    'image', 'price', 'old_price', 'level', 'duration_minutes',
    'is_published', 'is_featured',
])]
class Course extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'float',
            'old_price' => 'float',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
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

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function averageRating(): float
    {
        return round((float) $this->reviews()->where('status', 'approved')->avg('rating'), 1);
    }

    public function reviewCount(): int
    {
        return (int) $this->reviews()->where('status', 'approved')->count();
    }

    public function enrolledCount(): int
    {
        return (int) $this->enrollments()->whereIn('status', ['active', 'completed'])->count();
    }
}
