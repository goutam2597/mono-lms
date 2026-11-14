<?php

namespace App\Services\Sms;

use App\Models\Setting;
use App\Services\SmsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Cloud transport via Twilio Messages REST API.
 */
class TwilioSmsProvider implements SmsProvider
{
    public function name(): string
    {
        return 'twilio';
    }

    public function configured(): bool
    {
        return filled(Setting::get('twilio_sid'))
            && filled(Setting::get('twilio_token'))
            && filled(Setting::get('twilio_from'));
    }

    public function send(string $to, string $body, string $kind = 'other'): array
    {
        $to = SmsService::normalizePhone($to);

        if ($to === null || trim($body) === '') {
            return ['ok' => false, 'error' => 'Invalid recipient or empty message.'];
        }

        $sid = (string) Setting::get('twilio_sid');
        $token = (string) Setting::get('twilio_token');
        $from = (string) Setting::get('twilio_from');

        if (empty($sid) || empty($token) || empty($from)) {
            return ['ok' => false, 'error' => 'Twilio is not configured.'];
        }

        try {
            $endpoint = 'https://api.twilio.com/2010-04-01/Accounts/'.$sid.'/Messages.json';

            $response = Http::withBasicAuth($sid, $token)
                ->timeout(15)
                ->asForm()
                ->post($endpoint, [
                    'To' => $to,
                    'From' => $from,
                    'Body' => $body,
                ]);

            if ($response->successful()) {
                return [
                    'ok' => true,
                    'id' => (string) ($response->json('sid') ?? ''),
                ];
            }

            $errorMessage = $response->json('message') ?? 'Twilio API error HTTP '.$response->status();
            Log::warning('Twilio send failed: '.$errorMessage, [
                'to' => $to,
                'status' => $response->status(),
            ]);

            return ['ok' => false, 'error' => $errorMessage];
        } catch (Throwable $e) {
            Log::warning('Twilio exception: '.$e->getMessage());

            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}
