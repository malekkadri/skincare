<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blocked_time_ranges', function (Blueprint $table): void {
            $table->id();
            $table->date('blocked_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index('blocked_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_time_ranges');
    }
};
