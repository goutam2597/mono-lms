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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('certificate_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_name');
            $table->string('course_title');
            $table->string('instructor_name')->nullable();
            $table->timestamp('issue_date');
            $table->string('grade', 50)->default('Grade A+ (95%)');
            $table->decimal('score', 5, 2)->default(95.00);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
