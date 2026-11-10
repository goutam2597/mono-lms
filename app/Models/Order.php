<?php

namespace App\Models;

use App\Services\PaymentMethodService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_number', 'student_id', 'course_id', 'plan_id', 'amount', 'currency',
    'payment_method', 'payment_status', 'payment_deadline', 'payment_attempts',
    'payment_reference', 'discount', 'coupon_code', 'paid_at',
    'enrollment_activated_at', 'val_id', 'notes',
])]
class Order extends Model
{
    public const PAYMENT_STATUSES = ['pending', 'paid', 'failed', 'refunded', 'cancelled'];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'payment_deadline' => 'datetime',
        'paid_at' => 'datetime',
        'enrollment_activated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /** Remaining payment seconds before the 60-minute reservation expires. */
    public function paymentTimeLeft(): int
    {
        if (! $this->payment_deadline) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($this->payment_deadline, false));
    }

    /** Whether the student can still open a payment page / pay for this order. */
    public function canPay(): bool
    {
        if (! in_array($this->payment_status, ['pending', 'failed'], true)) {
            return false;
        }

        // A submitted transaction reference moves the order into admin
        // verification, so the countdown no longer blocks a retry page.
        if ($this->payment_reference) {
            return true;
        }

        return $this->paymentTimeLeft() > 0;
    }

    /** Transaction ID submitted and awaiting admin approval. */
    public function isAwaitingVerification(): bool
    {
        return $this->payment_status === 'pending'
            && filled($this->payment_reference)
            && is_null($this->paid_at);
    }

    public function methodLabel(): string
    {
        return PaymentMethodService::label($this->payment_method);
    }

    public function statusTone(): string
    {
        return match ($this->payment_status) {
            'paid' => 'emerald',
            'failed' => 'rose',
            'refunded' => 'slate',
            'cancelled' => 'slate',
            default => 'amber',
        };
    }
}
