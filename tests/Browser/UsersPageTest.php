<?php

namespace Tests\Browser;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Users;
use Tests\DuskTestCase;

class UsersPageTest extends DuskTestCase
{
  use DatabaseMigrations;

  /**
   *
   *
   * @return void
   */
  public function testWhenCreateNewUserWithValidInformation()
  {
    (new RolesAndPermissionsSeeder())->run();

    $user = User::factory()->make();

    $this->browse(function (Browser $browser) use ($user) {

      $admin = User::factory()->create();
      $admin->assignRole('super-admin');
      $browser->loginAs($admin);
      $browser->visit(new Users)
        ->createUser($user);
      $browser->waitForText('Created');

      $browser->assertSee($user->email)->assertSee($user->name);
    });

    $this->assertDatabaseHas('users', ['email' => $user->email]);
  }
  public function testWhenUpdateUserWithValidInformation()
  {
    (new RolesAndPermissionsSeeder())->run();

    $user = User::factory()->make();

    $this->browse(function (Browser $browser) use ($user) {

      $admin = User::factory()->create();
      $admin->assignRole('super-admin');
      $browser->loginAs($admin);
      $browser->visit(new Users)
        ->updateUser($user->name);
      $browser->waitUntilMissingText('NEVERMIND');

      $browser->assertSee($admin->email)->assertSee($user->name);
    });

    $this->assertDatabaseHas('users', ['name' => $user->name]);
  }

  public function testWhenDeleteNewUser()
  {
    (new RolesAndPermissionsSeeder())->run();
    $user = User::factory()->create();
    $this->browse(function (Browser $browser) use ($user) {
      $admin = User::factory()->create();
      $admin->assignRole('super-admin');
      $browser->loginAs($admin);
      $browser->visit(new Users)
        ->deleteUser($user);
      $browser->waitUntilMissingText('NEVERMIND');
      $browser->assertDontSee($user->email)->assertDontSee($user->name);
    });
    $this->assertDatabaseMissing('users', ['email' => $user->email]);
  }


  public function testAdminCanAssociateRolesToUsers() {
      $user = User::factory()->create();
      $role = Role::factory()->create();

      $this->browse(function (Browser $browser) use ($user, $role) {
          $browser->loginAs($this->createUserWithPermissions(['users.update']));
          $browser
              ->visit(new Users)
              ->updateUser($user, null, [$role->id], []);
      });

      $user->refresh();

      $this->assertTrue($user->hasRole($role), "User should have the added role");
  }

    public function testAdminCanDisassociateRolesToUsers() {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        $user->assignRole($role);
        $user->save();

        $this->browse(function (Browser $browser) use ($user, $role) {
            $browser->loginAs($this->createUserWithPermissions(['users.update']));
            $browser
                ->visit(new Users)
                ->updateUser($user, null, [], [$role->id]);
        });

        $user->refresh();

        $this->assertFalse($user->hasRole($role), "User should have the role removed");
    }
}
