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
        $user = User::factory()->create();
        $booking_request = BookingRequest::factory()->make();

        // dd($booking_request->start_time, $booking_request->end_time);

        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/book', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time->toDateTimeString(), 'end_time' => $booking_request->end_time->toDateTimeString()]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);
        
    }

    // /**
    //  * @test
    //  */
    public function users_can_update_booking_requests()
    {
        $room = Room::factory()->create();
        $user = User::factory()->create();
        $booking_request = BookingRequest::factory()->create();

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);

        $response = $this->actingAs($user)->put('/book/' . $booking_request->id, [
            'room_id' => $room->id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('booking_request', [
            'room_id' => $room->id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);
    }

}
