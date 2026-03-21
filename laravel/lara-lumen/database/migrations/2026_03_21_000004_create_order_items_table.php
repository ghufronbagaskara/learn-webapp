<?php // database/migrations/2026_03_21_000004_create_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('order_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
      $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
      $table->unsignedInteger('quantity');
      $table->decimal('price_per_unit', 15, 2);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('order_items');
  }
};
