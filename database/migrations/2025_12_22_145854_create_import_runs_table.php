<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_runs', function (Blueprint $table) {
            $table->id();

            $table->string('type')->default('catalog');
            $table->string('status')->default('uploaded');

            $table->string('original_name')->nullable();
            $table->string('stored_path');
            $table->string('file_hash', 64)->index();

            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('processed_rows')->default(0);
            $table->unsignedInteger('current_row')->default(0);

            $table->unsignedInteger('chunk_size')->default(100);

            $table->text('last_error')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_runs');
    }
};
