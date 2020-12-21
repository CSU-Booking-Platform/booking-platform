<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);

        if (app()->environment('local') || app()->environment('staging')) {
            $this->call([
                EasyUserSeeder::class
            ]);
            $users = User::factory(10)->create();
            $users->first()->assignRole('super-admin');

            $this->call([
                RoomSeeder::class
            ]);
            $rooms = Room::factory(5)->create();
        }


    }
}
