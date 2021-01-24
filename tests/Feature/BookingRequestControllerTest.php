<?php

namespace Tests\Feature;

use App\Models\Availability;
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
     * @test
     */
    public function user_can_create_booking_request()
    {
        Carbon::setTestNow(now());
        $room = Room::factory()->create(['status' => 'available']);
        $user = User::factory()->create();

        $this->assertDatabaseCount('booking_requests', 0);
        $this->assertDatabaseCount('reservations', 0);

        $this->faker->dateTimeInInterval('+'.$room->min_days_advance.' days', '+'.$room->max_days_advance.' days');
        $start =  $this->faker->dateTimeThisMonth('now');
        $end =  $this->faker->dateTimeThisMonth('+2 hours');
        $this->createReservationAvailabilities($start, $end, $room);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'start_time' => $start->format('Y-m-d\TH:i'),
            'end_time' => $end->format('Y-m-d\TH:i')
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseCount('booking_requests', 1);

        $this->assertDatabaseHas('booking_requests', ['user_id' => $user->id, ]);
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
    public function user_can_add_reference_files_to_booking()
    {
        Storage::fake('public');
        $room = Room::factory()->create(['status' => 'available']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room, false);

        $this->createBookingRequestAvailabilities($booking_request, $room);

        //test if function creates a new reference in booking after uploading an array of files
        $files = [UploadedFile::fake()->create('testFile.txt', 100)];

        $this->assertDatabaseMissing('booking_requests', ['reference' => $booking_request->reference]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $booking_request->room_id,
            'start_time' => $booking_request->start_time->toDateTimeString(),
            'end_time' => $booking_request->end_time->toDateTimeString(),
            'reference' => $files]);

        Storage::disk('public')->assertExists($booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference/testFile.txt');
        $response->assertStatus(302);
        $this->assertDatabaseHas('booking_requests', [
            'reference' => json_encode(['path' => $booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference'])]);

    }
        /**
     * @test
     */
    public function user_can_download_reference_files_from_booking()
    {
        Storage::fake('public');
        $room = Room::factory()->create(['status'=>'available']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room, false);

        $this->createBookingRequestAvailabilities($booking_request, $room);

        //make sure function creates a new reference in booking after uploading an array of files
        $files = [UploadedFile::fake()->create('testFile.txt', 100)];

        $this->assertDatabaseMissing('booking_requests', ['reference' => $booking_request->reference]);

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $booking_request->room_id,
            'start_time' => $booking_request->start_time->toDateTimeString(),
            'end_time' => $booking_request->end_time->toDateTimeString(),
            'reference' => $files]);

        Storage::disk('public')->assertExists($booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference/testFile.txt');
        $response->assertStatus(302);

        $this->assertDatabaseHas('booking_requests', ['reference' => json_encode(['path' => $booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference'])]);

        //Test if the required file was downloaded through the browser
        $response = $this->actingAs($user)->call('GET', '/bookings/download/' . $booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename=' . $booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference.zip');
    }

    /**
     * @test
     */
    public function user_cannot_create_booking_request_with_no_availabilities()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room, false);


        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/bookings', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time->toDateTimeString(), 'end_time' => $booking_request->end_time->toDateTimeString()]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);
    }

    /**
     * @test
     */
    public function booking_request_for_unavailable_room()
    {
        $room = Room::factory()->create(['status' => 'unavailable']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room, false);


        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/bookings', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time->toDateTimeString(), 'end_time' => $booking_request->end_time->toDateTimeString()]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

    }

    /**
     * @test
     */
    public function users_can_update_booking_requests_within_availabilities()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room);

        $this->createBookingRequestAvailabilities($booking_request, $room);

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);

        $startTime = Carbon::parse($booking_request->start_time)->addMinutes(2)->toDateTimeString();
        $endTime = Carbon::parse($booking_request->end_time)->subMinutes(2)->toDateTimeString();

        $response = $this->actingAs($user)->put('/bookings/' . $booking_request->id, [
            'room_id' => $room->id, 'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $room->id, 'start_time' => $startTime,
            'end_time' => $endTime
        ]);
    }

    /**
     * @test
     */
    public function users_cannot_update_booking_requests_outside_availabilities()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room);

        $this->createBookingRequestAvailabilities($booking_request, $room);

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);

        $startTime = Carbon::parse($booking_request->start_time)->subMinutes(2)->toDateTimeString();
        $endTime = Carbon::parse($booking_request->end_time)->addMinutes(2)->toDateTimeString();

        $response = $this->actingAs($user)->put('/bookings/' . $booking_request->id, [
            'room_id' => $room->id, 'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        $this->assertDatabaseMissing('booking_requests', [
            'room_id' => $room->id, 'start_time' => $startTime,
            'end_time' => $endTime
        ]);
    }

    /**
     * @test
     */
    public function users_can_update_reference_on_booking_request()
    {
        Storage::fake('public');
        $room = Room::factory()->create();
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room);
        $files = [UploadedFile::fake()->create('testFile.txt', 100)];

        $this->createBookingRequestAvailabilities($booking_request, $room);

        Storage::disk('public')->assertMissing($booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference/testFile.txt');
        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $booking_request->room_id,
            'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time,
            'reference' => $booking_request->reference]);
        $response = $this->actingAs($user)->put('/bookings/' . $booking_request->id, [
            'room_id' => $booking_request->room_id,
            'start_time' => $booking_request->start_time->toDateTimeString(),
            'end_time' => $booking_request->end_time->toDateTimeString(),
            'reference' => $files]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('booking_requests', [
            'reference' => json_encode(['path' => $booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference'])]);
        Storage::disk('public')->assertExists($booking_request->room_id . '_' . strtotime($booking_request->start_time) . '_reference/testFile.txt');

    }

    /**
     * @test
     */
    public function users_can_delete_booking_requests()
    {
        $room = Room::factory()->create();
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room);

        $this->assertDatabaseHas('booking_requests', [
            'room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time,
            'end_time' => $booking_request->end_time
        ]);

        $response = $this->actingAs($user)->delete('/bookings/' . $booking_request->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('booking_requests', ['id' => $booking_request->id . '']);
    }

    /**
     * @test
     */
    public function testBookingRequestsIndexPageLoads()
    {
        $room = Room::factory()->make();
        $user = User::factory()->make();
        $booking_request = $this->createBookingRequest($room, false);

        $response = $this->actingAs($user)->get('/bookings');
        $response->assertOk();
        $response->assertSee("BookingRequests");
    }

    /**
     * @test
     */
    public function booking_request_adds_log_entry()
    {
        Event::fake();

        $room = Room::factory()->create(['status'=>'available']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room, false);

        $this->createBookingRequestAvailabilities($booking_request, $room);

        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/bookings', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time->toDateTimeString(), 'end_time' => $booking_request->end_time->toDateTimeString()]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        Event::assertDispatched(BookingRequestUpdated::class);
    }

    /**
     * helper function
     */
    private function createBookingRequest($create = true)
    {
        if ($create) {
            $booking_request = BookingRequest::factory()->create();
        } else {
            $booking_request = BookingRequest::factory()->make();
        }
        return $booking_request;
    }


    /**
     * helper function
     */
    private function createReservation($room, $bookingRequest, $create = true)
    {
        $this->faker->dateTimeInInterval('+'.$room->min_days_advance.' days', '+'.$room->min_days_advance.' days');

        $data = [
            'room_id' => $room->id,
            'booking_request_id' => $bookingRequest->id,
            'start_time' => $this->faker->dateTimeThisMonth('now', '+2 hours')->format('Y-m-d\TH:i'),
            'end_time' => $this->faker->dateTimeThisMonth('+2 hours', '+4 hours')->format('Y-m-d\TH:i'),
        ];
        if ($create) {
            $reservation = Reservation::create([$data]);
        } else {
            $reservation = Reservation::make([$data]);
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
    private function createReservationAvailabilities($start, $end, $room)
    {
        $openingHours = Carbon::parse($start)->subMinute()->toTimeString();
        $closingHours = Carbon::parse($end)->addMinute()->toTimeString();

        Availability::create([
            'room_id' => $room->id,
            'opening_hours' => $openingHours,
            'closing_hours' => $closingHours,
            'weekday' => Carbon::parse($start)->subMinute()->format('l')
        ]);

        Availability::create([
            'room_id' => $room->id,
            'opening_hours' => $openingHours,
            'closing_hours' => $closingHours,
            'weekday' => Carbon::parse($end)->addMinute()->format('l')
        ]);
    }

}
