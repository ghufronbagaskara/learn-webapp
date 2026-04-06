<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->id();
      $table->string('title', 191);
      $table->string('slug', 191)->unique();
      $table->text('excerpt');
      $table->longText('body');
      $table->string('cover_image', 191)->nullable();
      $table->string('category', 100)->nullable();
      $table->boolean('is_published')->default(false);
      $table->timestamp('published_at')->nullable();
      $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('posts');
  }
};
