<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
        
        <x-authentication-card>
            <x-slot name="logo">
                <a href="/">
                    <div class="p-3 bg-white rounded-full shadow-md ring-4 ring-indigo-50 border border-indigo-100 transition hover:scale-105 duration-300">
                        <img src="{{ asset('tooth_logo.ico') }}" alt="Logo" class="w-16 h-16 object-contain" />
                    </div>
                </a>
            </x-slot>

            <div class="mb-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="block">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 focus:ring-indigo-500 transition-colors">
                        {{ __('Email Password Reset Link') }}
                    </x-button>
                </div>
            </form>
        </x-authentication-card>
    </div>
</x-guest-layout>