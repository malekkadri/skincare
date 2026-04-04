<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminAndSettingsSeeder::class,
            ServiceCatalogSeeder::class,
            BusinessAvailabilitySeeder::class,
            WhatsAppTemplateSeeder::class,
        ]);
    }
}
