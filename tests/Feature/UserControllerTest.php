<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
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
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertOk();
        $response->assertSee("Users");

    }

    public function testFormCreatesUser()
    {
        $user = User::factory()->make();
        $random = Str::random(40);
        $this->actingAs($this->createUserWithPermissions(['users.create']))
            ->post('/admin/users', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $random,
                'password_confirmation' => $random
            ]);

        $this->assertDatabaseCount('users', 2);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertTrue(Hash::check($random, User::whereEmail($user->email)->first()->password));
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();
        $this->assertDatabaseCount('users', 1);

        $roles = Role::factory()->count(20)->create();

        $response = $this->actingAs($this->createUserWithPermissions(['users.update']))
            ->put("/admin/users/{$user->id}", [
                'name' => 'TESTING NAME',
                'email' => 'test@test.com',
                'roles' => $roles->random(5)->pluck('name')->toArray(),
            ]);

        $response->assertStatus(302);
        $this->assertEquals(5, $user->roles()->count());

        $this->assertDatabaseHas('users', [
            'name' => 'TESTING NAME',
            'email' => 'test@test.com',
        ]);
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

        $response = $this->actingAs($this->createUserWithPermissions(['users.delete']))->delete('/admin/users/' . $user->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', ['name' => $user->name]);
        $this->assertDatabaseCount('users', 1);
    }
}
