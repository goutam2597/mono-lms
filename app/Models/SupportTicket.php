<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ticket_number', 'user_name', 'user_email', 'subject', 'priority', 'status', 'message', 'reply', 'replied_at'])]
class SupportTicket extends Model
{
    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function findUser()
    {
        return Student::where('email', $this->user_email)->first()
            ?: Instructor::where('email', $this->user_email)->first();
    }
}
