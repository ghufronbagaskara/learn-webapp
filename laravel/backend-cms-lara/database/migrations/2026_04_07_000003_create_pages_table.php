<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('pages', function (Blueprint $table) {
      $table->id();
      $table->string('title', 191);
      $table->string('slug', 191)->unique();
      $table->longText('content');
      $table->boolean('is_published')->default(false);
      $table->integer('order')->default(0);
      $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('pages');
  }
};
