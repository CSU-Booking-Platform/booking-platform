<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'number' => $this->faker->word,
            'floor' => $this->faker->RandomDigit,
            'building' => $this->faker->word,
            'status' => $this->faker->randomElement(["available", "unavailable"]),
            'room_type' => $this->faker->randomElement(Room::ROOM_TYPES),
            'min_days_advance' => $this->faker->numberBetween(0, 4),
            'max_days_advance' => $this->faker->numberBetween(5, 14),
            'attributes' => [
                'capacity_standing' => $this->faker->RandomDigit,
                'capacity_sitting' => $this->faker->RandomDigit,
                'food' => $this->faker->boolean,
                'alcohol' => $this->faker->boolean,
                'a_v_permitted' => $this->faker->boolean,
                'projector' => $this->faker->boolean,
                'television' => $this->faker->boolean,
                'computer' => $this->faker->boolean,
                'whiteboard' => $this->faker->boolean,
                'sofas' => $this->faker->RandomDigit,
                'coffee_tables' => $this->faker->RandomDigit,
                'tables' => $this->faker->RandomDigit,
                'chairs' => $this->faker->RandomDigit,
                'ambiant_music' => $this->faker->boolean,
                'sale_for_profit' => $this->faker->boolean,
                'fundraiser' => $this->faker->boolean
            ]
        ];
    }
}
