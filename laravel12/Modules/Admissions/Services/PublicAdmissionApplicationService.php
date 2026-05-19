<?php

namespace Modules\Admissions\Services;

use App\Models\AdmissionApplication;
use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\AdmissionApplicationSubmittedNotification;
use Illuminate\Support\Facades\Notification;
use Modules\Admissions\Actions\GenerateAdmissionApplicationNumberAction;

/**
 * Handles public admission application submission workflows.
 *
 * This service preserves the existing public admission behavior:
 * - assign active school year when none is submitted
 * - generate application number
 * - set submitted status and timestamp
 * - normalize IP checkbox value
 * - notify admins and registrar
 *
 * Module: Admissions
 * Layer: Service
 */
class PublicAdmissionApplicationService
{
    /**
     * Submit a public admission application.
     *
     * @param array<string, mixed> $data
     * @param bool $isIp
     * @return AdmissionApplication
     */
    public function submit(array $data, bool $isIp): AdmissionApplication
    {
        $data['school_year_id'] = $data['school_year_id']
            ?? optional(SchoolYear::where('is_active', 1)->first())->id;

        $data['application_number'] = app(GenerateAdmissionApplicationNumberAction::class)
            ->execute();

        $data['application_status'] = 'submitted';
        $data['submitted_at'] = now();
        $data['is_ip'] = $isIp;

        $application = AdmissionApplication::create($data);

        $this->notifyAdmissionReviewers($application);

        return $application;
    }

    /**
     * Notify admins and registrar about a new admission application.
     *
     * @param AdmissionApplication $application
     * @return void
     */
    protected function notifyAdmissionReviewers(AdmissionApplication $application): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', [
                'super_admin',
                'admin',
                'registrar',
            ]);
        })->get();

        Notification::send(
            $admins,
            new AdmissionApplicationSubmittedNotification($application)
        );
    }
}