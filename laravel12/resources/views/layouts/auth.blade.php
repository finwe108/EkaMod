<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'School System') }} - @yield('title', 'Auth')</title>


    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            background: #f3f4f6;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: .5rem;
        }

        .auth-subtitle {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: .6rem .75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .btn {
            display: inline-block;
            padding: .6rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .alert {
            padding: .75rem;
            border-radius: 6px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        @yield('content')
    </div>
</div>

</body>
</html>