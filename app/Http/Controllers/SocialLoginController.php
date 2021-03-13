<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Settings;

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
        $data = Settings::where('slug', 'app_config')->pluck('data')->toArray()[0];
        $config = new \SocialiteProviders\Manager\Config($data['id'], $data['secret'], $data['uri'], ['tenant' => $data['tenant']]);

        return Socialite::driver('microsoft')->setConfig($config)->stateless()->redirect();
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
        
        $msUser = Socialite::driver('microsoft')->setConfig($config)->stateless()->user();
    
        $isUser = User::where(['email' => $msUser->getEmail()])->first();

        if($isUser) 
        {
            Auth::login($isUser);
            return redirect('/dashboard');
        }

    }
}
