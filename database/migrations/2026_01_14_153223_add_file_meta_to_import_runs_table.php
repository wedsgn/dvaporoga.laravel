<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('import_runs', function (Blueprint $table) {
      if (!Schema::hasColumn('import_runs', 'file_size')) {
        $table->unsignedBigInteger('file_size')->nullable();
      }

      if (!Schema::hasColumn('import_runs', 'mime_type')) {
        $table->string('mime_type')->nullable();
      }
    });
  }

  public function down(): void
  {
    Schema::table('import_runs', function (Blueprint $table) {
      $table->dropColumn(['original_name', 'file_size', 'mime_type']);
    });
  }
};
