<?php

return [
    'name' => 'Notifications',
    'enabled' => true,

    'provider' => Modules\Notifications\Providers\NotificationsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Notifications',
            'route' => 'notifications.index',
            'active' => 'notifications.*',
            'icon' => '🔔',
            'roles' => ['super_admin', 'admin', 'hr', 'registrar', 'teacher', 'student'],
            'group' => 'Overview',
            'order' => 9,
        ],
    ],

    'dashboard' => [],
];