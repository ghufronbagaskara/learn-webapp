<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /** Run the migrations. */
  public function up(): void
  {
    Schema::create('orders', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->string('order_number')->unique();
      $table->string('status')->index();
      $table->string('category')->index();
      $table->decimal('total', 14, 2);
      $table->timestamps();

      $table->index('created_at');
      $table->index(['status', 'created_at']);
      $table->index(['category', 'created_at']);
      $table->index(['created_at', 'total']);
    });
  }

  /** Reverse the migrations. */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
