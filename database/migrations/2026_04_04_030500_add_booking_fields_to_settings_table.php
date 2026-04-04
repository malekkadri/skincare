<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->unsignedSmallInteger('slot_interval_minutes')->default(30)->after('timezone');
            $table->unsignedSmallInteger('minimum_notice_hours')->default(2)->after('slot_interval_minutes');
            $table->unsignedSmallInteger('maximum_booking_days_ahead')->default(30)->after('minimum_notice_hours');
            $table->unsignedSmallInteger('max_appointments_per_day')->nullable()->after('maximum_booking_days_ahead');
            $table->boolean('booking_enabled')->default(true)->after('max_appointments_per_day');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'slot_interval_minutes',
                'minimum_notice_hours',
                'maximum_booking_days_ahead',
                'max_appointments_per_day',
                'booking_enabled',
            ]);
        });
    }
};
