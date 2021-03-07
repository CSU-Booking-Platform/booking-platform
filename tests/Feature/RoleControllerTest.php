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

        $response = $this->actingAs($user)->get('/admin/roles');

        $response->assertOk();
        $response->assertSee($role->name);
    }

    /**
     * @test
     */
    public function admins_can_create_roles()
    {
        $role = Role::factory()->make();

        $this->assertDatabaseMissing('roles', ['name' => $role->name]);

        $response = $this->actingAs($this->createUserWithPermissions(['roles.create']))->post('/admin/roles', ['name' => $role->name]);

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

        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->assertEquals(0, $role->permissions()->count());

        $response = $this->actingAs($this->createUserWithPermissions(['roles.update']))->put('/admin/roles/' . $role->id, [
            'name' => $role->name,
            'permissions' => $permissions->random(5)->pluck('name')->toArray(),
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

        $this->assertDatabaseHas('roles', ['name' => $role->name]);

        $response = $this->actingAs($this->createUserWithPermissions(['roles.delete']))->delete('/admin/roles/' . $role->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('roles', ['name' => $role->name]);
    }
}
