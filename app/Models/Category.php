<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[\Illuminate\Database\Eloquent\Attributes\Fillable(['name', 'slug', 'icon', 'color', 'sort_order'])]
class Category extends Model
{
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
