<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitationMail;
use App\Models\UserInvitation;
use App\Models\User;

class TestEmailConfiguration extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Test email configuration by sending a test email';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info('Testing email configuration...');

        // Test basic mail configuration
        try {
            $this->info('Mail driver: ' . config('mail.default'));
            $this->info('Mail host: ' . config('mail.mailers.smtp.host'));
            $this->info('Mail port: ' . config('mail.mailers.smtp.port'));
            $this->info('Mail from: ' . config('mail.from.address'));

            // Create a test invitation
            $testInvitation = new UserInvitation([
                'email' => $email,
                'token' => 'test-token-123',
                'role' => 'teacher',
                'invited_by' => 1,
                'expires_at' => now()->addHours(24)
            ]);

            // Try to send test email
            Mail::to($email)->send(new UserInvitationMail($testInvitation));

            $this->info('✅ Test email sent successfully to: ' . $email);

        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
