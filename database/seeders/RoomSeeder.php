<?php

namespace Database\Seeders;
use App\Models\Room;

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
        Room::create([
            'name' => Str::random(10),
            'number' => Str::random(10),
            'floor' => rand(1, 25),
            'building' => Str::random(10),
            'status' => array_rand(["available", "unavailable"])
        ]);
    }
}
