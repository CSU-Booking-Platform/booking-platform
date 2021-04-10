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
            'bookings.restrictions.override', //added for future tests

            'rooms.create',
            'rooms.update',
            'rooms.delete',

            'rooms.blackouts.create',
            'rooms.blackouts.update',
            'rooms.blackouts.delete',

            'settings.edit'
        ];

        // Roles map, should map to relevant permissions for each specified role
        $roles = [
            'super-admin' => $permissions
        ];

        // Create or update permissions and roles
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        foreach ($roles as $role => $givenPermissions) {
            Role::updateOrCreate(['name' => $role])->syncPermissions($givenPermissions);
        }
    }
}
