<?php

namespace Tests\Feature;

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
        $room = Room::factory()->create(['status'=>'available']);
        $user = User::factory()->create();
        
        $this->assertDatabaseMissing('blackout_room', ['room_id' => $room->id]);
        $this->assertDatabaseMissing('blackouts', ['start_time' => $blackout->start_time,'end_time' => $blackout->end_time]);

        $response = $this->actingAs($user)->post('/blackouts/', ['room_id' => $room->id, 'start' => $blackout->start_time->toDateTimeString(), 'end' => $blackout->end_time->toDateTimeString(), 'name' => $blackout->name]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id]);
        $this->assertDatabaseHas('blackouts', ['start_time' => $blackout->start_time,'end_time' => $blackout->end_time, 'name' => $blackout->name]);

    }
    /**
     * @test
     */
    public function users_can_update_blackout()
    {
        $room = Room::factory()->create(['status'=>'available']);
        $user = User::factory()->create();
        $blackout = Blackout::factory()->create();

        $room->blackouts()->attach($blackout->id);

       $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id, 'blackout_id'=> $blackout->id]);
       $this->assertDatabaseHas('blackouts', ['start_time' => $blackout->start_time,'end_time' => $blackout->end_time, 'name' => $blackout->name]);

        $startTime = Carbon::parse($blackout->start_time)->addMinutes(2)->toDateTimeString();
        $endTime = Carbon::parse($blackout->end_time)->subMinutes(2)->toDateTimeString();
        $response = $this->actingAs($user)->put('/blackouts/' . $blackout->id, ['start' => $startTime,'end' => $endTime, $blackout->name]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id, 'blackout_id'=> $blackout->id]);
        $this->assertDatabaseHas('blackouts', ['start_time' => $startTime,'end_time' => $endTime]);
    }
    /**
     * @test
     */
    public function users_can_delete_blackout()
    {
        $this->withoutExceptionHandling();
        $room = Room::factory()->create(['status'=>'available']);
        $user = User::factory()->create();
        $blackout = Blackout::factory()->create();

        $room->blackouts()->attach($blackout->id);

        $this->assertDatabaseHas('blackout_room', ['room_id' => $room->id, 'blackout_id'=> $blackout->id]);
        $this->assertDatabaseHas('blackouts', ['start_time' => $blackout->start_time,'end_time' => $blackout->end_time]);

        $response = $this->actingAs($user)->delete('/blackouts/' . $blackout->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('blackouts', ['id' => $blackout->id]);
    }

}
