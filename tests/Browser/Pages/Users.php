<?php

namespace Tests\Browser\Pages;

use App\Models\User;
use Laravel\Dusk\Browser;

class Users extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/users';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function createUser(Browser $browser, User $user, string $password = "password") {
      $browser->type('#name', $user->name)
        ->type('#email', $user->email)
        ->type('#password', $password)
        ->type('#password_confirmation', $password)
        ->press('#create');
    }
    public function updateUser(Browser $browser, string $name = "New Name") {
      $browser
        ->press('Edit')
        ->type('.vue-portal-target #name', $name)
        ->press('UPDATE');
    }

  public function deleteUser(Browser $browser) {

    $browser->press('Delete')
      ->press('.vue-portal-target #delete');
  }
}
