<!-- KaiAdmin Main CSS (includes Bootstrap) -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">

<!-- JS -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

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
   <button onclick="openCreateModal()"
    class="btn btn-primary btn-md d-inline-flex align-items-center gap-2">
    <i class="fas fa-plus"></i>
    New Examination
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
                    <table id="myTable" class="min-w-full table-auto border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border px-4 py-2">Patient</th>
                            <th class="border px-4 py-2">Soft Tissues</th>
                            <th class="border px-4 py-2">Gingiva</th>
                            <th class="border px-4 py-2">Teeth</th>
                            <th class="border px-4 py-2">Occlusion</th>
                            <th class="border px-4 py-2">Oral Hygiene</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                 <tbody>
@forelse($examinations as $exam)
<tr class="text-gray-800 dark:text-gray-200">
    <td class="border px-4 py-2">{{ $exam->patient->full_name ?? '-' }}</td>

    {{-- Soft Tissues --}}
    <td class="border px-4 py-2">
        @if($exam->soft_tissues_status)
            {{ $exam->soft_tissues_status }}
        @endif
        @if($exam->soft_tissues)
            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($exam->soft_tissues, 30) }}</div>
        @endif
    </td>

    {{-- Gingiva --}}
    <td class="border px-4 py-2">
        Color / Texture: {{ $exam->gingiva_color ?? '-' }} / {{ $exam->gingiva_texture ?? '-' }}<br>
        Bleeding: {{ $exam->bleeding ?? 'No' }}<br>
        Recession: {{ $exam->recession ?? 'No' }}
    </td>

    {{-- Teeth / Hard Tissues --}}
    <td class="border px-4 py-2">{{ $exam->teeth_condition ?? '-' }}</td>

    {{-- Occlusion --}}
    <td class="border px-4 py-2">
        {{ $exam->occlusion_class ?? '-' }}
        @if($exam->occlusion_other)
            <br>{{ $exam->occlusion_other }}
        @endif
        @if($exam->premature_contacts)
            <br>Premature: {{ $exam->premature_contacts }}
        @endif
    </td>

    {{-- Oral Hygiene --}}
    <td class="border px-4 py-2">
        Status: {{ $exam->hygiene_status ?? '-' }}<br>
        Plaque: {{ $exam->plaque_index ?? '-' }}<br>
        Calculus: {{ $exam->calculus ?? '-' }}
    </td>

    {{-- Actions --}}
    <td class="border px-4 py-2 flex gap-2">
       <button onclick="openViewModal({{ $exam->id }}, event) " class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">View</button>
       <button onclick="openEditModal(this, event)" data-url="{{ route('oral_examination.edit', $exam->id) }}" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>
        <form action="{{ route('oral_examination.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Delete this examination?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="border px-4 py-2 text-center text-gray-500">No examinations found.</td>
