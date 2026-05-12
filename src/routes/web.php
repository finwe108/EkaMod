<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Admin\AdmissionApplicationReviewController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\DocumentRequirementRuleController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SectionEnrollmentController;
use App\Http\Controllers\Admin\SchoolSettingController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\StudentDocumentVerificationController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentCredentialController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherLoadController;
use App\Http\Controllers\Admin\TeacherScheduleController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

use App\Http\Controllers\Public\AdmissionApplicationController;

use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentDocumentController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentPasswordController;
use App\Http\Controllers\Student\StudentAccountController;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/clear-cache-now', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return 'Cache cleared';
});
/*
|--------------------------------------------------------------------------
| Home & Public Route
|--------------------------------------------------------------------------
*/
// Route::get('/', function () {
//     return auth()->check()
//         ? redirect()->route('dashboard')
//         : redirect()->route('login');
// })->name('home');

Route::get('/', function () {
    return view('public_site.home');
})->name('public.home');

Route::get('/about', function () {
    return view('public_site.about');
})->name('public.about');

Route::get('/privacy', function () {
    return view('public_site.privacy');
})->name('public.privacy');

Route::get('/admission', function () {
    return redirect()->route('public.admission.create');
});

Route::get('/tesda', function () {
    if (!config('school.tesda_enabled')) {
        abort(404);
    }

    return view('public_site.tesda');
})->name('public.tesda');

Route::get('/admission/apply', [AdmissionApplicationController::class, 'create'])
    ->name('public.admission.create');

Route::post('/admission/apply', [AdmissionApplicationController::class, 'store'])
    ->name('public.admission.store');

Route::get('/admission/success/{application}', [AdmissionApplicationController::class, 'success'])
    ->name('public.admission.success');
    
