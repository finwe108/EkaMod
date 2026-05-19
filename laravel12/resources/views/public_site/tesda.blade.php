@extends('layouts.landing')

@section('title', 'TESDA Programs | ' . config('school.name'))

@section('body_class', 'story-page')

@section('content')

{{-- HERO --}}
<section class="story-hero">
    <div class="container story-hero__inner">
        <p class="eyebrow">Technical Education</p>
        <h1>TESDA Programs</h1>
        <p>
            Empowering learners with industry-ready skills through Technical Vocational Education and Training (TVET).
        </p>
    </div>
</section>

{{-- OVERVIEW --}}
<section class="section">
    <div class="container split">
        <div>
            <p class="eyebrow">About TESDA</p>
            <h2>Skills training for real-world success</h2>
        </div>

        <div class="rich-copy">
            <p>
                {{ config('school.name') }} offers TESDA-aligned programs designed to equip learners
                with practical skills, industry certifications, and employment readiness.
            </p>

            <p>
                Our TechVoc programs focus on hands-on learning, competency-based training,
                and real-world application to prepare students for immediate employment or entrepreneurship.
            </p>
        </div>
    </div>
</section>

{{-- PROGRAMS --}}
<section class="section section--muted">
    <div class="container">
        <div class="section__heading">
            <p class="eyebrow">Programs Offered</p>
            <h2>Hilot (Wellness Massage) NC II</h2>
        </div>

        <div class="program-grid">

            @foreach(config('school.tesda_programs', []) as $program)
                <article class="program-card">
                    <h3>{{ $program['title'] }}</h3>
                    <p>{{ $program['description'] }}</p>

                    <a href="{{ route('public.admission.create', ['program' => $program['title']]) }}">
                        Apply for this Program
                    </a>
                </article>
            @endforeach

        </div>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div>
            <p class="eyebrow">Why Choose This Program</p>
            <h2>Practical skills. National certification. Real opportunities.</h2>
        </div>

        <div class="rich-copy">
            <ul>
                <li>✔ TESDA-aligned competency training</li>
                <li>✔ Hands-on practical learning</li>
                <li>✔ National Certification (NC II)</li>
                <li>✔ Employment and self-employment opportunities</li>
                <li>✔ Short-term, skill-focused program</li>
            </ul>
        </div>
    </div>
</section>

{{-- SUCCESS STORIES --}}
<section class="section">
    <div class="container">
        <div class="section__heading">
            <p class="eyebrow">Success Stories</p>
            <h2>Real stories from our TechVoc learners</h2>
        </div>

        <div class="tesda-success-grid">
            @foreach(config('school.tesda_success', []) as $story)
                <article class="tesda-success-card">
                    <div class="tesda-success-card__photo">
                        <img src="{{ asset($story['photo']) }}" alt="{{ $story['name'] }}">
                    </div>

                    <div class="tesda-success-card__body">
                        <span>{{ $story['program'] }}</span>
                        <h3>{{ $story['name'] }}</h3>
                        <p>{{ $story['story'] }}</p>

                        @if(!empty($story['video_url']))
                            <div class="tesda-video">
                                <iframe
                                    src="{{ $story['video_url'] }}"
                                    title="Video testimonial from {{ $story['name'] }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section section--accent">
    <div class="container admissions">
        <div>
            <p class="eyebrow">Start your journey</p>
            <h2>Enroll in TESDA programs today</h2>

            <a href="{{ route('public.admission.create') }}" class="button button--primary">
                Apply Now
            </a>
        </div>
    </div>
</section>

@endsection