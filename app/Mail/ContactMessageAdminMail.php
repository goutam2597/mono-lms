<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Setting;
use App\Support\MailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage
    ) {
        MailHelper::applySmtpConfig();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Contact Inquiry] {$this->contactMessage->subject} - from {$this->contactMessage->name}",
            replyTo: [
                new Address($this->contactMessage->email, $this->contactMessage->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-admin',
            with: [
                'contactMessage' => $this->contactMessage,
                'siteName' => Setting::get('site_name', 'Mono LMS'),
                'adminUrl' => route('admin.messages.index', ['search' => $this->contactMessage->email]),
            ],
        );
    }
}
