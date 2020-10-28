<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUsersIndexPageLoads()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get('/users');
        $response->assertOk();
        $response->assertSee("Users");

    }

    public function testFormCreatesUser()
    {
        $user = User::factory()->make();
        $random = Str::random(40);
        $this->assertDatabaseCount('users', 0);

        $this->actingAs($user)->post('users', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $random,
            'password_confirmation' => $random
        ]);

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertTrue(Hash::check($random, User::first()->password));
    }

    /**
     * @test
     */
    public function testCanDeleteUsers()
    {
        $user = User::factory()->make();
        $user->save();
        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', ['name' => $user->name]);

        $response = $this->actingAs($user)->delete('/users/' . $user->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', ['name' => $user->name]);
        $this->assertDatabaseCount('users', 0);
    }
}
