@section('title', 'Admin Login | Dr. Phua\'s Dental Clinic')
<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        .custom-error-box {
            background-color: #ffeaea;
            border: 1px solid #f8dadc;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 0 10px 3px rgba(255, 0, 0, 0.1);
        }

        .custom-error-list {
            list-style: none;
            padding: 0;
            margin: 0;
            color: #d11222;
            font-size: 0.8rem;
        }

        .custom-error-list-item {
            margin-bottom: 2px;
            font-weight: normal;
        }
        
        .custom-error-header {
            font-size: 0.9rem;
            color: #d11222;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .auth-card-white {
            width: 380px !important;
            min-height: auto;
            border-radius: 12px;
            padding: 2.5rem;
            z-index: 10; /* Ensures card stays above the background overlay */
        }

        .auth-wrapper {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Updated background logic */
            background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), 
                              url('{{ asset('dental_bg.jpg') }}'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .login-input {
            border-radius: 6px !important;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            font-size: 0.9rem;
            padding-left: 10px;
        }
        
        .input-icon-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #d1d5db;
            font-size: 0.9rem;
            cursor: pointer;
        }
    </style>

    <div class="auth-wrapper">
        <div class="auth-card-white shadow-xl bg-white">

            <div class="text-center mb-6">
                <div class="flex justify-center mb-3">
                    <a href="/">
                        <img src="{{ asset('tooth_logo.ico') }}" alt="Dr. Phua's Dental Clinic" class="h-14 w-auto">
                    </a>
                </div>

                <h2 class="text-xl font-bold text-slate-900 uppercase tracking-wide">
                    Admin Portal
                </h2>

                <p class="text-slate-500 text-xs">
                    Authorized Access Only
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 custom-error-box">
                    <div class="custom-error-header">Whoops! Something went wrong.</div>
                    <ul class="custom-error-list">
                        @foreach ($errors->all() as $error)
                            <li class="custom-error-list-item">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <x-label for="email" value="{{ __('Super Admin Email') }}" class="text-xs text-slate-700"/>

                    <div class="mt-1">
                        <x-input id="email"
                                 class="block w-full login-input"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required
                                 autofocus
                                 placeholder="admin@clinic.com"/>
                    </div>
                </div>

                <div class="mb-5">
                    <x-label for="password" value="{{ __('Password') }}" class="text-xs text-slate-700"/>

                    <div class="input-icon-wrapper mt-1">
                        <x-input id="password"
                                 class="block w-full login-input"
                                 type="password"
                                 name="password"
                                 required
                                 autocomplete="current-password"
                                 placeholder="••••••••"/>
                        <i id="toggleIcon" data-lucide="eye" class="input-icon" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="flex items-center justify-start mb-6">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <x-checkbox id="remember_me"
                                    name="remember"
                                    class="rounded-sm border-slate-300 text-blue-600 focus:ring-blue-500 h-3 w-3"/>
                        <span class="ms-2 text-xs text-slate-600">
                            {{ __('Keep me logged in') }}
                        </span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-md transition duration-200 flex items-center justify-center gap-1.5 shadow">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    {{ __('Secure Login') }}
                </button>

                @if (Route::has('password.request'))
                    <div class="text-center mt-5">
                        <a class="underline text-xs text-slate-500 hover:text-blue-600 transition font-medium"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot your admin password?') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword(){
            const password = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");

            if(password.type === "password"){
                password.type = "text";
                icon.setAttribute("data-lucide","eye-off");
            } else {
                password.type = "password";
                icon.setAttribute("data-lucide","eye");
            }
            lucide.createIcons();
        }
    </script>
</x-guest-layout>