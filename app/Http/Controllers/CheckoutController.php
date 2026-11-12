<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\Setting;
use App\Services\CheckoutService;
use App\Services\OnlinePaymentVerificationService;
use App\Services\PaymentMethodService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Course purchase flow (mono_market pattern):
 * start -> dedicated per-method summary page (60-minute countdown) ->
 * online gateway initiate/callback or offline transaction reference ->
 * verification -> enrollment activation.
 */
final class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly OnlinePaymentVerificationService $verifier,
    ) {}

    /** Create the pending order and send the student to the method's summary page. */
    public function start(Request $request, Course $course): RedirectResponse
    {
        $student = auth('student')->user();

        if ($course->price <= 0) {
            return redirect()->route('courses.show', $course->slug)
                ->with('info', 'This course is free — enroll directly.');
        }

        try {
            $order = $this->checkout->startPurchase(
                $student,
                $course,
                (string) $request->input('payment_method', 'offline'),
                $request->input('coupon_code'),
            );
        } catch (ValidationException $e) {
            return redirect()
                ->route('courses.show', $course->slug)
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()->route('checkout.pay', [$order, $order->payment_method]);
    }

    /** Dedicated summary page for one payment method (60-minute countdown). */
    public function payPage(Order $order, string $method): RedirectResponse|View
    {
        $this->authorizeOrder($order);

        if ($order->payment_method !== $method || ! PaymentMethodService::exists($method)) {
            return redirect()->route('student.orders.show', $order)
                ->with('error', 'This order was placed with a different payment method.');
        }

        if (! PaymentMethodService::enabled($method)) {
            return redirect()->route('student.orders.show', $order)
                ->with('error', 'This payment method is currently unavailable.');
        }

        if (! $order->canPay()) {
            return redirect()->route('student.orders.show', $order)
                ->with('error', 'The payment window for this order has expired or it is already paid.');
        }

        return view('pages.checkout.pay-'.$method, [
            'order' => $order,
            'course' => $order->course,
            'instructions' => PaymentMethodService::description($method),
            'offlineInstructions' => PaymentMethodService::offlineInstructions(),
        ]);
    }

    /** Dispatch the payment initiation to the matching gateway (or offline reference form). */
    public function initiate(Request $request, Order $order, string $method): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->payment_method !== $method) {
            return redirect()->route('student.orders.show', $order)
                ->with('error', 'This order was placed with a different payment method.');
        }

        if (! $order->canPay()) {
            return redirect()->route('student.orders.show', $order)
                ->with('error', 'The payment window for this order has expired or it is already paid.');
        }

        $order->increment('payment_attempts');

        return match ($method) {
            'stripe' => $this->initiateStripe($order),
            'paypal' => $this->initiatePaypal($order),
            'razorpay' => $this->initiateRazorpay($order),
            'offline' => $this->submitOfflineReference($request, $order),
            default => redirect()->route('student.orders.show', $order)->with('error', 'Unsupported payment method.'),
        };
    }

    /** Gateway / payment-link return handler — verifies then marks the order paid. */
    public function paymentSuccess(Request $request): RedirectResponse
    {
        $order = $this->orderFromRequest($request);

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        $this->authorizeOrder($order);

        // If the order has already been marked paid, redirect straight to success
        if ($order->payment_status === 'paid') {
            return redirect()->route('checkout.success', $order)
                ->with('success', 'Payment verified — you are enrolled!');
        }

        $gateway = (string) $request->input('gateway', $order->payment_method);

        $result = $this->verifier->verify($gateway, $request, $order);

        if (! $result['ok']) {
            return redirect()->route('student.orders.show', $order)
                ->with('error', $result['message'] ?? 'We could not verify your payment.');
        }

        $this->checkout->markPaid($order, $result['reference']);

        return redirect()->route('checkout.success', $order)
            ->with('success', 'Payment verified — you are enrolled!');
    }

    public function paymentCancel(Request $request): RedirectResponse
    {
        $order = $this->orderFromRequest($request);

        if (! $order) {
            return redirect()->route('home');
        }

        return redirect()->route('student.orders.show', $order)
            ->with('info', 'Payment was cancelled. You can retry within the reservation window.');
    }

    /** Post-payment result page (enrolled / awaiting verification). */
    public function success(Order $order): View
    {
        $this->authorizeOrder($order);
        $order->refresh();

        return view('pages.courses.success', ['order' => $order]);
    }

    /** Offline / manual method: submit the transaction reference for admin verification. */
    private function submitOfflineReference(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'payment_reference' => ['required', 'string', 'min:4', 'max:190'],
        ]);

        try {
            $this->checkout->submitReference($order, (string) $request->input('payment_reference'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('student.orders.show', $order)
            ->with('success', 'Transaction ID received. An administrator will verify your payment shortly.');
    }

    private function initiateStripe(Order $order): RedirectResponse
    {
        if (! PaymentMethodService::enabled('stripe')) {
            return $this->paymentFailureRedirect($order, 'Stripe is currently unavailable.');
        }

        $secret = (string) Setting::get('gateway_stripe_secret');
        $currency = strtolower((string) Setting::get('currency_code', 'USD'));

        $successUrl = route('checkout.payment.success', ['gateway' => 'stripe', 'order_id' => $order->id])
            .'&session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('checkout.payment.cancel', ['gateway' => 'stripe', 'order_id' => $order->id]);

        $postData = [
            'payment_method_types[]' => 'card',
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'customer_email' => $order->student->email,
            'metadata[order_id]' => (string) $order->id,
            'metadata[order_number]' => $order->order_number,
            'line_items[0][price_data][currency]' => $currency,
            'line_items[0][price_data][product_data][name]' => $order->course?->title ?? 'Course Enrollment',
            'line_items[0][price_data][unit_amount]' => (string) (int) round(((float) $order->amount) * 100),
            'line_items[0][quantity]' => '1',
        ];

        $session = $this->curlJson('https://api.stripe.com/v1/checkout/sessions', $postData, [
            'Authorization: Bearer '.$secret,
        ], true);

        if (! empty($session['url'])) {
            $order->update(['val_id' => (string) ($session['id'] ?? '')]);

            return redirect()->away($session['url']);
        }

        return $this->paymentFailureRedirect($order, $session['error']['message'] ?? 'Stripe checkout session creation failed.');
    }

    private function initiatePaypal(Order $order): RedirectResponse
    {
        $settings = [
            'mode' => (string) Setting::get('gateway_paypal_mode', 'sandbox'),
            'client_id' => (string) Setting::get('gateway_paypal_client_id'),
            'secret' => (string) Setting::get('gateway_paypal_secret'),
        ];

        if (! PaymentMethodService::enabled('paypal')) {
            return $this->paymentFailureRedirect($order, 'PayPal is currently unavailable.');
        }

        $baseUrl = $settings['mode'] === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $auth = $this->curlJson($baseUrl.'/v1/oauth2/token', ['grant_type' => 'client_credentials'], [], false, $settings['client_id'].':'.$settings['secret']);

        if (empty($auth['access_token'])) {
            return $this->paymentFailureRedirect($order, 'Could not connect to PayPal. Please try again.');
        }

        $successUrl = route('checkout.payment.success', ['gateway' => 'paypal', 'order_id' => $order->id]);
        $cancelUrl = route('checkout.payment.cancel', ['gateway' => 'paypal', 'order_id' => $order->id]);

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $order->order_number,
                'description' => 'Course: '.$order->course->title,
                'amount' => [
                    'currency_code' => $order->currency ?: 'USD',
                    'value' => number_format((float) $order->amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'brand_name' => Setting::get('site_name', 'Mono LMS'),
            ],
        ];

        $result = $this->curlJson(
            $baseUrl.'/v2/checkout/orders',
            $payload,
            ['Authorization: Bearer '.$auth['access_token'], 'Content-Type: application/json'],
            false,
            null,
            true,
        );

        $approvalLink = collect($result['links'] ?? [])
            ->firstWhere('rel', 'approve')['href'] ?? null;

        if ($approvalLink) {
            $order->update(['val_id' => (string) ($result['id'] ?? '')]);

            return redirect()->away($approvalLink);
        }

        return $this->paymentFailureRedirect($order, $result['details'][0]['description'] ?? 'PayPal payment initiation failed.');
    }

    private function initiateRazorpay(Order $order): RedirectResponse
    {
        $keyId = (string) Setting::get('gateway_razorpay_key_id');
        $keySecret = (string) Setting::get('gateway_razorpay_key_secret');

        if (! PaymentMethodService::enabled('razorpay') || ! $keyId || ! $keySecret) {
            return $this->paymentFailureRedirect($order, 'Razorpay is currently unavailable.');
        }

        $reference = 'RZP-'.$order->order_number.'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        $payload = [
            'amount' => (int) round(((float) $order->amount) * 100),
            // Razorpay Payment Links only settle in INR.
            'currency' => 'INR',
            'accept_partial' => false,
            'reference_id' => $reference,
            'description' => 'Course: '.$order->course->title,
            'customer' => [
                'name' => $order->student->name ?: 'Student',
                'email' => $order->student->email ?: 'student@example.com',
            ],
            'notify' => ['sms' => false, 'email' => false],
            'callback_url' => route('checkout.payment.success', ['gateway' => 'razorpay', 'order_id' => $order->id]),
            'callback_method' => 'get',
            'notes' => ['order_id' => (string) $order->id, 'order_number' => $order->order_number],
        ];

        $result = $this->curlJson(
            'https://api.razorpay.com/v1/payment_links',
            $payload,
            ['Content-Type: application/json'],
            false,
            $keyId.':'.$keySecret,
            true,
        );

        if (! empty($result['short_url'])) {
            $order->update(['val_id' => $reference]);

            return redirect()->away($result['short_url']);
        }

        return $this->paymentFailureRedirect($order, $result['error']['description'] ?? 'Razorpay payment initiation failed.');
    }

    private function paymentFailureRedirect(Order $order, string $message): RedirectResponse
    {
        return redirect()->route('checkout.pay', [$order, $order->payment_method])
            ->with('error', $message);
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless($order->student_id === auth('student')->id(), 403);
    }

    private function orderFromRequest(Request $request): ?Order
    {
        $orderId = $request->input('order_id', $request->input('order'));

        if (! $orderId) {
            return null;
        }

        return Order::find((int) $orderId);
    }

    /**
     * @param  array<string, mixed>|array<int, mixed>  $data
     * @param  list<string>  $headers
     * @return array<string, mixed>
     */
    private function curlJson(
        string $url,
        array $data,
        array $headers = [],
        bool $formEncoded = false,
        ?string $basicAuth = null,
        bool $jsonBody = false,
    ): array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($jsonBody) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($formEncoded) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        if ($basicAuth) {
            curl_setopt($ch, CURLOPT_USERPWD, $basicAuth);
        }

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode((string) $response, true) ?: [];
    }
}
