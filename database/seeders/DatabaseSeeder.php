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
        $categories = ['Kompetisi', 'Seminar', 'Workshop', 'Open Recruitment', 'Lomba', 'Webinar'];
        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($category)],
                ['name' => $category, 'description' => 'Description for ' . $category]
            );
        }

        // Create Users
        $users = User::factory(10)->create();

        // Create Organizations
        $organizations = Organization::factory(5)->create();

        // Create Events
        $events = Event::factory(20)
            ->recycle(Category::all())
            ->recycle($organizations)
            ->recycle($users)
            ->create();

        // Create Bookmarks, Reviews, Comments
        Bookmark::factory(10)->recycle($users)->recycle($events)->create();
        Review::factory(10)->recycle($users)->recycle($events)->create();
        Comment::factory(10)->recycle($users)->recycle($events)->create();
    }
}
