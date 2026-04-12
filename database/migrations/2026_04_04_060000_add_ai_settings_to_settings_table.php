<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('ai_enabled')->default(false)->after('send_booking_reschedule_whatsapp');
            $table->string('ai_provider')->default('groq')->after('ai_enabled');
            $table->text('ai_api_key')->nullable()->after('ai_provider');
            $table->string('ai_model')->nullable()->after('ai_api_key');
            $table->string('ai_base_url')->nullable()->after('ai_model');
            $table->decimal('ai_temperature', 3, 2)->nullable()->after('ai_base_url');
            $table->unsignedInteger('ai_timeout_seconds')->nullable()->after('ai_temperature');
            $table->boolean('ai_enable_consultation_summary')->default(true)->after('ai_timeout_seconds');
            $table->boolean('ai_enable_service_recommendation')->default(true)->after('ai_enable_consultation_summary');
            $table->boolean('ai_enable_admin_content_helper')->default(true)->after('ai_enable_service_recommendation');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'ai_enabled',
                'ai_provider',
                'ai_api_key',
                'ai_model',
                'ai_base_url',
                'ai_temperature',
                'ai_timeout_seconds',
                'ai_enable_consultation_summary',
                'ai_enable_service_recommendation',
                'ai_enable_admin_content_helper',
            ]);
        });
    }
};
