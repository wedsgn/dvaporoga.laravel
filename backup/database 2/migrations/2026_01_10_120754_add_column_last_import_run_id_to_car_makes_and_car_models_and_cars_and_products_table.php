<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['car_makes','car_models','cars','products'];

        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($t) {
                if (!Schema::hasColumn($t, 'last_import_run_id')) {
                    $table->unsignedBigInteger('last_import_run_id')->nullable()->index();
                }
            });
        }
    }

    public function down(): void
    {
        $tables = ['car_makes','car_models','cars','products'];

        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($t) {
                if (Schema::hasColumn($t, 'last_import_run_id')) {
                    $table->dropColumn('last_import_run_id');
                }
            });
        }
    }
};
