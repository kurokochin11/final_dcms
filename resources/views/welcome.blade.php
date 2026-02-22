<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - Dental Care</title>

    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
</head>
<body class="welcome-body">

    <div class="welcome-card">
        <h1>Dental Care Management System</h1>

        <div class="button-container">
            @if (Route::has('login'))
                @auth
                    {{-- If logged in, show Dashboard button --}}
                    <a href="{{ url('/dashboard') }}" class="btn-primary-custom">Dashboard</a>
                @else
                    {{-- If not logged in, show Login and Register buttons --}}
                    <a href="{{ route('login') }}" class="btn-primary-custom">Login</a>
                    
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-outline-custom">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

</body>
</html>