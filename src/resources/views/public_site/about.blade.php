@extends('layouts.landing')

@section('title', 'About | ' . config('school.name'))

@section('body_class', 'story-page')

@section('content')
<section class="story-hero">
    <div class="container story-hero__inner">
        <p class="eyebrow">About {{ config('school.short') }}</p>
        <h1>About {{ config('school.name') }}</h1>
        <p>Shaping future-ready learners through excellence, Christian values, yoga wisdom, and purpose.</p>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div>
            <p class="eyebrow">Who we are</p>
            <h2>A school community built around learning, Christian values, yoga wisdom, and growth.</h2>
        </div>

        <div class="rich-copy">
            <p>
                {{ config('school.name') }} is a private, non-sectarian, pro-life institution committed
                to quality education that nurtures academic excellence, strong moral values, and spiritual growth.
                Our approach combines Christian values and yoga wisdom to guide learners toward love for God,
                service to others, humility, self-discipline, compassion, and purposeful living.
            </p>
        </div>
    </div>
</section>

<section class="section section--muted">
    <div class="container story-card-grid">
        <article class="story-card">
            <h3>Our Mission</h3>
            <p>
                To develop 21st-century learners through high-quality instruction focused on Christian values,
                yoga wisdom, spiritual love, compassion, and service to humanity and the environment.
            </p>
        </article>

        <article class="story-card">
            <h3>Our Vision</h3>
            <p>
                The propagation of Christian values and yoga wisdom in all of humanity,
                for true everlasting happiness and success in life.
            </p>
        </article>

        <article class="story-card">
            <h3>Our Philosophy</h3>
            <p>
                Education goes beyond academics. We teach that each learner has a spiritual essence,
                a relationship with the Supreme Soul, and a lifelong calling to loving service,
                character formation, leadership, and holistic development.
            </p>
        </article>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__heading">
            <p class="eyebrow">Core values</p>
            <h2>The values that shape campus life.</h2>
        </div>

        <div class="story-card-grid">
            @foreach(config('school.story_values', []) as $value)
                <article class="story-card">
                    <h3>{{ $value['title'] }}</h3>
                    <p>{{ $value['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section section--muted">
    <div class="container split">
        <div>
            <p class="eyebrow">Our journey</p>
            <h2>Growing with the needs of modern learners.</h2>
        </div>

        <div class="rich-copy">
            <p>
                What started as a small learning initiative has grown into a respected institution serving
                students from Preschool to Senior High School. Through dedication, spiritual love,
                compassionate service, and a commitment to quality education,
                {{ config('school.name') }} continues to expand and evolve to meet the needs of modern learners.
            </p>
        </div>
    </div>
</section>

<section class="story-cta">
    <div class="container story-cta__inner">
        <h2>Be Part of Our Community</h2>
        <p>Join us in shaping a brighter future.</p>
        <a class="button button--primary" href="{{ route('public.admission.create') }}">Enroll Now</a>
    </div>
</section>
@endsection