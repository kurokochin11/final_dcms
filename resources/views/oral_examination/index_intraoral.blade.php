<!-- KaiAdmin Main CSS (includes Bootstrap) -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
 <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
  <!-- JS -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
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
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Intraoral Examinations
    </h2>
</div>
</x-slot>

<div class="py-12">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10">

        <h3 class="text-xl font-semibold text-gray-800 mb-6">Records</h3>

        @if(session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm border rounded-xl overflow-hidden">
            
            <div class="p-4 border-b bg-white">
                <div class="d-flex align-items-end justify-content-between flex-wrap gap-4">
                    
                    <div class="d-flex align-items-end gap-3">
                       <div class="flex-column">
    <label class="text-xs font-bold text-gray-400 uppercase mb-1 d-block tracking-wider">Patient Filter</label>
    <select id="filterPatient" class="form-select form-select-sm border-gray-300" style="width:240px;">
        <option value="">All Patients</option>
        @foreach($patients as $patient)
            <option value="{{ $patient->full_name }}">
                {{ $patient->full_name }}
            </option>
        @endforeach
    </select>
</div>
                        <div class="flex-column">
                            <select id="filterDate" class="form-select form-select-sm border-gray-300" style="width:180px;">
                                <option value="">All Dates</option>
                                @foreach($examinations->pluck('date')->unique()->sortDesc() as $date)
                                    <option value="{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}">
                                        {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button id="resetFilters" class="btn btn-outline-secondary btn-sm border-gray-300" title="Reset">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>

                    <div>
                        <button onclick="openCreateModal()" class="btn btn-primary px-4 shadow-sm d-flex align-items-center gap-2">
                            <i class="fas fa-plus"></i> NEW EXAMINATION
                        </button>
                    </div>
                </div>
            </div>

             <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-gray-700">
                            <th class="ps-6 py-3 text-xs font-bold uppercase tracking-wider border-end" style="width: 120px;">Patient No <i class="fas fa-sort text-gray-300 ms-1"></i></th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider border-end">Patient <i class="fas fa-sort text-gray-300 ms-1"></i></th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider border-end">Date <i class="fas fa-sort text-gray-300 ms-1"></i></th>
                            <th class="pe-6 py-3 text-xs font-bold uppercase tracking-wider text-start">Actions <i class="fas fa-sort text-gray-300 ms-1"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examinations as $exam)
                            <tr class="border-bottom hover:bg-gray-50 transition-colors">
                                <td class="ps-6 py-4 border-end bg-gray-50/30">{{ $exam->patient->id ?? '-' }}</td>
                                <td class="px-4 py-4 font-medium border-end text-gray-900">{{ $exam->patient->full_name ?? '-' }}</td>
                                <td class="px-4 py-4 border-end text-gray-600">
                                    {{ $exam->date ? \Carbon\Carbon::parse($exam->date)->format('M d, Y') : '-' }}
                                </td>
                                <td class="pe-6 py-4">
                                    <div class="d-flex gap-2">
                                        <button onclick="openViewModal({{ $exam->id }})" class="btn btn-primary btn-md  ">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="openEditModal(this)" data-url="{{ route('oral_examination.edit',$exam->id) }}" class="btn btn-warning btn-md text-white  ">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="openDeleteModal({{ $exam->id }}, '{{ $exam->patient->full_name }}')" class="btn btn-danger btn-md  ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-400">
                                    <i class="fas fa-folder-open d-block mb-2 style-2xl"></i>
                                    No examinations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
</div>

               <!-- {{-- Pagination --}} -->
<div class="mt-4">
    {{ $examinations->links() }}
</div>
            </div>
        </div>
    </div>
</div>

<!-- {{-- ADD Modal --}} -->
<div id="createIntraoralModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <!-- MODAL CONTAINER -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-7xl mx-4 shadow-xl flex flex-col">

        <!-- HEADER -->
        <div class="bg-primary text-white px-8 py-5 rounded-t-xl relative">
            <h2 class="text-2xl font-semibold">New Examination</h2>

            <button onclick="closeCreateModal()"
                class="absolute top-4 right-4 text-white rounded-full w-9 h-9 flex items-center justify-center hover:bg-white/20 transition">
                ✕
            </button>
        </div>

        <!-- FORM -->
        <form id="createIntraoralForm"
            action="{{ route('oral_examination.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="flex flex-col h-full">
            @csrf

            <!-- TABS -->
            <div class="px-6 pt-4">
                <ul class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                    <li><button type="button" class="create-tab-link px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-700"
                            data-tab="create-tab-soft">Soft Tissues</button></li>
                    <li><button type="button" class="create-tab-link px-4 py-2 rounded-md"
                            data-tab="create-tab-gingiva">Gingiva</button></li>
                    <li><button type="button" class="create-tab-link px-4 py-2 rounded-md"
                            data-tab="create-tab-periodontium">Periodontium</button></li>
                    <li><button type="button" class="create-tab-link px-4 py-2 rounded-md"
                            data-tab="create-tab-teeth">Hard Tissues</button></li>
                    <li><button type="button" class="create-tab-link px-4 py-2 rounded-md"
                            data-tab="create-tab-occlusion">Occlusion</button></li>
                    <li><button type="button" class="create-tab-link px-4 py-2 rounded-md"
                            data-tab="create-tab-hygiene">Oral Hygiene</button></li>
                </ul>
            </div>

            <!-- CONTENT -->
            <div class="px-8 py-6 overflow-y-auto max-h-[65vh] space-y-6">

                <!-- SOFT TISSUES -->
                <div id="create-tab-soft" class="create-tab-content">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-1">Patient</label>
                            <select name="patient_id"
                                class="w-full border rounded px-3 py-2" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1">Examination Date</label>
                            <input type="date" name="date"
                                class="w-full border rounded px-3 py-2"
                                value="{{ now()->toDateString() }}" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block mb-1">Soft Tissues</label>
                        <select name="soft_tissues_status"
                            class="w-full border rounded px-3 py-2 mb-2">
                            <option value="Normal">Normal</option>
                            <option value="Abnormal">Abnormal</option>
                        </select>

                        <textarea name="soft_tissues" rows="3"
                            class="w-full border rounded px-3 py-2"
                            placeholder="Specify lesions, swelling, etc."></textarea>
                    </div>
                </div>

                <!-- GINGIVA -->
                <div id="create-tab-gingiva" class="create-tab-content hidden">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label>Color</label>
                            <select name="gingiva_color" class="w-full border rounded px-3 py-2">
                                <option value="">Select</option>
                                <option>Pink</option>
                                <option>Red</option>
                                <option>Cyanotic</option>
                            </select>
                        </div>

                        <div>
                            <label>Texture</label>
                            <select name="gingiva_texture" class="w-full border rounded px-3 py-2">
                                <option value="">Select</option>
                                <option>Stippled</option>
                                <option>Edematous</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        <div>
                            <label>Bleeding</label>
                            <div class="flex gap-4 mt-1">
                                <label><input type="radio" name="bleeding" value="Yes"> Yes</label>
                                <label><input type="radio" name="bleeding" value="No" checked> No</label>
                            </div>
                            <input type="text" name="bleeding_area"
                                class="w-full border rounded px-3 py-2 mt-1"
                                placeholder="Specify area">
                        </div>

                        <div>
                            <label>Recession</label>
                            <div class="flex gap-4 mt-1">
                                <label><input type="radio" name="recession" value="Yes"> Yes</label>
                                <label><input type="radio" name="recession" value="No" checked> No</label>
                            </div>
                            <input type="text" name="recession_area"
                                class="w-full border rounded px-3 py-2 mt-1"
                                placeholder="Specify area">
                        </div>
                    </div>
                </div>

                <!-- PERIODONTIUM -->
                <div id="create-tab-periodontium" class="create-tab-content hidden space-y-4">
                    <div>
                        <label>Probing Depths</label>
                        <input type="file" name="probing_depths" class="w-full mt-1">
                    </div>

                    <div>
                        <label>Mobility</label>
                        <input type="file" name="mobility" class="w-full mt-1">
                    </div>

                    <div>
                        <label>Furcation</label>
                        <input type="file" name="furcation" class="w-full mt-1">
                    </div>
                </div>

                <!-- TEETH -->
                <div id="create-tab-teeth" class="create-tab-content hidden">
                    <label>Teeth Condition</label>
                    <select name="teeth_condition"
                        class="w-full border rounded px-3 py-2 mb-3">
                        <option value="">Select</option>
                        <option>Missing</option>
                        <option>Caries</option>
                        <option>Fillings</option>
                        <option>Crowns</option>
                        <option>Bridges</option>
                        <option>Implants</option>
                        <option>RCT</option>
                    </select>

                    <label>Odontogram</label>
                    <input type="file" name="odontogram" class="w-full mt-1">
                </div>

                <!-- OCCLUSION -->
                <div id="create-tab-occlusion" class="create-tab-content hidden">
                    <select name="occlusion_class"
                        class="w-full border rounded px-3 py-2 mb-2">
                        <option value="">Class</option>
                        <option>Class I</option>
                        <option>Class II</option>
                        <option>Class III</option>
                    </select>

                    <select name="occlusion_other"
                        class="w-full border rounded px-3 py-2 mb-2">
                        <option value="">Type</option>
                        <option>Open Bite</option>
                        <option>Deep Bite</option>
                    </select>

                    <input type="text" name="premature_contacts"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Contacts">
                </div>

                <!-- HYGIENE -->
                <div id="create-tab-hygiene" class="create-tab-content hidden">
                    <select name="hygiene_status"
                        class="w-full border rounded px-3 py-2 mb-2">
                        <option value="">Status</option>
                        <option>Good</option>
                        <option>Fair</option>
                        <option>Poor</option>
                    </select>

                    <input type="text" name="plaque_index"
                        class="w-full border rounded px-3 py-2 mb-2"
                        placeholder="Plaque Index">

                    <select name="calculus"
                        class="w-full border rounded px-3 py-2">
                        <option value="">Calculus</option>
                        <option>Light</option>
                        <option>Moderate</option>
                        <option>Heavy</option>
                    </select>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="px-6 py-4 border-t flex justify-end gap-3 bg-gray-50 dark:bg-gray-700 rounded-b-xl">
                <button type="button" onclick="closeCreateModal()" class="btn btn-black btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>

        </form>
    </div>
</div>

<!-- {{-- EDIT MODAL --}} -->
<div id="editIntraoralModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <!-- MODAL CONTAINER -->
    <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-7xl mx-4 shadow-xl flex flex-col">

        <!-- HEADER -->
        <div class="bg-primary text-white px-8 py-5 rounded-t-xl relative">
            <h2 class="text-2xl font-semibold">Edit Examination</h2>

            <button onclick="closeEditModal()"
                class="absolute top-4 right-4 text-white rounded-full w-9 h-9 flex items-center justify-center hover:bg-white/20 transition">
                ✕
            </button>
        </div>

        <!-- FORM -->
        <form id="editIntraoralForm" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
            @csrf
            @method('PUT')

            <!-- TABS -->
            <div class="px-6 pt-4">
                <ul class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                    <li><button type="button" class="edit-tab-link px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-700"
                            data-tab="edit-tab-soft">Soft Tissues</button></li>
                    <li><button type="button" class="edit-tab-link px-4 py-2 rounded-md"
                            data-tab="edit-tab-gingiva">Gingiva</button></li>
                    <li><button type="button" class="edit-tab-link px-4 py-2 rounded-md"
                            data-tab="edit-tab-periodontium">Periodontium</button></li>
                    <li><button type="button" class="edit-tab-link px-4 py-2 rounded-md"
                            data-tab="edit-tab-teeth">Hard Tissues</button></li>
                    <li><button type="button" class="edit-tab-link px-4 py-2 rounded-md"
                            data-tab="edit-tab-occlusion">Occlusion</button></li>
                    <li><button type="button" class="edit-tab-link px-4 py-2 rounded-md"
                            data-tab="edit-tab-hygiene">Oral Hygiene</button></li>
                </ul>
            </div>

            <!-- CONTENT -->
            <div class="px-8 py-6 overflow-y-auto max-h-[65vh] space-y-6">

                <!-- SOFT TISSUES -->
                <div id="edit-tab-soft" class="edit-tab-content">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-1">Patient</label>
                            <select name="patient_id" class="w-full border rounded px-3 py-2" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1">Examination Date</label>
                            <input type="date" name="date"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block mb-1">Soft Tissues</label>
                        <select name="soft_tissues_status"
                            class="w-full border rounded px-3 py-2 mb-2">
                            <option value="Normal">Normal</option>
                            <option value="Abnormal">Abnormal</option>
                        </select>

                        <textarea name="soft_tissues" rows="3"
                            class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                </div>

                <!-- GINGIVA -->
                <div id="edit-tab-gingiva" class="edit-tab-content hidden">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label>Color</label>
                            <select name="gingiva_color" class="w-full border rounded px-3 py-2">
                                <option value="">Select</option>
                                <option>Pink</option>
                                <option>Red</option>
                                <option>Cyanotic</option>
                            </select>
                        </div>

                        <div>
                            <label>Texture</label>
                            <select name="gingiva_texture" class="w-full border rounded px-3 py-2">
                                <option value="">Select</option>
                                <option>Stippled</option>
                                <option>Edematous</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        <div>
                            <label>Bleeding</label>
                            <div class="flex gap-4 mt-1">
                                <label><input type="radio" name="bleeding" value="Yes"> Yes</label>
                                <label><input type="radio" name="bleeding" value="No"> No</label>
                            </div>
                            <input type="text" name="bleeding_area"
                                class="w-full border rounded px-3 py-2 mt-1">
                        </div>

                        <div>
                            <label>Recession</label>
                            <div class="flex gap-4 mt-1">
                                <label><input type="radio" name="recession" value="Yes"> Yes</label>
                                <label><input type="radio" name="recession" value="No"> No</label>
                            </div>
                            <input type="text" name="recession_area"
                                class="w-full border rounded px-3 py-2 mt-1">
                        </div>
                    </div>
                </div>

                <!-- PERIODONTIUM -->
                <div id="edit-tab-periodontium" class="edit-tab-content hidden space-y-4">
                    <div>
                        <label>Probing Depths</label>
                        <input type="file" name="probing_depths" class="w-full mt-1">
                    </div>

                    <div>
                        <label>Mobility</label>
                        <input type="file" name="mobility" class="w-full mt-1">
                    </div>

                    <div>
                        <label>Furcation</label>
                        <input type="file" name="furcation" class="w-full mt-1">
                    </div>
                </div>

                <!-- TEETH -->
                <div id="edit-tab-teeth" class="edit-tab-content hidden">
                    <label>Teeth Condition</label>
                    <select name="teeth_condition"
                        class="w-full border rounded px-3 py-2 mb-3">
                        <option value="">Select</option>
                        <option>Missing</option>
                        <option>Caries</option>
                        <option>Fillings</option>
                        <option>Crowns</option>
                        <option>Bridges</option>
                        <option>Implants</option>
                        <option>RCT</option>
                    </select>

                    <label>Odontogram</label>
                    <input type="file" name="odontogram" class="w-full mt-1">
                </div>

                <!-- OCCLUSION -->
                <div id="edit-tab-occlusion" class="edit-tab-content hidden">
                    <select name="occlusion_class"
                        class="w-full border rounded px-3 py-2 mb-2">
                        <option value="">Class</option>
                        <option>Class I</option>
                        <option>Class II</option>
                        <option>Class III</option>
                    </select>

                    <select name="occlusion_other"
                        class="w-full border rounded px-3 py-2 mb-2">
                        <option value="">Type</option>
                        <option>Open Bite</option>
                        <option>Deep Bite</option>
                    </select>

                    <input type="text" name="premature_contacts"
                        class="w-full border rounded px-3 py-2">
                </div>

                <!-- HYGIENE -->
                <div id="edit-tab-hygiene" class="edit-tab-content hidden">
                    <select name="hygiene_status"
                        class="w-full border rounded px-3 py-2 mb-2">
                        <option value="">Status</option>
                        <option>Good</option>
                        <option>Fair</option>
                        <option>Poor</option>
                    </select>

                    <input type="text" name="plaque_index"
                        class="w-full border rounded px-3 py-2 mb-2">

                    <select name="calculus"
                        class="w-full border rounded px-3 py-2">
                        <option value="">Calculus</option>
                        <option>Light</option>
                        <option>Moderate</option>
                        <option>Heavy</option>
                    </select>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="px-6 py-4 border-t flex justify-end gap-3 bg-gray-50 dark:bg-gray-700 rounded-b-xl">
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
