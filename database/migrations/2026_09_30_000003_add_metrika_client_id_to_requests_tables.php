<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_consultations', function (Blueprint $table) {
            $table->string('metrika_client_id', 100)
                ->nullable()
                ->index('request_consultations_metrika_client_id_index');
        });

        Schema::table('request_products', function (Blueprint $table) {
            $table->string('metrika_client_id', 100)
                ->nullable()
                ->index('request_products_metrika_client_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('request_consultations', function (Blueprint $table) {
            $table->dropIndex('request_consultations_metrika_client_id_index');
            $table->dropColumn('metrika_client_id');
        });

        Schema::table('request_products', function (Blueprint $table) {
            $table->dropIndex('request_products_metrika_client_id_index');
            $table->dropColumn('metrika_client_id');
        });
    }
};
