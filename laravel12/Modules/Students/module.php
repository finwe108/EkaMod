<?php

return [
    'name' => 'Students',
    'enabled' => true,

    'provider' => Modules\Students\Providers\StudentsServiceProvider::class,

    'navigation' => [
        [
            'label' => 'Students',
            'route' => 'admin.students.index',
            'active' => 'admin.students.*',
            'icon' => '🎓',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'order' => 110,
        ],
        [
            'label' => 'My Profile',
            'route' => 'student.profile.show',
            'active' => 'student.profile.*',
            'icon' => '👤',
            'roles' => ['student'],
            'group' => 'Student',
            'order' => 10,
        ],
        [
            'label' => 'Required Documents',
            'route' => 'student.documents.index',
            'active' => 'student.documents.*',
            'icon' => '📄',
            'roles' => ['student'],
            'group' => 'Student',
            'badge' => 'required_documents',
            'order' => 20,
        ],
        [
            'label' => 'Account Settings',
            'route' => 'student.account.edit',
            'active' => 'student.account.*',
            'icon' => '⚙️',
            'roles' => ['student'],
            'group' => 'Account',
            'order' => 30,
        ],
        [
            'label' => 'Change Password',
            'route' => 'student.password.edit',
            'active' => 'student.password.*',
            'icon' => '🔐',
            'roles' => ['student'],
            'group' => 'Account',
            'order' => 40,
        ],
    ],

    'dashboard' => [
        [
            'label' => 'Students',
            'route' => 'admin.students.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'description' => 'Manage student records and profiles.',
        ],
        [
            'label' => 'My Profile',
            'route' => 'student.profile.show',
            'roles' => ['student'],
            'group' => 'Student Portal',
            'description' => 'View your student profile.',
        ],
    ],
];