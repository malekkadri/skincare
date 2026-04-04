<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->boolean('whatsapp_enabled')->default(false)->after('booking_enabled');
            $table->string('whatsapp_provider')->nullable()->after('whatsapp_enabled');
            $table->string('whatsapp_business_number')->nullable()->after('whatsapp_provider');
            $table->text('whatsapp_api_key')->nullable()->after('whatsapp_business_number');
            $table->string('whatsapp_api_base_url')->nullable()->after('whatsapp_api_key');
            $table->string('whatsapp_default_country_code', 6)->default('+216')->after('whatsapp_api_base_url');
            $table->boolean('send_booking_confirmation_whatsapp')->default(true)->after('whatsapp_default_country_code');
            $table->boolean('send_booking_cancellation_whatsapp')->default(true)->after('send_booking_confirmation_whatsapp');
            $table->boolean('send_booking_reschedule_whatsapp')->default(true)->after('send_booking_cancellation_whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'whatsapp_enabled',
                'whatsapp_provider',
                'whatsapp_business_number',
                'whatsapp_api_key',
                'whatsapp_api_base_url',
                'whatsapp_default_country_code',
                'send_booking_confirmation_whatsapp',
                'send_booking_cancellation_whatsapp',
                'send_booking_reschedule_whatsapp',
            ]);
        });
    }
};
