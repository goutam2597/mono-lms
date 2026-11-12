<?php

namespace App\Support;

use App\Models\Setting;
use Throwable;

class MailHelper
{
    /**
     * Apply database-configured SMTP settings to Laravel's runtime mail configuration.
     */
    public static function applySmtpConfig(): void
    {
        try {
            $host = Setting::get('mail_host');
            if ($host) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $host,
                    'mail.mailers.smtp.port' => (int) Setting::get('mail_port', 587),
                    'mail.mailers.smtp.encryption' => Setting::get('mail_encryption', 'tls'),
                    'mail.mailers.smtp.username' => Setting::get('mail_username'),
                    'mail.mailers.smtp.password' => Setting::get('mail_password'),
                    'mail.from.address' => Setting::get('mail_from_address', config('mail.from.address')),
                    'mail.from.name' => Setting::get('mail_from_name', config('mail.from.name')),
                ]);
            }
        } catch (Throwable) {
            // Gracefully ignore if database or settings are unavailable
        }
    }
}
