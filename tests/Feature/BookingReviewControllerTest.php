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

        $booking = BookingRequest::factory()->hasReviewers(1)->create(['status'=>BookingRequest::PENDING]);
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

        $booking = $booking->fresh();
        $this->assertEquals(BookingRequest::REVIEW, $booking->status);
    }

    /**
     * @test
     */
    public function booking_details_view_page_loads()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->hasReviewers(1)->create();
        $room = Room::factory()->create();
        $booking->rooms()->attach($room->id, [
            'created_at' => now(),
            'updated_at' => now(),
            'start_time' => now(),
            'end_time' => now()->addHours(2),
        ]);

        $response = $this->actingAs($user)->get(route('bookings.view', ['booking' => $booking]));
        $response->assertOk();
        $response->assertSee("bookings.view");
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

        $booking = BookingRequest::factory()->create(['status' => BookingRequest::REVIEW]);
        $room = Room::factory()->create();
        $booking->rooms()->attach($room->id, [
            'created_at' => now(),
            'updated_at' => now(),
            'start_time' => now(),
            'end_time' => now()->addHours(2),
        ]);

        $this->assertNotEquals(BookingRequest::APPROVED, $booking->status);
        $response = $this->actingAs($user)->post(route('bookings.reviews.update', ['booking' => $booking]), [
            'status' => BookingRequest::APPROVED
        ]);
        $response->assertRedirect(route('bookings.reviews.index'));
        $this->assertEquals(BookingRequest::APPROVED, $booking->refresh()->status);

    }

    /**
     * @test
     */
    public function can_refuse_booking_request()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->create(['status' => BookingRequest::REVIEW]);
        $room = Room::factory()->create();
        $booking->rooms()->attach($room->id, [
            'created_at' => now(),
            'updated_at' => now(),
            'start_time' => now(),
            'end_time' => now()->addHours(2),
        ]);

        $this->assertNotEquals(BookingRequest::REFUSED, $booking->status);
        $response = $this->actingAs($user)->post(route('bookings.reviews.update', ['booking' => $booking]), [
            'status' => BookingRequest::REFUSED
        ]);
        $response->assertRedirect(route('bookings.reviews.index'));
        $this->assertEquals(BookingRequest::REFUSED, $booking->refresh()->status);
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

    /**
     * @test
     */
    public function booking_reviewer_assign_is_validated()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->make();
        $user->givePermissionTo('bookings.approve')->save();

        $booking = BookingRequest::factory()->hasReservations(1)->create();

        // validation should check that ids exists in table
        $response = $this->actingAs($user)->post(route('bookings.reviews.assign', ['booking' => $booking]), [
            'reviewers_ids' => [24],
        ]);
        $response->assertSessionHasErrorsIn('setBookingReviewers');

        // validation should check for duplicates
        $response = $this->actingAs($user)->post(route('bookings.reviews.assign', ['booking' => $booking]), [
            'reviewers_ids' => [42, 42],
        ]);
        $response->assertSessionHasErrorsIn('setBookingReviewers');

        // validation should check that input is an array
        $response = $this->actingAs($user)->post(route('bookings.reviews.assign', ['booking' => $booking]), [
            'reviewers_ids' => 'not_an_array',
        ]);
        $response->assertSessionHasErrorsIn('setBookingReviewers', ['reviewers_ids']);
    }

    /**
     * @test
     */
    public function can_reassign_booking_to_another_user()
    {
        // setup a user with no assigned bookings
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo('bookings.approve')->save();
        // setup a fresh booking
        $booking = BookingRequest::factory()->hasReservations(1)->hasReviewers(1)->create();
        // assert our users doesn't have any bookings
        $this->assertDatabaseMissing('booking_reviewers', ['user_id' => $user->id]);
        $this->assertNotContains($user->id, $booking->reviewers()->pluck('id'));
        // submit post req to reassign this booking's reviewer
        $response = $this->actingAs($user)->post(route('bookings.reviews.assign', ['booking' => $booking]), [
            'reviewers_ids' => [$user->id],
        ]);
        // assert request was successful
        $response->assertSessionHasNoErrors();
        // assert new user is now responsible for this booking
        $this->assertDatabaseHas('booking_reviewers', ['user_id' => $user->id]);
        $this->assertContains($user->id, $booking->reviewers()->pluck('id'));
        $this->assertEquals(1, $booking->reviewers()->count());
    }

    /**
     * @test
     */
    public function can_assign_multiple_reviewers_to_a_booking()
    {
        // a few fresh users
        $this->seed(RolesAndPermissionsSeeder::class);
        $users = User::factory()->count(random_int(2,4))->create();
        $users->first()->givePermissionTo('bookings.approve')->save();
        // setup a fresh booking
        $booking = BookingRequest::factory()->hasReservations(1)->create();
        // assert that booking has no reviewers
        $this->assertEquals(0, $booking->reviewers()->count());
        // submit post req to assign multiple reviewers
        $response = $this->actingAs($users->first())->post(route('bookings.reviews.assign', ['booking' => $booking]), [
            'reviewers_ids' => $users->pluck('id')->toArray(),
        ]);
        // assert request was successful
        $response->assertSessionHasNoErrors();
        // assert all users are assigned to new booking
        $this->assertEquals($users->count(), $booking->reviewers()->count());
        $this->assertEquals($users->pluck('id'), $booking->reviewers()->pluck('id'));
    }

    /**
     * @test
     */
    public function can_fetch_a_list_of_available_reviewers()
    {
        // setup fresh reviewers
        $this->seed(RolesAndPermissionsSeeder::class);
        $users = User::factory()->count(random_int(5,10))->create();

        // assert that there are currently no reviewers in the system
        $this->assertEquals(0, User::permission('bookings.approve')->count());

        $response = $this->actingAs(
            $users->first()->givePermissionTo('bookings.approve') // temporarily give permission to fetch list
        )->get(route('api.bookings.reviews.assignable'));
        // assert request was successful
        $response->assertSessionHasNoErrors();
        $response->assertOk();
        $response->assertJsonCount(1);

        // give all our users the ability to review bookings
        $users->each->givePermissionTo('bookings.approve');
        $users->each->save();

        // assert that all our users can review bookings
        $this->assertEquals($users->count(), User::permission('bookings.approve')->count());
        $this->assertEquals($users->pluck('id'), User::permission('bookings.approve')->pluck('id'));

        $response = $this->actingAs($users->first())->get(route('api.bookings.reviews.assignable'));
        // assert request was successful
        $response->assertSessionHasNoErrors();
        $response->assertOk();
        $response->assertJsonCount($users->count());
        $response->assertJsonStructure([[ 'id', 'name', 'email', 'profile_photo_url' ]]);
    }


    /**
     * @test
     */
    public function user_model_belongs_to_many_reviews()
    {
        // setup fresh user & booking
        $booking = BookingRequest::factory()->hasReviewers(1)->hasReservations(1)->create();

        // attach user
        $reviewer = User::factory()->create();
        $booking->reviewers()->attach($reviewer->id);

        // verify reviewer can see the booking but only as a reviewer
        $this->assertEquals(1, $reviewer->bookingsToReview()->count());
        $this->assertContains($booking->id, $reviewer->bookingsToReview()->pluck('id'));
        $this->assertNotContains($booking->id, $reviewer->bookingRequests()->pluck('id'));


    }
}
