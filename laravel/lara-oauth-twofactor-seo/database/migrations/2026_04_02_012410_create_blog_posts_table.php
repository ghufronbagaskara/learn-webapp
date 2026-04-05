<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('blog_posts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->foreignId('category_id')->constrained()->cascadeOnDelete();
      $table->string('title');
      $table->string('slug')->unique();
      $table->longText('content');
      $table->string('excerpt', 320)->nullable();
      $table->string('featured_image')->nullable();
      $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
      $table->timestamp('published_at')->nullable();
      $table->string('meta_title')->nullable();
      $table->string('meta_description')->nullable();
      $table->string('meta_keywords')->nullable();
      $table->string('og_image')->nullable();
      $table->string('canonical_url')->nullable();
      $table->softDeletes();
      $table->timestamps();

      $table->index('status');
      $table->index('published_at');
      $table->index('slug');
      $table->index('user_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('blog_posts');
  }
};
