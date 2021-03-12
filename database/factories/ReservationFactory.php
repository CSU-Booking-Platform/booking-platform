<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'room_id' => Room::inRandomOrder()->first()->id ?? Room::factory(),
            'booking_request_id' => BookingRequest::factory(),
            'start_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_time' => function (array $attributes) {
                return Carbon::parse($attributes['start_time'])->addHours(2);
            },
        ];
    }
}
