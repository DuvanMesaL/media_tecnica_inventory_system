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
            'approved' => 'Préstamo Aprobado - ' . $this->loan->loan_number,
            'delivered' => 'Préstamo Entregado - ' . $this->loan->loan_number,
            'returned' => 'Préstamo Devuelto - ' . $this->loan->loan_number,
            'overdue' => 'Préstamo Vencido - ' . $this->loan->loan_number,
            default => 'Notificación de Préstamo - ' . $this->loan->loan_number
        };

        return new Envelope(subject: $subject);
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
