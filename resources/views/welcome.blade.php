<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dr. Phua's Dental Clinic | Management System</title>

    {{-- Local CSS & JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            scroll-behavior: smooth;
            overflow-x: hidden;
            background-color: #000;
        }

        .bg-animated {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .bg-image {
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("clinic.jfif") }}');
            background-size: cover;
            background-position: center;
            animation: slowZoom 20s infinite alternate ease-in-out;
        }

        @keyframes slowZoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1) translateX(-10px); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .logo-ring {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px; 
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #fbbf24 100%);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }
        
        .logo-ring::before {
            content: '';
            position: absolute;
            inset: 4px;
            background: white;
            border-radius: 50%;
            z-index: 0;
        }

        .logo-img {
            z-index: 10;
            width: 70%; 
            height: 70%;
            object-fit: contain;
        }

        .map-container img {
            border-radius: 1.5rem;
        }
    </style>
</head>

<body class="min-h-screen text-slate-900 antialiased">

    <!-- Background -->
    <div class="bg-animated">
        <div class="bg-image"></div>
    </div>

    <!-- Header -->
    <header class="flex flex-col items-center justify-center pt-12 pb-4">
        <div class="logo-ring mb-4">
            <img src="{{ asset('tooth_logo.ico') }}" alt="Logo" class="logo-img">
        </div>
        <div class="text-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 drop-shadow-md">
                Dr. Phua's <span class="text-blue-700">Dental Clinic</span>
            </h2>
        </div>
    </header>

    <!-- Hero -->
    <section class="text-center px-6 mt-16">
        <span class="px-4 py-1.5 bg-blue-600 text-white text-sm font-bold rounded-full shadow-lg uppercase">
            Authorized Personnel Only
        </span>

        <h1 class="mt-8 text-5xl md:text-7xl font-extrabold text-slate-900 drop-shadow-xl">
            <span class="text-blue-700 underline decoration-white/30 decoration-8 underline-offset-8">
                Dental Clinic Management Information System
            </span>
        </h1>

        <p class="mt-8 text-lg md:text-xl text-slate-800 max-w-2xl mx-auto font-semibold">
            A specialized administrative tool to manage patient records, examinations, and radiology data.
        </p>

        <div class="mt-12 flex flex-wrap gap-6 justify-center">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-2xl hover:bg-blue-700 transition transform hover:scale-105">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-2xl hover:bg-blue-700 transition transform hover:scale-105">
                    Admin Access
                </a>
            @endauth
        </div>
    </section>

    <!-- About -->
    <section class="mt-32 px-6">
        <div class="max-w-6xl mx-auto glass-card rounded-[3rem] p-10 md:p-20">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl font-extrabold mb-6">About Us</h2>
                    <p class="text-lg mb-6 font-medium">
                        Dr. Phua's Dental Clinic uses a digital system to improve efficiency and patient care.
                    </p>
                    <p class="text-lg font-medium">
                        Patient records and treatments are centralized for faster and more accurate service.
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="p-6 bg-white/20 rounded-2xl border">
                        <h4 class="font-bold">Data Security</h4>
                        <p class="text-sm">Encrypted patient records.</p>
                    </div>
                    <div class="p-6 bg-white/20 rounded-2xl border">
                        <h4 class="font-bold">Operational Speed</h4>
                        <p class="text-sm">Fast data retrieval.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="mt-24 px-6 mb-32">
        <div class="max-w-6xl mx-auto glass-card rounded-[3rem] p-8 md:p-12">
            <div class="grid md:grid-cols-3 gap-8 items-center">

                <!-- Map -->
                <div class="map-container h-48 w-full overflow-hidden">
                    <img src="{{ asset('location.png') }}" class="w-full h-full object-cover">
                </div>

                <!-- Address -->
                <div>
                    <h4 class="text-xs font-black text-blue-700 uppercase mb-1">Clinic Location</h4>
                    <p class="text-lg font-bold">
                        A. Borbajo St. Talamban,<br>
                        Cebu City, Philippines
                    </p>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-xs font-black text-blue-700 uppercase mb-1">Contact</h4>
                    <p class="font-bold">☎ 345-8870</p>
                    <p class="font-bold">📱 +63 917 123 4567</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-12 bg-white/10 border-t font-bold">
        <p>© {{ date('Y') }} Dr. Phua's Dental Clinic</p>
    </footer>

</body>
</html>
