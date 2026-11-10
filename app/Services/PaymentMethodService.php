<?php

namespace App\Services;

use App\Models\Setting;

/**
 * Payment method registry for course purchases.
 *
 * Online gateways (stripe, paypal, razorpay) are real cURL integrations
 * configured through the admin payment-gateways settings page. The offline
 * method is a manual bank/manual transfer with an admin verification step.
 */
final class PaymentMethodService
{
    /** @var array<string, array{label: string, description: string, online: bool}> */
    private const METHODS = [
        'stripe' => [
            'label' => 'Stripe',
            'description' => 'Credit/Debit cards, Apple Pay and Google Pay.',
            'online' => true,
        ],
        'paypal' => [
            'label' => 'PayPal',
            'description' => 'Pay with your PayPal balance or linked cards.',
            'online' => true,
        ],
        'razorpay' => [
            'label' => 'Razorpay',
            'description' => 'UPI, NetBanking, Cards across India and South Asia.',
            'online' => true,
        ],
        'offline' => [
            'label' => 'Bank Transfer / Manual',
            'description' => 'Send money directly, then submit your transaction ID for approval.',
            'online' => false,
        ],
    ];

    /** @return array<string, array{label: string, description: string, online: bool, enabled: bool}> */
    public static function all(): array
    {
        $methods = [];

        foreach (self::METHODS as $key => $meta) {
            $methods[$key] = $meta + ['enabled' => self::enabled($key)];
        }

        return $methods;
    }

    /** @return array<string, array{label: string, description: string, online: bool, enabled: bool}> */
    public static function available(): array
    {
        return array_filter(self::all(), fn (array $m) => $m['enabled']);
    }

    public static function exists(string $method): bool
    {
        return array_key_exists($method, self::METHODS);
    }

    public static function enabled(string $method): bool
    {
        return match ($method) {
            // Defaults mirror PaymentGatewayController so a fresh install
            // (no settings rows) keeps the seeded gateways usable.
            'stripe' => Setting::get('gateway_stripe_enabled', '1') === '1'
                && filled(Setting::get('gateway_stripe_secret', 'sk_test_demo_secret')),
            'paypal' => Setting::get('gateway_paypal_enabled', '1') === '1'
                && filled(Setting::get('gateway_paypal_client_id', 'demo_paypal_client_id'))
                && filled(Setting::get('gateway_paypal_secret', 'demo_paypal_secret')),
            'razorpay' => Setting::get('gateway_razorpay_enabled', '0') === '1'
                && filled(Setting::get('gateway_razorpay_key_id'))
                && filled(Setting::get('gateway_razorpay_key_secret')),
            'offline' => Setting::get('gateway_offline_enabled', '1') === '1',
            default => false,
        };
    }

    public static function isOnline(string $method): bool
    {
        return self::METHODS[$method]['online'] ?? false;
    }

    public static function label(string $method): string
    {
        return self::METHODS[$method]['label'] ?? ucfirst($method);
    }

    public static function description(string $method): string
    {
        return self::METHODS[$method]['description'] ?? '';
    }

    /** Display instructions for manual/offline payments (admin-configured). */
    public static function offlineInstructions(): string
    {
        return (string) Setting::get(
            'gateway_offline_instructions',
            "Bank: Mono Global Bank\nIBAN: US99MONO000123456789\nBIC: MONOUS33\nReference: Your Order ID",
        );
    }
}
