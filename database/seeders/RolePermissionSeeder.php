<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Tool permissions
            'view tools',
            'manage tools',

            // Loan permissions
            'view loans',
            'create loans',
            'approve loans',
            'deliver loans',
            'return loans',

            // User permissions
            'manage users',

            // System permissions
            'manage system',
            'view reports',
            'export data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view tools',
            'view loans',
            'create loans',
        ]);

        $logisticsRole = Role::firstOrCreate(['name' => 'logistics']);
        $logisticsRole->givePermissionTo([
            'view tools',
            'manage tools',
            'view loans',
            'approve loans',
            'deliver loans',
            'return loans',
            'view reports',
        ]);

        // Assign roles to existing users
        $adminUser = User::where('email', 'admin@school.edu')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $teacherUser = User::where('email', 'john.smith@school.edu')->first();
        if ($teacherUser) {
            $teacherUser->assignRole('teacher');
        }

        $logisticsUser = User::where('email', 'robert.johnson@school.edu')->first();
        if ($logisticsUser) {
            $logisticsUser->assignRole('logistics');
        }
    }
}
