<?php

return [
    'roles' => [
        'super_admin' => [
            'label' => 'Super Admin',
            'permissions' => ['*'],
        ],
        'admin' => [
            'label' => 'Admin',
            'permissions' => [
                'manage_settings',
                'manage_services',
                'manage_appointments',
                'manage_availability',
                'manage_whatsapp',
                'manage_consultations',
                'manage_ai',
                'manage_cms',
                'view_reports',
                'export_reports',
                'manage_admin_users',
                'view_system_health',
                'manage_backups',
            ],
        ],
        'editor' => [
            'label' => 'Editor',
            'permissions' => [
                'manage_cms',
                'manage_services',
                'manage_ai',
            ],
        ],
        'receptionist' => [
            'label' => 'Receptionist',
            'permissions' => [
                'manage_appointments',
                'manage_availability',
                'manage_whatsapp',
                'manage_consultations',
                'view_reports',
            ],
        ],
    ],

    'permissions' => [
        'manage_settings',
        'manage_services',
        'manage_appointments',
        'manage_availability',
        'manage_whatsapp',
        'manage_consultations',
        'manage_ai',
        'manage_cms',
        'view_reports',
        'export_reports',
        'manage_admin_users',
        'view_system_health',
        'manage_backups',
    ],
];
