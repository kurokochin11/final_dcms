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

        /* Animations */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-up { animation: fadeIn 0.5s ease-out forwards; }
        .delay-1 { animation-delay: 0.2s; opacity: 0; }
        .delay-2 { animation-delay: 0.4s; opacity: 0; }

        /* Navbar Styling */
        .navbar {
            background-color: #1572e8 !important;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Hero Area */
        .hero-container {
            background: linear-gradient(135deg, rgba(21, 114, 232, 0.9) 0%, rgba(14, 89, 188, 0.8) 100%), 
                        url("{{ asset('tooth_welcome.jfif') }}");
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

        /* Map Section Styling */
        .map-wrapper {
            background: white;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .map-container {
            overflow: hidden;
            border-radius: 20px;
            position: relative;
        }

        .btn-directions {
            background: #1572e8;
            color: white !important;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            margin-top: 20px;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .btn-directions:hover {
            background: #0e59bc;
            transform: scale(1.05);
        }

        .btn-admin {
    background: white;
    color: #1572e8 !important;
    border-radius: 50px;
    padding: 15px 40px;
    font-weight: 700;
    border: 1px solid #e0e0e0; /* Subtle default border */
    transition: all 0.2s ease-in-out;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

/* Simple Highlight Hover */
.btn-admin:hover {
    background-color: #fafafa;    /* Very slight off-white highlight */
    border-color: #1572e8;        /* Border changes to clinic blue */
    color: #1572e8 !important;
    transform: translateY(-2px);  /* Very small lift */
    box-shadow: 0 5px 15px rgba(0,0,0,0.08) !important;
}
        .footer-info-section {
            background: white;
            border-top: 1px solid #eef0f7;
            padding: 40px 0;
        }

        .contact-link {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }
        .contact-link:hover { color: #1572e8; }
    </style>
</head>

<body class="antialiased">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid px-4">
            <div class="navbar-brand d-flex align-items-center mb-0">
                <img src="{{ asset('tooth_logo.ico') }}" width="35" height="35" class="me-2" style="filter: brightness(0) invert(1);">
                <span class="fw-bold fs-4">DR. PHUA'S <span class="text-white">DENTAL CLINIC</span></span>
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
            <div class="d-flex justify-content-center animate-up delay-2">
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
        <div class="row justify-content-center">
            <div class="col-lg-11 animate-up delay-1">
                <div class="map-wrapper text-center">
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d981.189196568603!2d123.91771!3d10.37028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9989d3147c145%3A0x58cf898409db813e!2sCebu%20Mary%20Immaculate%20College!5e1!3m2!1sen!2sph!4v1710123456789!5m2!1sen!2sph" 
                            width="100%" 
                            height="550" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <a href="https://maps.app.goo.gl/3f8HshZ7xZf8Jv8A9" target="_blank" class="btn-directions shadow-sm">
                        <i class="fas fa-directions me-2"></i> Get Directions to Clinic
                    </a>
                </div>
            </div>
        </div>
    </main>

    <section class="footer-info-section">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold text-primary mb-3">About Us</h5>
                    <p class="text-muted small"><strong>Mission:</strong> Advancing dental care through technology. Driven by excellence and patient-first innovation under the leadership of Dr. Phua.</p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold text-primary mb-3">Contacts</h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2">
                            <i class="fas fa-phone text-primary me-2"></i> (032) 345 8870
                        </li>
                        <li>
                            <i class="fas fa-envelope text-primary me-2"></i> 
                            <a href="mailto:admin@gmail.com" class="contact-link">admin@gmail.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-4 bg-light">
        <div class="container text-muted">
            <p class="small mb-0">© {{ date('Y') }} Dr. Phua's Dental Clinic. Administrative Interface.</p>
        </div>
    </footer>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>