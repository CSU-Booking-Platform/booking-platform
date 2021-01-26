<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
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
      $browser->visit('/login')
        ->assertSee('Email')
        ->type('email', 'admin@example.com')
        ->type('password', 'password')
        ->press('login')
        ->assertSee('Dashboard');
    });
  }

  public function testCanLogout()
  {
    $this->browse(function (Browser $browser) {
      $browser->loginAs(User::factory()->create([
        'email' => 'admin@example.com'
      ]))->visit('/dashboard')
        ->assertSee('Dashboard')
        ->press('@nav-profile')
        ->pause(3000)
        ->waitForText('Logout')
        ->press('@nav-logout')
        ->pause(3000)
        ->assertSee('Email')
        ->assertSee('Password');
    });
  }
}
