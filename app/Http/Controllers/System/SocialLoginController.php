<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Socialite;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the o365 authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        try
        {
            $data = Settings::where('slug', 'app_config')->pluck('data')->toArray()[0];
            $config = new \SocialiteProviders\Manager\Config($data['id'], $data['secret'], $data['uri'], ['tenant' => $data['tenant']]);
            return Socialite::driver('microsoft')->setConfig($config)->stateless()->redirect();
        }
        catch (\Exception $e)
        {
            return redirect('/');
        }

    }

    /**
     * Obtain the user information from o365.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $data = Settings::where('slug', 'app_config')->pluck('data')->toArray()[0];
        
        $config = new \SocialiteProviders\Manager\Config($data['id'], $data['secret'], $data['uri'], ['tenant' => $data['tenant']]);

        try
        {
            $msUser = Socialite::driver('microsoft')->setConfig($config)->stateless()->user();
        }
        catch(\Exception $e)
        {
            $msUser = null;
        }
        
        $isUser = User::where(['email' => $msUser->getEmail()])->first();

        if($isUser)
        {
            Auth::login($isUser);
            return redirect('/dashboard');
        }

        else
        {       
            // Randomly creates a temporary password when it is not set.
            // The user can login using a reset link.
            $password = Str::random(20);

            try
            {
                $isUser = User::create([
                    'name' => $msUser->getName(),
                    'email' => $msUser->getEmail(),
                    'password' => Hash::make($password),
                ]);

                $isUser->assignRole('booking-user');

                Auth::login($isUser);
                return redirect('/dashboard');
            }
            catch(\Exception $e)
            {
                return redirect('/');
            }
            
        }
    }
}
