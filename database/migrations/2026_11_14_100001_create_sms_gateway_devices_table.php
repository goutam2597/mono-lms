<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_gateway_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('device_id', 120)->nullable();
            // Only the SHA-256 hash is stored; the plaintext token is shown once at registration.
            $table->string('token_hash', 64)->unique();
            $table->string('fcm_token', 512)->nullable();
            $table->string('sim_number', 40)->nullable();
            $table->string('sim_state', 20)->nullable();
            $table->string('carrier', 80)->nullable();
            $table->string('app_version', 40)->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->timestamp('last_seen_at')->nullable();
            $table->unsignedTinyInteger('battery_level')->nullable();
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamp('last_sms_at')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'is_enabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_gateway_devices');
    }
};
