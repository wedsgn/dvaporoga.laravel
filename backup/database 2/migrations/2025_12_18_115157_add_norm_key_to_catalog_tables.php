<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['car_makes', 'car_models', 'cars', 'products'] as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (!Schema::hasColumn($table, 'norm_key')) {
                    $t->string('norm_key', 255)->nullable()->index();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['car_makes', 'car_models', 'cars', 'products'] as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (Schema::hasColumn($table, 'norm_key')) {
                    $t->dropColumn('norm_key');
                }
            });
        }
    }
};
