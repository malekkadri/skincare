<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->boolean('whatsapp_automation_enabled')->default(false)->after('send_booking_reschedule_whatsapp');
            $table->boolean('send_24h_reminder')->default(true)->after('whatsapp_automation_enabled');
            $table->boolean('send_2h_reminder')->default(true)->after('send_24h_reminder');
            $table->boolean('send_post_appointment_followup')->default(true)->after('send_2h_reminder');
            $table->boolean('send_consultation_acknowledgement')->default(false)->after('send_post_appointment_followup');
            $table->unsignedSmallInteger('reminder_24h_lead_minutes')->default(1440)->after('send_consultation_acknowledgement');
            $table->unsignedSmallInteger('reminder_2h_lead_minutes')->default(120)->after('reminder_24h_lead_minutes');
            $table->unsignedSmallInteger('followup_lead_minutes')->default(720)->after('reminder_2h_lead_minutes');
            $table->unsignedTinyInteger('max_whatsapp_retry_attempts')->default(3)->after('followup_lead_minutes');
            $table->unsignedSmallInteger('whatsapp_retry_backoff_minutes')->default(10)->after('max_whatsapp_retry_attempts');
            $table->timestamp('automation_pause_until')->nullable()->after('whatsapp_retry_backoff_minutes');
        });

        Schema::table('whatsapp_message_logs', function (Blueprint $table): void {
            $table->foreignId('related_consultation_id')->nullable()->after('appointment_id')->constrained('consultations')->nullOnDelete();
            $table->timestamp('scheduled_for')->nullable()->after('status');
            $table->timestamp('sent_at')->nullable()->after('scheduled_for');
            $table->timestamp('failed_at')->nullable()->after('sent_at');
            $table->unsignedTinyInteger('attempts')->default(0)->after('failed_at');
            $table->timestamp('next_retry_at')->nullable()->after('attempts');
            $table->string('error_code', 100)->nullable()->after('next_retry_at');
            $table->string('automation_source', 100)->nullable()->after('error_code');
            $table->string('idempotency_key', 191)->nullable()->after('automation_source');

            $table->unique('idempotency_key');
            $table->index(['status', 'next_retry_at']);
            $table->index(['automation_source', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_message_logs', function (Blueprint $table): void {
            $table->dropUnique(['idempotency_key']);
            $table->dropIndex(['status', 'next_retry_at']);
            $table->dropIndex(['automation_source', 'status']);
            $table->dropConstrainedForeignId('related_consultation_id');
            $table->dropColumn([
                'scheduled_for',
                'sent_at',
                'failed_at',
                'attempts',
                'next_retry_at',
                'error_code',
                'automation_source',
                'idempotency_key',
            ]);
        });

        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'whatsapp_automation_enabled',
                'send_24h_reminder',
                'send_2h_reminder',
                'send_post_appointment_followup',
                'send_consultation_acknowledgement',
                'reminder_24h_lead_minutes',
                'reminder_2h_lead_minutes',
                'followup_lead_minutes',
                'max_whatsapp_retry_attempts',
                'whatsapp_retry_backoff_minutes',
                'automation_pause_until',
            ]);
        });
    }
};
