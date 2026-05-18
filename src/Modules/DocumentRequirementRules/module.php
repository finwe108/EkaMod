<?php

return [
    'name' => 'DocumentRequirementRules',
    'enabled' => true,

    'provider' => Modules\DocumentRequirementRules\Providers\DocumentRequirementRulesServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Document Rules',
            'route' => 'admin.document-requirement-rules.index',
            'active' => 'admin.document-requirement-rules.*',
            'icon' => '📋',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 21,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Document Rules',
            'route' => 'admin.document-requirement-rules.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'description' => 'Configure required documents by grade level and student type.',
        ],
    ],
];