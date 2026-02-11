<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="welcome-body">

    <!-- Top Right Auth Links -->
    <div class="top-right-links">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        @endif
    </div>

    <!-- Main Glassmorphic Card -->
    <div class="welcome-card">

        <!-- Center Logo -->
        <img src="{{ asset('assets/tooth_logo.png') }}" alt="Logo" class="welcome-logo">

        <!-- Title -->
        <h1>Dental Care Management System</h1>

        <!-- Action Buttons -->
        <a href="{{ route('login') }}" class="btn-primary-custom">Login</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-outline-custom">Register</a>
        @endif

    </div>

</body>
</html>
