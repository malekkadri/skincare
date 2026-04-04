<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_message_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('template_key');
            $table->string('language', 2);
            $table->string('recipient_phone', 50)->nullable();
            $table->text('message_body')->nullable();
            $table->string('status')->default('pending');
            $table->text('provider_response')->nullable();
            $table->timestamps();

            $table->index(['template_key', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message_logs');
    }
};
