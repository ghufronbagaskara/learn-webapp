<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Comment;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'blog_post_id' => BlogPost::factory(),
      'user_id' => User::factory(),
      'parent_id' => null,
      'content' => fake()->paragraph(),
      'is_approved' => true,
    ];
  }
}
