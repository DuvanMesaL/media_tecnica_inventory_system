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
            'create tools',
            'edit tools',
            'delete tools',
            'manage tools',

            // Loan permissions
            'view loans',
            'create loans',
            'approve loans',
            'deliver loans',
            'return loans',
            'cancel loans',

            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage users',
            'manage invitations',

            // System permissions
            'manage warehouses',
            'manage programs',
            'manage classrooms',
            'manage system',
            'view reports',
            'export data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // ADMIN - Full access to everything
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // TEACHER - Can only create loans and view their own
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view tools',
            'view loans',
            'create loans',
        ]);

        // LOGISTICS - Can manage loan process and view reports, but cannot create tools/loans
        $logisticsRole = Role::firstOrCreate(['name' => 'logistics']);
        $logisticsRole->givePermissionTo([
            'view tools',
            'view loans',
            'approve loans',
            'deliver loans',
            'return loans',
            'cancel loans',
            'view reports',
            'export data',
        ]);

        // Assign roles to existing users
        $this->assignRolesToUsers();
    }

    private function assignRolesToUsers()
    {
        // Buscar usuarios por email y asignar roles
        $adminEmails = [
            'Duvanmesa2415@gmail.com',
            'administrator@school.edu',
            'admin@example.com'
        ];

        $teacherEmails = [
            'john.smith@school.edu',
            'teacher@school.edu',
            'teacher@example.com'
        ];

        $logisticsEmails = [
            'robert.johnson@school.edu',
            'logistics@school.edu',
            'logistics@example.com'
        ];

        // Asignar rol admin
        foreach ($adminEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles(['admin']);
                echo "Assigned admin role to: {$email}\n";
            }
        }

        // Asignar rol teacher
        foreach ($teacherEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles(['teacher']);
                echo "Assigned teacher role to: {$email}\n";
            }
        }

        // Asignar rol logistics
        foreach ($logisticsEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles(['logistics']);
                echo "Assigned logistics role to: {$email}\n";
            }
        }
    }
}
