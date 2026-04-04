<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CoreSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminAndSettingsSeeder::class,
            RolePermissionSeeder::class,
            WhatsAppTemplateSeeder::class,
        ]);
    }
}
