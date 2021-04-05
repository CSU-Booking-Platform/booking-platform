<?php

namespace Tests\Unit;

use App\Models\Availability;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterRoomsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test filtering rooms with json attribute fields
     *
     * @return void
     */
    public function test_filter_rooms()
    {
        $this->withoutExceptionHandling();
        $user = $this->createUserWithPermissions(['bookings.create']);
        // Create room with boolean attribute as true and make sure it exists
        // and is returned by the filter endpoint
        $room = Room::factory()->create([
            'attributes' => [
                'food' => true,
                'alcohol' => true,
                'chairs' => 8
            ]]);
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
        ]);

        $response = $this->actingAs($user)->postJson('/api/filterRooms', ['food' => true]);
        $response
            ->assertStatus(200)
            ->assertJson([$room->attributesToArray()]);

        // Find room with numeric field filter, make sure it is found
        $response_2 = $this->postJson('/api/filterRooms', ['chairs' => 4]);
        $response_2
            ->assertStatus(200)
            ->assertJson([$room->attributesToArray()]);

        // Find room with numeric field filter that is out of the range, make sure it is not found
        $response_3 = $this->postJson('/api/filterRooms', ['chairs' => 15]);
        $response_3
            ->assertStatus(200)
            ->assertJson([]);

    }

    /**
     * Test filtering rooms with availability fields
     *
     * @return void
     */
    public function test_filter_rooms_by_availability()
    {
        $user = $this->createUserWithPermissions(['bookings.create']);
        $room = Room::factory()->create([
            'attributes' => [
                'food' => true,
                'alcohol' => true,
                'chairs' => 8
            ]]);
        $availability = Availability::create([
            'weekday' => Carbon::now()->format('l'),
            'opening_hours' => Carbon::now(),
            'closing_hours' => Carbon::now()->addMinutes(5),
            'room_id' => $room->id
        ]);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
        ]);
        $this->assertDatabaseHas('availabilities', [
            'id' => $availability->id,
        ]);


        // Send valid date range of availability, assert that the room is found
        $valid_pair = [
            'start_time' => Carbon::now()->addMinutes(1)->format('Y-m-d H:i'),
            'end_time' => Carbon::now()->addMinutes(2)->format('Y-m-d H:i')
        ];

        $valid_recurrences = ['recurrences' => [
            $valid_pair
        ]];

        $valid_response = $this->actingAs($user)->postJson('/api/filterRooms', $valid_recurrences);
        $valid_response
            ->assertStatus(200)
            ->assertJson([$room->attributesToArray()]);


        // Send invalid date range of availability, assert that the room is not found
        $invalid_pair = [
            'start_time' => Carbon::now()->addHours(8)->format('Y-m-d H:i'),
            'end_time' => Carbon::now()->addHours(9)->format('Y-m-d H:i')
        ];

        $invalid_recurrences = ['recurrences' => [
            $invalid_pair
        ]];

        $invalid_response = $this->actingAs($user)->postJson('/api/filterRooms', $invalid_recurrences);
        $invalid_response
            ->assertStatus(200)
            ->assertJson([]);

    }
}
