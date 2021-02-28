<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Socialite;

class LoginController extends Controller
{
    /**
     * Redirect the user to the o365 authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    /**
     * Obtain the user information from o365.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('microsoft')->user();

        echo 'Looks like we got there as ' . $user->email;
        // $user->token;
    }
}
