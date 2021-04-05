<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'booking_id' => BookingRequest::inRandomOrder()->first()->id ?? BookingRequest::factory(),
            'system'  => $this->faker->boolean(25),
            'body'    => strip_tags($this->faker->randomHtml(2, 3), ['p', 'a']),
        ];
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function systemLog()
    {
        return $this->state(function (array $attributes) {
            return [
                'system'  => true,
                'body'    => $this->faker->sentence,
            ];
        });
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function userComment()
    {
        return $this->state(function (array $attributes) {
            return [
                'system'  => false,
                'body'    => $this->faker->sentence,
            ];
        });
    }
}
