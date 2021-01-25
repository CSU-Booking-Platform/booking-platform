<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @var \Faker\Generator
   */
  public $faker;

  public function setUp(): void
  {
    parent::setUp();
    $this->faker = Factory::create();
  }

  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_storing_multiple_reservations()
  {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    $booking_request = $this->createBookingRequest( false);
    $reservation = $this->createReservation($room,$booking_request, false);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $reservation2 = $this->createReservationCopy($reservation, false);
//    $reservation2->end_time = Carbon::parse($reservation2->end_time)->addDay()->format('Y-m-d\TH:i');
    $response = $this->actingAs($user)->post('/reservation' , [
      'room_id' => $room->id,
      'recurrences' => [
        ['start_time' => $reservation->start_time->format('Y-m-d\TH:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d\TH:i:00')],
        ['start_time' => $reservation2->start_time->format('Y-m-d\TH:i:00'), 'end_time' => $reservation2->end_time->format('Y-m-d\TH:i:00')],
      ]
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 2);
    $this->assertDatabaseHas('reservations', [
      'room_id' => $room->id,
      'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
      'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
    ]);

    $this->assertDatabaseHas('reservations', [
      'room_id' => $room->id,
      'start_time' => $reservation2->start_time->format('Y-m-d H:i:00'),
      'end_time' => $reservation2->end_time->format('Y-m-d H:i:00'),
    ]);
  }

  public function test_deleting_reservations()
  {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    $booking_request = $this->createBookingRequest( );
    $reservation = $this->createReservation($room,$booking_request);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);

    $response = $this->actingAs($user)->delete('/reservation/'.$reservation->id );
    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);

  }

  public function test_update_reservations()
  {
    $user = User::factory()->create();
    $room = Room::factory()->create();

    $booking_request = $this->createBookingRequest( );
    $reservation = $this->createReservation($room,$booking_request,);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $reservationNew = $reservation->replicate();
    $reservationNew->start_time = Carbon::parse($reservation->start_time)->addMinute()->format('Y-m-d\TH:i');
    $reservationNew->end_time = Carbon::parse($reservation->end_time)->addMinute()->format('Y-m-d\TH:i');
    $response = $this->actingAs($user)->put('/reservation/'.$reservation->id, [
      'room_id' => $reservationNew->room_id,
      'recurrences'=>[
        [
          'start_time' => $reservationNew->start_time->format('Y-m-d\TH:i:00'),
          'end_time' => $reservationNew->end_time->format('Y-m-d\TH:i:00'),
        ]
      ],
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $this->assertDatabaseHas('reservations', [
      'room_id' => $room->id,
      'start_time' => $reservationNew->start_time->format('Y-m-d H:i:00'),
      'end_time' => $reservationNew->end_time->format('Y-m-d H:i:00'),
    ]);
  }

  public function test_conflict_block_case_A()
  {
    //Case A: The reservation ends after another is supposed to start
    $user = User::factory()->create();
    $room = Room::factory()->create();

    $booking_request = $this->createBookingRequest( );
    $reservation = $this->createReservation($room,$booking_request);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $reservationNew = $reservation->replicate();
    $reservationNew->start_time = Carbon::parse($reservation->start_time)->subMinute()->format('Y-m-d\TH:i');
    $reservationNew->end_time = Carbon::parse($reservation->start_time)->addMinute()->format('Y-m-d\TH:i');
    $response = $this->actingAs($user)->post('/reservation', [
      'room_id' => $reservationNew->room_id,
      'recurrences'=>[
        [
          'start_time' => $reservationNew->start_time->format('Y-m-d\TH:i:00'),
          'end_time' => $reservationNew->end_time->format('Y-m-d\TH:i:00'),
        ]
      ],
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $this->assertDatabaseHas('reservations', [
      'room_id' => $room->id,
      'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
      'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
    ]);
  }

  public function test_conflict_block_case_B()
  {
    //Case B: The reservation starts after another is supposed to end
    $user = User::factory()->create();
    $room = Room::factory()->create();

    $booking_request = $this->createBookingRequest( );
    $reservation = $this->createReservation($room,$booking_request);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $reservationNew = $reservation->replicate();
    $reservationNew->start_time = Carbon::parse($reservation->end_time)->subMinute()->format('Y-m-d\TH:i');
    $reservationNew->end_time = Carbon::parse($reservation->end_time)->addMinute()->format('Y-m-d\TH:i');
    $response = $this->actingAs($user)->post('/reservation', [
      'room_id' => $reservationNew->room_id,
      'recurrences'=>[
        [
          'start_time' => $reservationNew->start_time->format('Y-m-d\TH:i:00'),
          'end_time' => $reservationNew->end_time->format('Y-m-d\TH:i:00'),
        ]
      ],
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $this->assertDatabaseHas('reservations', [
      'room_id' => $room->id,
      'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
      'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
    ]);
  }

  public function test_conflict_block_case_C()
  {
    //Case C: The new reservation starts before and ends after
    $user = User::factory()->create();
    $room = Room::factory()->create();

    $booking_request = $this->createBookingRequest( );
    $reservation = $this->createReservation($room,$booking_request);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $reservationNew = $reservation->replicate();
    $reservationNew->start_time = Carbon::parse($reservation->start_time)->subMinute()->format('Y-m-d\TH:i');
    $reservationNew->end_time = Carbon::parse($reservation->end_time)->addMinute()->format('Y-m-d\TH:i');
    $response = $this->actingAs($user)->post('/reservation', [
      'room_id' => $reservationNew->room_id,
      'recurrences'=>[
        [
          'start_time' => $reservationNew->start_time->format('Y-m-d\TH:i:00'),
          'end_time' => $reservationNew->end_time->format('Y-m-d\TH:i:00'),
        ]
      ],
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $this->assertDatabaseHas('reservations', [
      'room_id' => $room->id,
      'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
      'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
    ]);
  }

  public function test_conflict_block_case_D()
  {
    //Case D: The reservation is inside another one.
    $user = User::factory()->create();
    $room = Room::factory()->create();

    $booking_request = $this->createBookingRequest( );
    $reservation = $this->createReservation($room,$booking_request);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $reservationNew = $reservation->replicate();
    $reservationNew->start_time = Carbon::parse($reservation->start_time)->addMinute()->format('Y-m-d\TH:i');
    $reservationNew->end_time = Carbon::parse($reservation->end_time)->subMinute()->format('Y-m-d\TH:i');
    $response = $this->actingAs($user)->post('/reservation', [
      'room_id' => $reservationNew->room_id,
      'recurrences'=>[
        [
          'start_time' => $reservationNew->start_time->format('Y-m-d\TH:i:00'),
          'end_time' => $reservationNew->end_time->format('Y-m-d\TH:i:00'),
        ]
      ],
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 1);
    $this->assertDatabaseCount('reservations', 1);
    $this->assertDatabaseHas('reservations', [
      'room_id' => $room->id,
      'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
      'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
    ]);
  }

  public function test_storing_multiple_reservations_fail_if_one_is_denied()
  {
    $user = User::factory()->create();
    $room = Room::factory()->create();
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);
    $booking_request = $this->createBookingRequest( false);
    $reservation = $this->createReservation($room,$booking_request, false);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    $reservation2 = $this->createReservationCopy($reservation, false);
    $reservation2->end_time = Carbon::parse($reservation2->end_time)->addDay()->format('Y-m-d\TH:i');
    $response = $this->actingAs($user)->post('/reservation' , [
      'room_id' => $room->id,
      'recurrences' => [
        ['start_time' => $reservation->start_time->format('Y-m-d\TH:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d\TH:i:00')],
        ['start_time' => $reservation2->start_time->format('Y-m-d\TH:i:00'), 'end_time' => $reservation2->end_time->format('Y-m-d\TH:i:00')],
      ]
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);

  }

  public function test_storing_too_early()
  {
    $user = User::factory()->create();
    $room = Room::factory()->create();
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);
    $booking_request = $this->createBookingRequest( false);
    $reservation = $this->createReservation($room,$booking_request, false);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    Carbon::setTestNow(now()->subDays($room->max_days_advance + 1));

    $response = $this->actingAs($user)->post('/reservation' , [
      'room_id' => $room->id,
      'recurrences' => [
        ['start_time' => $reservation->start_time->format('Y-m-d\TH:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d\TH:i:00')],
      ]
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);
  }

  public function test_storing_too_late()
  {
    $user = User::factory()->create();
    $room = Room::factory()->create(['min_days_advance' => 10, 'max_days_advance' => 100, ]);
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);
    $booking_request = $this->createBookingRequest( false);
    $reservation = $this->createReservation($room,$booking_request, false);
    $this->createReservationAvailabilities($reservation->start_time, $room);
    Carbon::setTestNow(Carbon::parse($reservation->start_time)->subDays( 1));

    $response = $this->actingAs($user)->post('/reservation' , [
      'room_id' => $room->id,
      'recurrences' => [
        ['start_time' => $reservation->start_time->format('Y-m-d\TH:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d\TH:i:00')],
      ]
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);
  }

  public function test_relations_set(){
    $room = Room::factory()->create();
    $booking_request = $this->createBookingRequest();
    $reservation = $this->createReservation($room,$booking_request);

    $this->assertEquals(1, $room->bookingRequests()->count());
    $this->assertEquals(1, $booking_request->rooms()->count());
    $this->assertEquals(1, $reservation->room()->count());
    $this->assertEquals(1, $reservation->bookingRequest()->count());

  }
  /**
   * helper functions
   */

  private function createReservationAvailabilities($start, $room)
  {
    $openingHours = Carbon::parse($start)->subMinutes(1)->toTimeString();
    $closingHours = Carbon::parse($start)->addHours(10)->toTimeString();

    Availability::create([
      'room_id' => $room->id,
      'opening_hours' => $openingHours,
      'closing_hours' => $closingHours,
      'weekday' => Carbon::parse($start)->format('l')
    ]);
  }

  private function createReservation($room, $bookingRequest, $create = true)
  {
    $date = $this->faker->dateTimeInInterval(
      '+' . $room->min_days_advance . ' days',
      '+' . ($room->max_days_advance - $room->min_days_advance) . ' days'
    )->setTime(12,0);

    $data = [
      'room_id' => $room->id,
      'booking_request_id' => $bookingRequest->id,
      'start_time' => Carbon::parse($date)->format('Y-m-d\TH:i'),
      'end_time' => Carbon::parse($date)->addMinutes(30)->format('Y-m-d\TH:i'),
    ];
    if ($create) {
      $reservation = Reservation::factory()->create($data);
    } else {
      $reservation = Reservation::factory()->make($data);
    }
    return $reservation;
  }

  private function createBookingRequest($create = true)
  {
    if ($create) {
      $booking_request = BookingRequest::factory()->create();
    } else {
      $booking_request = BookingRequest::factory()->make();
    }
    return $booking_request;
  }

  private function createReservationCopy(Reservation $reservation, bool $create = true)
  {
    $data = $reservation->attributesToArray();
    $data = [
      'start_time' => Carbon::parse($data['start_time'])->addMinute()->format('Y-m-d\TH:i'),
      'end_time' => Carbon::parse($data['end_time'])->addMinute()->format('Y-m-d\TH:i'),
    ];
    if ($create) {
      $reservation = Reservation::factory()->create($data);
    } else {
      $reservation = Reservation::factory()->make($data);
    }
    return $reservation;
  }
}
