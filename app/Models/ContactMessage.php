<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'phone', 'subject', 'message', 'reply', 'replied_at', 'is_read'])]
class ContactMessage extends Model
{
    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function isReplied(): bool
    {
        return ! empty($this->reply) || ! is_null($this->replied_at);
    }
}
