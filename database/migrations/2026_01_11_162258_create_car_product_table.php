<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('car_product', function (Blueprint $table) {
      $table->id();

      $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
      $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

      // фото товара "для этой машины"
      $table->string('image')->nullable();
      $table->string('image_mob')->nullable();

      $table->timestamps();

      $table->unique(['car_id', 'product_id']);
      $table->index(['product_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('car_product');
  }
};
