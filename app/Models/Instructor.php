<?php

namespace App\Models;

use App\Notifications\PanelResetPassword;
use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable([
    'name', 'email', 'password', 'phone', 'bio', 'avatar',
    'designation', 'locale', 'status',
])]
#[Hidden(['password', 'remember_token'])]
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
        $this->notify(new PanelResetPassword($token, __('emails.panels.instructor'), 'instructor'));
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
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
