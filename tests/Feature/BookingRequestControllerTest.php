<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Role;
use App\Models\Reservation;
use Faker\Factory;
use Tests\TestCase;
use App\Models\Room;
use App\Models\BookingRequest;
use App\Models\User;
use App\Models\Permission;
use App\Events\BookingRequestUpdated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class BookingRequestControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function user_can_view_booking_search()
    {
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->get(route('bookings.search'));
        $response->assertOk();
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function user_can_view_booking_index()
    {
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->get(route('bookings.index'));
        $response->assertOk();
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function user_can_view_booking_create()
    {
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->get(route('bookings.create'));
        $response->assertRedirect(); // redirect to search
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function user_can_view_booking_edit()
    {
        $booking = BookingRequest::factory()->create(['status'=>BookingRequest::PENDING]);
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.update']))->get(route('bookings.edit', $booking));
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function user_redirects_to_view_when_under_review()
    {
        $booking = BookingRequest::factory()->create(['status'=>BookingRequest::REVIEW]);
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.update']))->get(route('bookings.edit', $booking));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('bookings.view', $booking));
    }

    /**
     * @test
     */
    public function user_can_view_booking_view()
    {
        $booking = BookingRequest::factory()->create([
            'reference' => [['name' => 'file.pdf', 'path' => 'bookings/files/file.pdf']],
            'status'=>BookingRequest::REVIEW
        ]);
        $response = $this->actingAs($this->createUserWithPermissions(['bookings.update']))->get(route('bookings.view', $booking));
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function user_can_create_booking_request()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $user = $this->createUserWithPermissions(['bookings.create']);

        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);

        $date = $this->faker->dateTimeInInterval('+' . $room->min_days_advance . ' days', '+' . ($room->max_days_advance - $room->min_days_advance) . ' days')->setTime(12,0);

        $this->createReservationAvailabilities($date, $room);

        $start = Carbon::parse($date);
        $end = $start->copy()->addMinutes(60);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'reservations' => [
                [
                    'start_time' => $start->format('Y-m-d H:i:00'),
                    'end_time' => $end->format('Y-m-d H:i:00'),
                    'duration' => $this->faker->numberBetween(100)
                ]
            ],
            'event' => [
                'start_time' => $start->copy()->addMinute()->format('H:i'),
                'end_time' => $end->copy()->subMinute()->format('H:i'),
                'title' => $this->faker->word,
                'type' => $this->faker->word,
                'description' => $this->faker->paragraph,
                'guest_speakers' => $this->faker->name,
                'attendees' => $this->faker->numberBetween(100),
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseHas('booking_requests', ['user_id' => $user->id]);
        $booking = BookingRequest::first()->id;
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'booking_request_id' => $booking,
            'start_time' => $start->format('Y-m-d H:i:00'),
            'end_time' => $end->format('Y-m-d H:i:00'),
        ]);

    }

    /**
     * @test
     */
    public function user_can_add_reference_files_to_booking()
    {
        Storage::fake('public');
        $room = Room::factory()->create(['status' => 'available', 'attributes' => [
            'alcohol' => true
        ]]);
        $booking_request = $this->createBookingRequest(false);
        $reservation = $this->createReservation($room, $booking_request, false);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        //test if function creates a new reference in booking after uploading an array of files
        $files = [UploadedFile::fake()->create('testFile.pdf', 100)];

        $this->assertDatabaseMissing('booking_requests', ['reference' => $booking_request->reference]);

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/bookings', [
            'room_id' => $room->id,
            'reservations' => [
                [
                    'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
                    'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
                    'duration' => $this->faker->numberBetween(100)
                ]
            ],
            'event' => [
                'start_time' => $reservation->start_time->copy()->format('H:i'),
                'end_time' => $reservation->end_time->copy()->format('H:i'),
                'title' => $this->faker->word,
                'type' => $this->faker->word,
                'description' => $this->faker->paragraph,
                'guest_speakers' => $this->faker->name,
                'attendees' => $this->faker->numberBetween(100),
                'alcohol' => true,
            ],
            'files' => $files
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

    }

    /**
     * @test
     */
    public function user_can_download_reference_files_from_booking()
    {
        Storage::fake('public');
        Storage::fake('local');
        $room = Room::factory()->create(['status' => 'available']);
        $user = $this->createUserWithPermissions(['bookings.create']);
        $booking_request = $this->createBookingRequest(false);
        $reservation = $this->createReservation($room, $booking_request, false);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        //make sure function creates a new reference in booking after uploading an array of files
        $files = [UploadedFile::fake()->create('testFile.pdf', 100)];

        $this->assertDatabaseCount('booking_requests', 0);

        $response = $this->actingAs($user)->post('/bookings', [
                'room_id' => $room->id,
                'reservations' => [
                    [
                        'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
                        'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
                        'duration' => $this->faker->numberBetween(100)
                    ]
                ],
                'event' => [
                    'start_time' => $reservation->start_time->copy()->format('H:i'),
                    'end_time' => $reservation->end_time->copy()->format('H:i'),
                    'title' => $this->faker->word,
                    'type' => $this->faker->word,
                    'description' => $this->faker->paragraph,
                    'guest_speakers' => $this->faker->name,
                    'attendees' => $this->faker->numberBetween(100),
                    'alcohol' => true,
                ],
                'files' => $files,
            ]
        );

        $response->assertSessionHasNoErrors();
        Storage::fake('local');
        $booking_request = BookingRequest::first();
        //Test if the required file was downloaded through the browser
        $response = $this->actingAs($user)->get(route('bookings.download', $booking_request));
        $this->assertTrue(Str::contains($response->headers->get('content-disposition'), ['attachment', '.zip', "Booking#$booking_request->id"]));
    }

    /**
     * @test
     */
    public function user_cannot_create_booking_request_with_no_availabilities()
    {
        $room = Room::factory()->create(['status' => 'available']);

        $date = $this->faker->dateTimeInInterval('+' . $room->min_days_advance . ' days', '+' . ($room->max_days_advance - $room->min_days_advance) . ' days');
        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);

        $start = Carbon::parse($date);
        $end = $start->copy()->addMinutes(60);

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/bookings', [
            'room_id' => $room->id,
            'reservations' => [
                [
                    'start_time' => $start->format('Y-m-d H:i:00'),
                    'end_time' => $end->format('Y-m-d H:i:00')
                ]
            ],
            'event' => [
                'start_time' => $start->copy()->addMinute()->format('H:i'),
                'end_time' => $end->copy()->subMinute()->format('H:i'),
                'title' => $this->faker->word,
                'type' => $this->faker->word,
                'description' => $this->faker->paragraph,
                'guest_speakers' => $this->faker->name,
                'attendees' => $this->faker->numberBetween(100),
            ]
        ]);

        $response->assertSessionHasErrors();

        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);
        $this->assertDatabaseMissing('reservations', [
            'room_id' => $room->id,
            'start_time' => Carbon::parse($date)->toDateTimeString(),
            'end_time' => Carbon::parse($date)->addMinute()->toDateTimeString()
        ]);
    }

    /**
     * @test
     */
    public function booking_request_for_unavailable_room()
    {
        $room = Room::factory()->create(['status' => 'unavailable']);

        $date = $this->faker->dateTimeInInterval('+' . $room->min_days_advance . ' days', '+' . ($room->max_days_advance - $room->min_days_advance) . ' days');
        $start = Carbon::parse($date);
        $end = $start->copy()->addMinutes(60);

        $this->assertDatabaseCount('booking_requests', 0);

    $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->post('/bookings', [
      'room_id' => $room->id,
      'reservations' => [
        [
          'start_time' => $start->format('Y-m-d H:i:00'),
          'end_time' => $end->format('Y-m-d H:i:00')
        ]
      ],
      'event' => [
        'start_time' => $start->copy()->addMinute()->format('H:i'),
        'end_time' => $end->copy()->subMinute()->format('H:i'),
        'title' => $this->faker->word,
        'type' => $this->faker->word,
        'description' => $this->faker->paragraph,
        'guest_speakers' => $this->faker->name,
        'attendees' => $this->faker->numberBetween(100),
      ]
    ]);
    $response->assertSessionHasErrors(['reservations.*']);
    $this->assertDatabaseCount('booking_requests', 0);
    $this->assertDatabaseCount('reservations', 0);
    $this->assertDatabaseMissing('reservations', [
      'room_id' => $room->id,
      'start_time' => Carbon::parse($date)->toDateTimeString(),
      'end_time' => Carbon::parse($date)->addMinute()->toDateTimeString()
    ]);
  }

    /**
     * @test
     */
    public function users_can_update_booking_request()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $booking_request = $this->createBookingRequest(true, ['status'=>BookingRequest::PENDING]);
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'start_time' => Carbon::parse($reservation->start_time)->toDateTimeString(),
            'end_time' => Carbon::parse($reservation->end_time)->toDateTimeString(),
            'booking_request_id' => $booking_request->id
        ]);

        $old_title = $booking_request->event['title'];
        $new_title = $old_title . 'v2';

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.update']))->put('/bookings/' . $booking_request->id, [
            'event' => [
                'start_time' => $reservation->start_time->format('H:i'),
                'end_time' => $reservation->end_time->format('H:i'),
                'title' => $new_title,
                'type' => $booking_request->event['type'],
                'description' => $booking_request->event['description'],
                'guest_speakers' => $booking_request->event['guest_speakers'],
                'attendees' => $booking_request->event['attendees'],
            ]
        ]);

        $response->assertSessionHasNoErrors();

        $updatedBooking = BookingRequest::find($booking_request->id);
        $this->assertEquals(BookingRequest::where('event->title', $old_title)->count(), 0);
        $this->assertEquals($updatedBooking->event['title'], $new_title);
    }

    /**
     * @test
     */
    public function users_can_not_update_booking_request_when_in_review()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $booking_request = $this->createBookingRequest(true, ['status'=>BookingRequest::REVIEW]);
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);

        $this->assertDatabaseCount('booking_requests', 1);
        $this->assertDatabaseCount('reservations', 1);
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'start_time' => Carbon::parse($reservation->start_time)->toDateTimeString(),
            'end_time' => Carbon::parse($reservation->end_time)->toDateTimeString(),
            'booking_request_id' => $booking_request->id
        ]);

        $old_title = $booking_request->event['title'];
        $new_title = $old_title . 'v2';

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.update']))->put('/bookings/' . $booking_request->id, [
            'event' => [
                'start_time' => $reservation->start_time->format('H:i'),
                'end_time' => $reservation->end_time->format('H:i'),
                'title' => $new_title,
                'type' => $booking_request->event['type'],
                'description' => $booking_request->event['description'],
                'guest_speakers' => $booking_request->event['guest_speakers'],
                'attendees' => $booking_request->event['attendees'],
            ]
        ]);
        $response->dumpSession();
        $response->assertSessionHasNoErrors();
        $response->assertRedirect("bookings/".$booking_request->id."/view");
        $this->assertEquals(BookingRequest::where('event->title', $old_title)->count(), 1);
        $this->assertEquals(BookingRequest::where('event->title', $new_title)->count(), 0);
    }

    /**
     * @test
     */
    public function users_can_update_reference_on_booking_request()
    {
        Storage::fake('public');
        $user = $this->createUserWithPermissions(['bookings.update']);

        $room = Room::factory()->create();
        $booking_request = $this->createBookingRequest(true, ['status'=>BookingRequest::PENDING]);
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $booking_request->save();

        $files = [UploadedFile::fake()->create('test.pdf', 100)];
        Storage::disk('public')->assertMissing("{$booking_request->referenceFolderName}/test.pdf");
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking_request->id,
            'reference' => json_encode($booking_request->reference)
        ]);

        $response = $this->actingAs($user)->put('/bookings/' . $booking_request->id, [
            'event' => [
                'start_time' => $reservation->start_time->format('H:i'),
                'end_time' => $reservation->end_time->format('H:i'),
                'title' => $booking_request->event['title'],
                'type' => $booking_request->event['type'],
                'description' => $booking_request->event['description'],
                'guest_speakers' => $booking_request->event['guest_speakers'],
                'attendees' => $booking_request->event['attendees'],
            ],
            'files' => $files
        ]);

        $response->assertSessionHasNoErrors();
        $booking_request->refresh();
        Storage::disk('public')->assertExists($booking_request->reference[0]['path']);
    }

    /**
     * @test
     */
    public function users_can_delete_reference_files_on_booking_request()
    {
        Storage::fake('public');
        $user = $this->createUserWithPermissions(['bookings.update']);

        $room = Room::factory()->create();
        $booking_request = $this->createBookingRequest(true, ['status'=>BookingRequest::PENDING]);
        $reservation = $this->createReservation($room, $booking_request);
        $this->createReservationAvailabilities($reservation->start_time, $room);
        $booking_request->save();

        $files = [UploadedFile::fake()->create('test.pdf', 100)];

        $response = $this->actingAs($user)->put('/bookings/' . $booking_request->id, [
            'event' => [
                'start_time' => $reservation->start_time->format('H:i'),
                'end_time' => $reservation->end_time->format('H:i'),
                'title' => $booking_request->event['title'],
                'type' => $booking_request->event['type'],
                'description' => $booking_request->event['description'],
                'guest_speakers' => $booking_request->event['guest_speakers'],
                'attendees' => $booking_request->event['attendees'],
            ],
            'files' => $files
        ]);

        $response->assertSessionHasNoErrors();
        $booking_request->refresh();
        Storage::disk('public')->assertExists($booking_request->reference[0]['path']);

        $response = $this->actingAs($user)->post(route('api.bookings.remove-file', $booking_request), [
            'filenames' => [$booking_request->reference[0]['name']]
        ]);
        $response->assertSessionHasNoErrors();

        Storage::disk('public')->assertMissing($booking_request->reference[0]['path']);
    }

    /**
     * @test
     */
    public function users_can_delete_booking_requests()
    {
        $room = Room::factory()->create();
        $booking_request = $this->createBookingRequest();
        $this->createReservation($room, $booking_request);

        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking_request->id
        ]);

        $this->assertDatabaseHas('reservations', [
            'booking_request_id' => $booking_request->id
        ]);

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.delete']))
            ->delete('/bookings/' . $booking_request->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('booking_requests', ['id' => $booking_request->id]);
        $this->assertDatabaseMissing('reservations', ['booking_request_id' => $booking_request->id]);
    }

    /**
     * @test
     */
    public function user_can_create_booking_request_if_he_did_not_exceed_his_booking_request_per_period()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);
        $role = Role::factory()->create();
        $user->assignRole($role);

        $room = Room::factory()->create(['status' => 'available']);

        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);

        $date = $this->faker->dateTimeInInterval('+' . $room->min_days_advance . ' days', '+' . ($room->max_days_advance - $room->min_days_advance) . ' days');
        $start = Carbon::parse($date);
        $end = $start->copy()->addMinutes(60);

        $this->createReservationAvailabilities($date, $room);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'reservations' => [
                [
                    'start_time' => $start->format('Y-m-d H:i:00'),
                    'end_time' => $end->format('Y-m-d H:i:00'),
                    'duration' => $this->faker->numberBetween(100)
                ]
            ],
            'event' => [
                'start_time' => $start->copy()->addMinute()->format('H:i'),
                'end_time' => $end->copy()->subMinute()->format('H:i'),
                'title' => $this->faker->word,
                'type' => $this->faker->word,
                'description' => $this->faker->paragraph,
                'guest_speakers' => $this->faker->name,
                'attendees' => $this->faker->numberBetween(100),
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('booking_requests', 1);

        $this->assertDatabaseHas('booking_requests', ['user_id' => $user->id,]);
        $booking = BookingRequest::first()->id;
        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'start_time' => $start->format('Y-m-d H:i:00'),
            'end_time' => $end->format('Y-m-d H:i:00'),
            'booking_request_id' => $booking
        ]);
    }

    /**
     * @test
     */
    public function user_cannot_create_booking_request_if_he_did_not_exceed_his_booking_request_per_period()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);
        $role = Role::factory()->create();
        $user->assignRole($role);

        $room = Room::factory()->create(['status' => 'available']);
        $date = $this->faker->dateTimeInInterval('+' . $room->min_days_advance . ' days', '+' . ($room->max_days_advance - $room->min_days_advance) . ' days');
        $start = Carbon::parse($date);
        $end = $start->copy()->addMinutes(60);

        $this->createReservationAvailabilities($date, $room);
        $booking = $this->createBookingRequest(true, ['status' => BookingRequest::APPROVED]);

        Reservation::create([
            'room_id' => $room->id,
            'booking_request_id' => $booking->id,
            'start_time' => $date->format('Y-m-d H:i:00'),
            'end_time' => Carbon::parse($date)->addMinutes(1)->toDateTime()->format('Y-m-d H:i:00'),
        ]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'reservations' => [
                [
                    'start_time' => $start->format('Y-m-d H:i:00'),
                    'end_time' => $end->format('Y-m-d H:i:00')
                ]
            ],
            'event' => [
                'start_time' => $start->copy()->addMinute()->format('H:i'),
                'end_time' => $end->copy()->subMinute()->format('H:i'),
                'title' => $this->faker->word,
                'type' => $this->faker->word,
                'description' => $this->faker->paragraph,
                'guest_speakers' => $this->faker->name,
                'attendees' => $this->faker->numberBetween(100),
            ]
        ]);
        $response->dumpSession();
        $response->assertSessionHasErrors();
        $response->assertStatus(302);
        $this->assertDatabaseCount('booking_requests', 1);
    }

    /**
     * @test
     */
    public function booking_requests_index_page_loads()
    {
        Room::factory()->make();
        $this->createBookingRequest();

        $response = $this->actingAs($this->createUserWithPermissions(['bookings.create']))->get('/bookings');
        $response->assertOk();
    }

    /**
     * @test
     */
    public function booking_request_adds_log_entry()
    {

        $room = Room::factory()->create(['status' => 'available']);
        $user = $this->createUserWithPermissions(['bookings.create']);
        $booking_request = $this->createBookingRequest(false);
        $reservation = $this->createReservation($room, $booking_request, false);

        Event::fake();

        $this->createReservationAvailabilities($reservation->start_time, $room);

        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseMissing('reservations', ['room_id' => $room->id, 'start_time' => $reservation->start_time, 'end_time' => $reservation->end_time]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'reservations' => [
                [
                    'start_time' => $reservation->start_time->format('Y-m-d H:i:00'),
                    'end_time' => $reservation->end_time->format('Y-m-d H:i:00'),
                    'duration' => $this->faker->numberBetween(100)
                ]
            ],
            'event' => [
                'start_time' => $reservation->start_time->format('H:i'),
                'end_time' => $reservation->end_time->format('H:i'),
                'title' => $this->faker->word,
                'type' => $this->faker->word,
                'description' => $this->faker->paragraph,
                'guest_speakers' => $this->faker->name,
                'attendees' => $this->faker->numberBetween(100),
            ]
        ]);

