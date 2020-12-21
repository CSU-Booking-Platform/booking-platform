<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\BookingRequest;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingRequestControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     */
    public function user_can_create_booking_request()
    {
        $room = Room::factory()->make();
        $user = User::factory()->make();
        $booking_request = BookingRequest::factory()->make();

        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/book', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);
        
    }

    // /**
    //  * @test
    //  */
    // public function testUsersIndexPageLoads()
    // {
    //     $user = User::factory()->make();
    //     $response = $this->actingAs($user)->get('/rooms');
    //     $response->assertOk();
    //     $response->assertSee("Rooms");
    // }

    // /**
    //  * @test
    //  */
    // public function admins_can_update_rooms()
    // {
    //     $room = Room::factory()->create();
    //     $user = User::factory()->make();

    //     $this->assertDatabaseHas('rooms', [
    //         'name' => $room->name, 'number' => $room->number,
    //         'floor' => $room->floor, 'building' => $room->building
    //     ]);

    //     $response = $this->actingAs($user)->put('/rooms/' . $room->id, [
    //         'name' => 'the room', 'number' => '24',
    //         'floor' => '2009', 'building' => 'wiseau'
    //     ]);

    //     $response->assertStatus(302);

    //     $this->assertDatabaseHas('rooms', [
    //         'name' => 'the room', 'number' => '24',
    //         'floor' => '2009', 'building' => 'wiseau'
    //     ]);
    // }

    // /**
    //  * @test
    //  */
    // public function admins_can_delete_rooms()
    // {
    //     $room = Room::factory()->create();
    //     $user = User::factory()->make();

    //     $this->assertDatabaseHas('rooms', [
    //         'name' => $room->name, 'number' => $room->number,
    //         'floor' => $room->floor, 'building' => $room->building
    //     ]);

    //     $response = $this->actingAs($user)->delete('/rooms/' . $room->id);

    //     $response->assertStatus(302);
    //     $this->assertDatabaseMissing('rooms', ['name' => $room->name]);
    // }
}
