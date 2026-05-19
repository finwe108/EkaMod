<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | MMCI</title>

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#f5f7fb;
            color:#1f2937;
            display:flex;
            align-items:center;
            justify-content:center;
            min-height:100vh;
            padding:20px;
        }

        .error-card{
            width:100%;
            max-width:600px;
            background:#fff;
            border-radius:16px;
            padding:40px;
            text-align:center;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
        }

        h1{
            font-size:72px;
            margin:0 0 12px;
            color:#6d28d9;
        }

        p{
            color:#6b7280;
            line-height:1.6;
            margin-bottom:24px;
        }

        .btn{
            display:inline-block;
            padding:12px 20px;
            background:#6d28d9;
            color:#fff;
            text-decoration:none;
            border-radius:10px;
            border:none;
            cursor:pointer;
        }

        .btn:hover{
            opacity:.9;
        }

        .error-id{
            margin-top:20px;
            font-size:13px;
            color:#9ca3af;
        }
    </style>
</head>
<body>

<div class="error-card">
    @yield('content')
</div>

</body>
</html>