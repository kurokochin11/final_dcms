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

@section('title', 'Intraoral Examinations')
<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Intraoral Examinations
        </h2>
        <button onclick="openCreateModal()" class="btn btn-primary btn-md ">
              <i class="fas fa-plus me-2"></i> New Examination
        </button>
    </div>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                             <th class="border px-4 py-2">Patient No</th>
                            <th class="border px-4 py-2">Patient</th>
                            <th class="border px-4 py-2">Date</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                 <tbody>
@forelse($examinations as $exam)
<tr class="text-gray-800 dark:text-gray-200">
    <td class="border px-4 py-2">{{ $exam->patient->id ?? '-' }}</td>
    <td class="border px-4 py-2">{{ $exam->patient->full_name ?? '-' }}
    </td>

<td class="border px-4 py-2">
    {{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('M d, Y') : '-' }}
</td>
    
    <!-- {{-- Actions --}} -->
    <td class="border px-4 py-2 flex gap-2">
         <button onclick="openViewModal({{ $exam->id }})" class="btn btn-primary btn-md"><i class="fas fa-eye"></i></button>
        <button onclick="openEditModal(this)" data-url="{{ route('oral_examination.edit', $exam->id) }}" class="btn btn-warning btn-md text-white"><i class="fas fa-edit"></i></button>
        <button type="button" class="btn btn-danger btn-md" onclick="openDeleteModal({{ $exam->id }}, '{{ $exam->patient->full_name }}')"> <i class="fas fa-trash"></i> </button>
        <!--  class="m-0 d-flex"> -->
          
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="border px-4 py-2 text-center text-gray-500">No examinations found.</td>
</tr>
@endforelse
</tbody>

                </table>

                <!-- {{-- Pagination --}} -->
                <div class="mt-4">
                    {{ $examinations->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- {{-- ADD Modal --}} -->
<div id="createIntraoralModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl relative overflow-y-auto max-h-[90vh] flex flex-col">

        <!-- HEADER -->
        <div class="bg-primary text-white px-6 py-4 rounded-t-lg " >
            <h2 class="text-2xl font-semibold">
                New Examination
            </h2>

            <button
                onclick="closeCreateModal()"
                 class="absolute top-4 right-4 text-white rounded-full w-9 h-9 flex items-center justify-center hover:bg-primary/90 transition">
                ✕
            </button>
        </div>
        <form id="createIntraoralForm" action="{{ route('oral_examination.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- {{-- Tabs --}} -->
            <div class="mb-4">
                <ul class="flex border-b border-gray-200 dark:border-gray-700" id="create-tabs">
                    <li class="-mb-px mr-1"><button type="button" class="create-tab-link px-4 py-2 font-semibold" data-tab="create-tab-soft">Soft Tissues</button></li>
                    <li class="mr-1"><button type="button" class="create-tab-link px-4 py-2 font-semibold" data-tab="create-tab-gingiva">Gingiva</button></li>
                    <li class="mr-1"><button type="button" class="create-tab-link px-4 py-2 font-semibold" data-tab="create-tab-periodontium">Periodontium</button></li>
                    <li class="mr-1"><button type="button" class="create-tab-link px-4 py-2 font-semibold" data-tab="create-tab-teeth">Hard Tissues</button></li>
                    <li class="mr-1"><button type="button" class="create-tab-link px-4 py-2 font-semibold" data-tab="create-tab-occlusion">Occlusion</button></li>
                    <li class="mr-1"><button type="button" class="create-tab-link px-4 py-2 font-semibold" data-tab="create-tab-hygiene">Oral Hygiene</button></li>
                </ul>
            </div>

            <!-- {{-- Tab Contents --}} -->
            <div>

                <!-- {{-- Soft Tissues --}} -->
                <div id="create-tab-soft" class="create-tab-content mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" class="w-full border rounded px-2 py-1 mb-2" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                        @endforeach
                    </select>

  <!-- {{-- Examination Date --}} -->
    <label class="block mb-1 mt-2">Examination Date</label>
    <input
        type="date"
        name="date"
        class="w-full border rounded px-2 py-1 mb-2"
        value="{{ now()->toDateString() }}"
        required
    >
                    <label class="block mb-1">Soft Tissues (Lips, Cheeks, Tongue, Floor of Mouth, Palate, Oropharynx)</label>
                    <select name="soft_tissues_status" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="Normal">Normal</option>
                        <option value="Abnormal">Abnormal</option>
                    </select>
                    <textarea name="soft_tissues" rows="3" class="w-full border rounded px-2 py-1" placeholder="Specify any lesions, swelling, discoloration, etc."></textarea>
                </div>

                <!-- {{-- Gingiva --}} -->
                <div id="create-tab-gingiva" class="create-tab-content mb-4 hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>Color</label>
                            <select name="gingiva_color" class="w-full border rounded px-2 py-1">
                                <option value="">Select</option>
                                <option value="Pink">Pink</option>
                                <option value="Red">Red</option>
                                <option value="Cyanotic">Cyanotic</option>
                            </select>
                        </div>
                        <div>
                            <label>Texture</label>
                            <select name="gingiva_texture" class="w-full border rounded px-2 py-1">
                                <option value="">Select</option>
                                <option value="Stippled">Stippled</option>
                                <option value="Edematous">Edematous</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label>Bleeding on Probing</label>
                        <div class="flex gap-4 mt-1">
                            <label><input type="radio" name="bleeding" value="Yes"> Yes</label>
                            <label><input type="radio" name="bleeding" value="No" checked> No</label>
                        </div>
                        <input type="text" name="bleeding_area" class="w-full border rounded px-2 py-1 mt-1" placeholder="Specify areas if localized">

                        <label class="mt-2">Recession</label>
                        <div class="flex gap-4 mt-1">
                            <label><input type="radio" name="recession" value="Yes"> Yes</label>
                            <label><input type="radio" name="recession" value="No" checked> No</label>
                        </div>
                        <input type="text" name="recession_area" class="w-full border rounded px-2 py-1 mt-1" placeholder="Specify teeth/areas">
                    </div>
                </div>

                <!-- {{-- Periodontium --}} -->
                <div id="create-tab-periodontium" class="create-tab-content mb-4 hidden">
                     <!-- Probing Depths -->
    <div id="preview-probing_depths" class="mb-2"></div>
    <label>Probing Depths</label>
    <input type="file" name="probing_depths" accept="image/*" class="w-full mt-1">

    <!-- Mobility -->
    <div id="preview-mobility" class="mb-2"></div>
    <label>Mobility</label>
    <input type="file" name="mobility" accept="image/*" class="w-full mt-1">

    <!-- Furcation Involvement -->
    <div id="preview-furcation" class="mb-2"></div>
    <label>Furcation Involvement</label>
    <input type="file" name="furcation" accept="image/*" class="w-full mt-1">
</div>
                <!-- {{-- Hard Tissues --}} -->
                <div id="create-tab-teeth" class="create-tab-content mb-4 hidden">
                    <label>Teeth Condition</label>
                    <select name="teeth_condition" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="">Select</option>
                        <option value="Missing">Missing (X)</option>
                        <option value="Caries">Caries (C)</option>
                        <option value="Fillings">Fillings (F)</option>
                        <option value="Crowns">Crowns (Cr)</option>
                        <option value="Bridges">Bridges (B)</option>
                        <option value="Implants">Implants (I)</option>
                        <option value="RCT">Root Canal (RCT)</option>
                        <option value="Sealants">Sealants (S)</option>
                        <option value="Fractures">Fractures</option>
                        <option value="Attrition">Attrition</option>
                        <option value="Abrasion">Abrasion</option>
                        <option value="Erosion">Erosion</option>
                        <option value="Developmental Anomalies">Developmental Anomalies</option>
                    </select>

                    <label>Dental Chart (Odontogram)</label>
                    <input type="file" name="odontogram" accept="image/*" class="w-full mt-1">
                </div>

                <!-- {{-- Occlusion --}} -->
                <div id="create-tab-occlusion" class="create-tab-content mb-4 hidden">
                    <label>Occlusion Class</label> <select name="occlusion_class"class="w-full border rounded px-2 py-1 mb-2"> <option value="">Select Occlusion Class</option>
     <option value="Class I">Class I</option>
    <option value="Class II">Class II</option>
    <option value="Class III">Class III</option>
</select>

                    <select name="occlusion_other" class="w-full border rounded px-2 py-1 mb-2"> 
            <option value="">Select Occlusion Type</option>
    <option value="Open Bite">Open Bite</option>
    <option value="Deep Bite">Deep Bite</option>
    <option value="Overjet">Overjet</option>
    <option value="Overbite">Overbite</option>
</select>

                    <input type="text" name="premature_contacts" class="w-full border rounded px-2 py-1" placeholder="Premature Contacts / Interferences">
                </div>

                <!-- {{-- Oral Hygiene --}} -->
                <div id="create-tab-hygiene" class="create-tab-content mb-4 hidden">
                    <label>Oral Hygiene Status</label>
                    <select name="hygiene_status" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="">Select</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Poor">Poor</option>
                    </select>

                    <label>Plaque Index</label>
                    <input type="text" name="plaque_index" class="w-full border rounded px-2 py-1 mb-2">

                    <label>Calculus</label>
                    <select name="calculus" class="w-full border rounded px-2 py-1">
                        <option value="">Select</option>
                        <option value="Light">Light</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Heavy">Heavy</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeCreateModal()" class="btn btn-black btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- {{-- EDIT MODAL --}} -->
<div id="editIntraoralModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl relative overflow-y-auto max-h-[90vh] p-6">

        <!-- HEADER -->
        <div class="bg-primary text-white px-6 py-4 rounded-t-lg relative">
            <h2 class="text-2xl font-semibold">
                Edit Examination
            </h2>

            <button
                onclick="closeEditModal()"
                 class="absolute top-4 right-4 text-white rounded-full w-9 h-9 flex items-center justify-center hover:bg-primary/90 transition">
                ✕
            </button>
        </div>
       
        <form id="editIntraoralForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- {{-- Tabs --}} -->
            <div class="mb-4">
                <ul class="flex border-b border-gray-200 dark:border-gray-700" id="edit-tabs">
                    <li class="-mb-px mr-1"><button type="button" class="edit-tab-link px-4 py-2 font-semibold" data-tab="edit-tab-soft">Soft Tissues</button></li>
                    <li class="mr-1"><button type="button" class="edit-tab-link px-4 py-2 font-semibold" data-tab="edit-tab-gingiva">Gingiva</button></li>
                    <li class="mr-1"><button type="button" class="edit-tab-link px-4 py-2 font-semibold" data-tab="edit-tab-periodontium">Periodontium</button></li>
                    <li class="mr-1"><button type="button" class="edit-tab-link px-4 py-2 font-semibold" data-tab="edit-tab-teeth">Hard Tissues</button></li>
                    <li class="mr-1"><button type="button" class="edit-tab-link px-4 py-2 font-semibold" data-tab="edit-tab-occlusion">Occlusion</button></li>
                    <li class="mr-1"><button type="button" class="edit-tab-link px-4 py-2 font-semibold" data-tab="edit-tab-hygiene">Oral Hygiene</button></li>
                </ul>
            </div>

            <!-- {{-- Tab Contents --}} -->
            <div>
                <!-- {{-- Soft Tissues --}} -->
                <div id="edit-tab-soft" class="edit-tab-content mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" class="w-full border rounded px-2 py-1 mb-2" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                        @endforeach
                    </select>
  <!-- {{-- Examination Date --}} -->
    <label class="block mb-1 mt-2">Examination Date</label>
    <input
    type="date"
    name="date"
    class="w-full border rounded px-2 py-1 mb-2"
    value=""
    required
>
                    <label class="block mb-1">Soft Tissues</label>
                    <select name="soft_tissues_status" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="Normal">Normal</option>
                        <option value="Abnormal">Abnormal</option>
                    </select>
                    <textarea name="soft_tissues" rows="3" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                <!-- {{-- Gingiva --}} -->
                <div id="edit-tab-gingiva" class="edit-tab-content mb-4 hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>Color</label>
                            <select name="gingiva_color" class="w-full border rounded px-2 py-1">
                                <option value="">Select</option>
                                <option value="Pink">Pink</option>
                                <option value="Red">Red</option>
                                <option value="Cyanotic">Cyanotic</option>
                            </select>
                        </div>
                        <div>
                            <label>Texture</label>
                            <select name="gingiva_texture" class="w-full border rounded px-2 py-1">
                                <option value="">Select</option>
                                <option value="Stippled">Stippled</option>
                                <option value="Edematous">Edematous</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label>Bleeding on Probing</label>
                        <div class="flex gap-4 mt-1">
                            <label><input type="radio" name="bleeding" value="Yes"> Yes</label>
                            <label><input type="radio" name="bleeding" value="No"> No</label>
                        </div>
                        <input type="text" name="bleeding_area" class="w-full border rounded px-2 py-1 mt-1" placeholder="Specify areas if localized">

                        <label class="mt-2">Recession</label>
                        <div class="flex gap-4 mt-1">
                            <label><input type="radio" name="recession" value="Yes"> Yes</label>
                            <label><input type="radio" name="recession" value="No"> No</label>
                        </div>
                        <input type="text" name="recession_area" class="w-full border rounded px-2 py-1 mt-1" placeholder="Specify teeth/areas">
                    </div>
                </div>

                <!-- {{-- Periodontium --}} -->
                <div id="edit-tab-periodontium" class="edit-tab-content mb-4 hidden">
                    <label>Probing Depths</label>
                    <input type="file" name="probing_depths" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Mobility</label>
                    <input type="file" name="mobility" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Furcation Involvement</label>
                    <input type="file" name="furcation" accept="image/*" class="w-full mt-1">
                </div>

                <!-- {{-- Hard Tissues --}} -->
                <div id="edit-tab-teeth" class="edit-tab-content mb-4 hidden">
                    <label>Teeth Condition</label>
                    <select name="teeth_condition" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="">Select</option>
                        <option value="Missing">Missing (X)</option>
                        <option value="Caries">Caries (C)</option>
                        <option value="Fillings">Fillings (F)</option>
                        <option value="Crowns">Crowns (Cr)</option>
                        <option value="Bridges">Bridges (B)</option>
                        <option value="Implants">Implants (I)</option>
                        <option value="RCT">Root Canal (RCT)</option>
                        <option value="Sealants">Sealants (S)</option>
                        <option value="Fractures">Fractures</option>
                        <option value="Attrition">Attrition</option>
                        <option value="Abrasion">Abrasion</option>
                        <option value="Erosion">Erosion</option>
                        <option value="Developmental Anomalies">Developmental Anomalies</option>
                    </select>

                    <label>Dental Chart</label>
                    <input type="file" name="odontogram" accept="image/*" class="w-full mt-1">
                </div>

                <!-- {{-- Occlusion --}} -->
                <div id="edit-tab-occlusion" class="edit-tab-content mb-4 hidden">
                    <label>Occlusion Class</label>
                   <select name="occlusion_class"
    class="w-full border rounded px-2 py-1 mb-2">
    <option value="">Select Occlusion Class</option>
   <option value="Class I">Class I</option>
<option value="Class II">Class II</option>
<option value="Class III">Class III</option>

</select>

                   <select name="occlusion_other"
    class="w-full border rounded px-2 py-1 mb-2">
    <option value="">Select Occlusion Type</option>
<option value="Open Bite">Open Bite</option>
<option value="Deep Bite">Deep Bite</option>
<option value="Overjet">Overjet</option>
<option value="Overbite">Overbite</option>
</select>

                    <input type="text" name="premature_contacts" class="w-full border rounded px-2 py-1">
                </div>

                <!-- {{-- Oral Hygiene --}} -->
                <div id="edit-tab-hygiene" class="edit-tab-content mb-4 hidden">
                    <label>Oral Hygiene Status</label>
                    <select name="hygiene_status" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="">Select</option>
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Poor">Poor</option>
                    </select>

                    <label>Plaque Index</label>
                    <input type="text" name="plaque_index" class="w-full border rounded px-2 py-1 mb-2">

                    <label>Calculus</label>
                    <select name="calculus" class="w-full border rounded px-2 py-1">
                        <option value="">Select</option>
                        <option value="Light">Light</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Heavy">Heavy</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeEditModal()" class="btn btn-black btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- {{-- VIEW MODAL --}} -->
<div id="viewIntraoralModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl overflow-hidden max-h-[90vh]">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 bg-blue-600 text-white">
            <h2 class="text-xl font-semibold">Intraoral Examination Details</h2>
            <button onclick="closeViewModal()" class="text-white text-2xl leading-none hover:opacity-80">✕</button>
        </div>

        <!-- BODY -->
        <div class="p-6 overflow-y-auto max-h-[75vh] text-gray-800 dark:text-gray-200 space-y-6">

            <div id="viewIntraoralContent"></div>

            <div class="flex justify-end pt-4">
               <button
    type="button"
    id="downloadPdfBtn"
    class="btn btn-danger btn-sm">
    <i class="fas fa-file-pdf"></i> Download PDF
</button>

                <button onclick="closeViewModal()"
                    class="btn btn-black btn-sm">
                    Close
                </button>
            </div>
        </div>

    </div>
</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p>
                        Are you sure you want to delete
                        <strong id="deletePatientName"></strong>?
                    </p>
                    <p class="text-muted mb-0">This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-black" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Yes, Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    // Create Modal JS
    function openCreateModal(){
        document.getElementById('createIntraoralForm').reset();
        document.querySelectorAll('.create-tab-content').forEach(tc => tc.classList.add('hidden'));
        document.querySelectorAll('.create-tab-link').forEach(l => l.classList.remove('border-b-2','border-blue-600'));
        document.getElementById('create-tab-soft').classList.remove('hidden');
        document.querySelector('.create-tab-link').classList.add('border-b-2','border-blue-600');
        document.getElementById('createIntraoralModal').classList.remove('hidden');
    }

    function closeCreateModal(){ 
        document.getElementById('createIntraoralModal').classList.add('hidden'); 
    }

    // Tabs for Create Modal
    document.querySelectorAll('.create-tab-link').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.create-tab-content').forEach(tc => tc.classList.add('hidden'));
            document.querySelectorAll('.create-tab-link').forEach(l => l.classList.remove('border-b-2','border-blue-600'));
            document.getElementById(link.dataset.tab).classList.remove('hidden');
            link.classList.add('border-b-2','border-blue-600');
        });
    });

    // Edit Modal JS
