

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

@section('title', 'Patient Management')
<x-sidebar/>
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
            <x-button class="btn btn-primary btn-xs" @click="openViewId={{ $patient->id }}"> <i class="fas fa-eye"></i></x-button>
            <x-button class="btn btn-warning btn-xs" @click="openEditId={{ $patient->id }}"> <i class="fas fa-edit"></i></x-button>
            <x-danger-button class="btn btn-danger btn-xs" @click="openDeleteId={{ $patient->id }}"> <i class="fas fa-trash"></i></x-danger-button>
             <div class="dropdown">
    <button class="btn btn-secondary btn-xs dropdown-toggle" type="button" id="dropdownMenuButton{{ $patient->id }}" data-bs-toggle="dropdown" aria-expanded="false">
        Interview
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $patient->id }}">
        <li>
            <a class="dropdown-item" href="{{ route('medical-history.index', $patient->id) }}">
                Medical Check Up
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('check-up.checkup_index', $patient->id) }}">
                Dental Check Up
            </a>
        </li>
        
        </li>
    </ul>
</div>
 </td>
</tr>
</div>
                   <!-- VIEW MODAL -->
<div x-show="openViewId === {{ $patient->id }}" x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded w-1/2 max-h-[90vh] overflow-hidden shadow-lg relative">

        <!-- Sticky Header with Close -->
        <div class="bg-gray-100 px-6 py-3 flex justify-between items-center sticky top-0 z-10 border-b">
            <h2 class="text-lg font-bold">
                {{ $patient->nickname ?? $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}
            </h2>
            <button class="text-gray-700 hover:text-red-600 text-3xl font-extrabold leading-none"@click="openViewId = null"
                    class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        </div>

        <!-- Scrollable Content -->
        <div class="p-6 overflow-y-auto max-h-[80vh] space-y-2">

            <!-- General Info -->
            <div class="mb-4">
                <h3 class="font-semibold mb-2">General Information</h3>
                <p><b>Sex:</b> {{ $patient->sex }}</p>
                <p><b>Birth Date:</b> {{ $patient->date_of_birth->format('Y-m-d') }}</p>
                <p><b>Age:</b> {{ $patient->age }}</p>
                <p><b>Date Registered:</b> {{ $patient->date_registered->format('Y-m-d') }}</p>
                <p><b>Civil Status:</b> {{ $patient->civil_status ?? '-' }}</p>
                <p><b>Nationality:</b> {{ $patient->nationality ?? '-' }}</p>
                <p><b>Religion:</b> {{ $patient->religion ?? '-' }}</p>
                <p><b>Occupation:</b> {{ $patient->occupation ?? '-' }}</p>
                <p><b>Address:</b> {{ $patient->address ?? '-' }}</p>
                <p><b>City:</b> {{ $patient->city ?? '-' }}</p>
                <p><b>Province:</b> {{ $patient->province ?? '-' }}</p>
                <p><b>Zip Code:</b> {{ $patient->zip_code ?? '-' }}</p>
                <p><b>Mobile:</b> {{ $patient->mobile_number ?? '-' }}</p>
                <p><b>Landline:</b> {{ $patient->landline_number ?? '-' }}</p>
                <p><b>Email:</b> {{ $patient->email ?? '-' }}</p>
                <p><b>Referred By:</b> {{ $patient->referred_by ?? '-' }}</p>
            </div>

            <!-- Emergency Contact -->
            <div class="mb-4">
                <h3 class="font-semibold mb-2">Emergency Contact</h3>
                <p><b>Name:</b> {{ $patient->emergencyContact->full_name ?? '-' }}</p>
                <p><b>Relationship:</b> {{ $patient->emergencyContact->relationship ?? '-' }}</p>
                <p><b>Mobile:</b> {{ $patient->emergencyContact->mobile_number ?? '-' }}</p>
                <p><b>Landline:</b> {{ $patient->emergencyContact->landline_number ?? '-' }}</p>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-light px-4 py-3 text-end border-top">
          <button type="button" class="btn btn-dark btn-medium" @click="openViewId = null">Close</button>
        </div>

    </div>
</div>
<!-- EDIT MODAL -->
<div x-show="openEditId === {{ $patient->id }}" x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="relative bg-white p-6 rounded w-1/2 max-h-[90vh] overflow-y-auto"
         x-data="{ tab: 1 }">
  
        <!-- CLOSE BUTTON -->
        <button type="button"
            class="absolute top-3 right-4 text-gray-700 hover:text-red-600 text-3xl font-extrabold leading-none"
            @click="openEditId = null">
            &times;
        </button>

        <h2 class="text-lg font-bold mb-2">Edit Patient</h2>

        <form method="POST" action="{{ route('patients.update', $patient->id) }}">
            @csrf
            @method('PUT')

            <!-- TAB BUTTONS -->
            <div class="flex border-b mb-4">
                <button type="button" @click="tab = 1"
                    :class="tab === 1 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
                    class="px-4 py-2 font-semibold hover:bg-gray-50 rounded-t">
                    Basic Info
                </button>

                <button type="button" @click="tab = 2"
                    :class="tab === 2 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
                    class="px-4 py-2 font-semibold hover:bg-gray-50 rounded-t">
                    Address
                </button>

                <button type="button" @click="tab = 3"
                    :class="tab === 3 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
                    class="px-4 py-2 font-semibold hover:bg-gray-50 rounded-t">
                    Contact
                </button>

                <button type="button" @click="tab = 4"
                    :class="tab === 4 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
                    class="px-4 py-2 font-semibold hover:bg-gray-50 rounded-t">
                    Emergency
                </button>
            </div>

            <!-- TAB CONTENT -->
           <div class="relative border rounded bg-white p-4 mb-4" style="min-height:520px">
                <!-- TAB 1: BASIC INFO -->
                <div x-show="tab === 1" class="space-y-2">
                    <label class="font-medium">Last Name</label>
                    <input type="text" name="last_name" value="{{ $patient->last_name }}" class="w-full border p-2" required>

                    <label class="font-medium">First Name</label>
                    <input type="text" name="first_name" value="{{ $patient->first_name }}" class="w-full border p-2" required>

                    <label class="font-medium">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ $patient->middle_name }}" class="w-full border p-2">

                    <label class="font-medium">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ $patient->date_of_birth->format('Y-m-d') }}" class="w-full border p-2" required>

                    <label class="font-medium">Age</label>
                    <input type="text" name="age" value="{{ $patient->age }}" class="w-full border p-2" required>

                    <label class="font-medium">Nationality</label>
                    <input type="text" name="nationality" value="{{ $patient->nationality }}" class="w-full border p-2">

                    <label class="font-medium">Religion</label>
                    <input type="text" name="religion" value="{{ $patient->religion }}" class="w-full border p-2">

                    <label class="font-medium">Occupation</label>
                    <input type="text" name="occupation" value="{{ $patient->occupation }}" class="w-full border p-2">

                    <label class="font-medium">Sex</label>
                    <select name="sex" class="w-full border p-2" required>
                        <option value="Male" {{ $patient->sex == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $patient->sex == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Prefer not to say" {{ $patient->sex == 'Prefer not to say' ? 'selected' : '' }}>
                            Prefer not to say
                        </option>
                    </select>

                    <label class="font-medium">Civil Status</label>
                    <select name="civil_status" class="w-full border p-2">
                        @foreach (['Single','Married','Widowed','Separated','Divorced','Annulled','Common-Law'] as $status)
                            <option value="{{ $status }}" {{ $patient->civil_status == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>

                    <label class="font-medium">Date Registered</label>
                    <input type="date" name="date_registered" value="{{ $patient->date_registered->format('Y-m-d') }}" class="w-full border p-2">
                </div>

                <!-- TAB 2: ADDRESS -->
                <div x-show="tab === 2" class="space-y-2">
                    <label class="font-medium">Address</label>
                    <textarea name="address" class="w-full border p-2" rows="2">{{ $patient->address }}</textarea>

                    <label class="font-medium">City</label>
                    <input type="text" name="city" value="{{ $patient->city }}" class="w-full border p-2">

                    <label class="font-medium">Province</label>
                    <input type="text" name="province" value="{{ $patient->province }}" class="w-full border p-2">

                    <label class="font-medium">Zip Code</label>
                    <input type="text" name="zip_code" value="{{ $patient->zip_code }}" class="w-full border p-2">
                </div>

                <!-- TAB 3: CONTACT -->
                <div x-show="tab === 3" class="space-y-2">
                    <label class="font-medium">Mobile Number</label>
                    <input type="text" name="mobile_number" value="{{ $patient->mobile_number }}" class="w-full border p-2">

                    <label class="font-medium">Landline Number</label>
                    <input type="text" name="landline_number" value="{{ $patient->landline_number }}" class="w-full border p-2">

                    <label class="font-medium">Email</label>
                    <input type="email" name="email" value="{{ $patient->email }}" class="w-full border p-2">
                </div>

                <!-- TAB 4: EMERGENCY -->
                <div x-show="tab === 4" class="space-y-2">
                    <label class="font-medium">Referred By</label>
                    <input type="text" name="referred_by" value="{{ $patient->referred_by }}" class="w-full border p-2">

                    <label class="font-medium">Emergency Contact Name</label>
                    <input type="text" name="emergency_full_name" value="{{ $patient->emergencyContact->full_name ?? '' }}" class="w-full border p-2" required>

                    <label class="font-medium">Relationship</label>
                    <input type="text" name="emergency_relationship" value="{{ $patient->emergencyContact->relationship ?? '' }}" class="w-full border p-2">

                    <label class="font-medium">Emergency Mobile</label>
                    <input type="text" name="emergency_mobile" value="{{ $patient->emergencyContact->mobile_number ?? '' }}" class="w-full border p-2">

                    <label class="font-medium">Emergency Landline</label>
                    <input type="text" name="emergency_landline" value="{{ $patient->emergencyContact->landline_number ?? '' }}" class="w-full border p-2">
                </div>

            </div>

          <!-- FIXED BUTTONS INSIDE MODAL -->
  <div class="flex justify-end space-x-2 mt-4">
    <button type="button" @click="openEditId = null"
        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
        Cancel
    </button>
    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Update
    </button>
</div>
        </form>

    </div>
</div>
                                <!-- DELETE MODAL -->
                                <div x-show="openDeleteId === {{ $patient->id }}" x-cloak
                                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white p-6 rounded w-1/3 text-center">
                                        <h2 class="text-lg font-bold mb-4">Delete Patient?</h2>
                                        <p class="mb-4">Are you sure you want to delete <b>{{ $patient->first_name }} {{ $patient->last_name }}</b>?</p>
                                        <form method="POST" action="{{ route('patients.destroy', $patient->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-secondary-button type="button" @click="openDeleteId = null">Cancel</x-secondary-button>
                                            <x-danger-button type="submit">Delete</x-danger-button>
                                        </form>
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
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="relative bg-white p-6 rounded w-1/2 max-h-[90vh] overflow-y-auto">

        <!-- X Button (Always Visible) -->
        <button type="button"
            class="absolute top-3 right-4 text-gray-700 hover:text-red-600 text-3xl font-extrabold leading-none"
            @click="openAdd = false">
            &times;
        </button>

        <h2 class="text-lg font-bold mb-2">Add Patient</h2>
          
<form method="POST" action="{{ route('patients.store') }}" x-data="{ tab: 1 }">
    @csrf

    <!-- TAB HEADERS -->
<div class="flex border-b mb-4">
    <button type="button" @click="tab = 1"
        :class="tab === 1 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
        class="px-4 py-2 font-semibold border-transparent hover:bg-gray-50 rounded-t">
        Basic Info
    </button>

    <button type="button" @click="tab = 2"
        :class="tab === 2 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
        class="px-4 py-2 font-semibold border-transparent hover:bg-gray-50 rounded-t">
        Address
    </button>

    <button type="button" @click="tab = 3"
        :class="tab === 3 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
        class="px-4 py-2 font-semibold border-transparent hover:bg-gray-50 rounded-t">
        Contact
    </button>

    <button type="button" @click="tab = 4"
        :class="tab === 4 ? 'text-blue-600 bg-blue-100' : 'text-gray-600'"
        class="px-4 py-2 font-semibold border-transparent hover:bg-gray-50 rounded-t">
        Emergency
    </button>
</div>

 <div class="relative border rounded bg-white p-4 mb-4"
       style="min-height:520px">

         <!-- Step 1: Basic Info -->
    <div x-show="tab === 1" class="space-y-2">
        <label class="block mb-1 font-medium">Last Name</label>
        <input type="text" name="last_name" class="w-full border p-2" placeholder="Enter Last Name" required>

        <label class="block mb-1 font-medium">First Name</label>
        <input type="text" name="first_name" class="w-full border p-2" placeholder="Enter First Name" required>

        <label class="block mb-1 font-medium">Middle Name</label>
        <input type="text" name="middle_name" class="w-full border p-2" placeholder="Enter Middle Name">

        <label class="block mb-1 font-medium">Date of Birth</label>
        <input type="date" name="date_of_birth" class="w-full border p-2" >

        <label class="block mb-1 font-medium">Age</label>
        <input type="text" name="age" class="w-full border p-2" placeholder="Enter Age" required>
   <label class="block mb-1 font-medium">Nationality</label>
        <input type="text" name="nationality" class="w-full border p-2" placeholder="Enter Nationality">

        <label class="block mb-1 font-medium">Religion</label>
        <input type="text" name="religion" class="w-full border p-2" placeholder="Enter Religion">

        <label class="block mb-1 font-medium">Occupation</label>
        <input type="text" name="occupation" class="w-full border p-2" placeholder="Enter Occupation">

        <label class="block mb-1 font-medium">Sex</label>
        <select name="sex" class="w-full border p-2" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Prefer not to say">Prefer not to say</option>
        </select>

        <label class="block mb-1 font-medium">Civil Status</label>
        <select name="civil_status" class="w-full border p-2">
            <option value="">Select Civil Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Separated">Separated</option>
            <option value="Divorced">Divorced</option>
            <option value="Annulled">Annulled</option>
            <option value="Commonlaw">Common-Law / Live-in</option>
        </select>

        <label class="block mb-1 font-medium">Date Registered</label>
        <input type="date" name="date_registered" class="w-full border p-2">
    </div>

 <!-- Step 2: Personal Address -->
<div x-show="tab === 2" class="space-y-2">

   <label class="block mb-1 font-medium">Address</label>
        <textarea name="address" class="w-full border p-2" rows="2" placeholder="Enter Address"required></textarea >

        <label class="block mb-1 font-medium">City</label>
        <input type="text" name="city" class="w-full border p-2" placeholder="Enter City">

        <label class="block mb-1 font-medium">Province</label>
        <input type="text" name="province" class="w-full border p-2" placeholder="Enter Province">

        <label class="block mb-1 font-medium">Zip Code</label>
        <input type="text" name="zip_code" class="w-full border p-2" placeholder="Enter Zip Code">
</div>

    <!-- Step 3: Contact Info -->
    <div x-show="tab === 3" class="space-y-2">
        <label class="block mb-1 font-medium">Mobile Number</label>
        <input type="text" name="mobile_number" class="w-full border p-2" placeholder="Enter Mobile Number"required>

        <label class="block mb-1 font-medium">Landline Number</label>
        <input type="text" name="landline_number" class="w-full border p-2" placeholder="Enter Landline Number">

        <label class="block mb-1 font-medium">Email</label>
        <input type="email" name="email" class="w-full border p-2" placeholder="Enter Email Address" required>
    </div>

    <!-- Step 4: Emergency & Referral -->
    <div x-show="tab === 4" class="space-y-2">
        <label class="block mb-1 font-medium">Referred By</label>
        <input type="text" name="referred_by" class="w-full border p-2" placeholder="Enter Referrer Name">

        <label class="block mb-1 font-medium">Emergency Contact Name</label>
        <input type="text" name="emergency_full_name" class="w-full border p-2" placeholder="Enter Emergency Contact Name" required>

        <label class="block mb-1 font-medium">Relationship to the patient</label>
        <input type="text" name="emergency_relationship" class="w-full border p-2" placeholder="Enter Relationship"required>

        <label class="block mb-1 font-medium">Emergency Mobile</label>
        <input type="text" name="emergency_mobile" class="w-full border p-2" placeholder="Enter Emergency Mobile Number" required>

        <label class="block mb-1 font-medium">Emergency Landline</label>
        <input type="text" name="emergency_landline" class="w-full border p-2" placeholder="Enter Emergency Landline Number">

         </div>
            <!-- SUBMIT BUTTON -->
    <div class="mt-4 flex justify-end">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
            Submit
        </button>
    </div>
</form>
   
</div>
</div>
</x-app-layout> 