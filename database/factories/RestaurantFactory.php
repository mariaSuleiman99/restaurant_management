<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'mobile_number' => fake()->regexify('^\+?[0-9]{10}$'),
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'status' => 'Pending'
        ];
    }
}
