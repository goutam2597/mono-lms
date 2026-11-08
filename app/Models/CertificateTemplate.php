<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'template_type', 'signature_title', 'signer_name', 'bg_color', 'border_color', 'is_default'])]
class CertificateTemplate extends Model
{
    protected $casts = [
        'is_default' => 'boolean',
    ];
}
