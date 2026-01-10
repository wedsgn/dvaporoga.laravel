<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();

            $table->string('title');                 // текст чипса
            $table->unsignedInteger('sort')->default(1000);

            $table->timestamps();

            $table->index(['car_id', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_tags');
    }
};
