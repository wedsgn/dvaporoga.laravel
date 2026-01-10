<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();

            $table->string('title');
            $table->integer('price_from')->nullable();
            $table->integer('price_old')->nullable();
            $table->string('currency', 8)->default('₽');
            $table->unsignedInteger('sort')->default(1000);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['car_id', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_offers');
    }
};
