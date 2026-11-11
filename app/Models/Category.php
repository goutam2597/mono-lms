<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'name_translations', 'slug', 'icon', 'image', 'color', 'sort_order'])]
class Category extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'name_translations' => 'array',
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function imageUrl(): ?string
    {
        return $this->image ? PublicMedia::url($this->image) : null;
    }
}
