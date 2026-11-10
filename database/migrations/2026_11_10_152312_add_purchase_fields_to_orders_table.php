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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('payment_deadline')->nullable()->after('payment_status');
            $table->unsignedInteger('payment_attempts')->default(0)->after('payment_deadline');
            $table->string('payment_reference', 190)->nullable()->after('payment_attempts');
            $table->decimal('discount', 10, 2)->default(0)->after('payment_reference');
            $table->string('coupon_code', 50)->nullable()->after('discount');
            $table->timestamp('paid_at')->nullable()->after('coupon_code');
            $table->timestamp('enrollment_activated_at')->nullable()->after('paid_at');
            $table->string('val_id', 190)->nullable()->after('enrollment_activated_at');

            $table->index(['payment_status', 'payment_deadline']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'payment_deadline']);
            $table->dropColumn([
                'payment_deadline',
                'payment_attempts',
                'payment_reference',
                'discount',
                'coupon_code',
                'paid_at',
                'enrollment_activated_at',
                'val_id',
            ]);
        });
    }
};
