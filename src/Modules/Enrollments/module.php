<?php

return [
    'name' => 'Enrollments',
    'enabled' => true,

    'provider' => Modules\Enrollments\Providers\EnrollmentsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Enrollments',
            'route' => 'admin.enrollments.index',
            'active' => 'admin.enrollments.*',
            'icon' => '🧾',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 130,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Enrollments',
            'route' => 'admin.enrollments.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'description' => 'Manage student enrollments.',
        ],
    ],
];