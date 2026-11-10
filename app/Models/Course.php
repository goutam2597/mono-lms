<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'instructor_id', 'category_id', 'title', 'slug', 'subtitle', 'description',
    'image', 'price', 'old_price', 'level', 'duration_minutes',
    'is_published', 'is_featured', 'is_trending',
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
            'is_trending' => 'boolean',
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
