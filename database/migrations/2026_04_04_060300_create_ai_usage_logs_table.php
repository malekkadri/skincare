<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->string('feature_key', 100);
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->text('input_context_summary')->nullable();
            $table->text('output_summary')->nullable();
            $table->string('status', 50)->nullable()->default('skipped');
            $table->text('error_message')->nullable();
            $table->foreignId('related_consultation_id')->nullable()->constrained('consultations')->nullOnDelete();
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['feature_key', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
    }
};
