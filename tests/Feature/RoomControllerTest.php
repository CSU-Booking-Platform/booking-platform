<?php

namespace Tests\Feature;

use App\Models\Availability;
use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     */
    public function admins_can_create_rooms()
    {
        $room = Room::factory()->make();
        $user = User::factory()->make();

        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);

        $response = $this->actingAs($user)->post('/rooms', [
            'name' => $room->name, 
            'number' => $room->number,
            'floor' => $room->floor, 
            'building' => $room->building, 
            'status' => $room->status,
            'room_type'  => $room->room_type,
            'capacity_standing' => $room->attributes['capacity_standing'],
            'capacity_sitting' => $room->attributes['capacity_sitting'],
            'food' => $room->attributes['food'],
            'alcohol' => $room->attributes['alcohol'],
            'a_v_permitted' => $room->attributes['a_v_permitted'],
            'projector' => $room->attributes['projector'],
            'television' => $room->attributes['television'],
            'computer' => $room->attributes['computer'],
            'whiteboard' => $room->attributes['whiteboard'],
            'sofas' => $room->attributes['sofas'],
            'coffee_tables' => $room->attributes['coffee_tables'],
            'tables' => $room->attributes['tables'],
            'chairs' => $room->attributes['chairs'],
            'ambiant_music' => $room->attributes['ambiant_music'],
            'sale_for_profit' => $room->attributes['sale_for_profit'],
            'fundraiser' => $room->attributes['fundraiser'], 
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 
            'number' => $room->number,
            'floor' => $room->floor, 
            'building' => $room->building, 
            'status' => $room->status, 
            'room_type' => $room->room_type,
            'attributes' => json_encode([ 
                'capacity_standing' => $room->attributes['capacity_standing'],           
                'capacity_sitting' => $room->attributes['capacity_sitting'],
                'food' => $room->attributes['food'],
                'alcohol' => $room->attributes['alcohol'],
                'a_v_permitted' => $room->attributes['a_v_permitted'],
                'projector' => $room->attributes['projector'],
                'television' => $room->attributes['television'],
                'computer' => $room->attributes['computer'],
                'whiteboard' => $room->attributes['whiteboard'],
                'sofas' => $room->attributes['sofas'],
                'coffee_tables' => $room->attributes['coffee_tables'],
                'tables' => $room->attributes['tables'],
                'chairs' => $room->attributes['chairs'],
                'ambiant_music' => $room->attributes['ambiant_music'],
                'sale_for_profit' => $room->attributes['sale_for_profit'],
                'fundraiser' => $room->attributes['fundraiser'], 
            ]),
        ]);
    }

    /**
     * @test
     */
    public function admins_can_create_rooms_with_availabilities()
    {
        $room = Room::factory()->make();
        $user = User::factory()->make();

        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);

        $response = $this->actingAs($user)->post(
            '/rooms',
            [
                'name' => $room->name,
                'number' => $room->number,
                'floor' => $room->floor,
                'building' => $room->building,
                'status' => $room->status,
                'room_type' => $room->room_type,
                'capacity_standing' => $room->attributes['capacity_standing'],
                'capacity_sitting' => $room->attributes['capacity_sitting'],
                'food' => $room->attributes['food'],
                'alcohol' => $room->attributes['alcohol'],
                'a_v_permitted' => $room->attributes['a_v_permitted'],
                'projector' => $room->attributes['projector'],
                'television' => $room->attributes['television'],
                'computer' => $room->attributes['computer'],
                'whiteboard' => $room->attributes['whiteboard'],
                'sofas' => $room->attributes['sofas'],
                'coffee_tables' => $room->attributes['coffee_tables'],
                'tables' => $room->attributes['tables'],
                'chairs' => $room->attributes['chairs'],
                'ambiant_music' => $room->attributes['ambiant_music'],
                'sale_for_profit' => $room->attributes['sale_for_profit'],
                'fundraiser' => $room->attributes['fundraiser'],           
                'room_type'  => $room->room_type,
                'availabilities' => [
                    'Monday' => [
                        'opening_hours' => '12:00:00',
                        'closing_hours' => '13:00:00'
                    ]
                ]
            ]
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name,
            'number' => $room->number,
            'floor' => $room->floor,
            'building' => $room->building,
            'status' => $room->status,
            'room_type'  => $room->room_type,
            'attributes' => json_encode([
                'capacity_standing' => $room->attributes['capacity_standing'],
                'capacity_sitting' => $room->attributes['capacity_sitting'],
                'food' => $room->attributes['food'],
                'alcohol' => $room->attributes['alcohol'],
                'a_v_permitted' => $room->attributes['a_v_permitted'],
                'projector' => $room->attributes['projector'],
                'television' => $room->attributes['television'],
                'computer' => $room->attributes['computer'],
                'whiteboard' => $room->attributes['whiteboard'],
                'sofas' => $room->attributes['sofas'],
                'coffee_tables' => $room->attributes['coffee_tables'],
                'tables' => $room->attributes['tables'],
                'chairs' => $room->attributes['chairs'],
                'ambiant_music' => $room->attributes['ambiant_music'],
                'sale_for_profit' => $room->attributes['sale_for_profit'],
                'fundraiser' => $room->attributes['fundraiser'],          
            ]),

        ]);

        $this->assertDatabaseHas(
            'availabilities',
            [
                'weekday' => 'Monday',
                'opening_hours' => '12:00:00',
                'closing_hours' => '13:00:00'
            ]
        );
    }

    /**
     * @test
     */
    public function testUsersIndexPageLoads()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get('/rooms');
        $response->assertOk();
        $response->assertSee("Rooms");
    }

    /**
     * @test
     */
    public function admins_can_update_rooms()
    {
        $room = Room::factory()->create();
        $user = User::factory()->make();

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building,
            'status' => $room->status,'attributes' => json_encode($room->attributes),             
        ]);

        $response = $this->actingAs($user)->put('/rooms/' . $room->id, [
            'name' => 'the room', 
            'number' => '24',
            'floor' => '2009', 
            'building' => 'wiseau', 
            'status' => 'available',
            'room_type'  => 'Lounge',     
            'capacity_standing' => '100',
            'capacity_sitting' => '80',
            'food' => 'true',
            'alcohol' => 'true',
            'a_v_permitted' => 'false',
            'projector' => 'true',
            'television' => 'true',
            'computer' => 'true',
            'whiteboard' => 'true',
            'sofas' => '1',
            'coffee_tables' => '1',
            'tables' => '1',
            'chairs' => '1',
            'ambiant_music' => 'true',
            'sale_for_profit' => 'false',
            'fundraiser' => 'false'
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('rooms', [
            'name' => 'the room', 
            'number' => '24',
            'floor' => '2009', 
            'building' => 'wiseau', 
            'status' => 'available',
            'room_type'  => 'Lounge', 
            'attributes' => json_encode([            
                'capacity_standing' => '100',
                'capacity_sitting' => '80',
                'food' => 'true',
                'alcohol' => 'true',
                'a_v_permitted' => 'false',
                'projector' => 'true',
                'television' => 'true',
                'computer' => 'true',
                'whiteboard' => 'true',
                'sofas' => '1',
                'coffee_tables' => '1',
                'tables' => '1',
                'chairs' => '1',
                'ambiant_music' => 'true',
                'sale_for_profit' => 'false',
                'fundraiser' => 'false'
            ]),
        ]);
    }

    /**
     * @test
     */
    public function admins_can_delete_rooms()
    {
        $room = Room::factory()->create();
        $user = User::factory()->make();

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building
        ]);

        $response = $this->actingAs($user)->delete('/rooms/' . $room->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);
    }
}