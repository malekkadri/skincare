<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([CoreSeeder::class]);

        if (app()->environment('production') && ! (bool) env('SEED_DEMO_DATA_IN_PRODUCTION', false)) {
            $this->command?->warn('Skipping DemoContentSeeder in production. Set SEED_DEMO_DATA_IN_PRODUCTION=true to allow it.');

            return;
        }

        $this->call([DemoContentSeeder::class]);
    }
}
