<?php

namespace Tests\Feature;

use App\Models\AcademicDate;
use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Blackout;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlackoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_create_blackout()
    {
        $blackout = Blackout::factory()->make();
        $room = Room::factory()->create(['status' => 'available']);

        $this->assertDatabaseMissing('blackout_room', ['room_id' => $room->id]);
        $this->assertDatabaseMissing('blackouts', ['start_time' => $blackout->start_time, 'end_time' => $blackout->end_time]);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.blackouts.create']))->post("/admin/rooms/{$room->id}/blackouts/", ['room_id' => $room->id, 'start' => $blackout->start_time->toDateTimeString(), 'end' => $blackout->end_time->toDateTimeString(), 'name' => $blackout->name]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id]);
        $this->assertDatabaseHas('blackouts', ['start_time' => $blackout->start_time, 'end_time' => $blackout->end_time, 'name' => $blackout->name]);

    }

    /**
     * @test
     */
    public function users_can_update_blackout()
    {
        $room = Room::factory()->create(['status' => 'available']);
        $blackout = Blackout::factory()->create();

        $room->blackouts()->attach($blackout->id);

        $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id, 'blackout_id' => $blackout->id]);
        $this->assertDatabaseHas('blackouts', ['start_time' => $blackout->start_time, 'end_time' => $blackout->end_time, 'name' => $blackout->name]);

        $startTime = Carbon::parse($blackout->start_time)->addMinutes(2)->toDateTimeString();
        $endTime = Carbon::parse($blackout->end_time)->subMinutes(2)->toDateTimeString();
        $response = $this->actingAs($this->createUserWithPermissions(['rooms.blackouts.update']))->put("/admin/rooms/{$room->id}/blackouts/{$blackout->id}", ['start' => $startTime, 'end' => $endTime, 'name' => $blackout->name]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id, 'blackout_id' => $blackout->id]);
        $this->assertDatabaseHas('blackouts', ['start_time' => $startTime, 'end_time' => $endTime]);
    }

    /**
     * @test
     */
    public function users_can_delete_blackout()
    {
        $this->withoutExceptionHandling();
        $room = Room::factory()->create(['status' => 'available']);
        $blackout = Blackout::factory()->create();

        $room->blackouts()->attach($blackout->id);

        $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id, 'blackout_id' => $blackout->id]);
        $this->assertDatabaseHas('blackouts', ['start_time' => $blackout->start_time, 'end_time' => $blackout->end_time]);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.blackouts.delete']))->delete("/admin/rooms/{$room->id}/blackouts/" . $blackout->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('blackouts', ['id' => $blackout->id]);
    }

    /**
     * @test
     */
    public function users_can_create_daily_blackout()
    {
        AcademicDate::create([
            'semester' => 'Fall',
            'start_date' => Carbon::now()->subDays(2)->toDateString(),
            'end_date' => Carbon::now()->addDays(10)->toDateString()
        ]);

        $blackout = Blackout::create([
            'name' => 'test',
            'start_time' => Carbon::today(),
            'end_time' => Carbon::today()->addMinute()
        ]);

        $room = Room::factory()->create(['status' => 'available']);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.blackouts.create']))
            ->post("/admin/rooms/{$room->id}/blackouts/",
                [
                    'room_id' => $room->id,
                    'start' => $blackout->start_time->toDateTimeString(),
                    'end' => $blackout->end_time->toDateTimeString(),
                    'name' => $blackout->name,
                    'recurring' => 'daily'
                ]
            );

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('blackouts', 12);
    }

    /**
     * @test
     */
    public function users_can_create_weekly_blackout()
    {
        AcademicDate::create([
            'semester' => 'Fall',
            'start_date' => Carbon::now()->subWeeks(2)->toDateString(),
            'end_date' => Carbon::now()->addWeeks(10)->toDateString()
        ]);

        $blackout = Blackout::create([
            'name' => 'test',
            'start_time' => Carbon::today(),
            'end_time' => Carbon::today()->addMinute()
        ]);
        $room = Room::factory()->create(['status' => 'available']);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.blackouts.create']))->post("/admin/rooms/{$room->id}/blackouts/",
            [
                'room_id' => $room->id,
                'start' => $blackout->start_time->toDateTimeString(),
                'end' => $blackout->end_time->toDateTimeString(),
                'name' => $blackout->name,
                'recurring' => 'weekly'
            ]
        );

        $response->assertStatus(302);
        $this->assertDatabaseCount('blackouts', 11);
    }

}
