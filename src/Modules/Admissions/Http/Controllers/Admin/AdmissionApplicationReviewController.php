<?php

namespace Modules\Admissions\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\GradeLevel;
use App\Models\Section;
use Modules\Admissions\Requests\Admin\RejectAdmissionApplicationRequest;
use Modules\Admissions\Services\AdminAdmissionReviewService;
use Illuminate\Http\Request;

class AdmissionApplicationReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $applications = AdmissionApplication::with(['gradeLevel', 'schoolYear'])
            ->when($status, fn ($query) => $query->where('application_status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admissions::admin.index', compact('applications', 'status'));
    }

    public function show(AdmissionApplication $admissionApplication)
    {
        $admissionApplication->load([
            'gradeLevel',
            'schoolYear',
            'section',
            'acceptedStudent',
            'createdUser',
            'reviewer',
        ]);

        $gradeLevels = GradeLevel::orderBy('sort_order')->get();

        $sections = Section::orderBy('grade_level_id')
            ->orderBy('name')
            ->get();

        return view('admissions::admin.show', [
            'application' => $admissionApplication,
            'gradeLevels' => $gradeLevels,
            'sections' => $sections,
        ]);
    }

    public function markUnderReview(
        AdmissionApplication $admissionApplication,
        AdminAdmissionReviewService $admissionReviewService
    ) {
        $admissionReviewService->markUnderReview(
            $admissionApplication,
            auth()->id()
        );

        return back()->with('success', 'Application marked as under review.');
    }

    public function accept(
        AdmissionApplication $admissionApplication,
        AdminAdmissionReviewService $admissionReviewService
    ) {
        $result = $admissionReviewService->accept(
            $admissionApplication,
            auth()->id()
        );

        if ($result['already_accepted']) {
            return back()->with('success', 'Application is already accepted.');
        }

        session()->flash('temporary_student_password', $result['temporary_password']);

        return back()->with('success', 'Application accepted. Student account has been created.');
    }

    public function reject(
        RejectAdmissionApplicationRequest $request,
        AdmissionApplication $admissionApplication,
        AdminAdmissionReviewService $admissionReviewService
    ) {
        $admissionReviewService->reject(
            $admissionApplication,
            $request->validated('rejection_reason'),
            auth()->id()
        );

        return back()->with('success', 'Application rejected.');
    }

}