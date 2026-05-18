<?php

return [
    App\Providers\AppServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Admin / Shared Modules
    |--------------------------------------------------------------------------
    */    
    Modules\Academics\Providers\AcademicsServiceProvider::class,
    Modules\Admissions\Providers\AdmissionsServiceProvider::class,
    Modules\Announcements\Providers\AnnouncementsServiceProvider::class,
    Modules\Auth\Providers\AuthServiceProvider::class,
    Modules\Dashboard\Providers\DashboardServiceProvider::class,
    Modules\DocumentRequirementRules\Providers\DocumentRequirementRulesServiceProvider::class,
    Modules\DocumentTypes\Providers\DocumentTypesServiceProvider::class,
    Modules\Employees\Providers\EmployeesServiceProvider::class,
    Modules\Enrollments\Providers\EnrollmentsServiceProvider::class,
    Modules\Notifications\Providers\NotificationsServiceProvider::class,
    Modules\Reports\Providers\ReportsServiceProvider::class,
    Modules\Sections\Providers\SectionsServiceProvider::class,
    Modules\SchoolSettings\Providers\SchoolSettingsServiceProvider::class,
    Modules\SchoolYears\Providers\SchoolYearsServiceProvider::class,
    Modules\StudentDocuments\Providers\StudentDocumentsServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Student Portal Transitional Modules
    |--------------------------------------------------------------------------
    */    
    Modules\Students\Providers\StudentsServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Teacher Portal Module
    |--------------------------------------------------------------------------
    */
    Modules\Teachers\Providers\TeachersServiceProvider::class,
    
    /*
    |--------------------------------------------------------------------------
    | Public Site Module
    |--------------------------------------------------------------------------
    */    
    Modules\PublicSite\Providers\PublicSiteServiceProvider::class,
];
