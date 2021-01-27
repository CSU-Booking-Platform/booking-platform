<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class Navbar extends BaseComponent
{
  /**
   * Get the root selector for the component.
   *
   * @return string
   */
  public function selector()
  {
    return '@navbar';
  }

  /**
   * Assert that the browser page contains the component.
   *
   * @param Browser $browser
   * @return void
   */
  public function assert(Browser $browser)
  {
    $browser->assertVisible($this->selector());
  }

  /**
   * Get the element shortcuts for the component.
   *
   * @return array
   */
  public function elements()
  {
    return [];
  }

  public function openProfileMenu(Browser $browser)
  {
    $browser->press('@nav-profile')
      ->pause(3000)
      ->waitForText('Manage Account');
  }

  public function logoutAndWait(Browser $browser)
  {
    $browser->press('@nav-logout')
      ->pause(3000);
  }

}
