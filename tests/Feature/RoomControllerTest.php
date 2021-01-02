<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     */
    public function admins_can_create_rooms()
    {
        $room = Room::factory()->make();
        $user = User::factory()->make();

        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);

        $response = $this->actingAs($user)->post('/rooms', ['name' => $room->name , 'number' => $room->number,
        'floor' => $room->floor ,'building' => $room->building, 'status' => $room->status]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('rooms', ['name' => $room->name , 'number' => $room->number,
        'floor' => $room->floor ,'building' => $room->building, 'status' => $room->status]);
        
    }

    /**
     * @test
     */
    public function testUsersIndexPageLoads()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get('/rooms');
        $response->assertOk();
        $response->assertSee("Rooms");
    }

    /**
     * @test
     */
    public function admins_can_update_rooms()
    {
        $room = Room::factory()->create();
        $user = User::factory()->make();

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building, 'status' => $room->status
        ]);

        $response = $this->actingAs($user)->put('/rooms/' . $room->id, [
            'name' => 'the room', 'number' => '24',
            'floor' => '2009', 'building' => 'wiseau', 'status' => 'available'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('rooms', [
            'name' => 'the room', 'number' => '24',
            'floor' => '2009', 'building' => 'wiseau', 'status' => 'available'
        ]);
    }

    /**
     * @test
     */
    public function admins_can_delete_rooms()
    {
        $room = Room::factory()->create();
        $user = User::factory()->make();

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building
        ]);

        $response = $this->actingAs($user)->delete('/rooms/' . $room->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);
    }
}
