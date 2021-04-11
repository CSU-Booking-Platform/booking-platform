<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Comment;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\ReservationFactory;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function user_can_post_comments_on_booking_request()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $user = $this->createUserWithPermissions(['bookings.create']);

        $date = $this->faker->dateTimeInInterval('+' . $room->min_days_advance . ' days', '+' . ($room->max_days_advance - $room->min_days_advance) . ' days');

        $this->createReservationAvailabilities($date, $room);

        $start = Carbon::parse($date);
        $end = $start->copy()->addMinutes(4);

        $booking = BookingRequest::factory()->hasReservations(1, ['room_id'=>$room->id])->create(['status'=>BookingRequest::REVIEW]);

        $this->assertDatabaseCount('comments', 0);

        $comment = '<p>test</p>';
        $response = $this->actingAs($user)->post("/bookings/{$booking->id}/comment/",
            ['comment' => $comment]
        );
        $response->assertStatus(302);
        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseHas('comments', [
            'system' => false,
            'body' => $comment,
        ]);
    }

    private function createReservationAvailabilities($start, $room)
    {
        $openingHours = Carbon::parse($start)->subMinutes(5)->toTimeString();
        $closingHours = Carbon::parse($start)->addMinutes(10)->toTimeString();

        Availability::create([
            'room_id' => $room->id,
            'opening_hours' => $openingHours,
            'closing_hours' => $closingHours,
            'weekday' => Carbon::parse($start)->format('l')
        ]);
    }
}
