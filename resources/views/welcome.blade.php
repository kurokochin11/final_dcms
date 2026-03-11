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
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), 
                        url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=2070&auto=format&fit=crop');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        /* Logo Ring Styles */
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
    </style>
</head>

<body class="min-h-screen text-slate-800 antialiased">

    <header class="flex flex-col items-center justify-center pt-12 pb-4 z-50">
        <div class="logo-ring mb-4">
            <img src="{{ asset('tooth_logo.ico') }}" alt="Dr. Phua's Logo" class="logo-img">
        </div>
        <div class="text-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">
                Dr. Phua's <span class="text-blue-600">Dental Clinic</span>
            </h2>
        </div>
    </header>

    <section class="text-center px-6 mt-16">
        <span class="px-4 py-1.5 bg-blue-100 text-blue-700 text-sm font-bold rounded-full tracking-wide uppercase">
            Authorized Personnel Only
        </span>

        <h1 class="mt-8 text-5xl md:text-7xl font-extrabold text-slate-900 leading-[1.1] tracking-tight">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 underline decoration-blue-100 decoration-8 underline-offset-8">Dental Clinic Management Information System</span>
        </h1>

        <p class="mt-8 text-lg md:text-xl text-slate-700 max-w-2xl mx-auto leading-relaxed font-medium">
            A specialized administrative tool for Dr. Phua's Dental Clinic to manage patient records, clinical examinations, and radiology data.
        </p>

        <div class="mt-12 flex flex-wrap gap-6 justify-center">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-xl hover:bg-blue-700 transition transform hover:scale-105 flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-xl shadow-blue-200 hover:bg-blue-700 transition transform hover:scale-105 flex items-center gap-2">
                    <i data-lucide="lock" class="w-5 h-5"></i>
                    Admin Access
                </a>
            @endauth
        </div>
    </section>

    <section id="about-us" class="mt-32 px-6">
        <div class="max-w-6xl mx-auto glass-card rounded-[3rem] p-10 md:p-20 shadow-2xl">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                        <i data-lucide="info" class="text-blue-600"></i>
                        About Us:
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">
                        Dr. Phua's Dental Clinic is committed to providing top-tier oral healthcare through innovation. This Management Information System (MIS) serves as the digital foundation of our practice.
                    </p>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        By centralizing patient records, imaging, and treatment history, we ensure our clinical team can focus on what matters most: delivering precise, patient-centered care in a modern environment.
                    </p>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4 p-6 bg-white/50 rounded-2xl border border-white/40 shadow-sm">
                        <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900">Data Security</h4>
                            <p class="text-sm text-slate-600">Encrypted storage for sensitive medical records.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-6 bg-white/50 rounded-2xl border border-white/40 shadow-sm">
                        <div class="bg-indigo-100 p-3 rounded-xl text-indigo-600">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900">Operational Speed</h4>
                            <p class="text-sm text-slate-600">Instant retrieval of patient radiology and history.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact-us" class="mt-24 px-6 mb-32">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-10 uppercase tracking-wide">Contact Us</h2>
            Tel No. 345-8870<br>
            <div class="flex justify-center">
                <div class="glass-card rounded-3xl p-10 md:p-14 shadow-lg w-full max-w-2xl border border-white/50">
                    <div class="flex flex-col md:flex-row items-center justify-center gap-8 text-center md:text-left">
                        
                        <div class="bg-blue-600 p-5 rounded-2xl text-white shadow-xl transform transition hover:rotate-6">
                            <i data-lucide="map-pin" class="w-10 h-10"></i>
                        </div>

                        <div>
                            <h3 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-2">Clinic Location</h3>
                            <p class="text-2xl md:text-3xl font-bold text-slate-800 leading-tight">
                                A. Borbajo St. Talamban,<br>
                                Cebu City, Philippines
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-12 bg-white/40 backdrop-blur-md border-t border-white/20 text-slate-500 font-medium">
        <p>© {{ date('Y') }} Dr. Phua's Dental Clinic. Administrative Interface.</p>
    </footer>

    <script>
      // Initialize Lucide Icons
      lucide.createIcons();
    </script>
</body>
</html>