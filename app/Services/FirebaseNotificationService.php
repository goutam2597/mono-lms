<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FirebaseNotificationService
{
    /**
     * Check if Firebase is enabled and the service account is uploaded.
     */
    public static function isConfigured(): bool
    {
        if (! Setting::enabled('firebase_enabled')) {
            return false;
        }

        $path = (string) Setting::get('firebase_service_account_path', '');

        return $path !== '' && Storage::disk('local')->exists($path);
    }

    /**
     * Get the parsed service account credentials array.
     */
    public static function getServiceAccount(): ?array
    {
        $path = (string) Setting::get('firebase_service_account_path', '');
        if ($path === '' || ! Storage::disk('local')->exists($path)) {
            return null;
        }

        try {
            $content = Storage::disk('local')->get($path);
            $json = json_decode((string) $content, true);

            return is_array($json) ? $json : null;
        } catch (Throwable $e) {
            Log::error('Failed to read Firebase service account JSON: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Generate or retrieve a cached OAuth2 access token using the service account private key (RS256 JWT).
     */
    public static function getAccessToken(): ?string
    {
        $creds = self::getServiceAccount();
        if (! $creds || empty($creds['client_email']) || empty($creds['private_key'])) {
            return null;
        }

        $cacheKey = 'firebase_fcm_oauth_token_'.md5((string) ($creds['client_email'] ?? ''));

        return Cache::remember($cacheKey, 3300, function () use ($creds) {
            return self::generateOAuthToken($creds);
        });
    }

    /**
     * Generate an OAuth2 bearer token from Google's token endpoint using the service account JWT.
     */
    public static function generateOAuthToken(array $creds): ?string
    {
        try {
            $now = time();
            $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
            $claimSet = json_encode([
                'iss' => $creds['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ]);

            $base64UrlHeader = self::base64UrlEncode($header);
            $base64UrlClaim = self::base64UrlEncode($claimSet);
            $signatureInput = $base64UrlHeader.'.'.$base64UrlClaim;

            $privateKey = openssl_pkey_get_private($creds['private_key']);
            if (! $privateKey) {
                Log::error('Firebase Admin SDK: Invalid private key in service account JSON.');

                return null;
            }

            $signature = '';
            $success = openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
            if (! $success) {
                Log::error('Firebase Admin SDK: Failed to sign JWT with service account private key.');

                return null;
            }

            $jwt = $signatureInput.'.'.self::base64UrlEncode($signature);

            $response = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('Firebase OAuth Token request error: '.$response->body());

            return null;
        } catch (Throwable $e) {
            Log::error('Firebase generateOAuthToken exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Send a notification message via Firebase Cloud Messaging (FCM v1 HTTP API).
     */
    public static function sendPushNotification(
        string|array $deviceTokens,
        string $title,
        string $body,
        array $data = [],
    ): array {
        if (! self::isConfigured()) {
            return [
                'success' => false,
                'message' => 'Firebase is disabled or service account is not uploaded.',
            ];
        }

        $creds = self::getServiceAccount();
        $projectId = (string) ($creds['project_id'] ?? Setting::get('firebase_project_id', ''));
        if ($projectId === '') {
            return [
                'success' => false,
                'message' => 'Firebase Project ID is missing.',
            ];
        }

        $accessToken = self::getAccessToken();
        if (! $accessToken) {
            return [
                'success' => false,
                'message' => 'Unable to generate Firebase Admin SDK authorization token.',
            ];
        }

        $tokens = (array) $deviceTokens;
        $sentCount = 0;
        $failedCount = 0;
        $errors = [];

        $endpoint = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';

        foreach ($tokens as $token) {
            if (empty($token)) {
                continue;
            }

            try {
                $payload = [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                    ],
                ];

                if (! empty($data)) {
                    $stringData = [];
                    foreach ($data as $k => $v) {
                        $stringData[(string) $k] = is_scalar($v) ? (string) $v : json_encode($v);
                    }
                    $payload['message']['data'] = $stringData;
                }

                $res = Http::withToken($accessToken)
                    ->timeout(10)
                    ->post($endpoint, $payload);

                if ($res->successful()) {
                    $sentCount++;
                } else {
                    $failedCount++;
                    $errors[] = $res->json('error.message', 'Unknown FCM error');
                }
            } catch (Throwable $e) {
                $failedCount++;
                $errors[] = $e->getMessage();
            }
        }

        return [
            'success' => $sentCount > 0,
            'sent' => $sentCount,
            'failed' => $failedCount,
            'errors' => $errors,
        ];
    }

    /**
     * Test the Firebase Admin SDK connection by generating an OAuth token.
     */
    public static function testConnection(): array
    {
        $creds = self::getServiceAccount();
        if (! $creds) {
            return [
                'success' => false,
                'message' => 'No Firebase service account JSON file found. Please upload one first.',
            ];
        }

        if (empty($creds['client_email']) || empty($creds['private_key'])) {
            return [
                'success' => false,
                'message' => 'The uploaded service account JSON is missing client_email or private_key.',
            ];
        }

        $token = self::generateOAuthToken($creds);
        if ($token) {
            return [
                'success' => true,
                'message' => 'Successfully authenticated with Google Cloud! Firebase Admin SDK is active and ready for FCM push notifications.',
                'project_id' => $creds['project_id'] ?? 'unknown',
                'client_email' => $creds['client_email'] ?? 'unknown',
            ];
        }

        return [
            'success' => false,
            'message' => 'Google Cloud authentication failed. Please ensure the service account has Cloud Messaging permissions.',
        ];
    }

    /**
     * Check whether Firebase Cloud Messaging is available for wake-ups and notifications.
     */
    public function isAvailable(): bool
    {
        return self::isConfigured();
    }

    /**
     * Send a data-only FCM push message (used to wake background apps such as the Android SMS gateway).
     * Returns 'sent', 'unregistered', or 'failed'.
     */
    public function sendDataMessage(string $deviceToken, array $data): string
    {
        if (! self::isConfigured() || empty($deviceToken)) {
            return 'failed';
        }

        $creds = self::getServiceAccount();
        $projectId = (string) ($creds['project_id'] ?? Setting::get('firebase_project_id', ''));
        if ($projectId === '') {
            return 'failed';
        }

        $accessToken = self::getAccessToken();
        if (! $accessToken) {
            return 'failed';
        }

        $stringData = [];
        foreach ($data as $k => $v) {
            $stringData[(string) $k] = is_scalar($v) ? (string) $v : json_encode($v);
        }

        $endpoint = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';
        $payload = [
            'message' => [
                'token' => $deviceToken,
                'data' => $stringData,
                'android' => [
                    'priority' => 'high',
                ],
            ],
        ];

        try {
            $res = Http::withToken($accessToken)
                ->timeout(10)
                ->post($endpoint, $payload);

            if ($res->successful()) {
                return 'sent';
            }

            $errorCode = $res->json('error.details.0.errorCode') ?? $res->json('error.status');
            if (in_array($errorCode, ['UNREGISTERED', 'NOT_FOUND'], true)) {
                return 'unregistered';
            }

            Log::warning('FCM data message send rejected: '.$res->body());

            return 'failed';
        } catch (Throwable $e) {
            Log::warning('FCM data message exception: '.$e->getMessage());

            return 'failed';
        }
    }

    protected static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
