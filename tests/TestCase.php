<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createUserWithPermissions(array $permissions)
    {
        (new RolesAndPermissionsSeeder())->run();
        Role::create(['name' => 'test-role'])
            ->givePermissionTo($permissions);

        $user = User::factory()->make();
        $user->assignRole(['test-role']);
        $user->save();
        return $user;
    }
}
