<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('preferred_language', 5)->default('fr');
            $table->string('age_range')->nullable();
            $table->string('skin_type')->nullable();
            $table->string('skin_sensitivity_level')->nullable();
            $table->text('main_concerns');
            $table->text('allergies')->nullable();
            $table->text('current_products')->nullable();
            $table->text('current_treatments_or_medications')->nullable();
            $table->string('pregnancy_or_breastfeeding_status')->nullable();
            $table->text('preferred_goals')->nullable();
            $table->text('additional_notes')->nullable();
            $table->boolean('consent')->default(false);
            $table->string('status')->default('new');
            $table->text('admin_notes')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
