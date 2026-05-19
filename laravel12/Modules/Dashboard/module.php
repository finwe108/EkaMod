<?php

return [
    'name' => 'Dashboard',
    'enabled' => true,

    'provider' => Modules\Dashboard\Providers\DashboardServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard',
            'icon' => '⊞',
            'roles' => ['super_admin', 'admin', 'hr', 'registrar', 'teacher', 'student'],
            'group' => 'Overview',
            'order' => 1,
        ],
    ],

    'dashboard' => [],
];