</tr>
@endforelse
</tbody>

 </table>

        {{-- Pagination --}}
                <div class="mt-4">
                    {{ $examinations->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div id="createIntraoralModal" class="fixed inset-0 bg-transparent flex items-center justify-center z-[9999] hidden">

    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl  relative overflow-y-auto max-h-[90vh]">
       
    <!-- HEADER -->
<div class="bg-blue-600 text-white p-4 rounded-t flex justify-between items-center">
    <h2 class="text-lg font-bold">New Examination</h2>
    <button type="button"
        onclick="closeCreateModal()"
        class="text-white hover:text-gray-200 text-2xl font-extrabold leading-none">
        &times;
    </button>
</div>
<div class="p-6">
    <form id="createIntraoralForm" action="{{ route('oral_examination.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
{{-- Tabs --}}
<div class="mb-4">
    <ul class="flex border-b border-gray-200 dark:border-gray-700" id="create-tabs">
        <li class="mr-1">
            <button type="button" class="create-tab-link px-4 py-2 font-semibold rounded-t
                   bg-blue-600 text-white" data-tab="create-tab-soft">Soft Tissues</button>
        </li>
        <li class="mr-1">
            <button type="button" class="create-tab-link px-4 py-2 font-semibold rounded-t
                   bg-gray-200 text-gray-700 hover:bg-gray-300" data-tab="create-tab-gingiva">Gingiva</button>
        </li>
        <li class="mr-1">
            <button type="button" class="create-tab-link px-4 py-2 font-semibold rounded-t
                   bg-gray-200 text-gray-700 hover:bg-gray-300" data-tab="create-tab-periodontium">Periodontium</button>
        </li>
        <li class="mr-1">
            <button type="button" class="create-tab-link px-4 py-2 font-semibold rounded-t
                   bg-gray-200 text-gray-700 hover:bg-gray-300" data-tab="create-tab-teeth">Hard Tissues</button>
        </li>
        <li class="mr-1">
            <button type="button" class="create-tab-link px-4 py-2 font-semibold rounded-t
                   bg-gray-200 text-gray-700 hover:bg-gray-300" data-tab="create-tab-occlusion">Occlusion</button>
        </li>
        <li class="mr-1">
            <button type="button" class="create-tab-link px-4 py-2 font-semibold rounded-t
                   bg-gray-200 text-gray-700 hover:bg-gray-300" data-tab="create-tab-hygiene">Oral Hygiene</button>
        </li>
    </ul>
</div>
            {{-- Tab Contents --}}
            <div>
                {{-- Soft Tissues --}}
                <div id="create-tab-soft" class="create-tab-content mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" class="w-full border rounded px-2 py-1 mb-2" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                        @endforeach
                    </select>

                    <label class="block mb-1">Soft Tissues (Lips, Cheeks, Tongue, Floor of Mouth, Palate, Oropharynx)</label>
                    <select name="soft_tissues_status" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="Normal">Normal</option>
                        <option value="Abnormal">Abnormal</option>
                    </select>
                    <textarea name="soft_tissues" rows="3" class="w-full border rounded px-2 py-1" placeholder="Specify any lesions, swelling, discoloration, etc."></textarea>
                </div>

                {{-- Gingiva --}}
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

                {{-- Periodontium --}}
                <div id="create-tab-periodontium" class="create-tab-content mb-4 hidden">
                    <label>Probing Depths (Upload Periodontal Chart)</label>
                    <input type="file" name="probing_depths" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Mobility (Upload Odontogram)</label>
                    <input type="file" name="mobility" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Furcation Involvement (Upload Odontogram)</label>
                    <input type="file" name="furcation" accept="image/*" class="w-full mt-1">
                </div>

                {{-- Hard Tissues --}}
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

                {{-- Occlusion --}}
                <div id="create-tab-occlusion" class="create-tab-content mb-4 hidden">
                    <label>Occlusion Class</label>
                    <input type="text" name="occlusion_class" class="w-full border rounded px-2 py-1 mb-2" placeholder="Class I / Class II / Class III">
                    <input type="text" name="occlusion_other" class="w-full border rounded px-2 py-1 mb-2" placeholder="Open Bite / Deep Bite / Overjet / Overbite">
                    <input type="text" name="premature_contacts" class="w-full border rounded px-2 py-1" placeholder="Premature Contacts / Interferences">
                </div>

                {{-- Oral Hygiene --}}
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
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT MODAL --}}
<div id="editIntraoralModal"
     class="fixed inset-0 bg-transparent flex items-center justify-center z-[9999] hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
  <!-- HEADER -->
<div class="bg-blue-600 text-white p-4 rounded-t-lg flex items-center justify-between">
    <h2 class="text-lg font-semibold">Edit Examination</h2>
    <button onclick="closeEditModal()" class="text-white text-2xl hover:text-gray-200">
        &times;
    </button>
</div>

        <form id="editIntraoralForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

        <!-- Tabs -->
<div class="bg-white px-4 pt-3 border-b">
    <ul class="flex gap-1" id="edit-tabs">
        <li>
            <button type="button"
                class="edit-tab-link px-4 py-2 rounded-t bg-blue-600 text-white"
                data-tab="edit-tab-soft">Soft Tissues</button>
        </li>
        <li>
            <button type="button"
                class="edit-tab-link px-4 py-2 rounded-t bg-gray-200 text-gray-700 hover:bg-gray-300"
                data-tab="edit-tab-gingiva">Gingiva</button>
        </li>
        <li>
            <button type="button"
                class="edit-tab-link px-4 py-2 rounded-t bg-gray-200 text-gray-700 hover:bg-gray-300"
                data-tab="edit-tab-periodontium">Periodontium</button>
        </li>
        <li>
            <button type="button"
                class="edit-tab-link px-4 py-2 rounded-t bg-gray-200 text-gray-700 hover:bg-gray-300"
                data-tab="edit-tab-teeth">Hard Tissues</button>
        </li>
        <li>
            <button type="button"
                class="edit-tab-link px-4 py-2 rounded-t bg-gray-200 text-gray-700 hover:bg-gray-300"
                data-tab="edit-tab-occlusion">Occlusion</button>
        </li>
        <li>
            <button type="button"
                class="edit-tab-link px-4 py-2 rounded-t bg-gray-200 text-gray-700 hover:bg-gray-300"
                data-tab="edit-tab-hygiene">Oral Hygiene</button>
        </li>
    </ul>
