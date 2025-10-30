<!-- jQuery  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>


<script>
  $(document).ready(function () {
      $('#myTable').DataTable();
  });
</script>

<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Patient Management') }}
        </h2>
    </x-slot>
    <div class="py-6" x-data="{ openAdd:false, openViewId:null, openEditId:null, openDeleteId:null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                <!-- Add Patient Button -->
                <div class="flex justify-end mb-4">
                    <x-button @click="openAdd = true">+ New Patient</x-button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table id="myTable" class="min-w-full text-sm border border-gray-300 dark:border-gray-700 table table-striped">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                <th class="px-6 py-3 border border-gray-300">Patient No</th>
                <th class="px-6 py-3 border border-gray-300">Name</th>
                <th class="px-6 py-3 border border-gray-300">Sex</th>
                <th class="px-6 py-3 border border-gray-300">Contact</th>
                <th class="px-6 py-3 border border-gray-300">Address</th>
                <th class="px-6 py-3 border border-gray-300">Emergency</th>
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
                    {{ $patient->mobile_number ?? '-' }}
                    @if($patient->email)
                        <br><span class="text-xs text-blue-600">{{ $patient->email }}</span>
                    @endif
                </td>
                <td class="px-6 py-3 border border-gray-300">{{ $patient->address ?? '-' }}</td>
                <td class="px-6 py-3 border border-gray-300">
                    @if($patient->emergencyContact)
                        <b>{{ $patient->emergencyContact->full_name }}</b>
                        <br><span class="text-xs">{{ $patient->emergencyContact->relationship ?? '-' }}</span>
                        @if($patient->emergencyContact->mobile_number)
                            <br><span class="text-xs">{{ $patient->emergencyContact->mobile_number }}</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                                    <td class="px-4 py-2 border space-x-1">
                                        <x-button class="text-xs px-2 py-1" @click="openViewId={{ $patient->id }}">View</x-button>
                                        <x-secondary-button class="text-xs px-2 py-1" @click="openEditId={{ $patient->id }}">Edit</x-secondary-button>
                                        <x-danger-button class="text-xs px-2 py-1" @click="openDeleteId={{ $patient->id }}">Delete</x-danger-button>
                                    </td>
                                </tr>
                                <!-- VIEW MODAL -->
                                <div x-show="openViewId === {{ $patient->id }}" x-cloak
                                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white p-6 rounded w-1/2 max-h-[90vh] overflow-y-auto">
                                        <h2 class="text-lg font-bold mb-2">Patient Details</h2>
                                        <p><b>Name:</b> {{ $patient->last_name }}, {{ $patient->first_name }} {{ $patient->middle_name }}</p>
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
                                        <p><b>Emergency Contact:</b> {{ $patient->emergencyContact->full_name ?? '-' }}</p>
                                        <p><b>Emergency Relationship:</b> {{ $patient->emergencyContact->relationship ?? '-' }}</p>
                                        <p><b>Emergency Mobile:</b> {{ $patient->emergencyContact->mobile_number ?? '-' }}</p>
                                        <p><b>Emergency Landline:</b> {{ $patient->emergencyContact->landline_number ?? '-' }}</p>
                                        <div class="mt-4 text-right">
                                            <x-secondary-button @click="openViewId = null">Close</x-secondary-button>

                                             <a href="{{ route('medical-history.index', $patient->id) }}"
       class="inline-block px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
        Interview
                                          </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- EDIT MODAL -->
                                <div x-show="openEditId === {{ $patient->id }}" x-cloak
                                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white p-6 rounded w-1/2 max-h-[90vh] overflow-y-auto">
                                        <h2 class="text-lg font-bold mb-2">Edit Patient</h2>
                                        <form method="POST" action="{{ route('patients.update', $patient->id) }}">
                                            @csrf
                                            @method('PUT')
                                           <label class="block text-sm font-medium mb-1">Last Name</label>
    <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name) }}" class="w-full border p-2 mb-3" placeholder="Last Name" required>

    <label class="block text-sm font-medium mb-1">First Name</label>
    <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name) }}" class="w-full border p-2 mb-3" placeholder="First Name" required>

    <label class="block text-sm font-medium mb-1">Middle Name</label>
    <input type="text" name="middle_name" value="{{ old('middle_name', $patient->middle_name) }}" class="w-full border p-2 mb-3" placeholder="Middle Name">

    <label class="block text-sm font-medium mb-1">Date of Birth</label>
    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" class="w-full border p-2 mb-3" required>

    <label class="block text-sm font-medium mb-1">Age</label>
    <input type="text" name="age" value="{{ old('age', $patient->age) }}" class="w-full border p-2 mb-3" placeholder="Age">

    <label class="block text-sm font-medium mb-1">Date Registered</label>
    <input type="date" name="date_registered" value="{{ old('date_registered', $patient->date_registered->format('Y-m-d')) }}" class="w-full border p-2 mb-3">

    <label class="block text-sm font-medium mb-1">Sex</label>
    <select name="sex" class="w-full border p-2 mb-3" required>
        <option value="">Select Sex</option>
        <option value="Male" {{ old('sex', $patient->sex) == 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ old('sex', $patient->sex) == 'Female' ? 'selected' : '' }}>Female</option>
        <option value="Prefer not to say" {{ old('sex', $patient->sex) == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
    </select>

    <label class="block text-sm font-medium mb-1">Civil Status</label>
    <select name="civil_status" class="w-full border p-2 mb-3" required>
        <option value="">Select Civil Status</option>
        <option value="Single" {{ old('civil_status', $patient->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
        <option value="Married" {{ old('civil_status', $patient->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
        <option value="Widowed" {{ old('civil_status', $patient->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
        <option value="Separated" {{ old('civil_status', $patient->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
        <option value="Divorced" {{ old('civil_status', $patient->civil_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
        <option value="Annulled" {{ old('civil_status', $patient->civil_status ?? '') == 'Annulled' ? 'selected' : '' }}>Annulled</option>
        <option value="Common-Law" {{ old('civil_status', $patient->civil_status ?? '') == 'Common-Law' ? 'selected' : '' }}>Common-Law</option>
    </select>

    <label class="block text-sm font-medium mb-1">Nationality</label>
    <input type="text" name="nationality" value="{{ old('nationality', $patient->nationality) }}" class="w-full border p-2 mb-3" placeholder="Nationality">

    <label class="block text-sm font-medium mb-1">Religion</label>
    <input type="text" name="religion" value="{{ old('religion', $patient->religion) }}" class="w-full border p-2 mb-3" placeholder="Religion">

    <label class="block text-sm font-medium mb-1">Occupation</label>
    <input type="text" name="occupation" value="{{ old('occupation', $patient->occupation) }}" class="w-full border p-2 mb-3" placeholder="Occupation">

    <label class="block text-sm font-medium mb-1">Address</label>
    <textarea name="address" class="w-full border p-2 mb-3" placeholder="Address">{{ old('address', $patient->address) }}</textarea>

    <label class="block text-sm font-medium mb-1">City</label>
    <input type="text" name="city" value="{{ old('city', $patient->city) }}" class="w-full border p-2 mb-3" placeholder="City">

    <label class="block text-sm font-medium mb-1">Province</label>
    <input type="text" name="province" value="{{ old('province', $patient->province) }}" class="w-full border p-2 mb-3" placeholder="Province">

    <label class="block text-sm font-medium mb-1">Zip Code</label>
    <input type="text" name="zip_code" value="{{ old('zip_code', $patient->zip_code) }}" class="w-full border p-2 mb-3" placeholder="Zip Code">

    <label class="block text-sm font-medium mb-1">Mobile Number</label>
    <input type="text" name="mobile_number" value="{{ old('mobile_number', $patient->mobile_number) }}" class="w-full border p-2 mb-3" placeholder="Mobile Number">

    <label class="block text-sm font-medium mb-1">Landline Number</label>
    <input type="text" name="landline_number" value="{{ old('landline_number', $patient->landline_number) }}" class="w-full border p-2 mb-3" placeholder="Landline Number">

    <label class="block text-sm font-medium mb-1">Email</label>
    <input type="email" name="email" value="{{ old('email', $patient->email) }}" class="w-full border p-2 mb-3" placeholder="Email">

    <label class="block text-sm font-medium mb-1">Referred By</label>
    <input type="text" name="referred_by" value="{{ old('referred_by', $patient->referred_by) }}" class="w-full border p-2 mb-3" placeholder="Referred By">

    <hr class="my-4">

    <h3 class="font-semibold mb-2">Emergency Contact</h3>

    <label class="block text-sm font-medium mb-1">Full Name</label>
    <input type="text" name="emergency_full_name" value="{{ old('emergency_full_name', $patient->emergencyContact->full_name ?? '') }}" class="w-full border p-2 mb-3" placeholder="Emergency Contact Name" required>

    <label class="block text-sm font-medium mb-1">Relationship</label>
    <input type="text" name="emergency_relationship" value="{{ old('emergency_relationship', $patient->emergencyContact->relationship ?? '') }}" class="w-full border p-2 mb-3" placeholder="Relationship">

    <label class="block text-sm font-medium mb-1">Mobile</label>
    <input type="text" name="emergency_mobile" value="{{ old('emergency_mobile', $patient->emergencyContact->mobile_number ?? '') }}" class="w-full border p-2 mb-3" placeholder="Emergency Mobile">

    <label class="block text-sm font-medium mb-1">Landline</label>
    <input type="text" name="emergency_landline" value="{{ old('emergency_landline', $patient->emergencyContact->landline_number ?? '') }}" class="w-full border p-2 mb-3" placeholder="Emergency Landline">

                                    <div class="mt-4 flex justify-end space-x-2">
                    <x-secondary-button type="button" @click="openEditId = null">Cancel</x-secondary-button>
                  <x-button type="submit">Update</x-button>
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
    <div class="bg-white p-6 rounded w-1/2 max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg font-bold mb-2">Add Patient</h2>
        <form method="POST" action="{{ route('patients.store') }}">
            @csrf
              <label class="block mb-1 font-medium">Last Name</label>
            <input type="text" name="last_name" class="w-full border p-2 mb-2" placeholder="Enter Last Name" required>

            <label class="block mb-1 font-medium">First Name</label>
            <input type="text" name="first_name" class="w-full border p-2 mb-2" placeholder="Enter First Name" required>

            <label class="block mb-1 font-medium">Middle Name</label>
            <input type="text" name="middle_name" class="w-full border p-2 mb-2" placeholder="Enter Middle Name">

            <label class="block mb-1 font-medium">Date of Birth</label>
            <input type="date" name="date_of_birth" class="w-full border p-2 mb-2" required>

            <label class="block mb-1 font-medium">Age</label>
            <input type="text" name="age" class="w-full border p-2 mb-2" placeholder="Enter Age" required>

            <label class="block mb-1 font-medium">Date Registered</label>
            <input type="date" name="date_registered" class="w-full border p-2 mb-2">

            <label class="block mb-1 font-medium">Sex</label>
            <select name="sex" class="w-full border p-2 mb-2" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Prefer not to say">Prefer not to say</option>
            </select>

            <label class="block mb-1 font-medium">Civil Status</label>
            <select name="civil_status" class="w-full border p-2 mb-2" required>
                <option value="">Select Civil Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
                <option value="Divorced">Divorced</option>
                <option value="Annulled">Annulled</option>
                <option value="Commonlaw">Common-Law / Live-in</option>
            </select>

            <label class="block mb-1 font-medium">Nationality</label>
            <input type="text" name="nationality" class="w-full border p-2 mb-2" placeholder="Enter Nationality">

            <label class="block mb-1 font-medium">Religion</label>
            <input type="text" name="religion" class="w-full border p-2 mb-2" placeholder="Enter Religion">

            <label class="block mb-1 font-medium">Occupation</label>
            <input type="text" name="occupation" class="w-full border p-2 mb-2" placeholder="Enter Occupation">

            <label class="block mb-1 font-medium">Address</label>
            <textarea name="address" class="w-full border p-2 mb-2" rows="2" placeholder="Enter Complete Address"></textarea>

            <label class="block mb-1 font-medium">City</label>
            <input type="text" name="city" class="w-full border p-2 mb-2" placeholder="Enter City">

            <label class="block mb-1 font-medium">Province</label>
            <input type="text" name="province" class="w-full border p-2 mb-2" placeholder="Enter Province">

            <label class="block mb-1 font-medium">Zip Code</label>
            <input type="text" name="zip_code" class="w-full border p-2 mb-2" placeholder="Enter Zip Code">

            <label class="block mb-1 font-medium">Mobile Number</label>
            <input type="text" name="mobile_number" class="w-full border p-2 mb-2" placeholder="Enter Mobile Number">

            <label class="block mb-1 font-medium">Landline Number</label>
            <input type="text" name="landline_number" class="w-full border p-2 mb-2" placeholder="Enter Landline Number">

            <label class="block mb-1 font-medium">Email</label>
            <input type="email" name="email" class="w-full border p-2 mb-2" placeholder="Enter Email Address">

            <label class="block mb-1 font-medium">Referred By</label>
            <input type="text" name="referred_by" class="w-full border p-2 mb-2" placeholder="Enter Referrer Name">

            <label class="block mb-1 font-medium">Emergency Contact Name</label>
            <input type="text" name="emergency_full_name" class="w-full border p-2 mb-2" placeholder="Enter Emergency Contact Name" required>

            <label class="block mb-1 font-medium">Relationship</label>
            <input type="text" name="emergency_relationship" class="w-full border p-2 mb-2" placeholder="Enter Relationship">

            <label class="block mb-1 font-medium">Emergency Mobile</label>
            <input type="text" name="emergency_mobile" class="w-full border p-2 mb-2" placeholder="Enter Emergency Mobile Number">

            <label class="block mb-1 font-medium">Emergency Landline</label>
            <input type="text" name="emergency_landline" class="w-full border p-2 mb-2" placeholder="Enter Emergency Landline Number">

            <div class="mt-4 flex justify-end space-x-2">
                <x-secondary-button type="button" @click="openAdd = false">Cancel</x-secondary-button>
                <x-button type="submit">Save</x-button>
            </div>
        </form>
    </div>
</div>
</x-app-layout> 