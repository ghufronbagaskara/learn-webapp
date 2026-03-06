<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained()->cascadeOnDelete();

      $table->string('xendit_invoice_id')->unique()->nullable();
      $table->string('external_id')->unique();
      $table->string('invoice_url')->unique();

      $table->decimal('amount', 12, 2);
      $table->string('currency', 3)->default('IDR');
      $table->string('payment_method')->nullable();
      $table->string('payment_channel')->nullable();

      $table->enum('status', [
        'PENDING',
        'PAID',
        'EXPIRED',
        'FAILED',
        'CANCELLED'
      ])->default('PENDING');

      $table->json('xendit_response')->nullable();

      $table->timestamp('paid_at')->nullable();
      $table->dateTime('expired_at')->nullable();
      $table->timestamps();

      $table->index('status');
      $table->index('external_id');
      $table->index('xendit_invoice_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('payments');
  }
};
