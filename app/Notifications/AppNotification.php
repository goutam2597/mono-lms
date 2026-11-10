<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Throwable;

class AppNotification extends Notification
{
    use Queueable;

    /**
     * @param  string  $title  Short, descriptive notification title
     * @param  string  $message  Detailed body of the notification
     * @param  string  $category  'verification', 'support', 'smtp', 'enrollment', 'review', 'system'
     * @param  string|null  $actionUrl  Where clicking the notification navigates
     * @param  string|null  $actionLabel  Text for action button
     * @param  string|null  $icon  Icon key: 'shield', 'support', 'mail', 'user', 'star', 'bell'
     * @param  bool  $sendMail  Whether to also dispatch an email via SMTP
     * @param  array  $metadata  Extra contextual parameters
     */
    public function __construct(
        public readonly string $title,
        public readonly string $message,
        public readonly string $category = 'system',
        public readonly ?string $actionUrl = null,
        public readonly ?string $actionLabel = null,
        public readonly ?string $icon = null,
        public readonly bool $sendMail = false,
        public readonly array $metadata = [],
    ) {}

    public function via($notifiable): array
    {
        $channels = ['database'];

        // If requested to send via email and recipient has an email address
        if ($this->sendMail && ! empty($notifiable->email)) {
            // Apply SMTP settings dynamically if configured in admin settings
            $this->applyMailConfig();
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toDatabase($notifiable): array
    {
        $channels = ['In-App'];
        if ($this->sendMail && ! empty($notifiable->email)) {
            $channels[] = 'Email (SMTP)';
        }
        if (FirebaseNotificationService::isConfigured()) {
            $channels[] = 'Firebase Push (FCM)';
        }

        return [
            'title' => $this->title,
            'message' => $this->message,
            'category' => $this->category,
            'action_url' => $this->actionUrl,
            'action_label' => $this->actionLabel,
            'icon' => $this->icon ?: $this->defaultIconForCategory($this->category),
            'channels' => $channels,
            'metadata' => $this->metadata,
        ];
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello '.($notifiable->name ?? 'User').',')
            ->line($this->message);

        if ($this->actionUrl) {
            $url = str_starts_with($this->actionUrl, 'http') ? $this->actionUrl : url($this->actionUrl);
            $mail->action($this->actionLabel ?? 'View Details', $url);
        }

        $mail->line('Thank you for being a part of Mono LMS.');

        return $mail;
    }

    protected function defaultIconForCategory(string $category): string
    {
        return match ($category) {
            'verification' => 'shield',
            'support' => 'support',
            'smtp' => 'mail',
            'enrollment' => 'user',
            'review' => 'star',
            default => 'bell',
        };
    }

    protected function applyMailConfig(): void
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
