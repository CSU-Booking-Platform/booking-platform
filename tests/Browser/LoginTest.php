<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{

  use DatabaseMigrations;

  /**
   * A Dusk test example.
   *
   * @return void
   */
  public function testExample()
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
}
