<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $customerName,
        public string $alertMessage,
        public string $lotNumber,
        public int $orderId
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Important Medication Recall Notice',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
