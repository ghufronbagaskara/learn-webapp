<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $title = fake()->sentence(6);
    $content = fake()->paragraphs(6, true);

    return [
      'user_id' => User::factory(),
      'category_id' => Category::factory(),
      'title' => $title,
      'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(100, 999),
      'content' => sprintf('<p>%s</p>', nl2br($content)),
      'excerpt' => Str::limit(strip_tags($content), 180),
      'status' => fake()->randomElement(['draft', 'published', 'archived']),
      'published_at' => now()->subDays(fake()->numberBetween(1, 60)),
      'meta_title' => $title,
      'meta_description' => fake()->sentence(15),
      'meta_keywords' => implode(', ', fake()->words(5)),
      'og_image' => null,
      'canonical_url' => null,
    ];
  }
}
