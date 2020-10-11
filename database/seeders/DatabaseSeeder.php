<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->call([
            AdminSeeder::class
        ]);
        // User::factory(10)->create();
        $this->call(RolesAndPermissionsSeeder::class);

        if (app()->environment('local')) {
            $users = User::factory(10)->create();
            $users->first()->assignRole('super-admin');
        }


    }
}
