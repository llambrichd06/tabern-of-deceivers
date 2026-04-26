<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'room_code' => $this->faker->unique()->regexify('[A-F0-0]{8}'), //this is to make the 8 random characters without repeating the codes
            'state' => $this->faker->randomElement(['on_going', 'lobby', 'completed']),
            'private' => $this->faker->randomElement(['1', '0']),
            'host_id' => User::factory(),
        ];;
    }
}
