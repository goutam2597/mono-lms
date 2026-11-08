<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'slug', 'description', 'price', 'billing_period',
    'features', 'is_active', 'is_popular', 'sort_order',
])]
#[Hidden([])]
class Plan extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'float',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
