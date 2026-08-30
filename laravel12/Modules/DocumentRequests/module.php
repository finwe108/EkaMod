<?php

return [
    'name' => 'DocumentRequests',

    'enabled' => true,

    'provider' => Modules\DocumentRequests\Providers\DocumentRequestsServiceProvider::class,

    'navigation' => [
        [
            'group' => 'Registrar',
            'label' => 'Document Requests',
            'route' => 'admin.document-requests.index',
            'icon' => 'document-text',
            'order' => 20,
            'roles' => [
                'super_admin',
                'admin',
                'registrar',
            ],
        ],
    ],

    'dashboard' => [],
];