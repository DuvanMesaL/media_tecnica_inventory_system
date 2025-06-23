<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido al Sistema de Inventario! - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.welcome',
            text: 'emails.welcome-text',
            with: [
                'user' => $this->user,
                'loginUrl' => route('login'),
                'dashboardUrl' => route('dashboard')
            ]
        );
    }
}
