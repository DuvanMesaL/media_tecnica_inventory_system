<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserPermissions extends Command
{
    protected $signature = 'user:check-permissions {email}';
    protected $description = 'Check permissions for a specific user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found");
            return;
        }

        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Roles: " . $user->roles->pluck('name')->implode(', '));
        $this->info("Direct Permissions: " . $user->permissions->pluck('name')->implode(', '));
        $this->info("All Permissions: " . $user->getAllPermissions()->pluck('name')->implode(', '));

        // Check specific permission
        $hasViewReports = $user->hasPermissionTo('view reports');
        $this->info("Has 'view reports' permission: " . ($hasViewReports ? 'YES' : 'NO'));
    }
}
