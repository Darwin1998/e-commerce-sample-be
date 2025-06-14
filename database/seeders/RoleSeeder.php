<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            'manage-users',
            'manage-customers',
            'manage-orders',
            'manage-products',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $workerRole = Role::firstOrCreate(['name' => 'worker']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo($permissions);

        // Assign limited permissions to worker
        $workerRole->givePermissionTo(['manage-customers', 'manage-orders', 'manage-products']);
    }
}
