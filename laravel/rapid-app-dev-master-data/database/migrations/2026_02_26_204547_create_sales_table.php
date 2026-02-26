<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $col) {
            $col->id();
            $col->date('date');
            $col->foreignId('customer_id')->constrained()->onDelete('cascade');
            $col->decimal('total_amount', 15, 2)->default(0);
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
