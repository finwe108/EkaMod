<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolYear;
use App\Models\Payment;
use App\Models\Section;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'school_years' => SchoolYear::count(),
            'collected' => Payment::sum('amount'),
            'attendance_rate' => 94.2,
            'sections'     => Section::count(),
        ];

        $recentStudents = Student::latest()->take(5)->get();
        $recentAnnouncements = Announcement::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentStudents',
            'recentAnnouncements'
        ));
    }
}