<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendInvitationEmail;
use App\Models\UserInvitation;

class ProcessInvitationQueue extends Command
{
    protected $signature = 'invitations:process-queue';
    protected $description = 'Process pending invitation emails manually';

    public function handle()
    {
        $this->info('Processing pending invitation emails...');

        // Get recent invitations that might not have been processed
        $pendingInvitations = UserInvitation::where('is_used', false)
            ->where('created_at', '>=', now()->subHours(24))
            ->get();

        if ($pendingInvitations->isEmpty()) {
            $this->info('No pending invitations found.');
            return;
        }

        $this->info('Found ' . $pendingInvitations->count() . ' pending invitations.');

        foreach ($pendingInvitations as $invitation) {
            try {
                $this->info('Processing invitation for: ' . $invitation->email);

                // Dispatch the job directly
                SendInvitationEmail::dispatch($invitation);

                $this->info('✅ Queued invitation email for: ' . $invitation->email);

            } catch (\Exception $e) {
                $this->error('❌ Failed to queue invitation for ' . $invitation->email . ': ' . $e->getMessage());
            }
        }

        $this->info('Finished processing invitations.');
        $this->warn('Make sure to run: php artisan queue:work');
    }
}
