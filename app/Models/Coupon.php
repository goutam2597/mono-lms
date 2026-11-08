<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'title', 'discount_type', 'discount_value', 'max_uses', 'used_count', 'expires_at', 'is_active'])]
class Coupon extends Model
{
    protected $casts = [
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];
}
