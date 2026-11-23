<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the V-ESA inbox for each contact form request; replying answers the visitor directly.
 */
class ContactRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactRequest $contactRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->contactRequest->email, $this->contactRequest->name)],
            subject: 'Nouvelle demande — '.(ContactRequest::SERVICES[$this->contactRequest->service] ?? $this->contactRequest->service).' — '.$this->contactRequest->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-request-received',
        );
    }
}
