<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'event_date' => fake()->dateTimeBetween('+1 week', '+1 year'),
            'event_time' => fake()->time(),
            'location' => fake()->address(),
            'category_id' => \App\Models\Category::factory(),
            'organization_id' => \App\Models\Organization::factory(),
            'user_id' => \App\Models\User::factory(),
            'banner' => fake()->imageUrl(),
            'status' => fake()->randomElement(['draft', 'published', 'cancelled', 'completed']),
            'is_online' => fake()->boolean(),
            'registration_link' => fake()->url(),
        ];
    }
}
