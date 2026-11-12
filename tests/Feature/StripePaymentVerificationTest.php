<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Student;
use App\Services\OnlinePaymentVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class StripePaymentVerificationTest extends TestCase
{
    use RefreshDatabase;

    private Course $course;

    private Student $student;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('gateway_stripe_enabled', '1');
        Setting::set('gateway_stripe_secret', 'sk_test_mock_secret');

        $instructor = Instructor::create([
            'name' => 'Instructor',
            'email' => 'instructor@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Stripe Test Course',
            'slug' => 'stripe-test-course',
            'price' => 100.00,
            'level' => 'beginner',
            'type' => 'recorded',
            'is_published' => true,
        ]);

        $this->student = Student::create([
            'name' => 'Stripe Student',
            'email' => 'stripe-student@test.dev',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->order = Order::create([
            'order_number' => 'LMS-TEST-12345',
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_method' => 'stripe',
            'payment_status' => 'pending',
            'val_id' => 'cs_test_mock_session_123',
        ]);
    }

    public function test_stripe_verification_succeeds_when_metadata_has_order_id(): void
    {
        $mockService = new class extends OnlinePaymentVerificationService
        {
            public array $mockSession = [];

            protected function jsonRequest(string $url, array $curlOptions = [], ?string $basicAuth = null): array
            {
                return $this->mockSession;
            }
        };

        $mockService->mockSession = [
            'payment_status' => 'paid',
            'amount_total' => 10000,
            'payment_intent' => 'pi_test_intent_abc',
            'metadata' => [
                'order_id' => (string) $this->order->id,
                'order_number' => $this->order->order_number,
            ],
        ];

        $request = Request::create('/checkout/payment-success', 'GET', [
            'session_id' => 'cs_test_mock_session_123',
        ]);

        $result = $mockService->verify('stripe', $request, $this->order);

        $this->assertTrue($result['ok']);
        $this->assertSame('pi_test_intent_abc', $result['reference']);
        $this->assertNull($result['message']);
    }

    public function test_stripe_verification_fails_when_metadata_belongs_to_another_order(): void
    {
        $mockService = new class extends OnlinePaymentVerificationService
        {
            public array $mockSession = [];

            protected function jsonRequest(string $url, array $curlOptions = [], ?string $basicAuth = null): array
            {
                return $this->mockSession;
            }
        };

        $mockService->mockSession = [
            'payment_status' => 'paid',
            'amount_total' => 10000,
            'payment_intent' => 'pi_test_intent_abc',
            'metadata' => [
                'order_id' => '999999',
                'order_number' => 'LMS-OTHER-ORDER',
            ],
        ];

        $request = Request::create('/checkout/payment-success', 'GET', [
            'session_id' => 'cs_test_mock_session_123',
        ]);

        $result = $mockService->verify('stripe', $request, $this->order);

        $this->assertFalse($result['ok']);
        $this->assertSame('Stripe payment does not belong to this order.', $result['message']);
    }

    public function test_stripe_verification_fails_when_payment_not_paid(): void
    {
        $mockService = new class extends OnlinePaymentVerificationService
        {
            public array $mockSession = [];

            protected function jsonRequest(string $url, array $curlOptions = [], ?string $basicAuth = null): array
            {
                return $this->mockSession;
            }
        };

        $mockService->mockSession = [
            'payment_status' => 'unpaid',
            'amount_total' => 10000,
            'metadata' => [
                'order_id' => (string) $this->order->id,
            ],
        ];

        $request = Request::create('/checkout/payment-success', 'GET', [
            'session_id' => 'cs_test_mock_session_123',
        ]);

        $result = $mockService->verify('stripe', $request, $this->order);

        $this->assertFalse($result['ok']);
        $this->assertSame('Stripe payment was not completed.', $result['message']);
    }

    public function test_checkout_payment_success_flow_marks_order_paid_and_enrolls(): void
    {
        $mockService = new class extends OnlinePaymentVerificationService
        {
            protected function jsonRequest(string $url, array $curlOptions = [], ?string $basicAuth = null): array
            {
                return [
                    'payment_status' => 'paid',
                    'amount_total' => 10000,
                    'payment_intent' => 'pi_test_intent_success',
                    'metadata' => [
                        'order_id' => '1',
                        'order_number' => 'LMS-TEST-12345',
                    ],
                ];
            }
        };

        $this->app->instance(OnlinePaymentVerificationService::class, $mockService);

        $response = $this->actingAs($this->student, 'student')
            ->get(route('checkout.payment.success', [
                'gateway' => 'stripe',
                'order_id' => $this->order->id,
                'session_id' => 'cs_test_mock_session_123',
            ]));

        $response->assertRedirect(route('checkout.success', $this->order));

        $this->order->refresh();
        $this->assertSame('paid', $this->order->payment_status);
        $this->assertSame('pi_test_intent_success', $this->order->val_id);
        $this->assertNotNull($this->order->paid_at);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
    }
}
