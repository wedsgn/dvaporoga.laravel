<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Если есть внешние ключи — безопаснее временно отключить проверки
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('product_price');
        Schema::dropIfExists('product_steel_type');
        Schema::dropIfExists('product_size');
        Schema::dropIfExists('product_thickness');

        Schema::dropIfExists('prices');
        Schema::dropIfExists('steel_types');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('thicknesses');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
    }
};
