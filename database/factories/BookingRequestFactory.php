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
            'status' => $this->faker->randomElement(BookingRequest::STATUS_TYPES),
            'reference' => [],
            'onsite_contact' => [
                'name' => $this->faker->name,
                'phone' => $this->faker->phoneNumber,
                'email' => $this->faker->email,
            ],
            'event' => [
                'title' => $this->faker->word,
                'type' => $this->faker->word,
                'description' => $this->faker->paragraph,
                'guest_speakers' => $this->faker->name,
                'attendees' => $this->faker->numberBetween(100),
            ],
            'notes' => $this->faker->paragraphs(3, true),
        ];
    }
}
