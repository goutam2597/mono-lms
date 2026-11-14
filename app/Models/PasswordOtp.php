<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'channel',
        'purpose',
        'otp',
        'reset_token',
        'verified',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'attempts' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
