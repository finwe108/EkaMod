<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'School Admission') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
    <main class="guest-page">
        <div class="container">
            @yield('content')
        </div>
    </main>
</body>
</html>