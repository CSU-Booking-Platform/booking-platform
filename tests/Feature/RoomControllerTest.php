<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    const ROOM_TYPES = 'rooms.types';
    const BUILDING_NAMES = 'rooms.buildings';

    /**
     * @test
     */
    public function admins_can_create_rooms()
    {
        $room = Room::factory()->make();
        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.create']))->post('/admin/rooms', [
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
            'min_days_advance' => $room->min_days_advance,
            'max_days_advance' => $room->max_days_advance,
        ]);
        $response->assertSessionHasNoErrors();
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
            'min_days_advance' => $room->min_days_advance,
            'max_days_advance' => $room->max_days_advance,
        ]);
    }

    /**
     * @test
     */
    public function admins_can_create_rooms_with_availabilities()
    {
        $room = Room::factory()->make();

        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.create']))->post(
            '/admin/rooms',
            [
                'name' => $room->name,
                'number' => $room->number,
                'floor' => $room->floor,
                'building' => $room->building,
                'status' => $room->status,
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
                'room_type' => $room->room_type,
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
        $response = $this->actingAs($user)->get('/admin/rooms');
        $response->assertOk();
        $response->assertSee("Rooms");
    }

    /**
     * @test
     */
    public function admins_can_update_rooms()
    {
        $room = Room::factory()->create();

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building,
            'status' => $room->status, 'attributes' => json_encode($room->attributes),
        ]);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.update']))->put('/admin/rooms/' . $room->id, [
            'name' => 'the room',
            'number' => '24',
            'floor' => '2009',
            'building' => config(self::BUILDING_NAMES)[0],
            'status' => 'available',
            'room_type' => config(self::ROOM_TYPES)[0],
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
            'building' => config(self::BUILDING_NAMES)[0],
            'status' => 'available',
            'room_type' => config(self::ROOM_TYPES)[0],
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
    public function admins_can_update_rooms_with_availabilities()
    {
        $room = Room::factory()->create();

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building,
            'status' => $room->status, 'attributes' => json_encode($room->attributes),
        ]);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.update']))->put('/admin/rooms/' . $room->id, [
            'name' => 'the room',
            'number' => '24',
            'floor' => '2009',
            'building' => config(self::BUILDING_NAMES)[0],
            'status' => 'available',
            'room_type' => config(self::ROOM_TYPES)[0],
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
            'fundraiser' => 'false',
            'availabilities' => [
                'Monday' => [
                    'opening_hours' => '12:00:00',
                    'closing_hours' => '13:00:00'
                ]
            ]
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('rooms', [
            'name' => 'the room',
            'number' => '24',
            'floor' => '2009',
            'building' => config(self::BUILDING_NAMES)[0],
            'status' => 'available',
            'room_type' => config(self::ROOM_TYPES)[0],
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
    public function admins_can_update_rooms_with_availabilities_that_already_has_availabilities()
    {
        $room = Room::factory()->create();
        $user = $this->createUserWithPermissions(['rooms.update']);

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building,
            'status' => $room->status, 'attributes' => json_encode($room->attributes),
        ]);

        $response = $this->actingAs($user)->put('/admin/rooms/' . $room->id, [
            'name' => 'the room',
            'number' => '24',
            'floor' => '2009',
            'building' => config(self::BUILDING_NAMES)[0],
            'status' => 'available',
            'room_type' => config(self::ROOM_TYPES)[0],
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
            'fundraiser' => 'false',
            'availabilities' => [
                'Monday' => [
                    'opening_hours' => '12:00:00',
                    'closing_hours' => '13:00:00'
                ]
            ]
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('rooms', [
            'name' => 'the room',
            'number' => '24',
            'floor' => '2009',
            'building' => config(self::BUILDING_NAMES)[0],
            'status' => 'available',
            'room_type' => config(self::ROOM_TYPES)[0],
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

        $this->assertDatabaseHas(
            'availabilities',
            [
                'weekday' => 'Monday',
                'opening_hours' => '12:00:00',
                'closing_hours' => '13:00:00'
            ]
        );

        $response = $this->actingAs($user)->put('/admin/rooms/' . $room->id, [
            'name' => 'the room',
            'number' => '24',
            'floor' => '2009',
            'building' => config(self::BUILDING_NAMES)[0],
            'status' => 'available',
            'room_type' => config(self::ROOM_TYPES)[0],
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
            'fundraiser' => 'false',
            'availabilities' => [
                'Monday' => [
                    'opening_hours' => '13:00:00',
                    'closing_hours' => '14:00:00'
                ]
            ]
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas(
            'availabilities',
            [
                'weekday' => 'Monday',
                'opening_hours' => '13:00:00',
                'closing_hours' => '14:00:00'
            ]
        );

        $this->assertDatabaseMissing(
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
    public function admins_can_delete_rooms()
    {
        $room = Room::factory()->create();

        $this->assertDatabaseHas('rooms', [
            'name' => $room->name, 'number' => $room->number,
            'floor' => $room->floor, 'building' => $room->building
        ]);

        $response = $this->actingAs($this->createUserWithPermissions(['rooms.delete']))->delete('/admin/rooms/' . $room->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('rooms', ['name' => $room->name]);
    }
}
