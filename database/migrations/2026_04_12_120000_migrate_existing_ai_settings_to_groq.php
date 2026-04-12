<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')
            ->where(function ($query): void {
                $query->where('ai_provider', 'grok')
                    ->orWhereNull('ai_provider');
            })
            ->update([
                'ai_provider' => 'groq',
            ]);

        DB::table('settings')
            ->where(function ($query): void {
                $query->where('ai_model', 'grok-2-latest')
                    ->orWhereNull('ai_model')
                    ->orWhere('ai_model', '');
            })
            ->update([
                'ai_model' => 'llama-3.3-70b-versatile',
            ]);

        DB::table('settings')
            ->where(function ($query): void {
                $query->where('ai_base_url', 'https://api.x.ai/v1/chat/completions')
                    ->orWhereNull('ai_base_url')
                    ->orWhere('ai_base_url', '');
            })
            ->update([
                'ai_base_url' => 'https://api.groq.com/openai/v1/chat/completions',
            ]);
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('ai_provider', 'groq')
            ->update([
                'ai_provider' => 'grok',
            ]);

        DB::table('settings')
            ->where('ai_model', 'llama-3.3-70b-versatile')
            ->update([
                'ai_model' => 'grok-2-latest',
            ]);

        DB::table('settings')
            ->where('ai_base_url', 'https://api.groq.com/openai/v1/chat/completions')
            ->update([
                'ai_base_url' => 'https://api.x.ai/v1/chat/completions',
            ]);
    }
};
