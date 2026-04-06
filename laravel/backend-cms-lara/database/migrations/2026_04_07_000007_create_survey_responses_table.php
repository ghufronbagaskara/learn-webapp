<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('survey_responses', function (Blueprint $table) {
      $table->id();
      $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
      $table->string('respondent_email', 191)->nullable();
      $table->longText('response_data');
      $table->timestamp('submitted_at');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('survey_responses');
  }
};
