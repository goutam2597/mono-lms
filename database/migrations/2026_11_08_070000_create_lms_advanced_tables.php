<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('payment_method', 50)->default('stripe');
            $table->string('payment_status', 30)->default('paid'); // paid, pending, refunded, failed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('discount_type', 20)->default('percent'); // percent, fixed
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructors')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50)->default('paypal');
            $table->string('payout_account');
            $table->string('status', 30)->default('pending'); // pending, processed, rejected
            $table->dateTime('processed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('template_type', 50)->default('classic');
            $table->string('signature_title')->default('Program Director');
            $table->string('signer_name')->default('Dr. Alexander Wright');
            $table->string('bg_color', 20)->default('#ffffff');
            $table->string('border_color', 20)->default('#344c3d');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('user_name');
            $table->string('user_email');
            $table->string('subject');
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('status', 30)->default('open'); // open, in_progress, resolved, closed
            $table->text('message');
            $table->text('reply')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('source', 50)->default('website');
            $table->string('status', 30)->default('new'); // new, contacted, interested, enrolled, closed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->dateTime('start_time');
            $table->integer('duration_minutes')->default(60);
            $table->string('meeting_link')->nullable();
            $table->integer('capacity')->default(50);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('certificate_templates');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('orders');
    }
};
