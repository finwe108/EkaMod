<?php

return [
    'name' => 'SchoolSettings',
    'enabled' => true,

    'provider' => Modules\SchoolSettings\Providers\SchoolSettingsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'School Settings',
            'route' => 'admin.school-settings.edit',
            'active' => 'admin.school-settings.*',
            'icon' => '🏫',
            'roles' => ['super_admin', 'admin'],
            'group' => 'Administration',
            'order' => 40,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'School Settings',
            'route' => 'admin.school-settings.edit',
            'roles' => ['super_admin', 'admin'],
            'group' => 'Administration',
            'description' => 'Manage school profile, identity, and system-wide school settings.',
        ],
    ],
];