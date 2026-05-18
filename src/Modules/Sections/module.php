<?php

return [
    'name' => 'Sections',
    'enabled' => true,

    'provider' => Modules\Sections\Providers\SectionsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Sections',
            'route' => 'admin.sections.index',
            'active' => 'admin.sections.*',
            'icon' => '🏫',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 150,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Sections',
            'route' => 'admin.sections.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Academics',
            'description' => 'Manage class sections.',
        ],
    ],
];