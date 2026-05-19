@extends('layouts.landing')

@section('title', 'Application Submitted | ' . $shortName)

@section('content')

<section class="section">
    <div class="container" style="max-width: 760px;">

        {{-- SUCCESS HEADER --}}
        <div style="text-align:center; margin-bottom: 24px;">
            <h1>Application Submitted</h1>
            <p>Your admission request has been successfully received.</p>
        </div>

        {{-- APPLICATION SLIP --}}
        <div id="application-slip" style="background:#fff; border:1px solid #ddd; border-radius:10px; padding:24px;">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <div>
                    <strong>{{ $schoolName }}</strong><br>
                    <small>{{ $schoolAddress }}</small>
                </div>
                <img src="{{ $schoolLogo }}" width="60">
            </div>

            <hr>

            <h2 style="margin-top:16px;">Application Details</h2>

            <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
            <p><strong>Name:</strong> {{ $application->first_name }} {{ $application->last_name }}</p>
            <p><strong>Grade Level:</strong> {{ $application->gradeLevel?->name ?? '—' }}</p>
            <p><strong>Student Type:</strong> {{ ucfirst($application->student_type) }}</p>

            <p><strong>Email:</strong> {{ $application->email ?? 'Not provided' }}</p>
            <p><strong>Contact Number:</strong> {{ $application->contact_number ?? '—' }}</p>

            <hr>

            <h3>Next Steps</h3>
            <ul>
                <li>Wait for registrar review and approval</li>
                <li>Check your email for updates</li>
                <li>Prepare required documents</li>
            </ul>

            {{-- QR CODE --}}
            <div style="margin-top:24px; text-align:center;">
                <p><strong>Scan to Track Application</strong></p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('public.admission.success', $application->id)) }}">
            </div>

        </div>

        {{-- ACTION BUTTONS --}}
        <div style="margin-top:24px; display:flex; gap:10px; justify-content:center;">
            <button onclick="window.print()" class="button button--primary">
                Print Application
            </button>

            <a href="{{ route('public.home') }}" class="button button--outline">
                Back to Home
            </a>
        </div>

        {{-- NOTE --}}
        <p style="text-align:center; margin-top:20px; font-size:14px; color:#666;">
            Please save or print this page for your reference.
        </p>

    </div>
</section>

@endsection