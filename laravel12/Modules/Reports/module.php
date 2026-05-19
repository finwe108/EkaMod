<?php

return [
    'name' => 'Reports',
    'enabled' => true,

    'provider' => Modules\Reports\Providers\ReportsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'SF1 Report',
            'route' => 'admin.reports.sf1.filter',
            'active' => 'admin.reports.sf1.*',
            'icon' => '🧾',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Reports',
            'order' => 400,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'SF1 Report',
            'route' => 'admin.reports.sf1.filter',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Reports',
            'description' => 'Generate and review SF1 reports.',
        ],
    ],
];