</div>

            {{-- Tab Contents --}}
            <div>
                {{-- Use same structure as create modal but IDs prefixed with edit-tab- --}}
                {{-- Soft Tissues --}}
                <div id="edit-tab-soft" class="edit-tab-content mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" class="w-full border rounded px-2 py-1 mb-2" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                        @endforeach
                    </select>

                    <label class="block mb-1">Soft Tissues</label>
                    <select name="soft_tissues_status" class="w-full border rounded px-2 py-1 mb-2">
                        <option value="Normal">Normal</option>
                        <option value="Abnormal">Abnormal</option>
                    </select>
                    <textarea name="soft_tissues" rows="3" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                {{-- Gingiva --}}
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

                {{-- Periodontium --}}
                <div id="edit-tab-periodontium" class="edit-tab-content mb-4 hidden">
                    <label>Probing Depths</label>
                    <input type="file" name="probing_depths" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Mobility</label>
                    <input type="file" name="mobility" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Furcation Involvement</label>
                    <input type="file" name="furcation" accept="image/*" class="w-full mt-1">
                </div>

                {{-- Hard Tissues --}}
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

                {{-- Occlusion --}}
                <div id="edit-tab-occlusion" class="edit-tab-content mb-4 hidden">
                    <label>Occlusion Class</label>
                    <input type="text" name="occlusion_class" class="w-full border rounded px-2 py-1 mb-2">
                    <input type="text" name="occlusion_other" class="w-full border rounded px-2 py-1 mb-2">
                    <input type="text" name="premature_contacts" class="w-full border rounded px-2 py-1">
                </div>

                {{-- Oral Hygiene --}}
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
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- VIEW MODAL --}}
<div id="viewIntraoralModal"
     class="fixed inset-0  flex items-center justify-center z-50 hidden">

    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">

        <button onclick="closeViewModal()"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            ✕
        </button>

        <h2 class="text-2xl font-semibold mb-4">
            Intraoral Examination (View)
        </h2>

        <div id="viewIntraoralContent" class="space-y-3"></div>

        <div class="flex justify-end mt-4">
            <button onclick="closeViewModal()"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>
<script>
/* ================= GLOBAL MODAL CONTROL ================= */
function closeAllIntraoralModals() {
    [
        'createIntraoralModal',
        'editIntraoralModal',
        'viewIntraoralModal'
    ].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
}

/* ================= CREATE MODAL ================= */
function openCreateModal(){
    closeAllIntraoralModals(); // ✅ important

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

/* Create tabs */
document.querySelectorAll('.create-tab-link').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelectorAll('.create-tab-content').forEach(tc => tc.classList.add('hidden'));
        document.querySelectorAll('.create-tab-link').forEach(l => l.classList.remove('border-b-2','border-blue-600'));

        document.getElementById(link.dataset.tab).classList.remove('hidden');
        link.classList.add('border-b-2','border-blue-600');
    });
});

