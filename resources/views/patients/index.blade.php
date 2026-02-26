

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
             <div class="dropdown">
    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $patient->id }}" data-bs-toggle="dropdown" aria-expanded="false">
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
     class="fixed inset-0 black/40 backdrop-blur-sm flex items-center justify-center z-50 bg-black/40">

    <div class="relative bg-white rounded w-1/2 max-h-[90vh] overflow-y-auto shadow-lg"
         x-data="{ tab: 1 }">

        <!-- HEADER -->
        <div class="px-6 py-4 flex justify-between items-center bg-blue-600 rounded-t-xl shadow">
            <h2 class="text-lg font-bold text-white">Edit Patient</h2>
            <button type="button"
                class="text-white hover:text-gray-200 text-2xl font-bold leading-none"
                @click="openEditId = null">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('patients.update', $patient->id) }}">
            @csrf
            @method('PUT')

            <!-- TAB BUTTONS -->
            <div class="flex border-b mb-4 bg-blue-500 rounded-t">
                <button type="button" @click="tab = 1"
                    :class="tab === 1 ? 'bg-blue-700 text-white font-semibold' : 'text-white hover:bg-blue-400 hover:text-white'"
                    class="px-4 py-2 rounded-t transition-colors">
                    Basic Info
                </button>

                <button type="button" @click="tab = 2"
                    :class="tab === 2 ? 'bg-blue-700 text-white font-semibold' : 'text-white hover:bg-blue-400 hover:text-white'"
                    class="px-4 py-2 rounded-t transition-colors">
                    Address
                </button>

                <button type="button" @click="tab = 3"
                    :class="tab === 3 ? 'bg-blue-700 text-white font-semibold' : 'text-white hover:bg-blue-400 hover:text-white'"
                    class="px-4 py-2 rounded-t transition-colors">
                    Contact
                </button>

                <button type="button" @click="tab = 4"
                    :class="tab === 4 ? 'bg-blue-700 text-white font-semibold' : 'text-white hover:bg-blue-400 hover:text-white'"
                    class="px-4 py-2 rounded-t transition-colors">
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
                    class="btn btn-dark btn-sm">Cancel</button>
                <button type="submit"
                    class="btn btn-primary btn-sm">Update</button>
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
     class="fixed inset-0 black/40 backdrop-blur-sm flex items-center justify-center z-50 bg-black/40">

    <div class="relative bg-white rounded-lg w-1/2 max-h-[90vh] overflow-y-auto shadow-lg border border-gray-200">

        <!-- HEADER -->
        <div class="bg-blue-600 text-white p-4 rounded-t flex justify-between items-center">
            <h2 class="text-lg font-bold">Add Patient</h2>
            <button type="button"
                class="text-white hover:text-gray-200 text-2xl font-extrabold leading-none"
                @click="openAdd = false">&times;</button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('patients.store') }}" x-data="{ tab: 1 }" class="p-6">
            @csrf

            <!-- TAB HEADERS -->
            <div class="flex border-b mb-4">
                <button type="button" @click="tab = 1"
                    :class="tab === 1 ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600'"
                    class="px-4 py-2 font-semibold rounded-t mr-1">
                    Basic Info
                </button>

                <button type="button" @click="tab = 2"
                    :class="tab === 2 ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600'"
                    class="px-4 py-2 font-semibold rounded-t mr-1">
                    Address
                </button>

                <button type="button" @click="tab = 3"
                    :class="tab === 3 ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600'"
                    class="px-4 py-2 font-semibold rounded-t mr-1">
                    Contact
                </button>

                <button type="button" @click="tab = 4"
                    :class="tab === 4 ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600'"
                    class="px-4 py-2 font-semibold rounded-t">
                    Emergency
                </button>
            </div>

            <!-- TAB CONTENT -->
            <div class="border rounded bg-white p-4 mb-4" style="min-height:520px">
                
                <!-- Step 1: Basic Info -->
                <div x-show="tab === 1" class="space-y-2">
                    <label class="block font-medium">Last Name</label>
                    <input type="text" name="last_name" class="w-full border p-2" placeholder="Enter Last Name" required>

                    <label class="block font-medium">First Name</label>
                    <input type="text" name="first_name" class="w-full border p-2" placeholder="Enter First Name" required>

                    <label class="block font-medium">Middle Name</label>
                    <input type="text" name="middle_name" class="w-full border p-2" placeholder="Enter Middle Name">

                    <label class="block font-medium">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="w-full border p-2">

                    <label class="block font-medium">Age</label>
                    <input type="text" name="age" class="w-full border p-2" placeholder="Enter Age" required>

                    <label class="block font-medium">Nationality</label>
                    <input type="text" name="nationality" class="w-full border p-2" placeholder="Enter Nationality">

                    <label class="block font-medium">Religion</label>
                    <input type="text" name="religion" class="w-full border p-2" placeholder="Enter Religion">

                    <label class="block font-medium">Occupation</label>
                    <input type="text" name="occupation" class="w-full border p-2" placeholder="Enter Occupation">

                    <label class="block font-medium">Sex</label>
                    <select name="sex" class="w-full border p-2" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Prefer not to say">Prefer not to say</option>
                    </select>

                    <label class="block font-medium">Civil Status</label>
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

                    <label class="block font-medium">Date Registered</label>
                    <input type="date" name="date_registered" class="w-full border p-2">
                </div>

                <!-- Step 2: Address -->
                <div x-show="tab === 2" class="space-y-2">
                    <label class="block font-medium">Address</label>
                    <textarea name="address" class="w-full border p-2" rows="2" placeholder="Enter Address" required></textarea>

                    <label class="block font-medium">City</label>
                    <input type="text" name="city" class="w-full border p-2" placeholder="Enter City">

                    <label class="block font-medium">Province</label>
                    <input type="text" name="province" class="w-full border p-2" placeholder="Enter Province">

                    <label class="block font-medium">Zip Code</label>
                    <input type="text" name="zip_code" class="w-full border p-2" placeholder="Enter Zip Code">
                </div>

                <!-- Step 3: Contact -->
                <div x-show="tab === 3" class="space-y-2">
                    <label class="block font-medium">Mobile Number</label>
                    <input type="text" name="mobile_number" class="w-full border p-2" placeholder="Enter Mobile Number" required>

                    <label class="block font-medium">Landline Number</label>
                    <input type="text" name="landline_number" class="w-full border p-2" placeholder="Enter Landline Number">

                    <label class="block font-medium">Email</label>
                    <input type="email" name="email" class="w-full border p-2" placeholder="Enter Email Address" required>
                </div>

                <!-- Step 4: Emergency -->
                <div x-show="tab === 4" class="space-y-2">
                    <label class="block font-medium">Referred By</label>
                    <input type="text" name="referred_by" class="w-full border p-2" placeholder="Enter Referrer Name">

                    <label class="block font-medium">Emergency Contact Name</label>
                    <input type="text" name="emergency_full_name" class="w-full border p-2" placeholder="Enter Emergency Contact Name" required>

                    <label class="block font-medium">Relationship</label>
                    <input type="text" name="emergency_relationship" class="w-full border p-2" placeholder="Enter Relationship" required>

                    <label class="block font-medium">Emergency Mobile</label>
                    <input type="text" name="emergency_mobile" class="w-full border p-2" placeholder="Enter Emergency Mobile Number" required>

                    <label class="block font-medium">Emergency Landline</label>
                    <input type="text" name="emergency_landline" class="w-full border p-2" placeholder="Enter Emergency Landline Number">
                </div>

            </div>

            <!-- SUBMIT BUTTON -->
            <div class="flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Submit
                </button>
            </div>

        </form>
    </div>
</div>

</x-app-layout> 