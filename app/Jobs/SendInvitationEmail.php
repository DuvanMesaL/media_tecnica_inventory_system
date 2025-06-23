<?php

namespace App\Jobs;

use App\Models\UserInvitation;
use App\Mail\UserInvitationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInvitationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(
        public UserInvitation $invitation
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->invitation->email)
                ->send(new UserInvitationMail($this->invitation));

            Log::info('Invitation email sent successfully', [
                'email' => $this->invitation->email,
                'invitation_id' => $this->invitation->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send invitation email', [
                'email' => $this->invitation->email,
                'invitation_id' => $this->invitation->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Invitation email job failed permanently', [
            'email' => $this->invitation->email,
            'invitation_id' => $this->invitation->id,
            'error' => $exception->getMessage()
        ]);
    }
}
