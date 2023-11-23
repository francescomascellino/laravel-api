<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FromLeadEmail extends Mailable
{
    use Queueable, SerializesModels;

    // VARIABILE CONTENENTE ISTANZA DEL MODELLO Lead
    public $lead;

    /**
     * Create a new message instance.
     */
    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@portfolio.com', 'Francesco Mascellino'), // INDIRIZZO NOREPLY. E' IL FROM DI mailtrap.io
            replyTo: '',
            subject: 'New Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.from-lead-email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
