<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Organization;
use App\Models\Event;
use App\Models\Bookmark;
use App\Models\Review;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create specific categories
        // $categories = ['Kompetisi', 'Seminar', 'Workshop', 'Open Recruitment', 'Lomba', 'Webinar'];
        // foreach ($categories as $category) {
        //     Category::factory()->create([
        //         'name' => $category,
        //         'slug' => \Illuminate\Support\Str::slug($category),
        //     ]);
        // }

        // Create Users
        // $users = User::factory(10)->create();

        // Create Organizations
        // $organizations = Organization::factory(5)->create();

        // Run Event Seeder (New Implementation)
        $this->call([
            EventSeeder::class,
        ]);

        // Create Bookmarks, Reviews, Comments (Commented out as they rely on old schema)
        // Bookmark::factory(10)->recycle($users)->recycle($events)->create();
        // Review::factory(10)->recycle($users)->recycle($events)->create();
        // Comment::factory(10)->recycle($users)->recycle($events)->create();
    }
}
