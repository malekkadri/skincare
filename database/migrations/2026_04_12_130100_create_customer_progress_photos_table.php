<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_progress_photos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->date('captured_on')->nullable();
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('original_name')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'captured_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_progress_photos');
    }
};
