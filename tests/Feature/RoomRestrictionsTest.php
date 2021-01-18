<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoomRestrictionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the route can create, update and remove restrictions from the system
     *
     * @return void
     */
    public function test_restrictions_controller()
    {
        $rooms = Room::factory()->count(5)->create();
        $roles = Role::factory()->count(5)->create();
        $user = User::factory()->create();

        $test_room = $rooms->random();
        $test_roles = $roles->random(2);
        $test_role1 = $test_roles->first();
        $test_role2 = $test_roles->last();

        //Test if we can add a restriction if none are there yet.
        $this->assertDatabaseCount('room_restrictions', 0);
        $response = $this->actingAs($user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [$test_role1->id],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 1);
        $this->assertDatabaseHas('room_restrictions', ['room_id' => $test_room->id, 'role_id' => $test_role1->id]);

        //Test if we can add another restriction if one is already there
        $response = $this->actingAs($user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [$test_role1->id, $test_role2->id],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 2);
        $this->assertDatabaseHas('room_restrictions', ['room_id' => $test_room->id, 'role_id' => $test_role1->id]);
        $this->assertDatabaseHas('room_restrictions', ['room_id' => $test_room->id, 'role_id' => $test_role2->id]);

        //Test if we can remove a restriction if there are multiple attached and not lose any
        $response = $this->actingAs($user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [$test_role2->id],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 1);
        $this->assertDatabaseHas('room_restrictions', ['room_id' => $test_room->id, 'role_id' => $test_role2->id]);

        //Test if we can remove restrictions
        $response = $this->actingAs($user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 0);

    }
    /**
     * Test restirctions rooms scope filter
     *
     * @return void
     */
    public function test_restrictions_scope_for_rooms()
    {
        $this->assertDatabaseCount('room_restrictions', 0);

        Room::factory()->count(5)->create();
        $role = Role::factory()->create();
        $user = User::factory()->create();

        $restricted_room = Room::inRandomOrder()->first();
        $user->syncRoles($role);

        $room_query = Room::all();
        $result = Room::hideUserRestrictions($user)->get();
        //See if all rooms are available before restriction
        $this->assertEquals($result, $room_query);

        $restricted_room->restrictions()->sync($role->id);
        $this->assertDatabaseCount('room_restrictions', 1);

        $result = Room::hideUserRestrictions($user)->get();
        $this->assertNotEquals($result, $room_query);

        $this->assertEquals(4,$result->count());
        $this->assertNotContains($restricted_room, $result);
    }

}
