<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomRestrictionsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $rooms;
    private $test_role1;
    private $test_role2;

    protected function setUp(): void
    {
        parent::setUp();

        //Create rooms and roles
        $this->rooms = Room::factory()->count(5)->create();
        $roles = Role::factory()->count(5)->create();

        //Give test user permission to update rooms.
        Permission::create(['name'=>'bookings.approve', 'guard_name'=> 'web']);
        $this->user = User::factory()->create();
        $role = $roles->first();
        $role->givePermissionTo('bookings.approve');
        $this->user->assignRole($role);

        //Pick our random roles for each test.
        $test_roles = $roles->random(2);
        $this->test_role1 = $test_roles->first();
        $this->test_role2 = $test_roles->last();
    }

    /**
     * Test if the route can create restrictions from the system
     *
     * @return void
     */
    public function test_restrictions_controller_create()
    {
        $test_room = $this->rooms->random();
        $this->assertDatabaseCount('room_restrictions', 0);

        $response = $this->actingAs($this->user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [$this->test_role1->id],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 1);
        $this->assertDatabaseHas('room_restrictions',
            [
                'room_id' => $test_room->id,
                'role_id' => $this->test_role1->id
            ]);
    }

    /**
     * Test if the route can update number of restrictions from the system
     *
     * @return void
     */
    public function test_restrictions_controller_update_1_to_2()
    {
        $test_room = $this->rooms->random();
        $test_room->restrictions()->sync([$this->test_role1->id]);
        $this->assertDatabaseCount('room_restrictions', 1);

        //Test if we can add another restriction if one is already there
        $response = $this->actingAs($this->user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [$this->test_role1->id, $this->test_role2->id],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 2);
        $this->assertDatabaseHas('room_restrictions', ['room_id' => $test_room->id, 'role_id' => $this->test_role1->id]);
        $this->assertDatabaseHas('room_restrictions', ['room_id' => $test_room->id, 'role_id' => $this->test_role2->id]);
    }

    /**
     * Test if the route can update number of restrictions, by reducing, from the system
     *
     * @return void
     */
    public function test_restrictions_controller_update_2_to_1()
    {
        $test_room = $this->rooms->random();
        $test_room->restrictions()->sync([$this->test_role1->id, $this->test_role2->id]);
        $this->assertDatabaseCount('room_restrictions', 2);

        //Test if we can remove a restriction if there are multiple attached and not lose any
        $response = $this->actingAs($this->user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [$this->test_role2->id],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 1);
        $this->assertDatabaseHas('room_restrictions', ['room_id' => $test_room->id, 'role_id' => $this->test_role2->id]);
    }

    /**
     * Test if the route can remove restrictions from the system
     *
     * @return void
     */
    public function test_restrictions_controller_remove()
    {
        $test_room = $this->rooms->random();
        $test_room->restrictions()->sync([$this->test_role1->id, $this->test_role2->id]);
        $this->assertDatabaseCount('room_restrictions', 2);

        //Test if we can remove restrictions
        $response = $this->actingAs($this->user)->put('room/restrictions/' . $test_room->id, [
            'restrictions' => [],
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseCount('room_restrictions', 0);

    }

    /**
     * Test restrictions rooms scope filter
     *
     * @return void
     */
    public function test_restrictions_scope_for_rooms()
    {

        $role = $this->user->roles()->first();

        $restricted_room = Room::inRandomOrder()->first();

        $room_query = Room::all();
        $result = Room::hideUserRestrictions($this->user)->get();
        //See if all rooms are available before restriction
        $this->assertEquals($result, $room_query);

        $restricted_room->restrictions()->sync($role->id);
        $this->assertDatabaseCount('room_restrictions', 1);

        $result = Room::hideUserRestrictions($this->user)->get();
        $this->assertNotEquals($result, $room_query);

        $this->assertEquals(4,$result->count());
        $this->assertNotContains($restricted_room, $result);
    }

}