function openEditModal(buttonOrId) {
  // Determine URL and updateUrl
  let url = null;
  let updateUrl = null;
  let invokingElement = null;
  if (typeof buttonOrId === 'object' && buttonOrId !== null) {
    invokingElement = buttonOrId;
    url = invokingElement.dataset.url || invokingElement.getAttribute('data-url') || null;
    updateUrl = invokingElement.dataset.updateUrl || invokingElement.getAttribute('data-update-url') || null;
  } else {
    // assume id
    const id = buttonOrId;
    url = `/oral_examination/${id}/edit`;
    updateUrl = `/oral_examination/${id}`;
  }

  if (!url) {
    alert('Edit URL missing.');
    console.error('openEditModal: no URL to fetch (button.dataset.url missing and no id passed).');
    return;
  }

  fetch(url, {
    method: 'GET',
    headers: { 'Accept': 'application/json' },
    credentials: 'same-origin'
  })
  .then(res => {
    const ct = res.headers.get('content-type') || '';
    if (!res.ok) {
      if (ct.includes('application/json')) return res.json().then(j => Promise.reject(j));
      return res.text().then(t => Promise.reject({ message: 'Non-JSON response', html: t, status: res.status }));
    }
    if (!ct.includes('application/json')) {
      // server returned HTML (likely login or error page)
      return res.text().then(t => Promise.reject({ message: 'Expected JSON but got HTML', html: t, status: res.status }));
    }
    return res.json();
  })
  .then(data => {
    const form = document.getElementById('editIntraoralForm');
    if (!form) {
      console.error('editIntraoralForm not found in DOM.');
      alert('Edit form not present on page.');
      return;
    }

    // Set form action: prefer updateUrl from button if provided, else fallback to conventional route
    if (updateUrl) {
      form.action = updateUrl;
    } else if (data && data.id) {
      // default fallback — adjust if your route has a prefix
      form.action = `/oral_examination/${data.id}`;
    }

    // Helper to set inputs safely (handles radios and selects)
    const safeSet = (name, value) => {
      const els = form.querySelectorAll('[name="'+name+'"]');
      if (!els || els.length === 0) return;
      // If radios or multiple elements with same name
      if (els.length > 1) {
        // try find matching value radio
        let matched = Array.from(els).find(e => e.type === 'radio' && e.value == (value ?? ''));
        if (matched) {
          matched.checked = true;
          return;
        }
        // else uncheck all or set default
        els.forEach(e => { if (e.type === 'radio') e.checked = false; });
        return;
      }
      const el = els[0];
      if (!el) return;
      const t = el.type;
      if (t === 'checkbox') {
        el.checked = !!value;
        return;
      }
      if (t === 'radio') {
        if (el.value == (value ?? '')) el.checked = true;
        return;
      }
      // otherwise set value (select/textarea/text)
      el.value = value ?? '';
    };
    


    // Map expected fields from server JSON to form inputs
    safeSet('patient_id', data.patient_id ?? '');
    if (data.date) {
    const d = new Date(data.date);
    safeSet('date', d.toISOString().split('T')[0]); // YYYY-MM-DD
} else {
    safeSet('date', new Date().toISOString().split('T')[0]);
}
    safeSet('soft_tissues_status', data.soft_tissues_status ?? '');
    safeSet('soft_tissues', data.soft_tissues ?? '');
    safeSet('gingiva_color', data.gingiva_color ?? '');
    safeSet('gingiva_texture', data.gingiva_texture ?? '');
    safeSet('bleeding', data.bleeding ?? 'No');
    safeSet('bleeding_area', data.bleeding_area ?? '');
    safeSet('recession', data.recession ?? 'No');
    safeSet('recession_area', data.recession_area ?? '');
    safeSet('teeth_condition', data.teeth_condition ?? '');
    safeSet('occlusion_class', data.occlusion_class ?? '');
    safeSet('occlusion_other', data.occlusion_other ?? '');
    safeSet('premature_contacts', data.premature_contacts ?? '');
    safeSet('hygiene_status', data.hygiene_status ?? '');
    safeSet('plaque_index', data.plaque_index ?? '');
    safeSet('calculus', data.calculus ?? '');

    // If you want to display links/previews for uploaded images, you can read data.probing_depths etc.
    // e.g. if (data.probing_depths) show a preview element (not implemented here).

    // Reset & show tabs: hide all edit-tab-content then show first
    document.querySelectorAll('.edit-tab-content').forEach(tc => tc.classList.add('hidden'));
    document.querySelectorAll('.edit-tab-link').forEach(l => l.classList.remove('border-b-2','border-yellow-500'));
    const firstTab = document.getElementById('edit-tab-soft');
    if (firstTab) firstTab.classList.remove('hidden');
    const firstLink = document.querySelector('.edit-tab-link');
    if (firstLink) firstLink.classList.add('border-b-2','border-yellow-500');

    // Show modal
    const modal = document.getElementById('editIntraoralModal');
    if (modal) modal.classList.remove('hidden');
  })
  .catch(err => {
    console.error('openEditModal error:', err);
    // If err.html exists it's likely HTML content (login or server error). For debugging you can open it in a new tab:
    if (err && err.html) {
      console.error('Server returned HTML (first 500 chars):', err.html.slice(0,500));
    }
    // Friendly message to user
    alert('Failed to load examination data. See console/Network for details (likely auth redirect, 404, or server error).');
  });
}

