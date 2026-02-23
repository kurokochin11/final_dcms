@section('title', 'Dental Login')
<x-guest-layout>
    
<!-- CSS for authentication pages -->

    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

    <div class="auth-wrapper">
        <div class="auth-card-white">
            <h2 class="auth-page-title-blue">Login</h2>
          <div class="flex justify-center mb-6">
                <a href="/">
                    <img src="{{ asset('tooth_logo.ico') }}" alt="Dr. Phua's Dental Clinic" class="h-32 w-auto">
                </a>
            </div>
            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <x-label for="email" value="{{ __('Email Address') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                </div>

                <div class="mb-5">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary-blue">
                    {{ __('Log in') }}
                </button>

                @if (Route::has('password.request'))
                    <div class="text-center mt-4">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-guest-layout>