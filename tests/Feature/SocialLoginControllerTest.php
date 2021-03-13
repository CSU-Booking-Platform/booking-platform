<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use Illuminate\Support\Str;

class SocialLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_microsoft_login()
    {
        //create a config in the db to attatch to the O365 login request
        $user = $this->createUserWithPermissions(['settings.edit']);
        //test if fucntion creates if no option is there
        $this->assertDatabaseCount('settings', 0);
        $this->actingAs($user)->post('/admin/settings/app_config', [
            'label' => 'app_config',
            'client_secret' => 'test_client_secret',
            'client_id' => 'test_client_id',
            'redirect_uri' => 'test_redirect_uri',
            'tenant' => 'test_tenant'
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_config',
            'data' => json_encode([
                'secret' => 'test_client_secret',
                'id' => 'test_client_id',
                'uri' => 'test_redirect_uri',
                'tenant' => 'test_tenant'
            ]),
        ]);

        $response = $this->get('/login/microsoft');

        $response->assertStatus(302);
    }

    public function test_callback_from_microsoft_login()
    {
        //create a config in the db to attatch to the O365 login request
        $user = $this->createUserWithPermissions(['settings.edit']);
        //test if fucntion creates if no option is there
        $this->assertDatabaseCount('settings', 0);
        $this->actingAs($user)->post('/admin/settings/app_config', [
            'label' => 'app_config',
            'client_secret' => 'test_client_secret',
            'client_id' => 'test_client_id',
            'redirect_uri' => 'test_redirect_uri',
            'tenant' => 'test_tenant'
        ]);

        $this->assertDatabaseCount('settings', 1);

        $this->assertDatabaseHas('settings', [
            'slug' => 'app_config',
            'data' => json_encode([
                'secret' => 'test_client_secret',
                'id' => 'test_client_id',
                'uri' => 'test_redirect_uri',
                'tenant' => 'test_tenant'
            ]),
        ]);

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn(Str::random(10))
            ->shouldReceive('getEmail')
            ->andReturn(Str::random(10) . '@gmail.com')
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);

        $response = $this->get('/login/microsoft/callback');
           $response->assertStatus(302);
    }
}
