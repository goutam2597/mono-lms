<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['instructors', 'students'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('google_id')->nullable()->after('password');
            });
        }
    }

    public function down(): void
    {
        foreach (['instructors', 'students'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('google_id');
            });
        }
    }
};
