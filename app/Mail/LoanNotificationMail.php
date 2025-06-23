<?php

namespace App\Mail;

use App\Models\ToolLoan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ToolLoan $loan,
        public string $type
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'approved' => 'Préstamo Aprobado',
            'delivered' => 'Herramientas Entregadas',
            'overdue' => 'Préstamo Vencido - Acción Requerida',
            'returned' => 'Préstamo Completado',
            default => 'Notificación de Préstamo'
        };

        return new Envelope(
            subject: $subject . ' - ' . $this->loan->loan_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.loan-notification',
            text: 'emails.loan-notification-text',
            with: [
                'loan' => $this->loan,
                'type' => $this->type,
                'loanUrl' => route('loans.show', $this->loan),
                'dashboardUrl' => route('dashboard')
            ]
        );
    }
}
