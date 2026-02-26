<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $col) {
            $col->id();
            $col->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $col->foreignId('product_id')->constrained()->onDelete('cascade');
            $col->integer('quantity');
            $col->decimal('price', 15, 2);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
