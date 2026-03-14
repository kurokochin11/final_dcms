

<!-- KaiAdmin Main CSS (includes Bootstrap) -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
 <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
  <!-- JS -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
     <script src="assets/js/kaiadmin.min.js"></script>
    <script>
$(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true
    });
});
</script>



<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">
            {{ __('Patient Management') }}
        </h2>
    </x-slot>
    
    <div class="py-6" x-data="{ openAdd:false, openViewId:null, openEditId:null, openDeleteId:null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                <!-- Add Patient Button -->
                <div class="flex justify-end mb-4">
                    <x-button @click="openAdd = true" class="btn btn-primary">  <i class="fa fa-plus me-1"></i>  New Patient</x-button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="myTable"  class="table table-striped table-bordered table-hover">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                <th class="px-6 py-3 border border-gray-300">Patient No</th>
                <th class="px-6 py-3 border border-gray-300">Name</th>
                <th class="px-6 py-3 border border-gray-300">Sex</th>
                <th class="px-6 py-3 border border-gray-300">Status</th>
                <th class="px-6 py-3 border border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                <td class="px-6 py-3 border border-gray-300 font-bold"> {{ $loop->iteration }}</td>
                <td class="px-6 py-3 border border-gray-300">{{ $patient->last_name }}, {{ $patient->first_name }} @if($patient->middle_name), {{ $patient->middle_name }}@endif</td>
                <td class="px-6 py-3 border border-gray-300">{{ $patient->sex }}</td>
              <td class="px-6 py-3 border border-gray-300">
    @php
        $medical = $patient->medicalAnswers->count() > 0;
        $checkup = $patient->checkupAnswers->count() > 0;
    @endphp

     @if($medical && $checkup)
        <span class="badge bg-success">Interviewed for Medical & Dental</span>
    @elseif($medical)
        <span class="badge bg-primary">Medical Interviewed</span>
    @elseif($checkup)
        <span class="badge bg-warning text-dark">Dental Interviewed</span>
    @else
        <span class="badge bg-danger">Not Interviewed</span>
    @endif
      
</td>           
 <td class="px-4 py-2 border" style="text-align:left;">
         <div class="d-flex gap-1 align-items-center">
            <button class="btn btn-primary btn-medium" @click="openViewId={{ $patient->id }}"> <i class="fas fa-eye"></i></button>
            <button class="btn btn-warning text-white btn-medium" @click="openEditId={{ $patient->id }}"> <i class="fas fa-edit"></i></button>
            <button class="btn btn-danger btn-medium" @click="openDeleteId={{ $patient->id }}"> <i class="fas fa-trash"></i></button>
<div class="dropup">
    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $patient->id }}" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-stethoscope"></i>
    </button>
    <ul class="dropdown-menu shadow-sm" aria-labelledby="dropdownMenuButton{{ $patient->id }}">
        <li>
            <a class="dropdown-item" href="{{ route('medical-history.index', $patient->id) }}">
                Medical Check Up
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="{{ route('check-up.checkup_index', $patient->id) }}">
                Dental Check Up
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="{{ route('dental-chart.index', $patient->id) }}">
                Dental Chart
            </a>
        </li>
    </ul>
</div>
 </td>