//    dump(session()->all());
        $response->assertSessionHasNoErrors();

        Event::assertDispatched(BookingRequestUpdated::class);
    }

    /**
     * helper function
     * @param bool $create
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private function createBookingRequest($create = true, $input = [])
    {
        if ($create) {
            $booking_request = BookingRequest::factory()->create($input);
        } else {
            $booking_request = BookingRequest::factory()->make($input);
        }
        return $booking_request;
    }

    /**
     * helper function
     */
    private function createReservation($room, $bookingRequest, $create = true)
    {
        $date = $this->faker->dateTimeInInterval('+' . $room->min_days_advance . ' days', '+' . ($room->max_days_advance - $room->min_days_advance) . ' days');

        $data = [
            'room_id' => $room->id,
            'booking_request_id' => $bookingRequest->id,
            'start_time' => Carbon::parse($date)->format('Y-m-d\TH:i'),
            'end_time' => Carbon::parse($date)->addMinute(60)->format('Y-m-d\TH:i'),
        ];
        if ($create) {
            $reservation = Reservation::factory()->create($data);
        } else {
            $reservation = Reservation::factory()->make($data);
        }
        return $reservation;
    }

    /**
     * helper function
     */
    private function createBookingRequestAvailabilities($booking_request, $room)
    {
        $openingHours = Carbon::parse($booking_request->start_time)->subMinute()->toTimeString();
        $closingHours = Carbon::parse($booking_request->end_time)->addMinute()->toTimeString();

        Availability::create([
            'room_id' => $room->id,
            'opening_hours' => $openingHours,
            'closing_hours' => $closingHours,
            'weekday' => Carbon::parse($booking_request->start_time)->subMinute()->format('l')
        ]);

        Availability::create([
            'room_id' => $room->id,
            'opening_hours' => $openingHours,
            'closing_hours' => $closingHours,
            'weekday' => Carbon::parse($booking_request->end_time)->addMinute()->format('l')
        ]);
    }

    private function createReservationAvailabilities($start, $room)
    {
        $openingHours = Carbon::parse($start)->subMinutes(5)->toTimeString();
        $closingHours = Carbon::parse($start)->addMinutes(60)->toTimeString();

        Availability::create([
            'room_id' => $room->id,
            'opening_hours' => $openingHours,
            'closing_hours' => $closingHours,
            'weekday' => Carbon::parse($start)->format('l')
        ]);
    }

}
