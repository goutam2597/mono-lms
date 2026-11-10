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
        Schema::table('instructors', function (Blueprint $table) {
            $table->string('certification_file')->nullable()->after('bio');
            $table->string('national_id_file')->nullable()->after('certification_file');
            $table->string('verification_status', 30)->default('unverified')->after('national_id_file'); // unverified, pending, approved, rejected
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->text('rejection_reason')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn([
                'certification_file',
                'national_id_file',
                'verification_status',
                'verified_at',
                'rejection_reason',
            ]);
        });
    }
};
