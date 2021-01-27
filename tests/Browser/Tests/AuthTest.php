<?php

namespace Tests\Browser\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{

  use DatabaseMigrations;

  /**
   * A Dusk test example.
   *
   * @return void
   */
  public function testWhenLoginWithCorrectPassword()
  {
    User::factory()->create([
      'email' => 'admin@example.com'
    ]);

    $this->browse(function (Browser $browser) {
      $browser->visit(new Login)
        ->loginWithForm('admin@example.com', 'password');
    });
  }

  public function testCanLogout()
  {
    $this->browse(function (Browser $browser) {
      $browser->loginAs(User::factory()->create([
        'email' => 'admin@example.com'
      ]))->visit(new Dashboard)
        ->within(new Navbar, function ($browser) {
          $browser->openProfileMenu();
          $browser->logoutAndWait();
        })
        ->assertSee('Email')
        ->assertSee('Password');
    });
  }
}
