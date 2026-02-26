<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $col) {
            $col->id();
            $col->string('name');
            $col->string('sku')->unique();
            $col->decimal('price', 15, 2);
            $col->integer('stock')->default(0);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
