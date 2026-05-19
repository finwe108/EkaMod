@extends('layouts.landing')

@section('title', $shortName . ' | Admissions and Campus Life')

@section('content')
<section class="hero" id="home">
    <div class="hero__media" role="img" aria-label="K-12 learners in MMCI uniforms walking through a Philippine integrated school campus"></div>
    <div class="hero__overlay"></div>

    <div class="container hero__content">
        <div class="hero__seal" aria-hidden="true">
                <img src="{{ $schoolLogo }}" alt="{{ $schoolName }}">
            </div>

        <p class="eyebrow">Kibawe, Bukidnon / Admissions open for 2026</p>

        <h1>{{ $schoolName }}</h1>

        <p>
            {{ $tagline }}
            Know the self. Find the path. Begin the journey.
        </p>

        <div class="hero__actions">
            <a class="button button--primary" href="{{ route('public.admission.create') }}">Apply Now</a>
            <a class="button button--secondary" href="#programs">View Programs</a>
        </div>
    </div>
</section>

<section class="stats" aria-label="School highlights">
    <div class="container stats__grid">
        @foreach(config('school.stats', []) as $stat)
            <div class="stat">
                <strong>
                    <span data-count="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] }}
                </strong>
                <span>{{ $stat['label'] }}</span>
            </div>
        @endforeach
    </div>
</section>

<section class="section section--split" id="about">
    <div class="container split">
        <div>
            <p class="eyebrow">About the campus</p>
            <h2>One integrated K-12 path for strong foundations and practical futures.</h2>
        </div>

        <div class="rich-copy">
            <p>
                {{ $schoolName }} serves learners in Kibawe and neighboring municipalities
                in Southern Bukidnon from Preschool through Senior High School with a school experience
                shaped by Christian values, yoga wisdom, devotional loving service, self-discipline,
                compassion, and practical readiness.
            </p>

            <a class="text-link" href="{{ route('public.about') }}">View full story</a>
        </div>
    </div>
</section>

