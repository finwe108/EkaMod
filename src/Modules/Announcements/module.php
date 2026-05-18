<?php

return [
    'name' => 'Announcements',
    'enabled' => true,

    'provider' => Modules\Announcements\Providers\AnnouncementsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Announcements',
            'route' => 'admin.announcements.index',
            'active' => 'admin.announcements.*',
            'icon' => '📢',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'Administration',
            'order' => 20,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Announcements',
            'route' => 'admin.announcements.index',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'Administration',
            'description' => 'Manage school announcements.',
        ],
    ],
];