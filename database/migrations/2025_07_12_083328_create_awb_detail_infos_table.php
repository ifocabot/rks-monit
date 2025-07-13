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
        Schema::create('awb_detail_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('awb_tracking_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('service_code')->nullable();
            $table->string('service_type')->nullable();
            $table->string('cust_no')->nullable();
            $table->dateTime('cnote_date')->nullable();
            $table->string('goods_description')->nullable();
            $table->string('shipper_name')->nullable();
            $table->text('shipper_address')->nullable();
            $table->string('shipper_city')->nullable();
            $table->string('receiver_name')->nullable();
            $table->text('receiver_address')->nullable();
            $table->string('receiver_city')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awb_detail_infos');
    }
};
