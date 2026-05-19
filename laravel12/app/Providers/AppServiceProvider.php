<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\Dashboard\Services\SidebarNavigationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        * Share school identity globally so layouts, public pages, reports,
        * dashboards, and module views can use the same logo/name source.
        */
        View::composer('*', function ($view) {
            $schoolSetting = cache()->remember('school_setting_current', 300, function () {
                return \App\Models\SchoolSetting::current();
            });

            $schoolLogo = ! empty($schoolSetting?->logo_path)
                ? asset($schoolSetting->logo_path)
                : asset('assets/images/EkaModLogo.png');

            $schoolName = $schoolSetting?->school_name
                ?? config('app.name', 'School');
            
            $shortName = $schoolSetting?->short_name
                ?? config('school.short_name', 'School');

            $tagline = $schoolSetting?->tagline
                ?? config('school.tagline', '');

            $schoolPhone = $schoolSetting?->phone
                ?? config('school.phone', '');
            
            $schoolEmail = $schoolSetting?->email
                ?? config('school.email', '');
            
            $schoolAddress = $schoolSetting?->address
                ?? config('school.address', '');

            $view->with([
                'globalSchoolSetting' => $schoolSetting,
                'schoolLogo' => $schoolLogo,
                'schoolName' => $schoolName,
                'shortName' => $shortName,
                'tagline' => $tagline,
                'schoolPhone' => $schoolPhone,
                'schoolEmail' => $schoolEmail,
                'schoolAddress' => $schoolAddress,
            ]);
        });

        /*
        * Build sidebar navigation from module manifests and role-aware rules.
        */
        View::composer('layouts.partials.sidebar', function ($view) {
            $user = auth()->user();

            $sidebarSections = $user
                ? app(SidebarNavigationService::class)->sectionsFor($user)
                : [];

            $view->with('sidebarSections', $sidebarSections);
        });
    }
}
