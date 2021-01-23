<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;

/**
 * REMOVE ME IF MAKING CHANGES TO THIS FILE
 *
 * This file is excluded from coverage as it is vendor-published code
 *
 * @codeCoverageIgnore
 */
class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        $user->delete();
    }
}