/* ================= EDIT MODAL ================= */
function openEditModal(buttonOrId) {
    closeAllIntraoralModals(); // ✅ important

    const modal = document.getElementById('editIntraoralModal');
    if (modal) modal.classList.remove('hidden'); // ✅ open immediately

    let url = null;
    let updateUrl = null;

    if (typeof buttonOrId === 'object' && buttonOrId !== null) {
        url = buttonOrId.dataset.url || buttonOrId.getAttribute('data-url');
        updateUrl = buttonOrId.dataset.updateUrl || buttonOrId.getAttribute('data-update-url');
    } else {
        url = `/oral_examination/${buttonOrId}/edit`;
        updateUrl = `/oral_examination/${buttonOrId}`;
    }

    if (!url) {
        alert('Edit URL missing.');
        return;
    }

    fetch(url, {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {
        const form = document.getElementById('editIntraoralForm');
        if (!form) return;

        form.action = updateUrl || `/oral_examination/${data.id}`;

        const safeSet = (name, value) => {
            const els = form.querySelectorAll(`[name="${name}"]`);
            if (!els.length) return;

            if (els.length > 1) {
                els.forEach(e => e.checked = (e.value == value));
                return;
            }

            const el = els[0];
            if (el.type === 'checkbox') el.checked = !!value;
            else el.value = value ?? '';
        };

        safeSet('patient_id', data.patient_id);
        safeSet('soft_tissues_status', data.soft_tissues_status);
        safeSet('soft_tissues', data.soft_tissues);
        safeSet('gingiva_color', data.gingiva_color);
        safeSet('gingiva_texture', data.gingiva_texture);
        safeSet('bleeding', data.bleeding ?? 'No');
        safeSet('bleeding_area', data.bleeding_area);
        safeSet('recession', data.recession ?? 'No');
        safeSet('recession_area', data.recession_area);
        safeSet('teeth_condition', data.teeth_condition);
        safeSet('occlusion_class', data.occlusion_class);
        safeSet('occlusion_other', data.occlusion_other);
        safeSet('premature_contacts', data.premature_contacts);
        safeSet('hygiene_status', data.hygiene_status);
        safeSet('plaque_index', data.plaque_index);
        safeSet('calculus', data.calculus);

        document.querySelectorAll('.edit-tab-content').forEach(tc => tc.classList.add('hidden'));
        document.querySelectorAll('.edit-tab-link').forEach(l => l.classList.remove('border-b-2','border-yellow-500'));

        document.getElementById('edit-tab-soft').classList.remove('hidden');
        document.querySelector('.edit-tab-link').classList.add('border-b-2','border-yellow-500');
    })
    .catch(() => {
        alert('Failed to load examination data');
        closeEditModal();
    });
}

function closeEditModal(){
    document.getElementById('editIntraoralModal').classList.add('hidden');
}

/* Edit tabs */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-tab-link').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.edit-tab-content').forEach(tc => tc.classList.add('hidden'));
            document.querySelectorAll('.edit-tab-link').forEach(l => l.classList.remove('border-b-2','border-yellow-500'));

            document.getElementById(link.dataset.tab).classList.remove('hidden');
            link.classList.add('border-b-2','border-yellow-500');
        });
    });

    const editModal = document.getElementById('editIntraoralModal');
    if (editModal) {
        editModal.addEventListener('click', e => {
            if (e.target === editModal) closeEditModal();
        });
    }
});

/* ================= VIEW MODAL ================= */
function openViewModal(id) {
    closeAllIntraoralModals(); // ✅ important

    const modal = document.getElementById('viewIntraoralModal');
    modal.classList.remove('hidden'); // ✅ open immediately

    fetch(`/oral_examination/${id}/view`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('viewIntraoralContent').innerHTML = `
            <div class="space-y-2">
                <div><strong>Patient:</strong> ${data.patient_name ?? '-'}</div>
                <div><strong>Soft Tissues Status:</strong> ${data.soft_tissues_status ?? '-'}</div>
                <div><strong>Soft Tissues:</strong> ${data.soft_tissues ?? '-'}</div>
                <div><strong>Gingiva:</strong> ${data.gingiva_color ?? '-'} / ${data.gingiva_texture ?? '-'}</div>
                <div><strong>Bleeding:</strong> ${data.bleeding ?? 'No'}</div>
                <div><strong>Recession:</strong> ${data.recession ?? 'No'}</div>
                <div><strong>Teeth Condition:</strong> ${data.teeth_condition ?? '-'}</div>
                <div><strong>Occlusion:</strong> ${data.occlusion_class ?? '-'} ${data.occlusion_other ?? ''}</div>
                <div><strong>Oral Hygiene:</strong><br>
                    Status: ${data.hygiene_status ?? '-'}<br>
                    Plaque: ${data.plaque_index ?? '-'}<br>
                    Calculus: ${data.calculus ?? '-'}
                </div>

                ${data.odontogram ? `
                    <div>
                        <strong>Odontogram:</strong><br>
                        <img src="${data.odontogram}" class="mt-2 max-h-64 border rounded">
                    </div>` : ''}
            </div>
        `;
    })
    .catch(() => {
        alert('Failed to load data');
        closeViewModal();
    });
}

function closeViewModal(){
    document.getElementById('viewIntraoralModal').classList.add('hidden');
}
</script>

</x-app-layout>
