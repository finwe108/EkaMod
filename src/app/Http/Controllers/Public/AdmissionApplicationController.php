<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdmissionApplicationRequest;
use App\Models\AdmissionApplication;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\AdmissionApplicationSubmittedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdmissionApplicationController extends Controller
{
    public function create()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        $gradeLevels = GradeLevel::orderBy('id')->get();

        return view('public.admission.create', compact(
            'activeSchoolYear',
            'gradeLevels'
        ));
    }

    public function store(StoreAdmissionApplicationRequest $request)
    {
        $data = $request->validated();

        $data['school_year_id'] = $data['school_year_id']
            ?? optional(SchoolYear::where('is_active', 1)->first())->id;

        $data['application_number'] = $this->generateApplicationNumber();

        $data['application_status'] = 'submitted';

        $data['submitted_at'] = now();

        $data['is_ip'] = $request->boolean('is_ip');

        $application = AdmissionApplication::create($data);

        /*
        * Notify admins and registrar.
        */
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

        return redirect()
            ->route('public.admission.success', $application)
            ->with('success', 'Your admission application has been submitted.');
    }
    
    public function success(AdmissionApplication $application)
    {
        return view('public.admission.success', compact('application'));
    }

    private function generateApplicationNumber(): string
    {
        do {
            $number = 'ADM-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (AdmissionApplication::where('application_number', $number)->exists());

        return $number;
    }
}