<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name', 'email', 'password', 'phone', 'bio', 'avatar',
    'designation', 'locale', 'status',
])]
#[\Illuminate\Database\Eloquent\Attributes\Hidden(['password', 'remember_token'])]
class Instructor extends Authenticatable
{
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function enrollments(): HasManyThrough
    {
        return $this->hasManyThrough(Enrollment::class, Course::class);
    }

    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Course::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\PanelResetPassword($token, __('emails.panels.instructor'), 'instructor'));
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
