<?php


namespace Tests;


use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

trait ApplicationUsers
{
    private bool $permissionsSeeded = false;

    protected function createUserWithPermissions(array $permissions)
    {
        if (!$this->permissionsSeeded) {
            (new RolesAndPermissionsSeeder())->run();
            $this->permissionsSeeded = true;
        }
        Role::create(['name' => 'test-role'])
            ->givePermissionTo($permissions);

        $user = User::factory()->make();
        $user->assignRole(['test-role']);
        $user->save();
        return $user;
    }
}
