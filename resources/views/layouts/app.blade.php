<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
 
        <link rel="icon" type="image/x-icon" href="{{ asset('tooth_logo.ico') }}?v=1"> 

    <title>
        @hasSection('title') 
            @yield('title') | 
        @endif 
        {{ config('app.name') }}
    </title>
        
<!-- Fonts and icons KaiAdmin Bootstrap -->
<script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
<script>
  WebFont.load({
    google: { families: ["Public Sans:300,400,500,600,700"] },
    custom: {
      families: [
        "Font Awesome 5 Solid",
        "Font Awesome 5 Regular",
        "Font Awesome 5 Brands",
        "simple-line-icons",
      ],
      urls: ["{{ asset('assets/css/fonts.min.css') }}"],
    },
    active: function () {
      sessionStorage.fonts = true;
    },
  });
</script>



        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
     
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />
<!-- {{-- SIDEBAR --}} -->
    @include('components.sidebar')
    
        <div class="min-h-screen bg-gray-100">
            <x-sidebar />
            <div class="flex-1 ml-64">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
        
<!-- notification -->
    <script>
        window.notifications = @json($todayScheduledAppointments ?? []);
    </script>

        @livewireScripts
    </body>
</html>
