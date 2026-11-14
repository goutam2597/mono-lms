<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'code', 'phone_code', 'phone_example', 'is_active',
])]
class Country extends Model
{
    public function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /** Countries an end user may pick: enabled rows only. */
    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
