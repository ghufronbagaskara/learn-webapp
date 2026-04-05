<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $categories = Category::factory()->count(4)->create();
    $tags = Tag::factory()->count(8)->create();

    BlogPost::factory()
      ->count(12)
      ->for($user)
      ->state(fn() => ['category_id' => $categories->random()->id, 'status' => 'published'])
      ->create()
      ->each(function (BlogPost $post) use ($tags, $user): void {
        $post->tags()->sync($tags->random(rand(2, 4))->pluck('id'));

        Comment::factory()
          ->count(rand(1, 3))
          ->for($post)
          ->for($user)
          ->create(['is_approved' => true]);
      });
    }
}
