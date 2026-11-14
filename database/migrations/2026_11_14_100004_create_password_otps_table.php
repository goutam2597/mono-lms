<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->index();
            $table->string('phone', 40)->nullable()->index();
            $table->string('channel', 10)->default('email'); // email | sms | whatsapp
            $table->string('purpose', 30)->default('verify'); // verify | signup | reset
            $table->string('otp', 255); // Bcrypt hash of 6-digit code
            $table->string('reset_token', 64)->nullable()->index();
            $table->boolean('verified')->default(false);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['email', 'purpose']);
            $table->index(['phone', 'purpose']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_otps');
    }
};
