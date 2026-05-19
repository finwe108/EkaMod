<?php

return [
    'name' => 'StudentDocuments',
    'enabled' => true,

    'provider' => Modules\StudentDocuments\Providers\StudentDocumentsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Verify Documents',
            'route' => 'admin.student-documents.index',
            'active' => 'admin.student-documents.*',
            'icon' => '✅',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 150,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Student Documents',
            'route' => 'admin.student-documents.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'description' => 'Review, verify, or reject submitted student documents.',
        ],
    ],
];