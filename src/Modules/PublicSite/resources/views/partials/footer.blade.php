<footer class="footer">
    <div class="container footer__inner">

        <p>
            &copy; {{ date('Y') }} {{ $schoolName }}. All rights reserved.
        </p>

        <div>
            <a href="{{ route('public.home') }}#home">Back to top</a>
            <a href="{{ route('public.about') }}">Our Story</a>
            <a href="{{ route('public.admission.create') }}">Admissions</a>
            <a href="{{ route('public.privacy') }}">Privacy Notice</a>
            <a href="{{ route('public.home') }}#contact">Contact</a>
        </div>

    </div>
</footer>

{{-- JS --}}
<script src="{{ asset('assets/js/main.js') }}?v={{ config('app.asset_version') }}"></script>