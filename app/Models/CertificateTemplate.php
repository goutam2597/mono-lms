<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'template_type', 'signature_title', 'signer_name', 'bg_color', 'border_color', 'accent_color', 'subtitle', 'show_qr_code', 'is_default'])]
class CertificateTemplate extends Model
{
    protected $casts = [
        'is_default' => 'boolean',
        'show_qr_code' => 'boolean',
    ];

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first() ?: static::first();
    }
}
