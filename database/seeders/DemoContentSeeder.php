<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ConsultationSeeder::class,
        ]);
    }
}