</tr>
</div>
<!-- VIEW MODAL -->
<div
    x-show="openViewId === {{ $patient->id }}"
    x-cloak
    class="fixed inset-0 black/40 backdrop-blur-sm flex items-center justify-center z-50 bg-black/40">

    <!-- MODAL BOX -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl"
        @click.outside="openViewId = null"
    >

        <!-- HEADER -->
        <div class="px-6 py-4 flex justify-between items-center bg-blue-600 text-white rounded-t-xl">
            <div>
                <h2 class="text-lg font-bold">
                    {{ $patient->nickname ?? $patient->first_name }}
                    {{ $patient->middle_name }}
                    {{ $patient->last_name }}
                </h2>
                <p class="text-sm opacity-80">Patient Information</p>
            </div>

            <button
                @click="openViewId = null"
                class="text-white hover:text-gray-200 text-2xl font-bold leading-none"
            >
                &times;
            </button>
        </div>

        <!-- BODY -->
        <div class="p-6 overflow-y-auto max-h-[70vh] text-sm text-gray-700 dark:text-gray-200 space-y-6">

            <!-- GENERAL INFO -->
            <div>
                <h3 class="text-base font-semibold text-blue-600 mb-3">
                    General Information
                </h3>

                <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                    <p><span class="font-semibold">Sex:</span> {{ $patient->sex }}</p>
                    <p><span class="font-semibold">Age:</span> {{ $patient->age }}</p>
                   <p><span class="font-semibold">Birth Date:</span>{{ $patient->date_of_birth->format('F d, Y') }}</p>
                   <p><span class="font-semibold">Date Registered:</span>{{ $patient->date_registered->format('F d, Y') }}</p>


                    <p><span class="font-semibold">Civil Status:</span> {{ $patient->civil_status ?? '-' }}</p>
                    <p><span class="font-semibold">Nationality:</span> {{ $patient->nationality ?? '-' }}</p>

                    <p><span class="font-semibold">Religion:</span> {{ $patient->religion ?? '-' }}</p>
                    <p><span class="font-semibold">Occupation:</span> {{ $patient->occupation ?? '-' }}</p>

                    <p class="col-span-2">
                        <span class="font-semibold">Address:</span>
                        {{ $patient->address ?? '-' }}
                    </p>

                    <p><span class="font-semibold">City:</span> {{ $patient->city ?? '-' }}</p>
                    <p><span class="font-semibold">Province:</span> {{ $patient->province ?? '-' }}</p>

                    <p><span class="font-semibold">Zip Code:</span> {{ $patient->zip_code ?? '-' }}</p>
                    <p><span class="font-semibold">Mobile:</span> {{ $patient->mobile_number ?? '-' }}</p>

                    <p><span class="font-semibold">Landline:</span> {{ $patient->landline_number ?? '-' }}</p>
                    <p><span class="font-semibold">Email:</span> {{ $patient->email ?? '-' }}</p>

                    <p class="col-span-2">
                        <span class="font-semibold">Referred By:</span>
                        {{ $patient->referred_by ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- EMERGENCY CONTACT -->
            <div>
                <h3 class="text-base font-semibold text-blue-600 mb-3">
                    Emergency Contact
                </h3>

                <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                    <p>
                        <span class="font-semibold">Name:</span>
                        {{ $patient->emergencyContact->full_name ?? '-' }}
                    </p>
                    <p>
                        <span class="font-semibold">Relationship:</span>
                        {{ $patient->emergencyContact->relationship ?? '-' }}
                    </p>
                    <p>
                        <span class="font-semibold">Mobile:</span>
                        {{ $patient->emergencyContact->mobile_number ?? '-' }}
                    </p>
                    <p>
                        <span class="font-semibold">Landline:</span>
                        {{ $patient->emergencyContact->landline_number ?? '-' }}
                    </p>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="px-6 py-3 border-t bg-gray-50 dark:bg-gray-700 flex justify-end">
        
            <button
                type="button"
                @click="openViewId = null"
                class="btn btn-dark btn-sm"
            >
                Close
            </button>
        </div>

    </div>
</div>

<!-- EDIT MODAL -->

<div x-show="openEditId === {{ $patient->id }}" x-cloak
     {{-- Initializing data here ensures "editTab" and "totalEditTabs" are defined for all child elements --}}
     x-data="{ editTab: 1, totalEditTabs: 4 }" 
     class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="relative bg-white rounded-xl w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl border border-gray-200 flex flex-col">

        <div class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
            <div>
                <h2 class="text-xl font-bold">Edit Patient Record</h2>
                <p class="text-xs text-blue-100 opacity-80">Updating: {{ $patient->first_name }} {{ $patient->last_name }}</p>
            </div>
            <button type="button" class="text-white hover:rotate-90 transition-transform text-3xl font-light leading-none" @click="openEditId = null">&times;</button>
        </div>

        <form method="POST" action="{{ route('patients.update', $patient->id) }}" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="px-8 pt-6 pb-2 bg-gray-50 border-b">
                <div class="flex items-center justify-between mb-2">
                    <template x-for="i in totalEditTabs">
                        <div class="flex-1 flex items-center">
                            <div class="h-2 flex-1 rounded-full transition-colors duration-500" 
                                 :class="editTab >= i ? 'bg-blue-600' : 'bg-gray-200'"></div>
                            <div x-show="i < totalEditTabs" class="w-2"></div>
                        </div>
                    </template>
                </div>
                <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                    <span :class="editTab === 1 ? 'text-blue-600' : ''">Basic Info</span>
                    <span :class="editTab === 2 ? 'text-blue-600' : ''">Address</span>
                    <span :class="editTab === 3 ? 'text-blue-600' : ''">Contact</span>
                    <span :class="editTab === 4 ? 'text-blue-600' : ''">Emergency</span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-8 bg-white">
                
                <div x-show="editTab === 1" x-transition.opacity class="grid grid-cols-2 gap-x-6 gap-y-4">
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ $patient->last_name }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" required>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ $patient->first_name }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" required>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ $patient->middle_name }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ $patient->date_of_birth }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Age <span class="text-red-500">*</span></label>
                        <input type="number" name="age" value="{{ $patient->age }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sex <span class="text-red-500">*</span></label>
                        <select name="sex" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>
                            <option value="Male" {{ $patient->sex == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $patient->sex == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Civil Status</label>
                        <select name="civil_status" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                            <option value="Single" {{ $patient->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ $patient->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ $patient->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ $patient->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nationality</label>
                        <input type="text" name="nationality" value="{{ $patient->nationality }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Occupation</label>
                        <input type="text" name="occupation" value="{{ $patient->occupation }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Religion</label>
                        <input type="text" name="religion" value="{{ $patient->religion }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                    </div>
                </div>

                <div x-show="editTab === 2" x-transition.opacity class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" value="{{ $patient->address }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">City</label>
                            <input type="text" name="city" value="{{ $patient->city }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Province</label>
                            <input type="text" name="province" value="{{ $patient->province }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                        </div>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Zip Code</label>
                        <input type="text" name="zip_code" value="{{ $patient->zip_code }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                    </div>
                </div>

                <div x-show="editTab === 3" x-transition.opacity class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                            <input type="text" name="mobile_number" value="{{ $patient->mobile_number }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Landline (Optional)</label>
                            <input type="text" name="landline_number" value="{{ $patient->landline_number }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ $patient->email }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-blue-500" required>
                    </div>
                </div>

                <div x-show="editTab === 4" x-transition.opacity class="space-y-5">
                    <div class="p-0 mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Referred By</label>
                        <input type="text" name="referred_by" value="{{ $patient->referred_by }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="p-3 bg-blue-50 rounded-md border border-blue-100">
                        <p class="text-xs text-blue-700 font-semibold">Who should we contact in case of an emergency?</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Contact Person Name <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_full_name" value="{{ $patient->emergencyContact->full_name ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Relationship</label>
                            <input type="text" name="emergency_relationship"  value="{{ $patient->emergencyContact->relationship ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Emergency Mobile <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_mobile" value="{{ $patient->emergencyContact->mobile_number ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>

                        </div>
                          <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Emergency Landline <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_landline"  value="{{ $patient->emergencyContact->landline_number ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t flex justify-between items-center">
                <div class="w-1/4">
                    <button type="button" x-show="editTab > 1" @click="editTab--" class="flex items-center text-gray-600 hover:text-gray-900 font-bold transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                </div>

                <div class="text-sm font-bold text-gray-400">
                    Step <span x-text="editTab" class="text-blue-600"></span> of 4
                </div>

                <div class="w-1/4 flex justify-end">
                    <button type="button" x-show="editTab < totalEditTabs" @click="editTab++" class="px-8 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition shadow-md flex items-center">
                        Next <i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <button type="submit" x-show="editTab === totalEditTabs" class="px-8 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold transition shadow-md flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> Update Patient
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- DELETE MODAL -->
<div x-show="openDeleteId === {{ $patient->id }}"
     x-cloak
     class="fixed inset-0 black/40 backdrop-blur-sm flex items-center justify-center z-50 bg-black/40">

    <div class="bg-white rounded-lg shadow-lg w-1/3 overflow-hidden border border-gray-200">

        <!-- HEADER -->
        <div class="bg-red-600 text-white px-6 py-4">
            <h2 class="text-lg font-semibold">Delete Patient</h2>
        </div>

        <!-- BODY -->
        <div class="p-6 text-center">
            <p class="mb-4 text-gray-700">
                Are you sure you want to delete
                <b>{{ $patient->first_name }} {{ $patient->last_name }}</b>?
            </p>

            <!-- ACTIONS -->
            <form method="POST"
                  action="{{ route('patients.destroy', $patient->id) }}"
                  class="flex justify-center gap-3 mt-6">
                @csrf
                @method('DELETE')

                <button type="button"
                        @click="openDeleteId = null"
                        class="btn btn-black px-4 py-2 rounded">
                    Cancel
                </button>

                <button type="submit"
                        class="btn btn-danger px-4 py-2 rounded">
                    Delete
                </button>
            </form>
        </div>

    </div>
</div>
@endforeach
     </tbody>
   </table>
     </div>
        <div class="mt-4">
        {{ $patients->links() }}
            </div>
            
<!-- ADD MODAL -->
               <div x-show="openAdd" x-cloak
     class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="relative bg-white rounded-xl w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl border border-gray-200 flex flex-col">

        <div class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
            <div>
                <h2 class="text-xl font-bold">Registration</h2>
                <p class="text-xs text-blue-100 opacity-80">Fill in all required fields to add a new patient record.</p>
            </div>
            <button type="button" class="text-white hover:rotate-90 transition-transform text-3xl font-light leading-none" @click="openAdd = false">&times;</button>
        </div>

        <form method="POST" action="{{ route('patients.store') }}" x-data="{ tab: 1, totalTabs: 4 }" class="flex flex-col flex-1 overflow-hidden">
            @csrf

            <div class="px-8 pt-6 pb-2 bg-gray-50 border-b">
                <div class="flex items-center justify-between mb-2">
                    <template x-for="i in totalTabs">
                        <div class="flex-1 flex items-center">
                            <div class="h-2 flex-1 rounded-full transition-colors duration-500" 
                                 :class="tab >= i ? 'bg-blue-600' : 'bg-gray-200'"></div>
                            <div x-show="i < totalTabs" class="w-2"></div>
                        </div>
                    </template>
                </div>
                <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                    <span :class="tab === 1 ? 'text-blue-600' : ''">Basic Info</span>
                    <span :class="tab === 2 ? 'text-blue-600' : ''">Address</span>
                    <span :class="tab === 3 ? 'text-blue-600' : ''">Contact</span>
                    <span :class="tab === 4 ? 'text-blue-600' : ''">Emergency</span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-8 bg-white">
                
                <div x-show="tab === 1" x-transition.opacity class="grid grid-cols-2 gap-x-6 gap-y-4">
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" placeholder="Enter Last Name" required>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" placeholder="Enter First Name" required>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="Enter Middle Name">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Age <span class="text-red-500">*</span></label>
                        <input type="number" name="age" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="0" required>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sex <span class="text-red-500">*</span></label>
                        <select name="sex" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                             <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Civil Status</label>
                        <select name="civil_status" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border">
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nationality</label>
                        <input type="text" name="nationality" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="Filipino">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Occupation</label>
                        <input type="text" name="occupation" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="e.g. Engineer">
                    </div>
                   <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Religion</label>
                        <input type="text" name="religion" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="Religion">
                    </div>
                </div>


                <div x-show="tab === 2" x-transition.opacity class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="House No. / Street Name" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">City</label>
                            <input type="text" name="city" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="Cebu City">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Province</label>
                            <input type="text" name="province" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="Cebu">
                        </div>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Zip Code</label>
                        <input type="text" name="zip_code" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="6000">
                    </div>
                </div>

              <div x-show="tab === 3" x-transition.opacity class="space-y-5" x-data="{ 
    mobile: '', 
    email: '',
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    isValidMobile(num) {
        return /^09\d{9}$/.test(num); // Validates 09 + 9 digits
    }
}">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">
                Mobile Number <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="mobile_number" 
                   x-model="mobile"
                   @input="mobile = mobile.replace(/[^0-9]/g, '').slice(0, 11)"
                   class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-blue-500" 
                   placeholder="09123456789" 
                   pattern="09[0-9]{9}"
                   title="Please enter a valid 11-digit mobile number starting with 09"
                   required>
            <p x-show="mobile.length > 0 && !isValidMobile(mobile)" class="text-xs text-red-500 mt-1">Must be 11 digits starting with 09</p>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Landline (Optional)</label>
            <input type="text" 
                   name="landline_number" 
                   class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border"
                   @input="$el.value = $el.value.replace(/[^0-9-]/g, '')"
                   placeholder="032-1234567">
        </div>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">
            Email Address <span class="text-red-500">*</span>
        </label>
        <input type="email" 
               name="email" 
               x-model="email"
               class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-blue-500" 
               placeholder="patient@example.com" 
               required>
        <p x-show="email.length > 0 && !isValidEmail(email)" class="text-xs text-red-500 mt-1">Please enter a valid email address</p>
    </div>
</div>
                <div x-show="tab === 4" x-transition.opacity class="space-y-5">
                <div class="p-0 mb-4">
    <label class="block text-sm font-bold text-gray-700 mb-1">Referred By</label>
    <input type="text" name="referred_by" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-blue-500 focus:border-blue-500" placeholder="Name of Doctor or Patient who referred">
</div>
                    <div class="p-3 bg-blue-50 rounded-md border border-blue-100">
                        <p class="text-xs text-blue-700 font-semibold">Who should we contact in case of an emergency?</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Contact Person Name <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_full_name" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="Full Name" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Relationship</label>
                            <input type="text" name="emergency_relationship" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border" placeholder="e.g. Spouse" required>
                        </div>
                       <div x-data="{ 
    eMobile: '', 
    isValidEMobile(num) {
        return /^09\d{9}$/.test(num);
    }
}">
    <label class="block text-sm font-bold text-gray-700 mb-1">
        Emergency Mobile <span class="text-red-500">*</span>
    </label>
    
    <input type="text" 
           name="emergency_mobile" 
           x-model="eMobile"
           @input="eMobile = eMobile.replace(/[^0-9]/g, '').slice(0, 11)"
           class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border transition focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
           :class="eMobile.length > 0 && !isValidEMobile(eMobile) ? 'border-red-500 bg-red-50' : 'border-gray-300'"
           placeholder="09XXXXXXXXX" 
           maxlength="11"
           required>

    <div x-show="eMobile.length > 0 && !isValidEMobile(eMobile)" 
         x-transition 
         class="text-xs text-red-600 mt-1 font-semibold flex items-center">
        <i class="fas fa-exclamation-circle mr-1"></i> 
        Must start with 09 and be 11 digits
    </div>
</div>
                    </div>
                </div>

            </div>

            <div class="p-6 bg-gray-50 border-t flex justify-between items-center">
                <div class="w-1/4">
                    <button type="button" 
                            x-show="tab > 1" 
                            @click="tab--" 
                            class="flex items-center text-gray-600 hover:text-gray-900 font-bold transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                </div>

                <div class="text-sm font-bold text-gray-400">
                    Step <span x-text="tab" class="text-blue-600"></span> of 4
                </div>

                <div class="w-1/4 flex justify-end">
                    <button type="button" 
                            x-show="tab < totalTabs" 
                            @click="tab++" 
                            class="px-8 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition shadow-md flex items-center">
                        Next <i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <button type="submit" 
                            x-show="tab === totalTabs" 
                            class="px-8 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold transition shadow-md flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> Save Patient
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</x-app-layout> 