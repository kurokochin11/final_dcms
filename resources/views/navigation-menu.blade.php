<nav x-data="{ open: false }" class="bg-[#6495ed] border-b border-blue-400 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('tooth_logo.ico') }}" alt="Logo" class="block h-9 w-auto brightness-0 invert" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white hover:text-blue-100 border-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
             
            <div class="ms-3 relative flex items-center">
                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button class="relative p-2 text-white hover:bg-[#3659c7] rounded-full transition focus:outline-none">
                                <i class="fa fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-green-500 rounded-full border-2 border-[#4169e1]">
                                    4
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="w-64">
                                <div class="block px-4 py-2 text-sm font-semibold text-gray-700 border-b border-gray-100 text-center">
                                    {{ __('You have 4 new notifications') }}
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    <x-dropdown-link href="#" class="flex items-center space-x-3">
                                        <div class="bg-blue-500 p-2 rounded-full text-white"><i class="fa fa-user-plus text-xs"></i></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">New user registered</p>
                                            <p class="text-xs text-gray-500">5 mins ago</p>
                                        </div>
                                    </x-dropdown-link>
                                </div>
                                <a href="#" class="block py-2 text-sm text-center text-blue-600 font-bold hover:underline border-t border-gray-100">See all</a>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            
            <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#6495ed] hover:bg-[#4169e1] focus:outline-none transition ease-in-out duration-150">
                                    {{ Auth::user()->name }}
                                    <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>
                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-[#3659c7] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>