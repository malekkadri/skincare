<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->where('is_admin', true)->whereNull('role')->update(['role' => 'admin']);

        $defaultEmail = env('DEFAULT_SUPER_ADMIN_EMAIL', 'admin@asthetika.test');
        User::query()->where('email', $defaultEmail)->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);
    }
}
