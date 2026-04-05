<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table): void {
      if (! Schema::hasColumn('users', 'two_factor_enabled')) {
        $table->boolean('two_factor_enabled')->default(false)->after('two_factor_secret');
      }

      if (! Schema::hasColumn('users', 'deleted_at')) {
        $table->softDeletes();
      }
    });

    Schema::table('users', function (Blueprint $table): void {
      $table->string('password')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table): void {
      if (Schema::hasColumn('users', 'two_factor_enabled')) {
        $table->dropColumn('two_factor_enabled');
      }

      if (Schema::hasColumn('users', 'deleted_at')) {
        $table->dropSoftDeletes();
      }

      $table->string('password')->nullable(false)->change();
    });
  }
};
