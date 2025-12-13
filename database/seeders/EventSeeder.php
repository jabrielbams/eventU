<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Academic', 'Non-Academic', 'Workshop', 'Seminar', 'Competition'];
        
        // Generate 20 dummy events
        for ($i = 0; $i < 20; $i++) {
            Event::create([
                'title' => 'Event ' . ($i + 1) . ' - ' . fake()->sentence(3),
                'description' => fake()->paragraph(3),
                'date' => fake()->dateTimeBetween('now', '+3 months'),
                'category' => fake()->randomElement($categories),
                'image_url' => 'https://via.placeholder.com/600x400/EE2E24/000000?text=Event+' . ($i + 1),
                'organizer' => fake()->company(),
            ]);
        }
    }
}
