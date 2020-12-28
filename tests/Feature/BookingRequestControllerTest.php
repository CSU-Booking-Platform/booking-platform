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
        $room = Room::factory()->create(['status'=>'available']);
        $user = User::factory()->create();
        $booking_request = BookingRequest::factory()->make();


        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/bookings', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time->toDateTimeString(), 'end_time' => $booking_request->end_time->toDateTimeString()]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);
        
    }


    /**
     * @test
     */
    public function booking_request_for_unavailable_room()
    {
        $room = Room::factory()->create(['status'=>'unavailable']);
        $user = User::factory()->create();
        $booking_request = BookingRequest::factory()->make();


        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/bookings', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time->toDateTimeString(), 'end_time' => $booking_request->end_time->toDateTimeString()]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);
        
    }

    /**
     * @test
     */
    public function users_can_update_booking_requests()
    {
        $room = Room::factory()->create();
        $user = User::factory()->create();
        $booking_request = BookingRequest::factory()->create();

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);

        $response = $this->actingAs($user)->put('/bookings/' . $booking_request->id, [
            'room_id' => $room->id, 'start_time' => $booking_request->start_time->toDateTimeString(),
            'end_time' => $booking_request->end_time->toDateTimeString()
        ]);
       
        $response->assertStatus(302);

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $room->id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);
    }

    /**
     * @test
     */
    public function users_can_delete_booking_requests()
    {
        $room = Room::factory()->create();
        $user = User::factory()->create();
        $booking_request = BookingRequest::factory()->create();

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);

        $response = $this->actingAs($user)->delete('/bookings/' . $booking_request->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('booking_requests', ['id' => $booking_request->id.'']);
    }

    /**
     * @test
     */
    public function testBookingRequestsIndexPageLoads()
    {
        $room = Room::factory()->make();
        $user = User::factory()->make();
        $booking_request = BookingRequest::factory()->make();

        $response = $this->actingAs($user)->get('/bookings');
        $response->assertOk();
        $response->assertSee("BookingRequests");
    }

}
