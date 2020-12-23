<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
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

        $response->assertStatus(302);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
    }

    /**
     * @test
     */
    public function admins_can_update_roles()
    {
        $permissions = Permission::factory()->count(20)->create();

        $role = Role::factory()->create();

        $user = User::factory()->make();

        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->assertEquals(0, $role->permissions()->count());

        $addedPerms = $permissions->random(5)->pluck('name')->toArray();

        $response = $this->actingAs($user)->put('/roles/'. $role->id, [
            'name' => $role->name,
            'permissions' => $addedPerms,
        ]);

        $response->assertStatus(302);
        $this->assertEquals(5, $role->permissions()->count());
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
