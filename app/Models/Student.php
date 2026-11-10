<?php

namespace App\Models;

use App\Notifications\PanelResetPassword;
use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'phone', 'avatar', 'locale', 'status',
])]
#[Hidden(['password', 'remember_token'])]
class Student extends Authenticatable
{
    use Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PanelResetPassword($token, __('emails.panels.student'), 'student'));
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot(['progress', 'status'])
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->latest('id')
            ->first();
    }

    public function isSubscribed(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function wishlistCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'wishlists')
            ->withTimestamps();
    }

    public function hasWishlisted(int|Course $course): bool
    {
        $courseId = $course instanceof Course ? $course->id : $course;

        return $this->wishlists()->where('course_id', $courseId)->exists();
    }

    public function avatarUrl(?string $fallback = null): ?string
    {
        if ($this->avatar && trim((string) $this->avatar) !== '') {
            $url = PublicMedia::url($this->avatar);
            if ($url !== '') {
                return $url;
            }
        }

        return $fallback;
    }

    public function avatarOrDefault(): string
    {
        return $this->avatarUrl() ?: 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=344C3D&color=FFFFFF&size=200&bold=true';
    }
}
