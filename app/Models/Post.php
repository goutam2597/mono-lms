<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'admin_id', 'title', 'slug', 'excerpt', 'content', 'image', 'status', 'published_at',
    'title_translations', 'excerpt_translations', 'content_translations',
])]
#[Hidden([])]
class Post extends Model
{
    use HasTranslations;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'title_translations' => 'array',
            'excerpt_translations' => 'array',
            'content_translations' => 'array',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? PublicMedia::url($this->image) : null;
    }
}
