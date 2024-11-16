<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    const API = 'api';
    const WEB = 'web';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all permissions
        $permissions = Permission::all();

        // Create Administrator Role
        $role = Role::create([
            'name' => 'Administrator',
            'guard_name' => self::API,
        ]);

        // Assign all Permissions to Role
        $role->syncPermissions($permissions);

        $permissions = Permission::whereIn('name', [
            'user-update',
            'user-profile',
            'assignment-list',
            'assignment-show'
        ])->get();
        // Create Employee Role
        $role = Role::create([
            'name' => 'Employee',
            'guard_name' => self::API,
        ]);
        // Assign all Permissions to Role
        $role->syncPermissions($permissions);

        // Assign a permission to Role
        $role->givePermissionTo('activity_log-list');
    }
}
