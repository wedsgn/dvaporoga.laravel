<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('import_runs', function (Blueprint $table) {
            $table->json('detail_columns')->nullable();
            $table->timestamp('heartbeat_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('import_runs', function (Blueprint $table) {
            $table->dropColumn(['detail_columns', 'heartbeat_at']);
        });
    }
};
