<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-50 to-indigo-100">
        
        <x-authentication-card>
            <x-slot name="logo">
                <a href="/">
                    <div class="p-3 bg-white rounded-full shadow-md ring-4 ring-indigo-50 border border-indigo-100 transition hover:scale-105 duration-300">
                        <img src="{{ asset('tooth_logo.ico') }}" alt="Logo" class="w-16 h-16 object-contain" />
                    </div>
                </a>
            </x-slot>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="block">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4" x-data="{ show: false }">
                    <x-label for="password" value="{{ __('New Password') }}" />
                    <div class="relative">
                        <x-input id="password" 
                                 class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-10" 
                                 x-bind:type="show ? 'text' : 'password'" 
                                 name="password" 
                                 required 
                                 autocomplete="new-password" />
                        
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex class items-center text-gray-400 hover:text-indigo-600 focus:outline-none">
                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-4" x-data="{ show: false }">
                    <x-label for="password_confirmation" value="{{ __('Confirm New Password') }}" />
                    <div class="relative">
                        <x-input id="password_confirmation" 
                                 class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-10" 
                                 x-bind:type="show ? 'text' : 'password'" 
                                 name="password_confirmation" 
                                 required 
                                 autocomplete="new-password" />
                        
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 focus:outline-none">
                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 focus:ring-indigo-500 shadow-md transition-all">
                        {{ __('Reset Password') }}
                    </x-button>
                </div>
            </form>
        </x-authentication-card>
    </div>
</x-guest-layout>