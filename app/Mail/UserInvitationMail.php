<?php

namespace App\Mail;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UserInvitation $invitation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación al Sistema de Inventario - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.user-invitation',
            text: 'emails.user-invitation-text',
            with: [
                'invitation' => $this->invitation,
                'acceptUrl' => route('invitation.accept', $this->invitation->token),
                'expiresAt' => $this->invitation->expires_at,
                'invitedBy' => $this->invitation->invitedBy,
                'roleName' => $this->getRoleName($this->invitation->role)
            ]
        );
    }

    private function getRoleName(string $role): string
    {
        return match($role) {
            'admin' => 'Administrador',
            'teacher' => 'Profesor',
            'logistics' => 'Personal de Logística',
            default => ucfirst($role)
        };
    }
}
