<x-sidebar/>
<x-app-layout>
    <x-slot name="header">
       <div class="welcome-bar">
    <div class="welcome-track">
        <span>
            Welcome to your Dashboard, <strong>{{ Auth::user()->name }}</strong>!
        </span>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome 
                :totalPatients="$totalPatients" 
                    :totalAppointments="$totalAppointments"
                    :todayScheduledAppointments="$todayAppointments"
                    :diagnoses="$diagnoses"
                    :treatmentPlans="$treatmentPlans"
                    :radiographs="$radiographs"
                    :extraoralExaminations="$extraoralExaminations"
                    :intraoralExaminations="$intraoralExaminations"
                    :medicalhistory="$medicalhistory"
                    :checkup="$checkup"
                />
            </div>
        </div>
    </div>
</x-app-layout>
