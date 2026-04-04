<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminAndSettingsSeeder::class,
            RolePermissionSeeder::class,
            ServiceCatalogSeeder::class,
            BusinessAvailabilitySeeder::class,
            WhatsAppTemplateSeeder::class,
            MarketingContentSeeder::class,
            ConsultationSeeder::class,
        ]);
    }
}