function closeEditModal(){
  const modal = document.getElementById('editIntraoralModal');
  if (modal) modal.classList.add('hidden');
}

/* Tabs for edit modal (attach listeners) */
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.edit-tab-link').forEach(link => {
    link.addEventListener('click', () => {
      document.querySelectorAll('.edit-tab-content').forEach(tc => tc.classList.add('hidden'));
      document.querySelectorAll('.edit-tab-link').forEach(l => l.classList.remove('border-b-2','border-yellow-500'));
      const tgtId = link.dataset.tab;
      if (tgtId) {
        const tgt = document.getElementById(tgtId);
        if (tgt) tgt.classList.remove('hidden');
      }
      link.classList.add('border-b-2','border-yellow-500');
    });
  });

  // Close when clicking outside modal content
  const editModal = document.getElementById('editIntraoralModal');
  if (editModal) {
    editModal.addEventListener('click', function(e) {
      if (e.target === this) closeEditModal();
    });
  }
});

// View Modal JS

function openViewModal(id) {
    fetch(`/oral_examination/${id}/view`, {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
    })
    .then(res => {
        if (!res.ok) throw new Error('Failed request');
        return res.json();
    })
    .then(data => {
        const content = document.getElementById('viewIntraoralContent');

        const safe = v => v ?? '-';

content.innerHTML = `
    <!-- BASIC INFO -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <p><strong>Patient:</strong> ${safe(data.patient_name)}</p>
        <p><strong>Date:</strong> ${
            data.date
                ? new Date(data.date).toLocaleDateString('en-US', {
                    month: 'short',
                    day: '2-digit',
                    year: 'numeric'
                })
                : '-'
        }</p>
    </div>

    <hr>

    <!-- SOFT TISSUES -->
    <div>
        <h3 class="font-semibold text-blue-600 mb-2">Soft Tissues</h3>
        <p><strong>Status:</strong> ${safe(data.soft_tissues_status)}</p>
        <p><strong>Notes:</strong> ${safe(data.soft_tissues)}</p>
    </div>

    <!-- GINGIVA -->
    <div>
        <h3 class="font-semibold text-blue-600 mb-2">Gingiva</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <p><strong>Color:</strong> ${safe(data.gingiva_color)}</p>
            <p><strong>Texture:</strong> ${safe(data.gingiva_texture)}</p>
            <p><strong>Bleeding:</strong> ${safe(data.bleeding)}</p>
            <p><strong>Bleeding Area:</strong> ${safe(data.bleeding_area)}</p>
            <p><strong>Recession:</strong> ${safe(data.recession)}</p>
            <p><strong>Recession Area:</strong> ${safe(data.recession_area)}</p>
        </div>
    </div>

    <!-- TEETH / OCCLUSION -->
    <div>
        <h3 class="font-semibold text-blue-600 mb-2">Teeth & Occlusion</h3>
        <p><strong>Teeth Condition:</strong> ${safe(data.teeth_condition)}</p>
        <p><strong>Occlusion:</strong>
            ${safe(data.occlusion_class)}
            ${data.occlusion_other ? ' — ' + data.occlusion_other : ''}
        </p>
        <p><strong>Premature Contacts:</strong> ${safe(data.premature_contacts)}</p>
    </div>

    <!-- ORAL HYGIENE -->
    <div>
        <h3 class="font-semibold text-blue-600 mb-2">Oral Hygiene</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
            <p><strong>Status:</strong> ${safe(data.hygiene_status)}</p>
            <p><strong>Plaque Index:</strong> ${safe(data.plaque_index)}</p>
            <p><strong>Calculus:</strong> ${safe(data.calculus)}</p>
        </div>
    </div>
<br>
    <!-- IMAGES -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        ${data.probing_depths ? `
            <div>
                <strong>Probing Depths</strong>
                <img src="${data.probing_depths}" class="mt-2 rounded border max-h-60">
            </div>` : ''}

        ${data.mobility ? `
            <div>
                <strong>Mobility</strong>
                <img src="${data.mobility}" class="mt-2 rounded border max-h-60">
            </div>` : ''}

        ${data.furcation ? `
            <div>
                <strong>Furcation</strong>
                <img src="${data.furcation}" class="mt-2 rounded border max-h-60">
            </div>` : ''}

        ${data.odontogram ? `
            <div>
                <strong>Odontogram</strong>
                <img src="${data.odontogram}" class="mt-2 rounded border max-h-60">
            </div>` : ''}
    </div>
`;
// ✅ Attach PDF download button dynamically
        const pdfBtn = document.getElementById('downloadPdfBtn');
        if (pdfBtn) {
            pdfBtn.onclick = () => {
                window.open(`/oral_examination/${id}/pdf`, '_blank');
            };
        }
        document.getElementById('viewIntraoralModal').classList.remove('hidden');
    })
    .catch(err => {
        console.error('View fetch error:', err);
        alert('Failed to fetch examination data.');

        
    });
}

function closeViewModal() {
    const modal = document.getElementById('viewIntraoralModal');
    if (modal) modal.classList.add('hidden');
}
// Delete Modal JS
function openDeleteModal(id, patientName) {
    const form = document.getElementById('deleteForm');
    const nameHolder = document.getElementById('deletePatientName');

    form.action = `/oral_examination/${id}`;
    nameHolder.textContent = patientName;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}


</script>


</x-app-layout>
