<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Server-side verification of online gateway callbacks (mono_market port).
 * Every gateway return/webhook must pass through here before an order is
 * marked paid: status check, amount check and reference binding.
 */
final class OnlinePaymentVerificationService
{
    /**
     * @param  array{gateway: string, order: Order, request: Request}  $context
     * @return array{ok: bool, reference: ?string, message: ?string}
     */
    public function verify(string $gateway, Request $request, Order $order): array
    {
        $amount = (float) $order->amount;

        return match ($gateway) {
            'stripe' => $this->verifyStripe($request, (string) ($order->val_id ?? ''), $amount, $order->order_number),
            'paypal' => $this->verifyPaypal($request, $amount),
            'razorpay' => $this->verifyRazorpay($request, (string) ($order->val_id ?? ''), $amount),
            default => ['ok' => false, 'reference' => null, 'message' => 'Unsupported payment gateway.'],
        };
    }

    /** @return array{ok: bool, reference: ?string, message: ?string} */
    private function verifyStripe(Request $request, string $storedRef, float $amount, string $orderNumber): array
    {
        $settings = $this->stripeSettings();
        if (! $settings['enabled'] || ! $settings['secret']) {
            return $this->fail('Stripe is not configured.');
        }

        $sessionId = (string) $request->input('session_id');

        if ($sessionId === '') {
            return $this->fail('Missing Stripe checkout session.');
        }

        // The session stored at initiation is authoritative; a callback from
        // any other purchase is rejected.
        if ($storedRef !== '' && str_starts_with($storedRef, 'cs_') && $sessionId !== $storedRef) {
            return $this->fail('Stripe session does not match this payment.');
        }

        $session = $this->jsonRequest('https://api.stripe.com/v1/checkout/sessions/'.$sessionId, [
            CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$settings['secret']],
        ]);

        if (($session['payment_status'] ?? null) !== 'paid') {
            return $this->fail('Stripe payment was not completed.');
        }

        $paidMinor = (int) ($session['amount_total'] ?? 0);
        if ($amount > 0 && $paidMinor > 0 && $paidMinor < (int) round($amount * 100)) {
            return $this->fail('Stripe payment amount mismatch.');
        }

        $metaOrder = (string) ($session['metadata']['order_id'] ?? '');
        if ($metaOrder !== '' && (string) $session['metadata']['order_id'] !== (string) $orderNumber) {
            return $this->fail('Stripe payment does not belong to this order.');
        }

        return $this->ok((string) ($session['payment_intent'] ?? $session['id'] ?? $sessionId));
    }

    /** @return array{ok: bool, reference: ?string, message: ?string} */
    private function verifyPaypal(Request $request, float $amount): array
    {
        $settings = $this->paypalSettings();
        if (! $settings['enabled'] || ! $settings['client_id'] || ! $settings['secret']) {
            return $this->fail('PayPal is not configured.');
        }

        $token = (string) $request->input('token');
        if ($token === '') {
            return $this->fail('Missing PayPal order token.');
        }

        $baseUrl = ($settings['mode'] ?? 'sandbox') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $auth = $this->jsonRequest($baseUrl.'/v1/oauth2/token', [
            CURLOPT_POST => true,
            CURLOPT_USERPWD => $settings['client_id'].':'.$settings['secret'],
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        if (empty($auth['access_token'])) {
            return $this->fail('Could not authenticate with PayPal.');
        }

        $capture = $this->jsonRequest($baseUrl.'/v2/checkout/orders/'.$token.'/capture', [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$auth['access_token'],
                'Content-Type: application/json',
            ],
        ]);

        if (($capture['status'] ?? '') !== 'COMPLETED') {
            return $this->fail('PayPal payment was not completed.');
        }

        $paidValue = (float) ($capture['purchase_units'][0]['amount']['value'] ?? 0);
        if ($amount > 0 && $paidValue > 0 && $paidValue < $amount) {
            return $this->fail('PayPal payment amount mismatch.');
        }

        $captureId = $capture['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

        return $this->ok($captureId ?? $token);
    }

    /** @return array{ok: bool, reference: ?string, message: ?string} */
    private function verifyRazorpay(Request $request, string $storedRef, float $amount): array
    {
        $settings = $this->razorpaySettings();
        if (! $settings['enabled'] || ! $settings['key_id'] || ! $settings['key_secret']) {
            return $this->fail('Razorpay is not configured.');
        }

        $paymentId = (string) $request->input('razorpay_payment_id', '');

        if (! preg_match('/^pay_[A-Za-z0-9]+$/', $paymentId)) {
            return $this->fail('Missing Razorpay payment ID.');
        }

        $payment = $this->jsonRequest('https://api.razorpay.com/v1/payments/'.$paymentId, [], $settings['key_id'].':'.$settings['key_secret']);

        if (empty($payment['id'])) {
            return $this->fail('Could not verify the Razorpay payment.');
        }

        if (! in_array($payment['status'] ?? '', ['captured', 'authorized'], true)) {
            return $this->fail('Razorpay payment was not completed.');
        }

        if ((int) ($payment['amount'] ?? 0) < (int) round($amount * 100)) {
            return $this->fail('Razorpay payment amount mismatch.');
        }

        $callbackRef = (string) ($request->input('razorpay_payment_link_reference_id')
            ?: ($payment['notes']['reference_id'] ?? ''));

        if ($storedRef && $callbackRef && $storedRef !== $callbackRef) {
            return $this->fail('Razorpay payment reference mismatch.');
        }

        return $this->ok($paymentId);
    }

    /** @return array{enabled: mixed, mode: string, key: ?string, secret: ?string} */
    private function stripeSettings(): array
    {
        return [
            'enabled' => Setting::get('gateway_stripe_enabled', '1') === '1',
            'mode' => Setting::get('gateway_stripe_mode', 'test'),
            'key' => Setting::get('gateway_stripe_key'),
            'secret' => Setting::get('gateway_stripe_secret'),
        ];
    }

    /** @return array{enabled: mixed, mode: string, client_id: ?string, secret: ?string} */
    private function paypalSettings(): array
    {
        return [
            'enabled' => Setting::get('gateway_paypal_enabled', '1') === '1',
            'mode' => Setting::get('gateway_paypal_mode', 'sandbox'),
            'client_id' => Setting::get('gateway_paypal_client_id'),
            'secret' => Setting::get('gateway_paypal_secret'),
        ];
    }

    /** @return array{enabled: mixed, key_id: ?string, key_secret: ?string} */
    private function razorpaySettings(): array
    {
        return [
            'enabled' => Setting::get('gateway_razorpay_enabled', '0') === '1',
            'key_id' => Setting::get('gateway_razorpay_key_id'),
            'key_secret' => Setting::get('gateway_razorpay_key_secret'),
        ];
    }

    /**
     * @param  array<int, int>  $curlOptions
     * @return array<string, mixed>
     */
    private function jsonRequest(string $url, array $curlOptions = [], ?string $basicAuth = null): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        foreach ($curlOptions as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        if ($basicAuth) {
            curl_setopt($ch, CURLOPT_USERPWD, $basicAuth);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode((string) $response, true) ?: [];
    }

    /** @return array{ok: true, reference: string, message: null} */
    private function ok(string $reference): array
    {
        return ['ok' => true, 'reference' => $reference, 'message' => null];
    }

    /** @return array{ok: false, reference: null, message: string} */
    private function fail(string $message): array
    {
        return ['ok' => false, 'reference' => null, 'message' => $message];
    }
}
