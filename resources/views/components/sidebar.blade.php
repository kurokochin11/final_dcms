<aside class="sidebar bg-primary w-64 min-h-screen shadow-lg flex flex-col fixed left-0 top-0 z-50">
    <!-- Logo -->
    <div class="p-4 flex items-center border-b border-blue-400/30">
        <img src="{{ asset('tooth_logo.ico') }}" class="h-8 w-auto brightness-0 invert" alt="Logo">
        <span class="ms-3 text-lg font-bold text-white uppercase tracking-wider">Dental Clinic</span>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 sidebar-menu">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center p-2 mb-2 rounded transition-colors duration-200
           {{ request()->routeIs('dashboard') 
                ? 'bg-white/30 font-bold text-white' 
                : 'font-semibold text-white hover:bg-white/20' }}">
            <i class="fas fa-home w-5"></i>
            <span class="ms-2">Dashboard</span>
        </a>

      

        <!-- Patient Management Header -->
        <div class="mt-4 mb-2 text-white font-bold uppercase tracking-wide text-xs px-2">Patient Management</div>

        <!-- Patients -->
        <a href="{{ route('patients.index') }}"
           class="flex items-center p-2 mb-2 rounded transition-colors duration-200
           {{ request()->routeIs('patients.*') 
                ? 'bg-white/30 font-bold text-white' 
                : 'font-semibold text-white hover:bg-white/20' }}">
            <i class="fas fa-user w-5"></i>
            <span class="ms-2">Patients</span>
        </a>

        <!-- Appointments -->
        <a href="{{ route('appointments.index') }}"
           class="flex items-center p-2 mb-2 rounded transition-colors duration-200
           {{ request()->routeIs('appointments.*') 
                ? 'bg-white/30 font-bold text-white' 
                : 'font-semibold text-white hover:bg-white/20' }}">
            <i class="fas fa-calendar-check w-5"></i>
            <span class="ms-2">Appointments</span>
        </a>

        <!-- Treatment Plans -->
        <a href="{{ route('treatment-plans.index') }}"
   class="flex items-center p-2 mb-2 rounded transition-colors duration-200
   {{ request()->routeIs('treatment-plans.index') 
        ? 'bg-white/30 font-bold text-white' 
        : 'font-semibold text-white hover:bg-white/20' }}">
    <i class="fas fa-clipboard-list w-5"></i>
    <span class="ms-2">Treatment Plan</span>
</a>
        
        <!-- Examinations Header -->
        <div class="mt-4 mb-2 text-white font-bold uppercase tracking-wide text-xs px-2">Examinations</div>

        <!-- Medical History -->
        <a href="{{ route('medical-history.answer_index') }}"
           class="flex items-center p-2 mb-2 rounded transition-colors duration-200
           {{ request()->routeIs('medical-history.*') 
                ? 'bg-white/30 font-bold text-white' 
                : 'font-semibold text-white hover:bg-white/20' }}">
            <i class="fas fa-notes-medical w-5"></i>
            <span class="ms-2">Medical History</span>
        </a>

        <!-- Check-up -->
        <a href="{{ route('check-up.checkup_answer_index') }}"
           class="flex items-center p-2 mb-2 rounded transition-colors duration-200
           {{ request()->routeIs('check-up.*') 
                ? 'bg-white/30 font-bold text-white' 
                : 'font-semibold text-white hover:bg-white/20' }}">
            <i class="fas fa-stethoscope w-5"></i>
            <span class="ms-2">Check-up</span>
        </a>

       
        <!-- Oral Examination Collapse -->
        <div x-data="{ open: {{ request()->routeIs('oral_examination.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="flex items-center w-full p-2 mb-2 rounded transition-colors duration-200
                    {{ request()->routeIs('oral_examination.*') 
                         ? 'bg-white/30 font-bold text-white' 
                         : 'font-semibold text-white hover:bg-white/20' }}">
                <i class="fas fa-tooth w-5"></i>
                <span class="ms-2 flex-1 text-left">Oral Examination</span>
                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
            </button>
            <div x-show="open" class="pl-7 mt-1 space-y-1">
                <a href="{{ route('oral_examination.index_extraoral') }}"
                   class="block p-2 rounded transition-colors duration-200
                   {{ request()->routeIs('oral_examination.index_extraoral') 
                        ? 'bg-white/20 font-bold text-white' 
                        : 'font-semibold text-white hover:bg-white/10' }}">
                    Extra Oral
                </a>
                <a href="{{ route('oral_examination.index_intraoral') }}"
                   class="block p-2 rounded transition-colors duration-200
                   {{ request()->routeIs('oral_examination.index_intraoral') 
                        ? 'bg-white/20 font-bold text-white' 
                        : 'font-semibold text-white hover:bg-white/10' }}">
                    Intra Oral
                </a>
            </div>
        </div>

        <!-- Other Header -->
        <div class="mt-4 mb-2 text-white font-bold uppercase tracking-wide text-xs px-2">Other</div>

        <!-- Radiographs -->
        <a href="{{ route('radiographs.index') }}"
           class="flex items-center p-2 mb-2 rounded transition-colors duration-200
           {{ request()->routeIs('radiographs.*') 
                ? 'bg-white/30 font-bold text-white' 
                : 'font-semibold text-white hover:bg-white/20' }}">
            <i class="fas fa-x-ray w-5"></i>
            <span class="ms-2">Radiographs</span>
        </a>

        <!-- Diagnosis -->
        <a href="{{ route('diagnoses.index') }}"
           class="flex items-center p-2 mb-2 rounded transition-colors duration-200
           {{ request()->routeIs('diagnoses.*') 
                ? 'bg-white/30 font-bold text-white' 
                : 'font-semibold text-white hover:bg-white/20' }}">
            <i class="fas fa-file-medical-alt w-5"></i>
            <span class="ms-2">Diagnosis</span>
        </a>
         <!-- User Management -->
<div class="mt-4 mb-2 text-white font-bold uppercase tracking-wide text-xs px-2">User Management</div>

<a href="{{ route('users.index') }}"
   class="flex items-center p-2 mb-2 rounded transition-colors duration-200
   {{ request()->routeIs('users.*') 
        ? 'bg-white/30 font-bold text-white' 
        : 'font-semibold text-white hover:bg-white/20' }}">
    <i class="fas fa-users-cog w-5"></i>
    <span class="ms-2">Users</span>
</a>
<!-- Billing -->
<a href="{{ route('billings.index') }}"
   class="flex items-center p-2 mb-2 rounded transition-colors duration-200
   {{ request()->routeIs('billings.*') 
        ? 'bg-white/30 font-bold text-white' 
        : 'font-semibold text-white hover:bg-white/20' }}">

    <!-- Icon -->
    <i class="fas fa-file-invoice-dollar w-5"></i>

    <span class="ms-2">Billing</span>
</a>

    </nav>
</aside>
