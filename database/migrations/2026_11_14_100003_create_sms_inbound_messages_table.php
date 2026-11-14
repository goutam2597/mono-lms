<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_inbound_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_gateway_device_id')->constrained('sms_gateway_devices')->cascadeOnDelete();
            // Stable per-device UID so incoming messages sent multiple times are deduplicated.
            $table->string('message_uid', 120);
            $table->string('from_phone', 40)->index();
            $table->text('body');
            $table->string('sim_number', 40)->nullable();
            $table->string('subscription_id', 40)->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->unique(['sms_gateway_device_id', 'message_uid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_inbound_messages');
    }
};
