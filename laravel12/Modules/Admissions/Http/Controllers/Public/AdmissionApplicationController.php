<?php

namespace Modules\Admissions\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use Modules\Admissions\Requests\Public\StoreAdmissionApplicationRequest;
use Modules\Admissions\Services\PublicAdmissionApplicationService;

class AdmissionApplicationController extends Controller
{
    public function create()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        $gradeLevels = GradeLevel::orderBy('id')->get();

        return view('admissions::public.create', compact(
            'activeSchoolYear',
            'gradeLevels'
        ));
    }

    public function store(
        StoreAdmissionApplicationRequest $request,
        PublicAdmissionApplicationService $admissionApplicationService
    ) {
        $application = $admissionApplicationService->submit(
            $request->validated(),
            $request->boolean('is_ip')
        );

        return redirect()
            ->route('public.admission.success', $application)
            ->with('success', 'Your admission application has been submitted.');
    }

    public function success(AdmissionApplication $application)
    {
        return view('admissions::public.success', compact('application'));
    }
}