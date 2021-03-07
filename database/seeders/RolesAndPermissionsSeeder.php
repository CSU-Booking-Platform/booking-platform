<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Permissions map, separated by relevant model object
        $permissions = [
            'users.select',
            'users.select.same-role',
            'users.create',
            'users.update',
            'users.delete',

            'roles.assign', // to users
            'roles.create',
            'roles.update',
            'roles.delete',

            'bookings.create',
            'bookings.update',
            'bookings.approve', // view & respond
            'bookings.delete',

            'rooms.create',
            'rooms.update',
            'rooms.delete',

            'rooms.blackouts.create',
            'rooms.blackouts.update',
            'rooms.blackouts.delete',

            'settings.edit'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        Role::create(['name' => 'super-admin'])->givePermissionTo($permissions);
    }
}
