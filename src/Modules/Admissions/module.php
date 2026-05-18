<?php

return [
    'name' => 'Admissions',
    'enabled' => true,

    'provider' => Modules\Admissions\Providers\AdmissionsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Admission Applications',
            'route' => 'admin.admission_applications.index',
            'active' => 'admin.admission_applications.*',
            'icon' => '📋',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 10,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Admissions',
            'route' => 'admin.admission_applications.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Admissions',
            'description' => 'Review admission applications.',
        ],
    ],
];