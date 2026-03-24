<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main_infos', function (Blueprint $table) {
            $table->string('vk')->nullable()->after('telegram');
            $table->string('max')->nullable()->after('vk');
            $table->string('phone_clients')->nullable()->after('max');
        });
    }

    public function down(): void
    {
        Schema::table('main_infos', function (Blueprint $table) {
            $table->dropColumn(['vk', 'max', 'phone_clients']);
        });
    }
};
