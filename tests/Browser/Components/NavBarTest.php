<?php

namespace Tests\Browser\Components;

use App\Models\User;
use Database\Seeders\EasyUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NavBarTest extends DuskTestCase
{

  use DatabaseMigrations;


  public function setUp(): void
  {
    parent::setUp();
    $seeder = new EasyUserSeeder();
    $seeder->run();
    $seeder = new RolesAndPermissionsSeeder();
    $seeder->run();
    User::first()->assignRole('super-admin');
  }

//Update as more links are added
  public function testAdminCanSeeNavBar()
  {
    $this->browse(function (Browser $browser) {
      $browser->loginAs(User::first())->visit('/dashboard')
        ->assertSee('Dashboard')
        ->assertSee('Home')
        ->assertSee('Booking Management')
        ->assertSee('User Management');
    });
  }
}
