<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookingRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'room_id' => Room::inRandomOrder()->first()->id ?? Room::factory(),
            'start_time' => $this->faker->dateTimeThisMonth('now', '+2 hours'),
            'end_time' => $this->faker->dateTimeThisMonth('+2 hours', '+4 hours'),
            'status' => $this->faker->randomElement(["review", "approved", "refused"]),
            
        ];
    }
}
