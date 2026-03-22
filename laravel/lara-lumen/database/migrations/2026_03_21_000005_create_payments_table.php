<?php // database/migrations/2026_03_21_000005_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
      $table->decimal('amount', 15, 2);
      $table->enum('method', ['bank_transfer', 'credit_card', 'e_wallet']);
      $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
      $table->string('payment_ref', 100)->nullable()->unique();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('payments');
  }
};
