<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('penduduks', function (Blueprint $table): void {
      $table->id();
      $table->string('nama');
      $table->unsignedTinyInteger('usia');
      $table->text('alamat');
      $table->string('pekerjaan');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('penduduks');
  }
};
