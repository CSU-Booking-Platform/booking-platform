<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Browser\Pages;

use App\Models\Role;
use Laravel\Dusk\Browser;

class Roles extends Page
{
    public function url()
    {
        return '/admin/roles';
    }

    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    public function elements()
    {
        return [
//            '@element' => '#selector',
        ];
    }

    public function createRole(Browser $browser, Role $role, array $permissions) {

        $browser->type('#name', $role->name);
        foreach($permissions ?? [] as $permissionName){
            $browser->check('@check-'.$permissionName);
            $browser->assertChecked('@check-'.$permissionName);
        }
        $browser->type('#number_of_bookings_per_period', $role->number_of_bookings_per_period);
        $browser->type('#number_of_days_per_period', $role->number_of_days_per_period);
        $browser->press('CREATE');

        return $browser;
    }

    public function openUpdateModalForRole(Browser $browser, Role $role) {
        $browser->with('@role-' . $role->id, function ($row) {
            $row->press('UPDATE');
        });
        $browser->waitForText('Update Role');

        return $browser;
    }

    public function submitRoleUpdateForm(Browser $browser, array $permissionsToAdd, array $permissionsToRemove) {
        $browser->with('.vue-portal-target', function ($modal) use ($permissionsToAdd, $permissionsToRemove) {
            foreach($permissionsToAdd as $perm) {
                $modal->check('@check-'.$perm);
                $modal->assertChecked('@check-'.$perm);
            }
            foreach($permissionsToRemove as $perm) {
                $modal->uncheck('@check-'.$perm);
                $modal->assertNotChecked('@check-'.$perm);
            }
            $modal->press('UPDATE');
        });
        $browser->waitUntilMissingText('Update Role');

        return $browser;
    }

    public function updateRole(Browser $browser, Role $role, array $permissionsToAdd = [], array $permissionsToRemove = []) {
        $this->openUpdateModalForRole($browser, $role)->submitRoleUpdateForm($permissionsToAdd, $permissionsToRemove);
    }

    public function deleteRole(Browser $browser, Role $role) {
        $browser->with('@role-' . $role->id, function ($row) {
            $row->press('DELETE');
        });
        $browser->waitForText('Delete Role');
        $browser->press('DELETE ROLE');
        $browser->waitUntilMissingText('Delete Role');

        return $browser;
    }
}
