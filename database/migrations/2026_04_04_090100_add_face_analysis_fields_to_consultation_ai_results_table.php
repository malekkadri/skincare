<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_ai_results', function (Blueprint $table) {
            $table->json('normalized_result_json')->nullable()->after('raw_response_json');
            $table->longText('user_summary')->nullable()->after('summary_text');
            $table->longText('admin_summary')->nullable()->after('user_summary');
            $table->decimal('confidence_score', 4, 3)->nullable()->after('status');
            $table->boolean('needs_human_review')->default(false)->after('confidence_score');
            $table->boolean('refer_to_dermatologist')->default(false)->after('needs_human_review');
            $table->timestamp('processed_at')->nullable()->after('generated_at');
        });
    }

    public function down(): void
    {
        Schema::table('consultation_ai_results', function (Blueprint $table) {
            $table->dropColumn([
                'normalized_result_json',
                'user_summary',
                'admin_summary',
                'confidence_score',
                'needs_human_review',
                'refer_to_dermatologist',
                'processed_at',
            ]);
        });
    }
};
