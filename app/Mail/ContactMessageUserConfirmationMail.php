<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Setting;
use App\Support\MailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageUserConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage
    ) {
        MailHelper::applySmtpConfig();
    }

    public function envelope(): Envelope
    {
        $siteName = Setting::get('site_name', 'Mono LMS');

        return new Envelope(
            subject: "We have received your message: {$this->contactMessage->subject} - {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-user-confirmation',
            with: [
                'contactMessage' => $this->contactMessage,
                'siteName' => Setting::get('site_name', 'Mono LMS'),
            ],
        );
    }
}
