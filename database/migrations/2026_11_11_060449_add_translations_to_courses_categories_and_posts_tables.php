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
        Schema::table('courses', function (Blueprint $table) {
            $table->json('title_translations')->nullable()->after('title');
            $table->json('subtitle_translations')->nullable()->after('subtitle');
            $table->json('description_translations')->nullable()->after('description');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->json('title_translations')->nullable()->after('title');
            $table->json('excerpt_translations')->nullable()->after('excerpt');
            $table->json('content_translations')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['title_translations', 'subtitle_translations', 'description_translations']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name_translations');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title_translations', 'excerpt_translations', 'content_translations']);
        });
    }
};
