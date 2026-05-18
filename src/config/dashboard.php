<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard Module Links
    |--------------------------------------------------------------------------
    |
    | These links are filtered by user role and route availability.
    | If a module route does not exist, the dashboard will skip it.
    |
    */

    'modules' => [
        [
            'label' => 'Students',
            'route' => 'admin.students.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'description' => 'Manage student records and profiles.',
        ],
        [
            'label' => 'Admissions',
            'route' => 'admin.admission_applications.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Admissions',
            'description' => 'Review admission applications.',
        ],
        [
            'label' => 'Enrollments',
            'route' => 'admin.enrollments.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Registrar',
            'description' => 'Manage student enrollments.',
        ],
        [
            'label' => 'Sections',
            'route' => 'admin.sections.index',
            'roles' => ['super_admin', 'admin', 'registrar'],
            'group' => 'Academics',
            'description' => 'Manage class sections.',
        ],
        [
            'label' => 'School Years',
            'route' => 'admin.school_years.index',
            'roles' => ['super_admin', 'admin'],
            'group' => 'Academics',
            'description' => 'Manage school years.',
        ],
        [
            'label' => 'Subjects',
            'route' => 'admin.subjects.index',
            'roles' => ['super_admin', 'admin'],
            'group' => 'Academics',
            'description' => 'Manage subjects.',
        ],
        [
            'label' => 'Employees',
            'route' => 'admin.employees.index',
            'roles' => ['super_admin', 'admin', 'hr'],
            'group' => 'HR',
            'description' => 'Manage employee records.',
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
        [
            'label' => 'My Schedule',
            'route' => 'teacher.schedule',
            'roles' => ['teacher'],
            'group' => 'Teacher Portal',
            'description' => 'View teaching schedule.',
        ],
        [
            'label' => 'My Profile',
            'route' => 'student.profile.show',
            'roles' => ['student'],
            'group' => 'Student Portal',
            'description' => 'View student profile.',
        ],
        [
            'label' => 'My Documents',
            'route' => 'student.documents.index',
            'roles' => ['student'],
            'group' => 'Student Portal',
            'description' => 'Upload required documents.',
        ],
    ],

];