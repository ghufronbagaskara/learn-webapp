<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('seo_metas', function (Blueprint $table) {
      $table->id();
      $table->string('seoable_type', 191);
      $table->unsignedBigInteger('seoable_id');
      $table->string('meta_title', 191)->nullable();
      $table->text('meta_description')->nullable();
      $table->text('meta_keywords')->nullable();
      $table->string('og_title', 191)->nullable();
      $table->text('og_description')->nullable();
      $table->string('og_image', 191)->nullable();
      $table->string('canonical_url', 191)->nullable();
      $table->timestamps();

      $table->index(['seoable_type', 'seoable_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('seo_metas');
  }
};
