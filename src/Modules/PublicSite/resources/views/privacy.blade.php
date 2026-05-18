@extends('layouts.landing')

@section('title', 'Privacy Notice | ' . config('school.name'))

@section('meta_description', 'Data Privacy Notice of ' . config('school.name') . '.')

@section('body_class', 'story-page')

@section('content')
<section class="story-hero story-hero--compact">
    <div class="container story-hero__inner">
        <p class="eyebrow">Data Privacy</p>
        <h1>Privacy Notice</h1>
        <p>How {{ config('school.short') }} collects, uses, shares, stores, and protects personal data.</p>
    </div>
</section>

<section class="section privacy-section">
    <div class="container privacy-copy">
        <h2>Introduction</h2>

        <p>
            The School's policy for handling the data it collects, uses, shares, and/or processes is described in this Data Privacy Notice.
            To ensure the privacy, safety, security, and responsible use of your personal data,
            {{ config('school.short') }} is committed to complying with Philippine Republic Act No. 10173,
            better known as the Data Privacy Act of 2012 (DPA).
            {{ config('school.short') }} respects your right to privacy.
        </p>

        <p>
            Under the Data Privacy Act of 2012 and its Implementing Rules and Regulations, personal information may be processed or disclosed
            when required for {{ config('school.short') }} to comply with a legal obligation, protect vital interests, respond to emergencies,
            maintain public order and safety, fulfill duties of public authority, or serve the School's legitimate interests.
        </p>

        <p>
            Sensitive personal data may be processed or disclosed with the data owner's consent if legally permitted.
            Only authorized {{ config('school.short') }} personnel are permitted to process personal information as part of their official duties.
        </p>

        <h2>What Data Does {{ config('school.short') }} Collect, Acquire, or Generate?</h2>

        <p>
            {{ config('school.short') }} collects, acquires, and generates personal data in various forms and methods.
            These may include written records, photographs, video recordings, and digital materials.
        </p>

        <h3>Information Provided During Application</h3>

        <ul>
            <li>Name</li>
            <li>Home address</li>
            <li>Email address</li>
            <li>Contact number</li>
            <li>Date and place of birth</li>
            <li>Sex</li>
            <li>Religion</li>
            <li>Citizenship</li>
            <li>Family background</li>
            <li>Academic background</li>
            <li>Medical records</li>
            <li>Other submitted documents</li>
        </ul>

        <h3>Information Generated During Enrollment or School Participation</h3>

        <ul>
            <li>Academic activities</li>
            <li>Medical information</li>
            <li>Co-curricular and extra-curricular activities</li>
            <li>Service records</li>
            <li>Other school-related information</li>
        </ul>

        <h2>How Does {{ config('school.short') }} Use Your Personal Data?</h2>

        <p>
            To the fullest extent allowed or required by law and with consent where required,
            {{ config('school.short') }} may use personal information for legitimate educational,
            academic, administrative, research, and statistical purposes.
        </p>

        <ul>
            <li>To evaluate applications to the School</li>
            <li>To process confirmations of acceptance</li>
            <li>To maintain student, employee, alumni, and client records</li>
            <li>To investigate and report violations or misconduct</li>
            <li>To generate statistical and research data</li>
            <li>To provide academic, health, emotional, or welfare support</li>
            <li>To provide IT, library, sports, and recreation services</li>
            <li>To disseminate official School communications</li>
        </ul>

        <h2>How Will {{ config('school.short') }} Share, Disclose, or Transfer Your Personal Data?</h2>

        <p>
            With consent or as required by law, {{ config('school.short') }} may share, disclose,
            or transfer personal data to individuals, organizations, or agencies when necessary
            to protect student interests or pursue legitimate institutional purposes.
        </p>

        <ul>
            <li>To notify relevant parties regarding admission</li>
            <li>To share information with authorized individuals as prescribed by law</li>
            <li>To coordinate with donors, funders, or benefactors for scholarships and assistance</li>
            <li>To comply with requirements from government agencies such as DepEd and other lawful authorities</li>
            <li>To comply with court orders, subpoenas, and legal obligations</li>
            <li>To conduct research or surveys for school development</li>
            <li>To feature photos, videos, and school activities in official publications or marketing materials</li>
            <li>To publish school news, features, or event coverage through official channels</li>
        </ul>

        <h2>How Will {{ config('school.short') }} Store and Retain Your Personal Data?</h2>

        <p>
            Information and data are securely stored and transmitted in paper and electronic formats.
            Access is limited to authorized personnel who have legitimate duties related to the information.
        </p>

        <p>
            The School may be required to permanently retain certain student and employee records.
            Records may only be destroyed when permitted by law and when properly documented by the concerned office.
        </p>

        <p>
            {{ config('school.short') }} may periodically update this Privacy Notice.
            Updates will be posted on the School website and will take effect immediately upon posting.
        </p>
    </div>
</section>
@endsection