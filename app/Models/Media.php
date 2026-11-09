<?php

namespace App\Models;

use App\Support\PublicMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'disk',
        'path',
        'name',
        'mime',
        'size',
        'width',
        'height',
        'alt',
        'admin_id',
        'instructor_id',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function url(): string
    {
        return PublicMedia::url($this->path);
    }

    protected static function booted(): void
    {
        static::deleting(function (self $media) {
            try {
                Storage::disk($media->disk)->delete($media->path);
            } catch (\Throwable $e) {
                // ignore — file may already be gone
            }
        });
    }
}
