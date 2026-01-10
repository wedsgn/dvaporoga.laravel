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
        Schema::create('car_make_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_make_id');
            $table->unsignedBigInteger('order_id');

            $table->foreign('car_make_id')->references('id')->on('car_makes')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_make_order');
    }
};

