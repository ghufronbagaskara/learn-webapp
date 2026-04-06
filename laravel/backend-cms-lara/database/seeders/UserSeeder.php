<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    User::updateOrCreate([
      'email' => 'admin@example.com',
    ], [
      'name' => 'Super Admin',
      'password' => Hash::make('password'),
      'role' => 'admin',
      'email_verified_at' => now(),
    ]);

    User::updateOrCreate([
      'email' => 'editor@example.com',
    ], [
      'name' => 'Jane Editor',
      'password' => Hash::make('password'),
      'role' => 'editor',
      'email_verified_at' => now(),
    ]);
  }
}
