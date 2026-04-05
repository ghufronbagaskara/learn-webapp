<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialAccount>
 */
class SocialAccountFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'user_id' => User::factory(),
      'provider' => fake()->randomElement(['google', 'facebook']),
      'provider_id' => (string) fake()->unique()->randomNumber(9),
      'token' => fake()->sha1(),
      'refresh_token' => fake()->sha1(),
    ];
  }
}