/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 🔥 Unified Dashboard (MAIN ENTRY POINT)
    Route::get('/dashboard', function () {
        $user = auth()->user()->loadMissing('roles');

        if ($user->roles->contains('name', 'student')) {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', LogoutController::class)->name('logout');


    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');
    /*
    |--------------------------------------------------------------------------
    | Admin / HR / Super Admin Modules
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin,admin,hr'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
            
            Route::resource('students', StudentController::class);
            Route::resource('teachers', TeacherController::class);
            Route::resource('sections', SectionController::class);
            Route::resource('finance', FinanceController::class)->only(['index', 'create', 'store']);
            Route::resource('announcements', AnnouncementController::class);
            Route::resource('school_years', SchoolYearController::class);
            Route::resource('employees', EmployeeController::class);
            Route::resource('teacher_loads', TeacherLoadController::class);
            Route::resource('subjects', SubjectController::class);
            Route::resource('enrollments', EnrollmentController::class);
            Route::patch('/enrollments/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])
                ->name('enrollments.status.update'); 
            Route::get('/teachers/{teacher}/schedule', [TeacherScheduleController::class, 'show'])
                ->name('teachers.schedule');
            Route::get('/admin/students/sections/{schoolYearId}', [StudentController::class, 'getSectionsBySchoolYear'])
                ->name('students.sections');

            Route::get('ajax/sections-by-school-year', [EnrollmentController::class, 'sectionsBySchoolYear'])
                ->name('ajax.sections-by-school-year');

            Route::get('school-settings', [SchoolSettingController::class, 'edit'])
                ->name('school-settings.edit');

            Route::put('school-settings', [SchoolSettingController::class, 'update'])
                ->name('school-settings.update');

            Route::get('/admission-applications', [AdmissionApplicationReviewController::class, 'index'])
                ->name('admission_applications.index');

            Route::get('/admission-applications/{admissionApplication}', [AdmissionApplicationReviewController::class, 'show'])
                ->name('admission_applications.show');

            Route::post('/admission-applications/{admissionApplication}/review', [AdmissionApplicationReviewController::class, 'markUnderReview'])
                ->name('admission_applications.review');

            Route::post('/admission-applications/{admissionApplication}/accept', [AdmissionApplicationReviewController::class, 'accept'])
                ->name('admission_applications.accept');

            Route::post('/admission-applications/{admissionApplication}/reject', [AdmissionApplicationReviewController::class, 'reject'])
                ->name('admission_applications.reject');    

            Route::get('sections/{section}/enroll/search', [SectionEnrollmentController::class, 'search'])
                ->name('sections.enroll.search');

            Route::post('sections/{section}/enroll/existing', [SectionEnrollmentController::class, 'enrollExisting'])
                ->name('sections.enroll.existing');

            Route::get('students/{student}/credentials', [StudentCredentialController::class, 'edit'])
                ->name('students.credentials.edit');

            Route::put('students/{student}/credentials', [StudentCredentialController::class, 'update'])
                ->name('students.credentials.update');

            Route::post('students/{student}/credentials/create', [StudentCredentialController::class, 'store'])
                ->name('students.credentials.store');

            Route::resource('document-types', DocumentTypeController::class);

            Route::resource('document-requirement-rules', DocumentRequirementRuleController::class)
                ->except(['show']);

            Route::get('student-documents', [StudentDocumentVerificationController::class, 'index'])
                ->name('student-documents.index');

            Route::put('student-documents/{studentDocument}/verify', [StudentDocumentVerificationController::class, 'verify'])
                ->name('student-documents.verify');

            Route::put('student-documents/{studentDocument}/reject', [StudentDocumentVerificationController::class, 'reject'])
                ->name('student-documents.reject');

            /* 
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */

            Route::get('reports/sf1', [EnrollmentController::class, 'sf1Filter'])
                ->name('reports.sf1.filter');

            Route::get('reports/sf1/print', [EnrollmentController::class, 'sf1'])
                ->name('reports.sf1.print');

            Route::post('reports/sf1/queue', [EnrollmentController::class, 'queueSf1'])
                ->name('reports.sf1.queue');

            Route::get('reports/sf1/generated', [EnrollmentController::class, 'sf1Generated'])
                ->name('reports.sf1.generated');

            Route::post('reports/sf1/generated/{report}/generate', [EnrollmentController::class, 'generateSf1Report'])
                ->name('reports.sf1.generated.generate');

            Route::get('reports/sf1/generated/{report}/download', [EnrollmentController::class, 'downloadGeneratedSf1'])
                ->name('reports.sf1.generated.download');
        });

    /* 
    |--------------------------------------------------------------------------
    | Registrar Module (optional pages, not dashboard)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:registrar,super_admin,admin'])
        ->prefix('registrar')
        ->name('registrar.')
        ->group(function () {



        });

    /*
    |--------------------------------------------------------------------------
    | Teacher Module (optional pages, not dashboard)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:teacher,super_admin,admin,hr'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {

            Route::get('/classes', [\App\Http\Controllers\Teacher\DashboardController::class, 'classes'])->name('classes');
            Route::get('/grades', [\App\Http\Controllers\Teacher\DashboardController::class, 'grades'])->name('grades');
            Route::get('/attendance', [\App\Http\Controllers\Teacher\DashboardController::class, 'attendance'])->name('attendance');
            Route::get('/loads/{teacherLoad}/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'index'])
                ->name('loads.grades.index');
            Route::post('/loads/{teacherLoad}/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'store'])
                ->name('loads.grades.store');   
            Route::get('/teacher/schedule', [TeacherScheduleController::class, 'mySchedule'])
                ->name('schedule');
         });
});

/*
|--------------------------------------------------------------------------
| Student Module
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [StudentProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');

    Route::get('/change-password', [StudentPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/change-password', [StudentPasswordController::class, 'update'])->name('password.update');

    Route::get('/documents', [StudentDocumentController::class, 'index'])
        ->name('documents.index');

    Route::post('/documents/{documentRequirementRule}/upload', [StudentDocumentController::class, 'upload'])
        ->name('documents.upload');

    Route::get('/account', [StudentAccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [StudentAccountController::class, 'update'])->name('account.update');
    
});