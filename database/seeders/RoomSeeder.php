<?php

namespace Database\Seeders;
use App\Models\Room;
use App\Models\Availability;


use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $weekdays =['Monday', 'Tuesday', 'Wednesday','Thursday','Friday','Saturday','Sunday'];

        $room = Room::create([
            'name' => 'CSU Lounge',
            'number' => 'H-709',
            'floor' => '7',
            'building' => 'Hall',
            'status' => 'available',
            'attributes' => [
                'capacity_standing' => '450',
                'capacity_sitting' => '150',
                'food' => true,
                'alcohol' => true,
                'a_v_permitted' => true,
                'projector' => false,
                'television' => false,
                'computer' => false,
                'whiteboard' => false,
                'sofas' => 3,
                'coffee_tables' => 3,
                'tables' => 0,
                'chairs' => 0,
                'ambiant_music' => false,
                'sale_for_profit' => false,
                'fundraiser' => false,
            ],
            'min_days_advance' => 10,
            'max_days_advance' => 25,
            'room_type' => 'Lounge'
        ]);

        foreach ($weekdays as $weekday) {
            Availability::create([
                'weekday' => $weekday, 
                'opening_hours' => '07:00',
                'closing_hours' => '23:00',
                'room_id' => $room->id
            ]);
        }


        $room = Room::create([
            'name' => 'CSU Cafeteria',
            'number' => 'H-718',
            'floor' => '7',
            'building' => 'Hall',
            'status' => 'available',
            'attributes' => [
                'capacity_standing' => '327',
                'capacity_sitting' => '109',
                'food' => true,
                'alcohol' => true,
                'a_v_permitted' => true,
                'projector' => false,
                'television' => false,
                'computer' => false,
                'whiteboard' => false,
                'sofas' => 0,
                'coffee_tables' => 0,
                'tables' => 0,
                'chairs' => 0,
                'ambiant_music' => false,
                'sale_for_profit' => false,
                'fundraiser' => false,
            ],
            'min_days_advance' => 10,
            'max_days_advance' => 25,
            'room_type' => 'Lounge'
        ]);

        foreach ($weekdays as $weekday) {
        Availability::create([
            'weekday' => $weekday, 
            'opening_hours' => '07:00',
            'closing_hours' => '23:00',
            'room_id' => $room->id
        ]);
        }


        $room = Room::create([
            'name' => 'Modular Conference Room',
            'number' => 'H-711-4',
            'floor' => '7',
            'building' => 'Hall',
            'status' => 'available',
            'attributes' => [
                'capacity_standing' => '0',
                'capacity_sitting' => '20',
                'food' => false,
                'alcohol' => false,
                'a_v_permitted' => true,
                'projector' => true,
                'television' => false,
                'computer' => false,
                'whiteboard' => false,
                'sofas' => 0,
                'coffee_tables' => 0,
                'tables' => 1,
                'chairs' => 10,
                'ambiant_music' => false,
                'sale_for_profit' => false,
                'fundraiser' => false,
            ],
            'min_days_advance' => 0,
            'max_days_advance' => 200,
            'room_type' => 'Conference'
        ]);

        foreach ($weekdays as $weekday) {
            Availability::create([
                'weekday' => $weekday, 
                'opening_hours' => '10:00',
                'closing_hours' => '18:00',
                'room_id' => $room->id
            ]);
            } 

            $room = Room::create([
                'name' => 'Executive Conference Room',
                'number' => 'H-711-3',
                'floor' => '7',
                'building' => 'Hall',
                'status' => 'available',
                'attributes' => [
                    'capacity_standing' => '0',
                    'capacity_sitting' => '15',
                    'food' => false,
                    'alcohol' => false,
                    'a_v_permitted' => true,
                    'projector' => false,
                    'television' => true,
                    'computer' => false,
                    'whiteboard' => false,
                    'sofas' => 0,
                    'coffee_tables' => 0,
                    'tables' => 1,
                    'chairs' => 12,
                    'ambiant_music' => false,
                    'sale_for_profit' => false,
                    'fundraiser' => false,
                ],
                'min_days_advance' => 0,
                'max_days_advance' => 200,
                'room_type' => 'Conference'
            ]);
    
            foreach ($weekdays as $weekday) {
                Availability::create([
                    'weekday' => $weekday, 
                    'opening_hours' => '10:00',
                    'closing_hours' => '18:00',
                    'room_id' => $room->id
                ]);
                } 

                $room = Room::create([
                    'name' => 'Art Nook',
                    'number' => 'H-725',
                    'floor' => '7',
                    'building' => 'Hall',
                    'status' => 'available',
                    'attributes' => [
                        'capacity_standing' => '0',
                        'capacity_sitting' => '20',
                        'food' => true,
                        'alcohol' => false,
                        'a_v_permitted' => true,
                        'projector' => false,
                        'television' => false,
                        'computer' => false,
                        'whiteboard' => true,
                        'sofas' => 0,
                        'coffee_tables' => 0,
                        'tables' => 4,
                        'chairs' => 8,
                        'ambiant_music' => false,
                        'sale_for_profit' => false,
                        'fundraiser' => false,
                    ],
                    'min_days_advance' => 0,
                    'max_days_advance' => 200,
                    'room_type' => 'Mezzanine'
                ]);
        
                foreach ($weekdays as $weekday) {
                    Availability::create([
                        'weekday' => $weekday, 
                        'opening_hours' => '07:00',
                        'closing_hours' => '23:00',
                        'room_id' => $room->id
                    ]);
                    } 
                    $room = Room::create([
                        'name' => 'CSU Information table',
                        'number' => 'H-220-90',
                        'floor' => '2',
                        'building' => 'Hall',
                        'status' => 'available',
                        'attributes' => [
                            'capacity_standing' => '0',
                            'capacity_sitting' => '8',
                            'food' => true,
                            'alcohol' => false,
                            'a_v_permitted' => false,
                            'projector' => false,
                            'television' => false,
                            'computer' => false,
                            'whiteboard' => false,
                            'sofas' => 0,
                            'coffee_tables' => 0,
                            'tables' => 4,
                            'chairs' => 8,
                            'ambiant_music' => false,
                            'sale_for_profit' => false,
                            'fundraiser' => false,
                        ],
                        'min_days_advance' => 0,
                        'max_days_advance' => 200,
                        'room_type' => 'Mezzanine'
                    ]);
            
                    foreach ($weekdays as $weekday) {
                        Availability::create([
                            'weekday' => $weekday, 
                            'opening_hours' => '07:00',
                            'closing_hours' => '23:00',
                            'room_id' => $room->id
                        ]);
                        } 
                        $room = Room::create([
                            'name' => 'CSU Kiosk',
                            'number' => 'H-220-90',
                            'floor' => '2',
                            'building' => 'Hall',
                            'status' => 'available',
                            'attributes' => [
                                'capacity_standing' => '0',
                                'capacity_sitting' => '4',
                                'food' => false,
                                'alcohol' => false,
                                'a_v_permitted' => false,
                                'projector' => false,
                                'television' => false,
                                'computer' => false,
                                'whiteboard' => false,
                                'sofas' => 0,
                                'coffee_tables' => 0,
                                'tables' => 1,
                                'chairs' => 4,
                                'ambiant_music' => false,
                                'sale_for_profit' => true,
                                'fundraiser' => false,
                            ],
                            'min_days_advance' => 0,
                            'max_days_advance' => 200,
                            'room_type' => 'Mezzanine'
                        ]);
                
                        foreach ($weekdays as $weekday) {
                            Availability::create([
                                'weekday' => $weekday, 
                                'opening_hours' => '07:00',
                                'closing_hours' => '23:00',
                                'room_id' => $room->id
                            ]);
                            } 
    }
}