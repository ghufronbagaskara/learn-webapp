<?php // database/migrations/2026_03_21_000001_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100);
      $table->string('email', 150)->unique();
      $table->string('password', 255);
      $table->enum('role', ['admin', 'customer'])->default('customer');
      $table->timestamp('email_verified_at')->nullable();
      $table->rememberToken();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
