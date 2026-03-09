<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('title')->nullable();
            $table->json('images')->nullable();
            $table->json('items')->nullable(); // для сложных блоков (до / после)
            $table->timestamps();
        });

        DB::table('blocks')->insert([

            [
                'key' => 'home_gallery',
                'name' => 'Галерея на главной (Примеры готовых изделий)',
                'title' => 'Примеры готовых изделий',
                'images' => json_encode([]),
                'items' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'key' => 'catalog_default_parts',
                'name' => 'Галерея на главной (Дефолтные детали с каталогов)',
                'title' => 'Дефолтные детали с каталогов',
                'images' => json_encode([]),
                'items' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'key' => 'repair_examples',
                'name' => 'Галерея на главной и товарной(Примеры ремонтов авто)',
                'title' => 'Примеры ремонтов авто',
                'images' => null,
                'items' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
