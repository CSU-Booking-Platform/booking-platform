<?php

namespace Tests\Browser\Pages;

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
      $browser->loginAs(User::first())->visit('/settings')
        ->assertSourceHas('<title>Laravel</title>')
        ->assertSee('Settings')
        ->assertSee('Application Name')
        ->type('app_name', 'New Name')
        ->pressAndWaitFor('UPDATE APPLICATION NAME', 3)
        ->refresh()->pause(3000)
        ->assertSourceHas('<title>New Name</title>');
    });
  }

  public function testAdminCanChangeSiteLogo()
  {
    $this->browse(function (Browser $browser) {
      $browser->loginAs(User::first())->visit('/settings')
        ->assertSourceHas('<a href="/dashboard"><svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="block h-15 w-auto"><path d="M11.395 44.428C4.557 40.198 0 32.632 0 24 0 10.745 10.745 0 24 0a23.891 23.891 0 0113.997 4.502c-.2 17.907-11.097 33.245-26.602 39.926z" fill="#6875F5"></path> <path d="M14.134 45.885A23.914 23.914 0 0024 48c13.255 0 24-10.745 24-24 0-3.516-.756-6.856-2.115-9.866-4.659 15.143-16.608 27.092-31.75 31.751z" fill="#6875F5"></path></svg>')
        ->assertSee('Settings')
        ->assertSee('Application Logo')
        ->attach('app_logo', __DIR__.'/test.jpg')
        ->pressAndWaitFor('UPDATE APPLICATION LOGO', 3)
        ->refresh()->pause(3000)
        ->assertSourceHas('<a href="/dashboard"><img src="storage/logos/');
    });
  }
}
