<?php

return [
    'name' => 'Academics',
    'enabled' => true,

    'provider' => Modules\Academics\Providers\AcademicsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Subjects',
            'route' => 'admin.subjects.index',
            'active' => 'admin.subjects.*',
            'icon' => '📚',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 120,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Subjects',
            'route' => 'admin.subjects.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Academics',
            'description' => 'Manage academic subjects.',
        ],
    ],
];