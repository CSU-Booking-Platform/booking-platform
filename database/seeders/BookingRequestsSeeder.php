<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingRequest;

class BookingRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookingRequest::factory()->count(10)->create();
    }
}
