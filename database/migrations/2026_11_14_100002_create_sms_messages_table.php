<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            // Public UUID the device reports against; row ID stays internal.
            $table->uuid('public_id')->unique();
            $table->foreignId('sms_gateway_device_id')->nullable()->constrained('sms_gateway_devices')->nullOnDelete();
            $table->string('to_phone', 40)->index();
            $table->text('body');
            // otp | enrollment | notice | test | other
            $table->string('kind', 32)->default('other');
            $table->string('provider', 20)->default('gateway');
            // pending | processing | sent | failed
            $table->string('status', 20)->default('pending');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedTinyInteger('max_attempts')->default(3);
            $table->timestamp('available_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            // The claim query filters on status + available_at and device + status.
            $table->index(['status', 'available_at']);
            $table->index(['sms_gateway_device_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
    }
};
