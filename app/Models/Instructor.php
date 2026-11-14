<?php

namespace App\Models;

use App\Notifications\PanelResetPassword;
use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'phone', 'phone_e164', 'phone_country', 'bio', 'avatar',
    'designation', 'locale', 'status',
    'certification_file', 'national_id_file', 'verification_status',
    'verified_at', 'rejection_reason',
])]
#[Hidden(['password', 'remember_token'])]
class Instructor extends Authenticatable
{
    use Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'verified_at' => 'datetime',
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

    public function isVerified(): bool
    {
        return $this->verification_status === 'approved' && $this->status === 'active';
    }

    public function isPendingVerification(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isVerificationRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    public function canAddCourses(): bool
    {
        return $this->isVerified();
    }

    public function certificationUrl(): ?string
    {
        if ($this->certification_file && trim((string) $this->certification_file) !== '') {
            return PublicMedia::url($this->certification_file);
        }

        return null;
    }

    public function nationalIdUrl(): ?string
    {
        if ($this->national_id_file && trim((string) $this->national_id_file) !== '') {
            return PublicMedia::url($this->national_id_file);
        }

        return null;
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
