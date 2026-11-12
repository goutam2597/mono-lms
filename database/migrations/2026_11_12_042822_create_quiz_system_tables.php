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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('live_class_id')->nullable()->constrained('live_classes')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('time_limit_minutes')->default(15);
            $table->unsignedInteger('passing_percentage')->default(70);
            $table->unsignedInteger('max_attempts')->default(3);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('title_translations')->nullable();
            $table->json('description_translations')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'is_published']);
            $table->index(['live_class_id']);
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->text('question_text');
            $table->string('type')->default('multiple_choice');
            $table->json('options');
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->unsignedInteger('points')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('question_text_translations')->nullable();
            $table->json('explanation_translations')->nullable();
            $table->timestamps();

            $table->index(['quiz_id', 'sort_order']);
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('live_class_id')->nullable()->constrained('live_classes')->nullOnDelete();
            $table->unsignedInteger('attempt_number')->default(1);
            $table->unsignedInteger('total_questions')->default(0);
            $table->unsignedInteger('correct_answers_count')->default(0);
            $table->decimal('score_percentage', 5, 2)->default(0.00);
            $table->boolean('is_passed')->default(false);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->json('answers')->nullable();
            $table->timestamps();

            $table->index(['quiz_id', 'student_id']);
            $table->index(['student_id', 'is_passed']);
            $table->index(['course_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
