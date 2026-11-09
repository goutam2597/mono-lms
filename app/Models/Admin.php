<?php

namespace App\Models;

use App\Notifications\PanelResetPassword;
use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable([
    'role_id', 'name', 'email', 'password', 'phone', 'avatar',
    'locale', 'is_super_admin', 'status',
])]
#[Hidden(['password', 'remember_token'])]
class Admin extends Authenticatable
{
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar ? PublicMedia::url($this->avatar) : null;
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PanelResetPassword($token, __('emails.panels.admin'), 'admin'));
    }

    /**
     * Same contract as mono_market's Admin::hasPermission(): the super admin
     * bypasses everything, everyone else inherits their role's permissions.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->role?->permissions()->where('slug', $permission)->exists() ?? false;
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * All permission slugs granted through the admin's role.
     */
    public function allPermissions(): Collection
    {
        return $this->role?->permissions ?? collect();
    }
}
