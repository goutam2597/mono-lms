<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiUsage extends Model
{
    protected $table = 'ai_usage';

    protected $fillable = [
        'date',
        'kind',
        'model',
        'vendor_id',
        'requests',
        'input_tokens',
        'output_tokens',
        'images',
    ];

    protected $casts = [
        'date' => 'date',
        'vendor_id' => 'integer',
    ];

    /**
     * Increment the daily aggregate for a kind+model pair. Never throws.
     *
     * @param  array{input?: int, output?: int}  $tokens
     */
    public static function track(string $kind, ?string $model, array $tokens = [], int $images = 0, ?int $vendorId = null): void
    {
        if (! $model) {
            return;
        }

        try {
            $row = static::firstOrCreate([
                'date' => now()->toDateString(),
                'kind' => $kind,
                'model' => $model,
                'vendor_id' => $vendorId,
            ]);

            $row->timestamps = false;
            $row->increment('requests');
            $row->increment('input_tokens', max(0, (int) ($tokens['input'] ?? 0)));
            $row->increment('output_tokens', max(0, (int) ($tokens['output'] ?? 0)));

            if ($images > 0) {
                $row->increment('images', $images);
            }
        } catch (\Throwable) {
            // Best-effort stats only.
        }
    }
}
