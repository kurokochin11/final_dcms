@section('title', 'Admin Login | Dr. Phua\'s Dental Clinic')

<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

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
        }

        .custom-error-header {
            font-size: 0.9rem;
            color: #d11222;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .auth-card-white {
            width: 380px !important;
            border-radius: 12px;
            padding: 2.5rem;
            z-index: 10;
        }

        .auth-wrapper {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)),
                url('{{ asset('auth_logo.jfif') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .login-input {
            border-radius: 6px !important;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            font-size: 0.9rem;
            padding-left: 10px;
            padding-right: 35px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .input-icon svg {
            transition: all 0.2s ease;
        }

        .input-icon:hover svg {
            stroke: #2563eb;
        }
    </style>

    <div class="auth-wrapper">
        <div class="auth-card-white shadow-xl bg-white">

            <!-- Logo -->
            <div class="text-center mb-6">
                <div class="flex justify-center mb-3">
                    <a href="/">
                        <img src="{{ asset('tooth_logo.ico') }}" class="h-14">
                    </a>
                </div>

                <h2 class="text-xl font-bold text-slate-900 uppercase">
                    Admin Portal
                </h2>

                <p class="text-slate-500 text-xs">
                    Authorized Access Only
                </p>
            </div>

            <!-- Errors -->
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

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <x-label for="email" value="Super Admin Email" class="text-xs"/>

                    <x-input id="email"
                             class="block w-full login-input mt-1"
                             type="email"
                             name="email"
                             :value="old('email')"
                             required autofocus
                             placeholder="Email"/>
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <x-label for="password" value="Password" class="text-xs"/>

                    <div class="input-icon-wrapper mt-1">
                        <x-input id="password"
                                 class="block w-full login-input"
                                 type="password"
                                 name="password"
                                 required
                                 placeholder="Password"/>

                        <!-- ICON TOGGLE -->
                        <span id="toggleIcon" class="input-icon" onclick="togglePassword()">
                            
                            <!-- Eye Open (SHOW password) -->
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                                width="18" height="18" fill="none" stroke="#6b7280"
                                stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                            <!-- Eye Slash (HIDE password - DEFAULT) -->
                            <svg id="eyeSlash" xmlns="http://www.w3.org/2000/svg"
                                width="18" height="18" fill="none" stroke="#6b7280"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8"/>
                                <path d="M1 1l22 22"/>
                            </svg>

                        </span>
                    </div>
                </div>

                <!-- Remember -->
                <div class="flex items-center mb-6">
                    <label class="flex items-center cursor-pointer">
                        <x-checkbox name="remember" class="h-3 w-3"/>
                        <span class="ms-2 text-xs">Keep me logged in</span>
                    </label>
                </div>

                <!-- Button -->
                <button type="submit"
                        class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md flex items-center justify-center gap-1.5 shadow">
                    Secure Login
                </button>

                <!-- Forgot -->
                @if (Route::has('password.request'))
                    <div class="text-center mt-5">
                        <a class="underline text-xs text-slate-500 hover:text-blue-600"
                           href="{{ route('password.request') }}">
                            Forgot your admin password?
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        function togglePassword(){
            const password = document.getElementById("password");
            const eyeOpen = document.getElementById("eyeOpen");
            const eyeSlash = document.getElementById("eyeSlash");

            if(password.type === "password"){
                // SHOW PASSWORD
                password.type = "text";
                eyeOpen.style.display = "block";
                eyeSlash.style.display = "none";
            } else {
                // HIDE PASSWORD
                password.type = "password";
                eyeOpen.style.display = "none";
                eyeSlash.style.display = "block";
            }
        }
    </script>

</x-guest-layout>
