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
        return '/admin/users';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
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

    public function createUser(Browser $browser, User $user, string $password = "password")
    {
        $browser->type('#name', $user->name)
            ->type('#email', $user->email)
            ->type('#password', $password)
            ->type('#password_confirmation', $password)
            ->press('#create');
    }

    public function updateUser(Browser $browser, User $user, $name, array $rolesToAdd = [], array $rolesToRemove = [])
    {


        $browser->with('#user-row-' . $user->id, function ($row) {
                $row->press('Edit');
            });
        $browser->with('.vue-portal-target', function ($modal) use ($name, $rolesToAdd, $rolesToRemove) {
            if (isset($name))
                $modal->type('#name', $name);
            foreach ($rolesToAdd as $role) {
                $modal->check("@check-" . $role);
                $modal->assertChecked("@check-" . $role);
            }
            foreach ($rolesToRemove as $role) {
                $modal->uncheck("@check-" . $role);
                $modal->assertNotChecked("@check-" . $role);
            }
            $modal->press('UPDATE');
        });
        $browser->waitUntilMissingText('Update User:');
    }

    public function deleteUser(Browser $browser)
    {

        $browser->press('Delete')
            ->press('.vue-portal-target #delete');
    }
}
