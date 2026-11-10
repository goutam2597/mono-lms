<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    private Course $course;

    private Student $student;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $instructor = Instructor::create(['name' => 'I', 'email' => 'i@test.dev', 'password' => 'password', 'status' => 'active']);
        $this->course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Paid Course',
            'slug' => 'paid-course',
            'price' => 100.00,
            'level' => 'beginner',
            'type' => 'recorded',
            'is_published' => true,
        ]);
        $this->student = Student::create(['name' => 'Buyer', 'email' => 'buyer@test.dev', 'password' => 'password', 'status' => 'active']);
        $this->admin = Admin::create(['name' => 'A', 'email' => 'a@test.dev', 'password' => 'password', 'status' => 'active']);
    }

    private function startPurchase(?string $method = 'offline', ?string $coupon = null)
    {
        return $this->actingAs($this->student, 'student')
            ->post(route('checkout.start', $this->course), array_filter([
                'payment_method' => $method,
                'coupon_code' => $coupon,
            ]));
    }

    public function test_paid_course_checkout_creates_order_with_60_minute_deadline(): void
    {
        $this->startPurchase();

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame('offline', $order->payment_method);
        $this->assertSame('100.00', $order->amount);
        $this->assertNotNull($order->payment_deadline);
        $this->assertTrue($order->payment_deadline->gt(now()));
        $this->assertTrue($order->payment_deadline->diffInMinutes(now()) <= 61);

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_checkout_reuses_pending_order_instead_of_duplicating(): void
    {
        $this->startPurchase();
        $this->startPurchase('stripe')->assertSessionHasNoErrors();

        $this->assertDatabaseCount('orders', 1);
        $this->assertSame('stripe', Order::first()->payment_method);
    }

    public function test_offline_reference_submission_awaits_admin_verification(): void
    {
        $this->startPurchase();
        $order = Order::first();

        $this->actingAs($this->student, 'student')
            ->post(route('checkout.pay.initiate', [$order, 'offline']), ['payment_reference' => 'TRX12345'])
            ->assertRedirect(route('student.orders.show', $order));

        $order->refresh();
        $this->assertSame('TRX12345', $order->payment_reference);
        $this->assertSame('pending', $order->payment_status);
        $this->assertTrue($order->isAwaitingVerification());

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_admin_approval_activates_enrollment_and_coupon(): void
    {
        Coupon::create([
            'code' => 'HALF50', 'title' => 'Half off', 'discount_type' => 'percent',
            'discount_value' => 50, 'is_active' => true,
        ]);

        $this->startPurchase('offline', 'half50');
        $order = Order::first();
        $this->assertSame('50.00', $order->amount);

        // Submit the transaction reference first (offline flow).
        $this->actingAs($this->student, 'student')
            ->post(route('checkout.pay.initiate', [$order, 'offline']), ['payment_reference' => 'TRXABC']);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.orders.verify', $order), ['action' => 'approve'])
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('paid', $order->payment_status);
        $this->assertNotNull($order->paid_at);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('coupons', ['code' => 'HALF50', 'used_count' => 1]);
    }

    public function test_admin_rejection_marks_order_failed(): void
    {
        $this->startPurchase();
        $order = Order::first();
        $order->update(['payment_reference' => 'TRXBAD']);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.orders.verify', $order), ['action' => 'reject', 'reason' => 'Fake receipt'])
            ->assertSessionHas('success');

        $this->assertSame('failed', $order->refresh()->payment_status);
        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_expiry_command_cancels_unpaid_reservation(): void
    {
        $this->startPurchase();
        Order::first()->update(['payment_deadline' => now()->subMinutes(61)]);

        Artisan::call('orders:cancel-expired');

        $this->assertSame('cancelled', Order::first()->payment_status);
        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_order_with_submitted_reference_survives_expiry_command(): void
    {
        $this->startPurchase();
        $order = Order::first();
        $order->update([
            'payment_reference' => 'TRXKEEP',
            'payment_deadline' => now()->subMinutes(61),
        ]);

        Artisan::call('orders:cancel-expired');

        $this->assertSame('pending', $order->refresh()->payment_status);
    }

    public function test_disabled_gateway_blocks_checkout(): void
    {
        Setting::set('gateway_razorpay_enabled', '0');

        $this->startPurchase('razorpay')
            ->assertRedirect(route('courses.show', $this->course->slug))
            ->assertSessionHasErrors();

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_student_cannot_view_another_students_order(): void
    {
        $this->startPurchase();
        $order = Order::first();

        $other = Student::create(['name' => 'O', 'email' => 'o@test.dev', 'password' => 'password', 'status' => 'active']);

        $this->actingAs($other, 'student')
            ->get(route('student.orders.show', $order))
            ->assertForbidden();
    }

    public function test_student_orders_page_lists_orders(): void
    {
        $this->startPurchase();

        $this->actingAs($this->student, 'student')
            ->get(route('student.orders'))
            ->assertOk()
            ->assertSee(Order::first()->order_number);
    }

    public function test_already_enrolled_student_skips_purchase(): void
    {
        Enrollment::create(['student_id' => $this->student->id, 'course_id' => $this->course->id, 'status' => 'active']);

        $this->startPurchase()
            ->assertRedirect(route('courses.show', $this->course->slug))
            ->assertSessionHasErrors();

        $this->assertDatabaseCount('orders', 0);
    }
}
