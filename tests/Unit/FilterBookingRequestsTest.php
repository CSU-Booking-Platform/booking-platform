<?php

namespace Tests\Unit;

use App\Models\BookingRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterBookingRequestsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test filtering booking request with status
     *
     * @return void
     */
    public function test_filter_by_status(){
        $user = $this->createUserWithPermissions(['bookings.approve']);
        $booking = BookingRequest::factory()->create([
            'status' => 'review']);
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking->id,
        ]);


        $response = $this->actingAs($user)->postJson('/api/filterBookingRequests', ['status_list' => ['review' => true]]);
        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();

        $res_id = collect($response->json())->pluck('id');
        $this->assertContains($booking->id, $res_id);

        $response2 = $this->actingAs($user)->postJson('/api/filterBookingRequests', ['status_list' => ['review' => false]]);
        $response2
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        $res_id_2 = collect($response2->json())->pluck('id');
        $this->assertNotContains($booking->id, $res_id_2);
    }

    /**
     * Test filtering booking request with status
     *
     * @return void
     */
    public function test_filter_by_dates(){
        $user = $this->createUserWithPermissions(['bookings.approve']);
        $booking = BookingRequest::factory()->create();
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking->id,
        ]);

        $response = $this->actingAs($user)->postJson('/api/filterBookingRequests', ['date_range_end' => Carbon::now()->addMinutes(5)]);
        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();

        $res_id = collect($response->json())->pluck('id');
        $this->assertContains($booking->id, $res_id);


        $response2 = $this->actingAs($user)->postJson('/api/filterBookingRequests', ['date_range_end' => Carbon::now()->subDay()]);
        $response2
            ->assertStatus(200);
        $res_id_2 = collect($response2->json())->pluck('id');
        $this->assertNotContains($booking->id, $res_id_2);


        $response3 = $this->actingAs($user)->postJson('/api/filterBookingRequests', ['date_range_start' => Carbon::now()->subDay()]);
        $response3
            ->assertStatus(200)
            ->assertSessionHasNoErrors();

        $res_id_3 = collect($response->json())->pluck('id');
        $this->assertContains($booking->id, $res_id_3);


        $response4 = $this->actingAs($user)->postJson('/api/filterBookingRequests', ['date_range_start' => Carbon::now()->addMinutes(5)]);
        $response4
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        $res_id_4 = collect($response2->json())->pluck('id');
        $this->assertNotContains($booking->id, $res_id_4);
    }
}
