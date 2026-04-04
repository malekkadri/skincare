<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table): void {
            $table->string('preferred_language', 2)->default('fr')->after('booked_currency');
            $table->index(['appointment_date', 'preferred_language']);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table): void {
            $table->dropIndex(['appointment_date', 'preferred_language']);
            $table->dropColumn('preferred_language');
        });
    }
};
