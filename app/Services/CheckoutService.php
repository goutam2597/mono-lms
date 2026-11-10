<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Order lifecycle for course purchases (mono_market pattern):
 * pending order with a 60-minute payment reservation -> online gateway
 * verification or offline transaction reference -> admin approval ->
 * enrollment activation. Unpaid reservations are cancelled after expiry.
 */
final class CheckoutService
{
    /** Reservation window for a new unpaid order. */
    public const RESERVATION_MINUTES = 60;

    /**
     * Create a pending order for a paid course and start the 60-minute window.
     */
    public function startPurchase(Student $student, Course $course, string $method, ?string $couponCode = null): Order
    {
        if (! $course->is_published) {
            throw ValidationException::withMessages(['course' => 'This course is not available for purchase.']);
        }

        if ($course->price <= 0) {
            throw ValidationException::withMessages(['course' => 'This course is free — enroll directly.']);
        }

        if (Enrollment::where('student_id', $student->id)->where('course_id', $course->id)->exists()) {
            throw ValidationException::withMessages(['course' => 'You are already enrolled in this course.']);
        }

        if (! PaymentMethodService::exists($method) || ! PaymentMethodService::enabled($method)) {
            throw ValidationException::withMessages(['method' => 'The selected payment method is not available.']);
        }

        $discount = 0.0;
        $appliedCoupon = null;

        if (filled($couponCode)) {
            $appliedCoupon = Coupon::where('code', strtoupper(trim($couponCode)))->first();

            if (! $appliedCoupon || ! $appliedCoupon->is_active) {
                throw ValidationException::withMessages(['coupon' => 'This coupon code is not valid.']);
            }

            if ($appliedCoupon->expires_at && $appliedCoupon->expires_at->isPast()) {
                throw ValidationException::withMessages(['coupon' => 'This coupon has expired.']);
            }

            if ($appliedCoupon->max_uses !== null && $appliedCoupon->used_count >= $appliedCoupon->max_uses) {
                throw ValidationException::withMessages(['coupon' => 'This coupon has reached its usage limit.']);
            }

            $discount = $appliedCoupon->discount_type === 'percent'
                ? round(((float) $course->price) * ((float) $appliedCoupon->discount_value) / 100, 2)
                : min((float) $appliedCoupon->discount_value, (float) $course->price);
        }

        // Reuse an existing pending order for the same course (method switch /
        // retry) instead of stacking duplicate reservations.
        $existing = Order::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('payment_status', 'pending')
            ->first();

        if ($existing && blank($existing->payment_reference)) {
            $existing->update([
                'payment_method' => $method,
                'amount' => max(0, round((float) $course->price - $discount, 2)),
                'discount' => $discount,
                'coupon_code' => $appliedCoupon?->code,
                'payment_deadline' => now()->addMinutes(self::RESERVATION_MINUTES),
            ]);

            return $existing;
        }

        /** @var Order $order */
        $order = DB::transaction(function () use ($student, $course, $method, $discount, $appliedCoupon) {
            return Order::create([
                'order_number' => $this->generateOrderNumber(),
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => max(0, round((float) $course->price - $discount, 2)),
                'currency' => Setting::get('currency_code', 'USD'),
                'payment_method' => $method,
                'payment_status' => 'pending',
                'payment_deadline' => now()->addMinutes(self::RESERVATION_MINUTES),
                'payment_attempts' => 0,
                'discount' => $discount,
                'coupon_code' => $appliedCoupon?->code,
                'notes' => 'Course purchase: '.$course->title,
            ]);
        });

        return $order;
    }

    /**
     * Offline methods: the student sends money externally and submits the
     * transaction reference. The order moves into admin verification and the
     * reservation countdown stops blocking a retry.
     */
    public function submitReference(Order $order, string $reference): Order
    {
        if (! $order->canPay()) {
            throw ValidationException::withMessages(['order' => 'This order can no longer be paid.']);
        }

        if (PaymentMethodService::isOnline($order->payment_method)) {
            throw ValidationException::withMessages(['order' => 'This payment method does not accept transaction references.']);
        }

        $order->update([
            'payment_reference' => strtoupper(trim($reference)),
            'payment_status' => 'pending',
        ]);

        NotificationService::notifyAdmins(
            'New payment awaiting verification',
            $order->student->name.' submitted a '.$order->methodLabel().' transaction ID ('.$order->payment_reference.') for "'.$order->course->title.'" ('.$order->order_number.').',
            category: 'order',
            actionUrl: route('admin.orders.show', $order),
            actionLabel: 'Review Payment',
        );

        return $order->refresh();
    }

    /**
     * Central "payment verified" transition (online callback or admin approval):
     * guarded against double payment / duplicate gateway references, then
     * activates the enrollment and notifies everyone.
     */
    public function markPaid(Order $order, ?string $valId = null): Order
    {
        DB::transaction(function () use ($order, $valId) {
            /** @var Order $locked */
            $locked = Order::query()->lockForUpdate()->findOrFail($order->id);

            if (filled($valId)) {
                $duplicate = Order::query()
                    ->where('val_id', $valId)
                    ->where('payment_status', 'paid')
                    ->whereKeyNot($locked->getKey())
                    ->lockForUpdate()
                    ->exists();

                if ($duplicate) {
                    throw ValidationException::withMessages(['val_id' => 'This payment has already been used for another order.']);
                }
            }

            if ($locked->payment_status !== 'paid') {
                $locked->update([
                    'payment_status' => 'paid',
                    'payment_deadline' => null,
                    'paid_at' => now(),
                    'val_id' => $valId ?: $locked->val_id,
                ]);
            }

            Enrollment::firstOrCreate(
                ['student_id' => $locked->student_id, 'course_id' => $locked->course_id],
                ['status' => 'active', 'progress' => 0],
            );

            if (! $locked->enrollment_activated_at) {
                $locked->update(['enrollment_activated_at' => now()]);
            }

            if (filled($locked->coupon_code)) {
                Coupon::where('code', $locked->coupon_code)->increment('used_count');
            }
        });

        $order->refresh();

        NotificationService::send(
            $order->student,
            'Enrollment activated',
            'Your payment for "'.$order->course->title.'" was confirmed. You can start learning now.',
            category: 'enrollment',
            actionUrl: route('student.courses.learn', $order->course),
            actionLabel: 'Start Learning',
            sendMail: true,
        );

        return $order;
    }

    /** Admin rejected a submitted payment (wrong amount, fake reference, ...). */
    public function rejectPayment(Order $order, ?string $reason = null): Order
    {
        if ($order->payment_status === 'paid') {
            throw ValidationException::withMessages(['payment_status' => 'A paid order cannot be marked as failed. Use a refund instead.']);
        }

        $order->update(['payment_status' => 'failed']);

        NotificationService::send(
            $order->student,
            'Payment could not be verified',
            'Your payment for "'.$order->course->title.'" ('.$order->order_number.') was rejected.'.($reason ? ' Reason: '.$reason : ''),
            category: 'order',
            actionUrl: route('student.orders.show', $order),
            actionLabel: 'View Order',
            sendMail: true,
        );

        return $order->refresh();
    }

    /**
     * Cancel unpaid orders whose 60-minute reservation expired without a
     * submitted transaction reference. Idempotent.
     */
    public function cancelExpired(): int
    {
        $expired = Order::query()
            ->where('payment_status', 'pending')
            ->whereNull('payment_reference')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<=', now())
            ->get();

        foreach ($expired as $order) {
            $order->update(['payment_status' => 'cancelled']);
        }

        return $expired->count();
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'LMS-'.now()->format('ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
