<?php

namespace Tests\Browser;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AdminRoles extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * My test implementation
     */
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
        $this->assertEquals(['users.select', 'users.create'], $role->permissions->toArray());
    }
}
