<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index(): View
    {
        $gateways = [
            'stripe' => [
                'name' => 'Stripe',
                'description' => 'Credit/Debit cards, Apple Pay, Google Pay worldwide.',
                'enabled' => (bool) Setting::get('gateway_stripe_enabled', '1'),
                'mode' => Setting::get('gateway_stripe_mode', 'test'),
                'key' => Setting::get('gateway_stripe_key', 'pk_test_51MzDemoMonoLMSKey'),
                'secret' => Setting::get('gateway_stripe_secret', 'sk_test_demo_secret'),
                'webhook' => Setting::get('gateway_stripe_webhook', 'whsec_demo_webhook'),
            ],
            'paypal' => [
                'name' => 'PayPal',
                'description' => 'PayPal balance, Pay in 4, and buyer protection.',
                'enabled' => (bool) Setting::get('gateway_paypal_enabled', '1'),
                'mode' => Setting::get('gateway_paypal_mode', 'sandbox'),
                'client_id' => Setting::get('gateway_paypal_client_id', 'demo_paypal_client_id'),
                'secret' => Setting::get('gateway_paypal_secret', 'demo_paypal_secret'),
            ],
            'razorpay' => [
                'name' => 'Razorpay',
                'description' => 'UPI, NetBanking, Cards across India and South Asia.',
                'enabled' => (bool) Setting::get('gateway_razorpay_enabled', '0'),
                'key_id' => Setting::get('gateway_razorpay_key_id', ''),
                'key_secret' => Setting::get('gateway_razorpay_key_secret', ''),
            ],
            'offline' => [
                'name' => 'Bank Transfer / Manual',
                'description' => 'Accept direct bank wire or cash with manual receipt approval.',
                'enabled' => (bool) Setting::get('gateway_offline_enabled', '1'),
                'instructions' => Setting::get('gateway_offline_instructions', "Bank: Mono Global Bank\nIBAN: US99MONO000123456789\nBIC: MONOUS33\nReference: Your Order ID"),
            ],
        ];

        return view('pages.admin.payment-gateways', [
            'gateways' => $gateways,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $gateway = $request->input('gateway');

        if ($gateway === 'stripe') {
            Setting::set('gateway_stripe_enabled', $request->has('enabled') ? '1' : '0');
            Setting::set('gateway_stripe_mode', $request->input('mode', 'test'));
            Setting::set('gateway_stripe_key', (string) $request->input('key'));
            Setting::set('gateway_stripe_secret', (string) $request->input('secret'));
            Setting::set('gateway_stripe_webhook', (string) $request->input('webhook'));
        } elseif ($gateway === 'paypal') {
            Setting::set('gateway_paypal_enabled', $request->has('enabled') ? '1' : '0');
            Setting::set('gateway_paypal_mode', $request->input('mode', 'sandbox'));
            Setting::set('gateway_paypal_client_id', (string) $request->input('client_id'));
            Setting::set('gateway_paypal_secret', (string) $request->input('secret'));
        } elseif ($gateway === 'razorpay') {
            Setting::set('gateway_razorpay_enabled', $request->has('enabled') ? '1' : '0');
            Setting::set('gateway_razorpay_key_id', (string) $request->input('key_id'));
            Setting::set('gateway_razorpay_key_secret', (string) $request->input('key_secret'));
        } elseif ($gateway === 'offline') {
            Setting::set('gateway_offline_enabled', $request->has('enabled') ? '1' : '0');
            Setting::set('gateway_offline_instructions', (string) $request->input('instructions'));
        }

        return back()->with('success', ucfirst($gateway).' gateway configuration saved.');
    }
}
