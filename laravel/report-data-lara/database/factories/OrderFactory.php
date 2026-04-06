<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $statuses = ['pending', 'paid', 'completed', 'cancelled'];
    $categories = ['electronics', 'fashion', 'grocery', 'services'];

    return [
      'user_id' => User::factory(),
      'order_number' => 'ORD-' . strtoupper(fake()->bothify('??######')),
      'status' => fake()->randomElement($statuses),
      'category' => fake()->randomElement($categories),
      'total' => fake()->numberBetween(50_000, 5_000_000),
      'created_at' => fake()->dateTimeBetween('-90 days', 'now'),
      'updated_at' => now(),
    ];
  }
}
