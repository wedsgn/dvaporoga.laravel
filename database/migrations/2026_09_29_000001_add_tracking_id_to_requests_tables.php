<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_consultations', function (Blueprint $table) {
            $table->uuid('tracking_id')->nullable()->unique()->after('id');
        });

        Schema::table('request_products', function (Blueprint $table) {
            $table->uuid('tracking_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('request_consultations', function (Blueprint $table) {
            $table->dropUnique(['tracking_id']);
            $table->dropColumn('tracking_id');
        });

        Schema::table('request_products', function (Blueprint $table) {
            $table->dropUnique(['tracking_id']);
            $table->dropColumn('tracking_id');
        });
    }
};
