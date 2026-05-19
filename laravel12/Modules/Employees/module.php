<?php

return [
    'name' => 'Employees',
    'enabled' => true,

    'provider' => Modules\Employees\Providers\EmployeesServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Employees',
            'route' => 'admin.employees.index',
            'active' => 'admin.employees.*',
            'icon' => '👥',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'Administration',
            'order' => 3,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Employees',
            'route' => 'admin.employees.index',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'HR',
            'description' => 'Manage employee records.',
        ],
    ],
];