<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function user_can_store_multiple_reservations()
    {
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest(false);
        $reservation = $this->createReservation($room, $booking_request, false);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $reservation2 = $this->createReservationCopy($reservation, false);
//    $reservation2->end_time = Carbon::parse($reservation2->end_time)->addDay()->format('Y-m-d H:i');
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $room->id,
            'reservations' => [
                ['start_time' => $reservation->start_time->format('Y-m-d H:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d H:i:00')],
                ['start_time' => $reservation2->start_time->format('Y-m-d H:i:00'), 'end_time' => $reservation2->end_time->format('Y-m-d H:i:00')],
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

    /**
     * @test
     */
    public function deleting_reservations()
    {
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest();
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->delete('/reservation/' . $reservation->id);
        $response->assertStatus(302);
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);

    }

    /**
     * @test
     */
    public function update_reservations()
    {
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest();
        $reservation = $this->createReservation($room, $booking_request,);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);
        $reservationNew = $reservation->replicate();
        $reservationNew->start_time = Carbon::parse($reservation->start_time)->addMinute()->format('Y-m-d H:i');
        $reservationNew->end_time = Carbon::parse($reservation->end_time)->addMinute()->format('Y-m-d H:i');
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->put('/reservation/' . $reservation->id, [
            'room_id' => $reservationNew->room_id,
            'reservations' => [
                [
                    'start_time' => $reservationNew->start_time->format('Y-m-d H:i:00'),
                    'end_time' => $reservationNew->end_time->format('Y-m-d H:i:00'),
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

    /**
     * @test
     */
    public function conflict_block_case_A()
    {
        //Case A: The reservation ends after another is supposed to start
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest();
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);
        $reservationNew = $reservation->replicate();
        $reservationNew->start_time = Carbon::parse($reservation->start_time)->subMinute()->format('Y-m-d H:i');
        $reservationNew->end_time = Carbon::parse($reservation->start_time)->addMinute()->format('Y-m-d H:i');
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $reservationNew->room_id,
            'reservations' => [
                [
                    'start_time' => $reservationNew->start_time->format('Y-m-d H:i:00'),
                    'end_time' => $reservationNew->end_time->format('Y-m-d H:i:00'),
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

    /**
     * @test
     */
    public function conflict_block_case_B()
    {
        //Case B: The reservation starts after another is supposed to end
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest();
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);
        $reservationNew = $reservation->replicate();
        $reservationNew->start_time = Carbon::parse($reservation->end_time)->subMinute()->format('Y-m-d H:i');
        $reservationNew->end_time = Carbon::parse($reservation->end_time)->addMinute()->format('Y-m-d H:i');
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $reservationNew->room_id,
            'reservations' => [
                [
                    'start_time' => $reservationNew->start_time->format('Y-m-d H:i:00'),
                    'end_time' => $reservationNew->end_time->format('Y-m-d H:i:00'),
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

    /**
     * @test
     */
    public function conflict_block_case_C()
    {
        //Case C: The new reservation starts before and ends after
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest();
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);
        $reservationNew = $reservation->replicate();
        $reservationNew->start_time = Carbon::parse($reservation->start_time)->subMinute()->format('Y-m-d H:i');
        $reservationNew->end_time = Carbon::parse($reservation->end_time)->addMinute()->format('Y-m-d H:i');
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $reservationNew->room_id,
            'reservations' => [
                [
                    'start_time' => $reservationNew->start_time->format('Y-m-d H:i:00'),
                    'end_time' => $reservationNew->end_time->format('Y-m-d H:i:00'),
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

    /**
     * @test
     */
    public function conflict_block_case_D()
    {
        //Case D: The reservation is inside another one.
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest();
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);
        $reservationNew = $reservation->replicate();
        $reservationNew->start_time = Carbon::parse($reservation->start_time)->addMinute()->format('Y-m-d H:i');
        $reservationNew->end_time = Carbon::parse($reservation->end_time)->subMinute()->format('Y-m-d H:i');
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $reservationNew->room_id,
            'reservations' => [
                [
                    'start_time' => $reservationNew->start_time->format('Y-m-d H:i:00'),
                    'end_time' => $reservationNew->end_time->format('Y-m-d H:i:00'),
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

    /**
     * @test
     */
    public function storing_multiple_reservations_fail_if_one_is_denied()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);
        $booking_request = $this->createBookingRequest(false);
        $reservation = $this->createReservation($room, $booking_request, false);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $reservation2 = $this->createReservationCopy($reservation, false);
        $reservation2->end_time = Carbon::parse($reservation2->end_time)->addDay()->format('Y-m-d H:i');
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $room->id,
            'reservations' => [
                ['start_time' => $reservation->start_time->format('Y-m-d H:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d H:i:00')],
                ['start_time' => $reservation2->start_time->format('Y-m-d H:i:00'), 'end_time' => $reservation2->end_time->format('Y-m-d H:i:00')],
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('storeReservationsRequest');
//        $response->dumpSession();
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);

    }

    /**
     * @test
     */
    public function storing_too_early()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);
        $booking_request = $this->createBookingRequest(false);
        $reservation = $this->createReservation($room, $booking_request, false);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        Carbon::setTestNow(now()->subDays($room->max_days_advance + 1));

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $room->id,
            'reservations' => [
                ['start_time' => $reservation->start_time->format('Y-m-d H:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d H:i:00')],
            ]
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);
    }

    /**
     * @test
     */
    public function storing_too_late()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['min_days_advance' => 10, 'max_days_advance' => 100,]);
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);
        $booking_request = $this->createBookingRequest(false);
        $reservation = $this->createReservation($room, $booking_request, false);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        Carbon::setTestNow(Carbon::parse($reservation->start_time)->subDays(1));

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/reservation', [
            'room_id' => $room->id,
            'reservations' => [
                ['start_time' => $reservation->start_time->format('Y-m-d H:i:00'), 'end_time' => $reservation->end_time->format('Y-m-d H:i:00')],
            ]
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);
    }

    /**
     * @test
     */
    public function relations_set()
    {
        $room = Room::factory()->create();
        $booking_request = $this->createBookingRequest();
        $reservation = $this->createReservation($room, $booking_request);

        $this->assertEquals(1, $room->bookingRequests()->count());
        $this->assertEquals(1, $booking_request->rooms()->count());
        $this->assertEquals(1, $reservation->room()->count());
        $this->assertEquals(1, $reservation->bookingRequest()->count());

    }

    /**
     * @test
     */
    public function retrieve_approved_reservations_on_given_date()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest(true, ['status' => "approved"]);
        $reservation = $this->createReservation($room, $booking_request, true);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->get(route('api.reservations.by-room', [
            'room' => $room,
            'date' => $reservation->start_time->format('Y-m-d')
        ]));

        $response->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function retrieve_approved_reservations_with_no_date_return_empty()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $booking_request = $this->createBookingRequest(true, ['status' => "approved"]);
        $reservation = $this->createReservation($room, $booking_request, true);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->get(route('api.reservations.by-room', $room));

        $response->assertJsonCount(0);
    }

    /**
     * @test
     */
    public function retrieve_rooms_availabilities_by_date()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);

        $room = Room::factory()->create();
        $booking_request = $this->createBookingRequest(true, ['status' => "approved"]);
        $reservation = $this->createReservation($room, $booking_request, true);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        $response = $this->actingAs($user)->get(route('api.reservations.by-date', [
            'date' => $reservation->start_time->format('Y-m-d')
        ]));

        $response->assertOk();
        $response->assertSessionHasNoErrors();
        // 1 room
        $response->assertJsonCount(1);
        // room details
        $response->assertJsonFragment([
            'id' => $room->id,
            'name' => $room->name,
            'floor' => $room->floor,
            'building' => $room->building,
            'room_type' => $room->room_type,
        ]);
        // structure matches
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'floor',
                'building',
                'room_type',
                'reservations',
                'blackouts',
                'availabilities',
                'day_breakdown' => [
                    [
                        [
                            'start_time',
                            'end_time',
                            'closed',
                            'booked',
                        ] // ... 4 blocks of 15m
                    ], // ...24 hours
                ]
            ]
        ]);
    }
    /**
     * @test
     */
    public function retrieve_rooms_availabilities_for_today()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);

        $room = Room::factory()->create();
        $booking_request = $this->createBookingRequest(true, ['status' => "approved"]);
        $reservation = $this->createReservation($room, $booking_request, true);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        $response = $this->actingAs($user)->get(route('api.reservations.by-date'));

        $response->assertOk();
        $response->assertSessionHasNoErrors();
        // 1 room
        $response->assertJsonCount(1);
        // room details
        $response->assertJsonFragment([
            'id' => $room->id,
            'name' => $room->name,
            'floor' => $room->floor,
            'building' => $room->building,
            'room_type' => $room->room_type,
        ]);
        // structure matches
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'floor',
                'building',
                'room_type',
                'reservations',
                'blackouts',
                'availabilities',
                'day_breakdown' => [
                    [
                        [
                            'start_time',
                            'end_time',
                            'closed',
                            'booked',
                        ] // ... 4 blocks of 15m
                    ], // ...24 hours
                ]
            ]
        ]);
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
        )->setTime(12, 0);

        $data = [
            'room_id' => $room->id,
            'booking_request_id' => $bookingRequest->id,
            'start_time' => Carbon::parse($date)->format('Y-m-d H:i'),
            'end_time' => Carbon::parse($date)->addMinutes(30)->format('Y-m-d H:i'),
        ];
        if ($create) {
            $reservation = Reservation::factory()->create($data);
        } else {
            $reservation = Reservation::factory()->make($data);
        }
        return $reservation;
    }

    private function createBookingRequest($create = true, $input = [])
    {
        if ($create) {
            $booking_request = BookingRequest::factory()->create($input);
        } else {
            $booking_request = BookingRequest::factory()->make($input);
        }
        return $booking_request;
    }

    private function createReservationCopy(Reservation $reservation, bool $create = true)
    {
        $data = array_merge($reservation->attributesToArray(), [
            'start_time' => $reservation->start_time->addMinute(),
            'end_time' => $reservation->end_time->addMinute(),
        ]);
        if ($create) {
            $reservation = Reservation::factory()->create($data);
        } else {
            $reservation = Reservation::factory()->make($data);
        }
        return $reservation;
    }
}
