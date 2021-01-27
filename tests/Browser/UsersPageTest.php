<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
}
