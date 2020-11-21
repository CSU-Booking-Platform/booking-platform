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
        'floor' => $room->floor ,'building' => $room->building]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('rooms', ['name' => $room->name , 'number' => $room->number,
        'floor' => $room->floor ,'building' => $room->building]);
        
    }
}
