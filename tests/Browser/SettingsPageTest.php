<?php

namespace Tests\Browser;

use App\Models\User;
use Database\Seeders\EasyUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SettingsPageTest extends DuskTestCase
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



  public function testAdminCanChangeSiteName()
  {
    $this->browse(function (Browser $browser) {
      $browser->loginAs(User::first())->visit('/admin/settings')
        ->assertSourceHas('<title>CSU Booking Platform</title>')
        ->assertSee('Settings')
        ->assertSee('Application Name')
        ->type('app_name', 'New Name')
        ->pressAndWaitFor('UPDATE APPLICATION NAME', 3)
        ->refresh()->pause(3000)
        ->assertSourceHas('<title>New Name</title>')
        ->assertSee('New Name');

    });
  }

  public function testAdminCanChangeSiteLogo()
  {
    $this->browse(function (Browser $browser) {
      $browser->loginAs(User::first())->visit('/admin/settings')
        ->assertSourceHas('<a href="/dashboard"><img src="' . config('app.logo'))
        ->assertSee('Settings')
        ->assertSee('Application Logo')
        ->attach('app_logo', __DIR__ . '/test.jpg')
        ->pressAndWaitFor('UPDATE APPLICATION LOGO', 3)
        ->refresh()->pause(3000)
        ->assertSourceHas('<a href="/dashboard"><img src="' . url('/storage/logos/'));
    });
  }
}
