<?php

return [
    'name' => 'DocumentTypes',
    'enabled' => true,

    'provider' => Modules\DocumentTypes\Providers\DocumentTypesServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Document Types',
            'route' => 'admin.document-types.index',
            'active' => 'admin.document-types.*',
            'icon' => '📄',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 30,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Document Types',
            'route' => 'admin.document-types.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'description' => 'Manage document categories used for student requirements.',
        ],
    ],
];