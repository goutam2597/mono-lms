<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('students') && ! Schema::hasColumn('students', 'phone_verified_at')) {
            Schema::table('students', function (Blueprint $table) {
                $table->timestamp('phone_verified_at')->nullable()->after('phone');
            });
        }

        if (Schema::hasTable('instructors') && ! Schema::hasColumn('instructors', 'phone_verified_at')) {
            Schema::table('instructors', function (Blueprint $table) {
                $table->timestamp('phone_verified_at')->nullable()->after('phone');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'phone')) {
                    $table->string('phone', 40)->nullable()->after('email');
                }
                if (! Schema::hasColumn('users', 'phone_verified_at')) {
                    $table->timestamp('phone_verified_at')->nullable()->after('phone');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'phone_verified_at')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('phone_verified_at');
            });
        }

        if (Schema::hasTable('instructors') && Schema::hasColumn('instructors', 'phone_verified_at')) {
            Schema::table('instructors', function (Blueprint $table) {
                $table->dropColumn('phone_verified_at');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'phone_verified_at')) {
                    $table->dropColumn('phone_verified_at');
                }
                if (Schema::hasColumn('users', 'phone')) {
                    $table->dropColumn('phone');
                }
            });
        }
    }
};
