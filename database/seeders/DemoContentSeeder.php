<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $shouldSeedOperationalDemo = app()->environment(['local', 'testing', 'development'])
            || (bool) env('ASTHETIKA_SEED_OPERATIONAL_DEMO', false);

        if (! $shouldSeedOperationalDemo) {
            return;
        }

        $this->call([
            AsthetikaOperationalDemoSeeder::class,
        ]);
    }
}
