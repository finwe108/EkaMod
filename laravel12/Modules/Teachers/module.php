<?php

return [
    'name' => 'Teachers',
    'enabled' => true,

    'provider' => Modules\Teachers\Providers\TeachersServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Faculty',
            'route' => 'admin.teachers.index',
            'active' => 'admin.teachers.*',
            'icon' => '👨‍🏫',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'Teachers',
            'order' => 300,
        ],
        [
            'label' => 'Teacher Loads',
            'route' => 'admin.teacher_loads.index',
            'active' => 'admin.teacher_loads.*',
            'icon' => '📘',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'Teachers',
            'order' => 301,
        ],
        [
            'label' => 'Classes',
            'route' => 'teacher.classes',
            'active' => 'teacher.classes',
            'icon' => '📘',
            'roles' => ['teacher'],
            'group' => 'Teaching',
            'order' => 310,
        ],
        [
            'label' => 'Grades',
            'route' => 'teacher.grades',
            'active' => 'teacher.grades',
            'icon' => '📝',
            'roles' => ['teacher'],
            'group' => 'Teaching',
            'order' => 320,
        ],
        [
            'label' => 'Attendance',
            'route' => 'teacher.attendance',
            'active' => 'teacher.attendance',
            'icon' => '✅',
            'roles' => ['teacher'],
            'group' => 'Teaching',
            'order' => 330,
        ],
        [
            'label' => 'Schedule',
            'route' => 'teacher.schedule',
            'active' => 'teacher.schedule',
            'icon' => '📅',
            'roles' => ['teacher'],
            'group' => 'Teaching',
            'order' => 340,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Faculty',
            'route' => 'admin.teachers.index',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'Teachers',
            'description' => 'Manage teacher profiles.',
        ],
        [
            'label' => 'Teacher Loads',
            'route' => 'admin.teacher_loads.index',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'Teachers',
            'description' => 'Manage teaching assignments.',
        ],
        [
            'label' => 'My Classes',
            'route' => 'teacher.classes',
            'roles' => ['teacher'],
            'group' => 'Teacher Portal',
            'description' => 'View assigned classes.',
        ],
    ],
];