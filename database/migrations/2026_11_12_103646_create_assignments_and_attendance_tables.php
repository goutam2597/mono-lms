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
        Schema::table('live_classes', function (Blueprint $table) {
            $table->string('class_type', 30)->default('main')->after('meeting_platform');
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('total_points')->default(100);
            $table->unsignedInteger('pass_points')->default(50);
            $table->timestamp('due_date')->nullable();
            $table->boolean('allow_resubmission')->default(true);
            $table->string('status', 20)->default('published');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->text('submission_content')->nullable();
            $table->string('external_link', 500)->nullable();
            $table->string('file_path', 500)->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->string('status', 30)->default('submitted');
            $table->text('instructor_feedback')->nullable();
            $table->text('resubmission_note')->nullable();
            $table->unsignedBigInteger('graded_by')->nullable();
            $table->string('graded_by_type', 30)->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id']);
        });

        Schema::create('live_class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->unique(['live_class_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_class_attendances');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');

        Schema::table('live_classes', function (Blueprint $table) {
            $table->dropColumn('class_type');
        });
    }
};
