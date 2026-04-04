<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_ai_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->longText('summary_text')->nullable();
            $table->json('recommended_services_json')->nullable();
            $table->json('risk_flags_json')->nullable();
            $table->json('raw_response_json')->nullable();
            $table->string('status')->default('skipped');
            $table->text('error_message')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['consultation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_ai_results');
    }
};
