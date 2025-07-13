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
        Schema::create('awb_trackings', function (Blueprint $table) {
            $table->id();
            $table->string('awb_number')->unique();
            $table->string('status_code', 10)->nullable(); // misalnya D01, IP3, RC1
            $table->string('status_label')->nullable();    // "DELIVERED", "IN TRANSIT"
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('delivered_at')->nullable(); // dari API jika tersedia
            $table->string('pod_receiver')->nullable();    // nama penerima
            $table->boolean('is_completed')->default(false);
            $table->foreignId('uploaded_by')->constrained('users');
            $table->foreignId('batch_id')->nullable()->constrained('upload_batches');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awb_trackings');
    }
};
