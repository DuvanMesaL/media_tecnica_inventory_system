<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class CheckPermissions extends Command
{
    protected $signature = 'permission:check';
    protected $description = 'Check if permissions and roles are working correctly';

    public function handle()
    {
        $this->info('🔍 Verificando configuración de permisos...');

        // Verificar roles
        $roles = Role::all();
        $this->info("📋 Roles encontrados: " . $roles->count());
        foreach ($roles as $role) {
            $this->line("  - {$role->name} ({$role->permissions->count()} permisos)");
        }

        // Verificar permisos
        $permissions = Permission::all();
        $this->info("🔐 Permisos encontrados: " . $permissions->count());

        // Verificar usuarios con roles
        $usersWithRoles = User::role(['admin', 'teacher', 'logistics'])->get();
        $this->info("👥 Usuarios con roles: " . $usersWithRoles->count());

        foreach ($usersWithRoles as $user) {
            $roleNames = $user->getRoleNames()->implode(', ');
            $this->line("  - {$user->name} ({$user->email}): {$roleNames}");
        }

        $this->success('✅ Verificación completada');

        return 0;
    }
}
