<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ticket_number', 'user_name', 'user_email', 'subject', 'priority', 'status', 'message', 'reply'])]
class SupportTicket extends Model {}
