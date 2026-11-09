<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ai_providers')) {
            Schema::create('ai_providers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type', 20)->default('openai'); // openai | anthropic
                $table->string('base_url');
                $table->text('api_key');                        // encrypted via cast
                $table->string('model')->nullable();
                $table->json('models')->nullable();
                $table->json('selected_models')->nullable();
                $table->timestamp('models_fetched_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ai_usage')) {
            Schema::create('ai_usage', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('kind', 20); // translator | generator | image | playground
                $table->string('model', 190);
                $table->unsignedBigInteger('vendor_id')->nullable();
                $table->unsignedInteger('requests')->default(0);
                $table->unsignedBigInteger('input_tokens')->default(0);
                $table->unsignedBigInteger('output_tokens')->default(0);
                $table->unsignedInteger('images')->default(0);
                $table->timestamps();

                $table->unique(['date', 'kind', 'model']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage');
        Schema::dropIfExists('ai_providers');
    }
};
