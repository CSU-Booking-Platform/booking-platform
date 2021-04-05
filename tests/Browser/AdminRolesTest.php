<?php /** @noinspection ALL */

namespace Tests\Browser;

use App\Models\Role;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Roles;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AdminRolesTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function testAdminCanCreateRole()
    {
        $this->browse(function (Browser $browser) {
            $role = new Role();
            $role->name = "Demo Role Dusk";

            $browser->loginAs($this->createUserWithPermissions(['roles.create']));
            $page = $browser->visit(new Roles());
            $page->createRole($role, ['users.select', 'users.create']);
            $page->waitForText($role->name);
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
            $page = $browser->visit(new Roles());
            $page->updateRole($role, ['users.select', 'users.create']);
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
            $page = $browser->visit(new Roles());
            $page->updateRole($role, [], ['users.select', 'users.create']);
        });

        $role->refresh();
        try {
            $this->assertFalse($role->hasAnyPermission(['users.select', 'users.create']), "Role should not have any of the removed permissions");
        } catch (Exception $e) {
            $this->fail($e);
        }
    }

    public function testAdminCanDeleteRole() {
        $user = $this->createUserWithPermissions(['roles.delete']);
        $role = Role::create(['name' => 'Demo Role Dusk']);
        $role->save();

        $this->browse(function (Browser $browser) use ($role, $user) {
            $browser->loginAs($user);
            $browser->visit(new Roles());
            $browser->deleteRole($role);
        });

        $this->assertDeleted($role);
    }

    public function testRoleIsRestrictedFromBooking()
    {
        //User without bookings.create permissions is turned away with a 403 code
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->createUserWithPermissions(['']));
            $browser->visit('/bookings');
            $browser->assertSee('403');
            $browser->assertMissing('My Bookings');         
        });
    }

}
