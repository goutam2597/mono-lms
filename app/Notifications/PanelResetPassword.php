<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

/**
 * Panel-aware, translated password reset email. Each user model passes its
 * panel label so the email can say which account type it belongs to.
 */
class PanelResetPassword extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $token,
        public readonly string $panelLabel,
        public readonly string $panel,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = URL::route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
            'panel' => $this->panel,
        ]);

        return (new MailMessage)
            ->subject(__('emails.reset.subject'))
            ->view('emails.reset-password', [
                'url' => $url,
                'name' => $notifiable->name,
                'panelLabel' => $this->panelLabel,
                'lifetime' => config('auth.passwords.'.$this->panel.'.expire'),
            ]);
    }
}
