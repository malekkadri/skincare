<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // CoreSeeder = admin/settings/technical defaults.
        // AsthetikaContentSeeder = required public website content.
        $this->call([
            CoreSeeder::class,
            AsthetikaContentSeeder::class,
        ]);

        // DemoContentSeeder = optional sample/testing data.
        if (app()->environment('production') && ! (bool) env('SEED_DEMO_DATA_IN_PRODUCTION', false)) {
            $this->command?->warn('Skipping DemoContentSeeder in production. Set SEED_DEMO_DATA_IN_PRODUCTION=true to allow sample data.');

            return;
        }

        $this->call([
            DemoContentSeeder::class,
        ]);
    }
}
