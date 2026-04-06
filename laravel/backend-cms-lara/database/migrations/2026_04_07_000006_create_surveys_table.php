<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('surveys', function (Blueprint $table) {
      $table->id();
      $table->string('title', 191);
      $table->text('description')->nullable();
      $table->longText('json_schema');
      $table->boolean('is_active')->default(true);
      $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('surveys');
  }
};
