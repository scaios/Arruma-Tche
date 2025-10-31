<?php

namespace App\Mail;

use App\Models\Complaint; // Importa o modelo da reclamação
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * A instância da reclamação.
     * * @var \App\Models\Complaint
     */
    public $complaint;

    /**
     * Cria uma nova instância da mensagem.
     * * @param \App\Models\Complaint $complaint
     * @return void
     */
    public function __construct(Complaint $complaint)
    {
        // Recebe a reclamação e a armazena na propriedade pública
        $this->complaint = $complaint;
    }

    /**
     * Define o "envelope" da mensagem (Assunto, etc).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sua Reclamação foi Recebida (Arruma, Tchê)',
        );
    }

    /**
     * Define o conteúdo da mensagem (qual view usar).
     */
    public function content(): Content
    {
        return new Content(
            // Aponta para o arquivo que criamos: resources/views/emails/complaint_submitted.blade.php
            view: 'emails.complaint_submitted',
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