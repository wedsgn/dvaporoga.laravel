<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('products', function (Blueprint $table) {
      $table->unsignedInteger('price')->nullable();
      $table->unsignedTinyInteger('discount_percentage')->nullable();
      $table->unsignedInteger('price_old')->nullable();
    });
  }

  public function down(): void
  {
    Schema::table('products', function (Blueprint $table) {
      $table->dropColumn([
        'price',
        'discount_percentage',
        'price_old'
      ]);
    });
  }
};
