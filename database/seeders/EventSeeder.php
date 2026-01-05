<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Users exist (we need an admin or organizer user)
        $user = User::first();
        if (!$user) {
             $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                //'role' => 'admin',
            ]);
        }

        // 2. Create Categories
        $categories = [
            ['name' => 'Workshop', 'slug' => 'workshop', 'description' => 'Interactive learning sessions.'],
            ['name' => 'Seminar', 'slug' => 'seminar', 'description' => 'Expert talks and panels.'],
            ['name' => 'Competition', 'slug' => 'competition', 'description' => 'Skill-based contests.'],
        ];

        foreach ($categories as $cat) {
            DB::table('category')->updateOrInsert(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Get Category IDs
        $workshopId = DB::table('category')->where('slug', 'workshop')->value('id');
        $seminarId = DB::table('category')->where('slug', 'seminar')->value('id');
        $competitionId = DB::table('category')->where('slug', 'competition')->value('id');

        // 4. Create 5 Dummy Events
        $events = [
            [
                'title' => 'Web Development Workshop',
                'description' => 'A hands-on workshop to learn the basics of Modern Web Development using Laravel and React.',
                'date' => Carbon::now()->addDays(2),
                'time' => '10:00:00',
                'location' => 'Gedung Tokong Nanas, Telkom University',
                'category_id' => $workshopId,
                'user_id' => $user->id,
                'image' => 'https://placehold.co/600x400/EE2E24/FFFFFF?text=Workshop',
                'status' => 'published',
                'is_online' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'National Tech Seminar',
                'description' => 'Join industry leaders as they discuss the future of AI and Machine Learning.',
                'date' => Carbon::now()->addDays(5),
                'time' => '09:00:00',
                'location' => 'Auditorium Telyu',
                'category_id' => $seminarId,
                'user_id' => $user->id,
                'image' => 'https://placehold.co/600x400/000000/FFFFFF?text=Seminar',
                'status' => 'published',
                'is_online' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'UI/UX Design Competition',
                'description' => 'Showcase your creativity in this 24-hour design hackathon. Win exciting prizes!',
                'date' => Carbon::now()->addDays(10),
                'time' => '08:00:00',
                'location' => 'Telyu Creative Hub',
                'category_id' => $competitionId,
                'user_id' => $user->id,
                'image' => 'https://placehold.co/600x400/EE2E24/000000?text=Competition',
                'status' => 'published',
                'is_online' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'title' => 'Advanced Laravel Masterclass',
                'description' => 'Deep dive into Laravel core concepts, service providers, and architecture patterns.',
                'date' => Carbon::now()->addDays(12),
                'time' => '13:00:00',
                'location' => 'Zoom Meeting',
                'category_id' => $workshopId,
                'user_id' => $user->id,
                'image' => 'https://placehold.co/600x400/333333/FFFFFF?text=Masterclass',
                'status' => 'published',
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'title' => 'E-Sports Tournament 2025',
                'description' => 'The biggest collegiate e-sports tournament. Dota 2, Valorant, and Mobile Legends.',
                'date' => Carbon::now()->addMonth(),
                'time' => '10:00:00',
                'location' => 'Telyu Convention Hall',
                'category_id' => $competitionId,
                'user_id' => $user->id,
                'image' => 'https://placehold.co/600x400/EE2E24/FFFFFF?text=E-Sports',
                'status' => 'published',
                'is_online' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('event')->insert($events);
    }
}
