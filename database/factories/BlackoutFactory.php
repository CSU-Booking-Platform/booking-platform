<?php

namespace Database\Factories;

use App\Models\Blackout;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlackoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blackout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'name' => $this->faker->word,
            'start_time' => $this->faker->dateTimeBetween('now', '+2 hours'),
            'end_time' => $this->faker->dateTimeBetween('now +2 hours', '+4 hours')
        ];
    }
}
