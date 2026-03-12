<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dr. Phua's Dental Clinic | Management System</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.min.css') }}">

    <style>
        body { 
            font-family: 'Public Sans', sans-serif; 
            background-color: #f8f9fc;
            color: #2a2f5b;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        /* --- Animations --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-up { animation: fadeInUp 0.8s ease-out forwards; }
        .delay-1 { animation-delay: 0.2s; opacity: 0; }
        .delay-2 { animation-delay: 0.4s; opacity: 0; }
        .delay-3 { animation-delay: 0.6s; opacity: 0; }

        /* Navbar Styling */
        .navbar {
            background-color: #1572e8 !important;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* White Dropdown Styling */
        .dropdown-menu {
            background-color: white !important; /* Matches navbar */
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .dropdown-item {
            color: black !important;
            font-weight: 500;
        }
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
        .dropdown-header {
            color: black !important;
            letter-spacing: 1px;
        }
        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .dropdown-menu p {
            color: black !important; /* Forces the small text to be white */
        }

        /* Hero Area with Background Image */
        .hero-container {
            /* Overlay + Background Image */
            background: linear-gradient(135deg, rgba(21, 114, 232, 0.9) 0%, rgba(14, 89, 188, 0.8) 100%), 
                        url("{{ asset('tooth_welcome.jfif') }}"); /* Changed to .jpg based on standard exports, adjust extension if needed */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 100px 20px 140px 20px;
            border-bottom-left-radius: 80px;
            border-bottom-right-radius: 80px;
            text-align: center;
            position: relative;
        }

        .hero-logo { animation: float 4s ease-in-out infinite; }

        .authorized-badge {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            color: white;
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-block;
        }

        .glass-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }
        .glass-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(21, 114, 232, 0.15);
        }

       .btn-admin {
    background: white;
    color: #1572e8 !important;
    border-radius: 50px;
    padding: 15px 40px;
    font-weight: 700;
    border: none;
    /* Added transition for smooth return */
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-admin:hover {
    /* Slight scale up */
    transform: translateY(-2px) scale(1.03);
    /* Deeper shadow for "lift" effect */
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    /* Slight blue tint to the background */
    background-color: #f0f7ff !important;
    /* Keeps the cursor as a pointer */
    cursor: pointer;
}

.btn-admin:active {
    /* "Press" effect when clicked */
    transform: translateY(0) scale(0.98);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}
    </style>
</head>

<body class="antialiased">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('tooth_logo.ico') }}" width="35" height="35" class="me-2" style="filter: brightness(0) invert(1);">
                <span class="fw-bold fs-4">DR. PHUA <span class="text-white">MIS</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-bold" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            About Us
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3 animate-up">
                            <li><h6 class="dropdown-header fw-bold">Mission</h6></li>
                            <li><p class="px-3 small">Advancing dental care through technology.</p></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-2" href="#"><i class="fas fa-user-md me-2"></i> Dr. Phua</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-bold" href="#" id="contactDropdown" role="button" data-bs-toggle="dropdown">
                            Contacts
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3 animate-up">
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-phone me-2"></i> 345-8870</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-map-marker-alt me-2"></i> Cebu City</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-container">
        <div class="container">
            <div class="mb-4 hero-logo">
                <img src="{{ asset('tooth_logo.ico') }}" alt="Logo" style="width: 90px; filter: brightness(0) invert(1);">
            </div>
            <div class="mb-4 animate-up">
                <span class="authorized-badge">System Access Point</span>
            </div>
            <h1 class="display-3 fw-bold mb-3 animate-up delay-1">
                Dental Clinic Management<br>
                <span class="text-white">Information System</span>
            </h1>
            <p class="text-white opacity-75 mb-5 mx-auto lead animate-up delay-2" style="max-width: 650px;">
                Securely manage patient records, digital imaging, and clinical schedules with our centralized administrative portal.
            </p>
            <div class="d-flex justify-content-center animate-up delay-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-admin shadow-lg">
                        <i class="fas fa-columns me-2"></i> Open Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-admin shadow-lg">
                        <i class="fas fa-lock me-2"></i> Admin Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container py-5 mt-n5">
        <div class="row g-4 justify-content-center">
            <div class="col-md-6 animate-up delay-1">
                <div class="glass-card">
                    <div class="d-flex align-items-center mb-4">
                        <div class="feature-icon-box me-3" style="width:60px; height:60px; background:#eef4ff; color:#1572e8; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px;">
                            <i class="fas fa-notes-medical"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-0">Clinic Excellence</h3>
                    </div>
                    <p class="text-muted mb-4">This MIS serves as the digital backbone of Dr. Phua's Dental Clinic, designed to streamline clinical workflows and improve patient data accuracy.</p>
                    <div class="bg-light p-3 rounded-4 border-start border-primary border-4">
                        <p class="mb-0 small fw-bold text-primary">Precision in every record, care in every visit.</p>
                    </div>
                </div>
            </div>
            </div>
    </main>

    <footer class="text-center py-5">
        <div class="container text-muted">
            <p class="small mb-0">© {{ date('Y') }} Dr. Phua's Dental Clinic. Administrative Interface.</p>
        </div>
    </footer>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>