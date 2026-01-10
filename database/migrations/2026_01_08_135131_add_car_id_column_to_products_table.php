<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('car_id')->nullable()->after('id');

            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('cascade');

            $table->index('car_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->dropIndex(['car_id']);
            $table->dropColumn('car_id');
        });
    }
};
