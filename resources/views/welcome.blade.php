<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dr. Phua's Dental Clinic | Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

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

        /* Map styling to blend with blue theme */
        .map-container iframe {
            filter: grayscale(0.2) contrast(1.1) brightness(1.1);
            border-radius: 1.5rem;
        }
    </style>
</head>

<body class="min-h-screen text-slate-900 antialiased">

    <div class="bg-animated">
        <div class="bg-image"></div>
    </div>

    <header class="flex flex-col items-center justify-center pt-12 pb-4">
        <div class="logo-ring mb-4">
            <img src="{{ asset('tooth_logo.ico') }}" alt="Dr. Phua's Logo" class="logo-img">
        </div>
        <div class="text-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 drop-shadow-md">
                Dr. Phua's <span class="text-blue-700">Dental Clinic</span>
            </h2>
        </div>
    </header>

    <section class="text-center px-6 mt-16">
        <span class="px-4 py-1.5 bg-blue-600 text-white text-sm font-bold rounded-full tracking-wide uppercase shadow-lg">
            Authorized Personnel Only
        </span>

        <h1 class="mt-8 text-5xl md:text-7xl font-extrabold text-slate-900 leading-[1.1] tracking-tight drop-shadow-xl">
            <span class="text-blue-700 underline decoration-white/30 decoration-8 underline-offset-8">Dental Clinic Management Information System</span>
        </h1>

        <p class="mt-8 text-lg md:text-xl text-slate-800 max-w-2xl mx-auto leading-relaxed font-semibold">
            A specialized administrative tool for Dr. Phua's Dental Clinic to manage patient records, clinical examinations, and radiology data.
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

    <section id="about-us" class="mt-32 px-6">
        <div class="max-w-6xl mx-auto glass-card rounded-[3rem] p-10 md:p-20">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl font-extrabold text-slate-900 mb-6">About Us</h2>
                    <p class="text-slate-800 text-lg leading-relaxed mb-6 font-medium">
                        Dr. Phua's Dental Clinic is committed to providing top-tier oral healthcare through innovation. This Management Information System (MIS) serves as the digital foundation of our practice.
                    </p>
                    <p class="text-slate-800 text-lg leading-relaxed font-medium">
                        By centralizing patient records and treatment history, we ensure our clinical team can focus on delivering precise, patient-centered care.
                    </p>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4 p-6 bg-white/20 rounded-2xl border border-white/30 shadow-sm backdrop-blur-md">
                        <div>
                            <h4 class="font-bold text-slate-900">Data Security</h4>
                            <p class="text-sm text-slate-800 font-medium">Encrypted storage for sensitive medical records.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-6 bg-white/20 rounded-2xl border border-white/30 shadow-sm backdrop-blur-md">
                        <div>
                            <h4 class="font-bold text-slate-900">Operational Speed</h4>
                            <p class="text-sm text-slate-800 font-medium">Instant retrieval of patient radiology and history.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact-us" class="mt-24 px-6 mb-32">
        <div class="max-w-6xl mx-auto">
            <div class="glass-card rounded-[3rem] p-8 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    
                   <div class="map-container h-48 w-full overflow-hidden rounded-lg">
    <img 
        src="{{ asset('location.png') }}" 
        alt="Location Map" 
        class="w-full h-full object-cover transition-hover duration-300 hover:scale-105"
        loading="lazy"
    >
</div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-blue-600/10 rounded-2xl">
                            <i data-lucide="map-pin" class="w-6 h-6 text-blue-700"></i>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-blue-700 uppercase tracking-widest mb-1">Clinic Location</h4>
                            <p class="text-lg font-bold text-slate-900 leading-tight">
                                A. Borbajo St. Talamban,<br>
                                Cebu City, Philippines
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 border-t md:border-t-0 md:border-l border-white/30 pt-6 md:pt-0 md:pl-10">
                        <h4 class="text-[10px] font-black text-blue-700 uppercase tracking-widest mb-1">Contact Details</h4>
                        <div class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-5 h-5 text-blue-700"></i>
                            <p class="text-lg font-bold text-slate-900">345-8870</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="smartphone" class="w-5 h-5 text-blue-700"></i>
                            <p class="text-sm font-bold text-slate-700">+63 917 123 4567</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-12 bg-white/10 backdrop-blur-xl border-t border-white/20 text-slate-900 font-bold">
        <p>© {{ date('Y') }} Dr. Phua's Dental Clinic. Administrative Interface.</p>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>