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
        $rooms = Room::factory(5)->create();
    }
}
