<?php

namespace Tests\Unit;

use App\Models\BookingRequest;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterPersonalBookingRequestsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test filtering booking request with status
     *
     * @return void
     */
    public function test_filter_by_status()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);
        $booking = BookingRequest::factory()->create([
            'status' => BookingRequest::REVIEW]);
        $booking2 = BookingRequest::factory()->create([
            'status' => BookingRequest::PENDING]);
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking->id,
        ]);
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking2->id,
        ]);


        $response = $this->actingAs($user)->postJson('/api/filterMyBookingRequests', ['selectStatus' => BookingRequest::REVIEW]);
        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();

        $res_id = collect($response->json())->pluck('id');
        $this->assertContains($booking->id, $res_id);
        $this->assertNotContains($booking2->id, $res_id);

        $response2 = $this->actingAs($user)->postJson('/api/filterMyBookingRequests', ['selectStatus' => BookingRequest::APPROVED]);
        $response2
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        $res_id_2 = collect($response2->json())->pluck('id');
        $this->assertNotContains($booking->id, $res_id_2);
        $this->assertNotContains($booking2->id, $res_id_2);
    }

    /**
     * Test filtering booking request with date
     *
     * @return void
     */
    public function test_filter_by_date()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);
        $reservation = Reservation::factory()->create();
        $this->assertDatabaseHas('booking_requests', [
            'id' => $reservation->booking_request_id,
        ]);


        $response = $this->actingAs($user)->postJson('/api/filterMyBookingRequests',
            ['dateCheck' => $reservation->start_time]);
        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();

        $res_id = collect($response->json())->pluck('id');
        $this->assertContains($reservation->booking_request_id, $res_id);


        $response2 = $this->actingAs($user)->postJson('/api/filterMyBookingRequests', ['dateCheck' => Carbon::make($reservation->start_time)->subDay()]);
        $response2
            ->assertStatus(200);
        $res_id_2 = collect($response2->json())->pluck('id');
        $this->assertNotContains($reservation->booking_request_id, $res_id_2);


    }
}
