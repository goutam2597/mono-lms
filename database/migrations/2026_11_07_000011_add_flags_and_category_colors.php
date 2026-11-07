<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Country flag codes for language switchers (en→us, hi→in, ar→sa) and
     * brand colors for the storefront category cards.
     */
    public function up(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->string('flag', 2)->default('us')->after('direction');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('color', 20)->default('#392c7d')->after('icon');
            $table->unsignedInteger('sort_order')->default(0)->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('flag');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('color');
            $table->dropColumn('sort_order');
        });
    }
};
