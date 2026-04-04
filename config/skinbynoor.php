<?php

return [
    'timezone' => env('SKINBYNOOR_TIMEZONE', 'Africa/Tunis'),
    'supported_locales' => ['fr', 'en'],
    'supported_currencies' => ['TND', 'EUR'],
    'defaults' => [
        'site_name' => env('SKINBYNOOR_SITE_NAME', 'Skin by Noor'),
        'super_admin_email' => env('DEFAULT_SUPER_ADMIN_EMAIL', 'admin@skinbynoor.test'),
    ],
    'ops' => [
        'backup_disk' => env('SKINBYNOOR_BACKUP_DISK', 'local'),
        'backup_path' => env('SKINBYNOOR_BACKUP_PATH', 'backups'),
    ],
];
