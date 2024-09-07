<?php

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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('generation')->nullable();
            $table->string('years')->nullable();
            $table->string('body')->nullable();
            $table->string('top')->nullable();
            $table->string('artikul')->nullable();
            $table->string('image')->nullable();
            $table->string('image_mob')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('car_model_id')->constrained('car_models')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
