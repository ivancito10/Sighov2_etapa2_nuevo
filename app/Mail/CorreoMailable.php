<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address as MailablesAddress;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CorreoMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $datos;
    public $asunto;
    /**
     * Create a new message instance.
     */
    public function __construct($data, $asunto)
    {
        //
        $this->datos = $data;
        $this->asunto = $asunto;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new MailablesAddress('sigho@ssulapaz.org', 'SighoV2'),
            subject: 'Correo Test',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mi_correo',
            with: [
                'datos' => $this->datos,
                'asunto' => $this->asunto
            ]
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
