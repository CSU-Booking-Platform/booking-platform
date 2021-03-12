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
        BookingRequest::factory()
            ->hasReservations(random_int(1, 3))
            ->hasReviewers(random_int(1, 3))
            ->count(10)
            ->create();
    }
}
