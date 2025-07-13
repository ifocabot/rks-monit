<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('awb_import_failures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('upload_batches');
            $table->string('awb_number');
            $table->string('reason'); // format_invalid, duplicate_db, etc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awb_import_failure_logs');
    }
};
