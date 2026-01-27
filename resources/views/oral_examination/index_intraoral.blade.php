@section('title', 'Intraoral Examinations')
<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Intraoral Examinations
        </h2>
        <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200 dark:border-gray-700">
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
         <button onclick="openViewModal({{ $exam->id }})" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">View</button>
        <button onclick="openEditModal(this)" data-url="{{ route('oral_examination.edit', $exam->id) }}" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>
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
<div id="createIntraoralModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="closeCreateModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">✕</button>
        <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">New Examination</h2>

        <form id="createIntraoralForm" action="{{ route('oral_examination.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tabs --}}
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
<div id="editIntraoralModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">✕</button>
        <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Edit Examination</h2>

        <form id="editIntraoralForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tabs --}}
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
<div id="viewIntraoralModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="closeViewModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">✕</button>
        <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">View Examination</h2>

        <div id="viewIntraoralContent" class="space-y-3 text-gray-800 dark:text-gray-200">
            {{-- Content will be filled dynamically --}}
        </div>

        <div class="flex justify-end mt-4">
            <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
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
    .then(res => res.json())
    .then(data => {
        const content = document.getElementById('viewIntraoralContent');
        content.innerHTML = `
            <p><strong>Patient:</strong> ${data.first_name}</p>
            <p><strong>Soft Tissues Status:</strong> ${data.soft_tissues_status}</p>
            <p><strong>Soft Tissues:</strong> ${data.soft_tissues}</p>
            <p><strong>Gingiva:</strong> ${data.gingiva_color} / ${data.gingiva_texture}</p>
            <p><strong>Bleeding:</strong> ${data.bleeding}</p>
            <p><strong>Recession:</strong> ${data.recession}</p>
            <p><strong>Teeth:</strong> ${data.teeth_condition}</p>
            <p><strong>Occlusion:</strong> ${data.occlusion_class}${data.occlusion_other ? ' — ' + data.occlusion_other : ''}</p>
            <p><strong>Hygiene:</strong> ${data.hygiene_status} — Plaque: ${data.plaque_index} — Calculus: ${data.calculus}</p>
            ${data.odontogram ? `<div><strong>Odontogram:</strong><br><img src="${data.odontogram}" class="max-h-60 mt-2" /></div>` : ''}
        `;
        document.getElementById('viewIntraoralModal').classList.remove('hidden');
    })
    .catch(err => {
        console.error('View fetch error:', err);
        alert('Failed to fetch examination data.');
    });
}

// Close modal
function closeViewModal() {
    document.getElementById('viewIntraoralModal').classList.add('hidden');
}

// Close modal when clicking outside content
document.getElementById('viewIntraoralModal').addEventListener('click', function(e) {
    if (e.target.id === 'viewIntraoralModal') {
        closeViewModal();
    }
});

</script>


</x-app-layout>
