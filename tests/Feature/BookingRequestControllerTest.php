<?php

namespace Tests\Feature;

use App\Models\Availability;
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
        $room = Room::factory()->create(['status'=>'available']);
        $user = User::factory()->create();
        $booking_request = $this->createBookingRequest($room, false);

        $this->createBookingRequestAvailabilities($booking_request, $room);

        $this->assertDatabaseMissing('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

        $response = $this->actingAs($user)->post('/bookings', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time->toDateTimeString(), 'end_time' => $booking_request->end_time->toDateTimeString()]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('booking_requests', ['room_id' => $booking_request->room_id, 'start_time' => $booking_request->start_time, 'end_time' => $booking_request->end_time]);

    }
    /**
     * @test
     */
    public function user_can_add_reference_files_to_booking()
    {
        Storage::fake('public');
        $room = Room::factory()->create(['status'=>'available']);
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
        $room = Room::factory()->create(['status'=>'available']);
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
        $room = Room::factory()->create(['status'=>'unavailable']);
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
        $room = Room::factory()->create(['status'=>'available']);
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
        $room = Room::factory()->create(['status'=>'available']);
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
        $this->assertDatabaseMissing('booking_requests', ['id' => $booking_request->id.'']);
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
    private function createBookingRequest($room, $create = true) {
        $start = $this->faker->dateTimeInInterval('+'.$room->min_days_advance.' days', '+'.$room->min_days_advance.' days');

        $input = [
            'start_time' => $start,
            'end_time' => Carbon::parse($start)->addHours(2),
        ];

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

}