<section class="section section--muted" id="programs">
    <div class="container">
        <div class="section__heading">
            <p class="eyebrow">Academic pathways</p>
            <h2>Departments that support every learner stage.</h2>
        </div>

        <div class="tabs" role="tablist" aria-label="Program categories">
            <button class="tab is-active" type="button" data-tab="basic">Preschool to Elementary</button>
            <button class="tab" type="button" data-tab="secondary">JHS to SHS</button>
            <button class="tab" type="button" data-tab="tech-voc">Technical Skills</button>
        </div>

        <div class="program-grid">
            @foreach(config('school.academic_programs', []) as $program)
                <article class="program-card" data-program-tab="{{ $program['tab'] }}">
                    <span>{{ $program['subtitle'] }}</span>
                    <h3>{{ $program['title'] }}</h3>
                    <p>{{ $program['copy'] }}</p>
                    <a href="{{ route('public.admission.create', ['program' => $program['title']]) }}">
                        Inquire about {{ $program['title'] }}
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section" id="departments">
    <div class="container">
        <div class="section__heading">
            <p class="eyebrow">Departments</p>
            <h2>One coordinated academic community.</h2>
        </div>

        <div class="department-grid">
            @foreach(config('school.departments', []) as $department)
                <article class="department-card">
                    <div class="department-card__icon">
                        {!! $department['icon_svg'] !!}
                    </div>
                    <h3>{{ $department['name'] }}</h3>
                    <p>{{ $department['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section section--accent" id="admissions">
    <div class="container admissions">
        <div class="admissions__content">
            <p class="eyebrow">Admissions process</p>
            <h2>From inquiry to enrollment in four clear steps.</h2>

            <div class="timeline">
                @foreach(config('school.steps', []) as $index => $step)
                    <div class="timeline__item">
                        <span>{{ $index + 1 }}</span>
                        <div>
                            <h3>{{ $step['title'] }}</h3>
                            <p>{{ $step['copy'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="inquiry-form">
            <h2>Start an Inquiry</h2>
            <p>
                Ready to begin? Open the official admission form and submit your application
                directly to the registrar for review.
            </p>

            <a class="button button--primary" href="{{ route('public.admission.create') }}">
                Open Admission Form
            </a>

            <a class="button button--outline" href="{{ route('login') }}">
                Student Portal
            </a>
        </div>
    </div>
</section>

<section class="section" id="campus">
    <div class="container campus">
        <div class="section__heading">
            <p class="eyebrow">Campus life</p>
            <h2>Balanced routines for study, service, compassion, creativity, and leadership.</h2>
        </div>

        <div class="campus__grid">
            <div class="campus-tile campus-tile--large">
                <strong>Learning Labs</strong>
                <span>Hands-on projects, practical demos, and guided research work.</span>
            </div>

            <div class="campus-tile">
                <strong>Clubs</strong>
                <span>Arts, sports, debate, coding, outreach, and entrepreneurship.</span>
            </div>

            <div class="campus-tile">
                <strong>Guidance</strong>
                <span>Advising, wellness support, and parent communication.</span>
            </div>
        </div>
    </div>
</section>

<section class="section section--muted" id="news">
    <div class="container">
        <div class="section__heading section__heading--row">
            <div>
                <p class="eyebrow">Latest updates</p>
                <h2>Announcements students and parents can act on.</h2>
            </div>

            <a class="text-link" href="#contact">Send an announcement request</a>
        </div>

        <div class="news-grid">
            @forelse($announcements as $announcement)
                <article class="news-card">
                    <span>
                        {{ optional($announcement->created_at)->format('M d, Y') }}
                        / Announcement
                    </span>

                    <h3>{{ $announcement->title }}</h3>

                    <p>
                        {{ \Illuminate\Support\Str::limit($announcement->content, 120) }}
                    </p>

                    <a href="{{ route('public.news.show', $announcement) }}">
                        Read more
                    </a>
                </article>
            @empty
                <article class="news-card">
                    <span>Updates</span>
                    <h3>No announcements yet</h3>
                    <p>Please check back soon for school updates.</p>
                </article>
            @endforelse
        </div>

        <div style="margin-top: 20px;">
            <a class="button button--secondary" href="{{ route('public.news.index') }}">
                View all announcements
            </a>
        </div>
        
    </div>
</section>

<section class="section" id="support">
    <div class="container support">
        <div>
            <p class="eyebrow">Family support</p>
            <h2>Clear channels for applicants, parents, and enrolled students.</h2>
            <p>
                Quick links can be connected to your student information system, LMS,
                payment portal, scholarship application, or help desk when those URLs are ready.
            </p>
        </div>

        <div class="quick-panel" id="portal">
            <a href="{{ route('public.admission.create') }}">Enrollment Form</a>
            <a href="{{ route('login') }}">Student Account</a>
            <a href="#">Learning Portal</a>
            <a href="#contact">Request Documents</a>
        </div>
    </div>
</section>

<section class="section section--muted">
    <div class="container">
        <div class="section__heading">
            <p class="eyebrow">FAQ</p>
            <h2>Common admissions questions.</h2>
        </div>

        <div class="faq">
            @foreach(config('school.faqs', []) as $index => $faq)
                <article class="faq__item">
                    <button type="button" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                        {{ $faq['q'] }}
                        <span></span>
                    </button>

                    <p @if($index !== 0) hidden @endif>{{ $faq['a'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section section--contact" id="contact">
    <div class="container contact">
        <div>
            <p class="eyebrow">Contact</p>
            <h2>Ready to visit or ask a question?</h2>
            <p>{{ $schoolAddress }}</p>
        </div>

        <div class="contact__links">
            <a href="tel:{{ $schoolPhone }}">{{ $schoolPhone }}</a>
            <a href="mailto:{{ $schoolEmail }}">{{ $schoolEmail }}</a>
            <a href="https://{{ config('school.domain') }}">{{ config('school.domain') }}</a>
        </div>
    </div>
</section>
@endsection