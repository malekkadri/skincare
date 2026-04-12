<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->text('allergies')->nullable()->after('notes');
            $table->text('skin_notes')->nullable()->after('allergies');
            $table->text('medical_notes')->nullable()->after('skin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn(['allergies', 'skin_notes', 'medical_notes']);
        });
    }
};
