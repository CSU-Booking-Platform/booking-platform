<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admins_can_see_roles()
    {
        $role = Role::factory()->create();
        $user = User::factory()->make();

        $this->assertDatabaseHas('roles', ['name' => $role->name]);

        $response = $this->actingAs($user)->get('/roles');

        $response->assertOk();
        $response->assertSee($role->name);
    }

    /**
     * @test
     */
    public function admins_can_see_roles_create_form()
    {
        $role = Role::factory()->create();
        $user = User::factory()->make();

        $this->assertDatabaseHas('roles', ['name' => $role->name]);

        $response = $this->actingAs($user)->get('/roles/create');

        $response->assertOk();
        $response->assertSee("create");
    }

    /**
     * @test
     */
    public function admins_can_create_roles()
    {
        $role = Role::factory()->make();
        $user = User::factory()->make();

        $this->assertDatabaseMissing('roles', ['name' => $role->name]);

        $response = $this->actingAs($user)->post('/roles', ['name' => $role->name]);

        $response->assertOk();
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
    }

    /**
     * @test
     */
    public function admins_can_delete_roles()
    {
        $role = Role::factory()->create();
        $user = User::factory()->make();

        $this->assertDatabaseHas('roles', ['name' => $role->name]);

        $response = $this->actingAs($user)->delete('/roles/' . $role->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('roles', ['name' => $role->name]);
    }
}
