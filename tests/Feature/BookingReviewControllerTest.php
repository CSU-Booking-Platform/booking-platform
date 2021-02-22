<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function bookings_review_index_page_loads()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();

        $response = $this->actingAs($user)->get(route('bookings.reviews.index'));
        $response->assertForbidden();

        $this->assertFalse($user->can('bookings.approve'));
        $user->givePermissionTo('bookings.approve')->save();
        $this->assertTrue($user->can('bookings.approve'));

        $response = $this->actingAs($user)->get(route('bookings.reviews.index'));
        $response->assertOk();
        $response->assertSee("bookings.reviews.index");

    }

    /**
     * @test
     */
    public function booking_details_review_page_loads()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->create();
        $room = Room::factory()->create();
        $booking->rooms()->attach($room->id, [
            'created_at' => now(),
            'updated_at' => now(),
            'start_time' => now(),
            'end_time' => now()->addHours(2),
        ]);

        $response = $this->actingAs($user)->get(route('bookings.reviews.show', ['booking' => $booking]));
        $response->assertOk();
        $response->assertSee("bookings.reviews.show");
        $response->assertSee($booking->event['description']);

    }

    /**
     * @test
     */
    public function can_approve_booking_request()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->create(['status' => 'review']);
        $room = Room::factory()->create();
        $booking->rooms()->attach($room->id, [
            'created_at' => now(),
            'updated_at' => now(),
            'start_time' => now(),
            'end_time' => now()->addHours(2),
        ]);

        $this->assertNotEquals('approved', $booking->status);
        $response = $this->actingAs($user)->post(route('bookings.reviews.update', ['booking' => $booking]), [
            'status' => 'approved'
        ]);
        $response->assertRedirect(route('bookings.reviews.index'));
        $this->assertEquals('approved', $booking->refresh()->status);

    }

    /**
     * @test
     */
    public function can_refuse_booking_request()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->create(['status' => 'review']);
        $room = Room::factory()->create();
        $booking->rooms()->attach($room->id, [
            'created_at' => now(),
            'updated_at' => now(),
            'start_time' => now(),
            'end_time' => now()->addHours(2),
        ]);

        $this->assertNotEquals('refused', $booking->status);
        $response = $this->actingAs($user)->post(route('bookings.reviews.update', ['booking' => $booking]), [
            'status' => 'refused'
        ]);
        $response->assertRedirect(route('bookings.reviews.index'));
        $this->assertEquals('refused', $booking->refresh()->status);
    }

    /**
     * @test
     */
    public function booking_review_action_is_validated()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->create();
        $room = Room::factory()->create();
        $booking->rooms()->attach($room->id, [
            'created_at' => now(),
            'updated_at' => now(),
            'start_time' => now(),
            'end_time' => now()->addHours(2),
        ]);

        $response = $this->actingAs($user)->post(route('bookings.reviews.update', ['booking' => $booking]), [
            'status' => null
        ]);
        $response->assertSessionHasErrorsIn('reviewBooking', ['status']);
    }
}
