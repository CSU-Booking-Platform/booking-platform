<?php

namespace Tests\Browser;

use App\Models\Role;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AdminRolesTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function testAdminCanCreateRole()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->createUserWithPermissions(['roles.create']));
            $browser->visit('/admin/roles');
            $browser->type('#name', 'Demo Role Dusk');
            $browser->check('@check-users.select');
            $browser->check('@check-users.create');
            $browser->type('#number_of_bookings_per_period', '1');
            $browser->type('#number_of_days_per_period', '2');
            $browser->press('#create-role');
            $browser->waitForText('Demo Role Dusk');
        });

        $role = Role::findByName('Demo Role Dusk');
        $this->assertTrue($role->exists, "Demo Role Dusk should exist");
        try {
            $this->assertTrue($role->hasAllPermissions(['users.select', 'users.create']));
        } catch (Exception $e) {
            $this->fail($e);
        }
    }

    public function testAdminCanAssociatePermissionsToRoles()
    {
        $role = Role::create(['name' => 'Demo Role Dusk']);

        $this->browse(function (Browser $browser) use ($role) {
            $browser->loginAs($this->createUserWithPermissions(['roles.update']));
            $browser->visit('/admin/roles');
            $browser->with('@role-' . $role->id, function ($row) {
                $row->press('Action');
                $row->waitForText('Update');
                $row->press('Update');
            });
            $browser->waitForText('Update Role');
            $browser->with('@update-role-modal', function ($modal) {
                $modal->check('@check-users.select');
                $modal->check('@check-users.create');
                $modal->click('@update');
            });
            $browser->waitUntilMissing('@update-role-modal');
        });

        try {
            $this->assertTrue($role->hasAllPermissions(['users.select', 'users.create']));
        } catch (Exception $e) {
            $this->fail($e);
        }
    }

    public function testAdminCanDisassociatePermissionsFromRoles()
    {
        $user = $this->createUserWithPermissions(['roles.update']);
        $role = Role::create(['name' => 'Demo Role Dusk']);
        $role->givePermissionTo(['users.select', 'users.create', 'users.delete']);
        $role->save();

        try {
            $this->assertTrue($role->hasAllPermissions(['users.select', 'users.create', 'users.delete']));
        } catch (Exception $e) {
            $this->fail($e);
        }

        $this->browse(function (Browser $browser) use ($role, $user) {
            $browser->loginAs($user);
            $browser->visit('/admin/roles');
            $browser->with('@role-' . $role->id, function ($row) {
                $row->press('Action');
                $row->waitForText('Update');
                $row->press('Update');
            });
            $browser->waitForText('Update Role');
            $browser->with('@update-role-modal', function ($modal) {
                $modal->assertChecked('@check-users.select');
                $modal->uncheck('@check-users.select');
                $modal->assertNotChecked('@check-users.select');
                $modal->assertChecked('@check-users.create');
                $modal->uncheck('@check-users.create');
                $modal->assertNotChecked('@check-users.create');
                $modal->click('@update');
            });
            $browser->waitUntilMissing('@update-role-modal');
        });

        $role->refresh();
        try {
            $this->assertFalse($role->hasAnyPermission(['users.select', 'users.create']), "Role should not have any of the removed permissions");
        } catch (Exception $e) {
            $this->fail($e);
        }
    }


}
