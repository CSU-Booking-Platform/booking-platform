<?php

namespace Tests\Feature;

use App\Http\Resources\RoomResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomCustomDateRestrictionsTest extends TestCase
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
   * Test if the route can create date restrictions from the system
   *
   * @return void
   */
  public function test_date_restrictions_controller_create()
  {
    $test_room = $this->rooms->random();
    $this->assertDatabaseCount('custom_date_restrictions', 0);

    $response = $this->actingAs($this->user)->put("/admin/rooms/{$test_room->id}/date-restrictions", [
      'date_restrictions' => [
        $this->test_role1->id => [
          "min_days_advance" => 5,
          "max_days_advance" => 10
        ],
        $this->test_role2->id => [//test not adding to role
          "min_days_advance" => null,
          "max_days_advance" => null
        ],
        //Test for innexistant id (used since array indexes get set to null if no role is associated with it)
        45645 => null
      ],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertStatus(302);
    $this->assertDatabaseCount('custom_date_restrictions', 1);
    $this->assertDatabaseHas('custom_date_restrictions',
      [
        'room_id' => $test_room->id,
        'role_id' => $this->test_role1->id,
        "min_days_advance" => 5,
        "max_days_advance" => 10
      ]);
  }

  /**
   * Test if the route can update date restrictions
   *
   * @return void
   */
  public function test_date_restrictions_controller_update()
  {
    $test_room = $this->rooms->random();
    $this->createInitialDataForRoom($test_room);

    //Test if we can add another restriction if one is already there
    $response = $this->actingAs($this->user)->put("/admin/rooms/{$test_room->id}/date-restrictions", [
      'date_restrictions' => [
        $this->test_role1->id => [
          "min_days_advance" => 500,
          "max_days_advance" => 1000
        ],
        $this->test_role2->id => [
          "min_days_advance" => 200,
          "max_days_advance" => 20000
        ],
      ],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertStatus(302);
    $this->assertDatabaseCount('custom_date_restrictions', 2);
    $this->assertDatabaseHas('custom_date_restrictions', [
      'room_id' => $test_room->id,
      'role_id' => $this->test_role1->id,
      "min_days_advance" => 500,
      "max_days_advance" => 1000
    ]);
    $this->assertDatabaseHas('custom_date_restrictions', [
      'room_id' => $test_room->id,
      'role_id' => $this->test_role2->id,
      "min_days_advance" => 200,
      "max_days_advance" => 20000
    ]);
  }

  /**
   * Test if the route can remove date restrictions
   *
   * @return void
   */
  public function test_date_restrictions_controller_remove()
  {
    $test_room = $this->rooms->random();
    $this->createInitialDataForRoom($test_room);

    //Test if we can add another restriction if one is already there
    $response = $this->actingAs($this->user)->put("/admin/rooms/{$test_room->id}/date-restrictions", [
      'date_restrictions' => [
        $this->test_role1->id => [
          "min_days_advance" => null,
          "max_days_advance" => null
        ],
        $this->test_role2->id => [
          "min_days_advance" => null,
          "max_days_advance" => null
        ],
      ],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertStatus(302);
    $this->assertDatabaseCount('room_restrictions', 0);

  }

  /**
   * @param Room $test_room
   * helper function that simply creates date restrictions
   */
  private function createInitialDataForRoom(Room &$test_room){
    $test_room->dateRestrictions()->sync([
      $this->test_role1->id=>[
        "min_days_advance" => 1,
        "max_days_advance" => 10
      ],
      $this->test_role2->id=>[
        "min_days_advance" => 2,
        "max_days_advance" => 20
      ]]);

    //Make sure everything is ready
    $this->assertDatabaseCount('custom_date_restrictions', 2);
    $this->assertDatabaseHas('custom_date_restrictions', [
      'room_id' => $test_room->id,
      'role_id' => $this->test_role1->id,
      "min_days_advance" => 1,
      "max_days_advance" => 10
    ]);
    $this->assertDatabaseHas('custom_date_restrictions', [
      'room_id' => $test_room->id,
      'role_id' => $this->test_role2->id,
      "min_days_advance" => 2,
      "max_days_advance" => 20
    ]);

  }

  /**
   * Test the room resource class that is used to convert a room into json.
   * Allows making date restrictions into simpler data
   * Normal room controller does not cover this since there are no date restrictions initially
   */
  public function test_room_resource_class(){
    $test_room = $this->rooms->random();
    $this->createInitialDataForRoom($test_room);
    $response = $this->actingAs($this->user)->get('/admin/rooms');
    $response->assertOk();
    $room_response = json_decode(json_encode(collect($response->getOriginalContent()
      ->getData()['page']['props']['rooms'])
      ->where('id', $test_room->id)->pluck('date_restrictions')->first()), true);
    $this->assertEquals(1, $room_response[$this->test_role1->id]['min']);
    $this->assertEquals(10, $room_response[$this->test_role1->id]['max']);
    $this->assertEquals(2, $room_response[$this->test_role2->id]['min']);
    $this->assertEquals(20, $room_response[$this->test_role2->id]['max']);
  }

  //TODO::When date validation bug is fixed create test to see if validation checks for custom dates restrictions

}
