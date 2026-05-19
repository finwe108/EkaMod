<?php

return [
    'name' => 'SchoolYears',
    'enabled' => true,

    'provider' => Modules\SchoolYears\Providers\SchoolYearsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'School Years',
            'route' => 'admin.school_years.index',
            'active' => 'admin.school_years.*',
            'icon' => '📅',
            'roles' => ['super_admin', 'admin'],
            'group' => 'Administration',
            'order' => 35,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'School Years',
            'route' => 'admin.school_years.index',
            'roles' => ['super_admin', 'admin'],
            'group' => 'Academics',
            'description' => 'Manage school years.',
        ],
    ],
];