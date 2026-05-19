<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AsthetikaContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceCatalogSeeder::class,
            BusinessAvailabilitySeeder::class,
            MarketingContentSeeder::class,
        ]);
    }
}
