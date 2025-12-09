{{-- resources/views/oral_examination/index_intraoral.blade.php --}}
<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Intraoral Examinations
        </h2>
        <button onclick="openIntraoralModal('add')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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
                                <td class="border px-4 py-2">{{ Str::limit($exam->soft_tissues, 30) }}</td>
                                <td class="border px-4 py-2">{{ $exam->gingiva_color ?? '-' }} / {{ $exam->gingiva_texture ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $exam->teeth_condition ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $exam->occlusion_class ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $exam->hygiene_status ?? '-' }}</td>
                                <td class="border px-4 py-2 flex gap-2">
                                    <button onclick="openIntraoralModal('edit', {{ $exam->id }})" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>
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

{{-- Tabbed Modal --}}
<div id="intraoralModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-5xl p-6 relative overflow-y-auto max-h-[90vh]">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">✕</button>
        <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100" id="modalTitle">New Examination</h2>

        <form id="intraoralForm" action="{{ route('oral_examination.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="exam_id" id="exam_id">

            {{-- Tabs --}}
            <div class="mb-4">
                <ul class="flex border-b border-gray-200 dark:border-gray-700" id="tabs">
                    <li class="-mb-px mr-1"><button type="button" class="tab-link px-4 py-2 font-semibold" data-tab="tab-soft">Soft Tissues</button></li>
                    <li class="mr-1"><button type="button" class="tab-link px-4 py-2 font-semibold" data-tab="tab-gingiva">Gingiva</button></li>
                    <li class="mr-1"><button type="button" class="tab-link px-4 py-2 font-semibold" data-tab="tab-periodontium">Periodontium</button></li>
                    <li class="mr-1"><button type="button" class="tab-link px-4 py-2 font-semibold" data-tab="tab-teeth">Hard Tissues</button></li>
                    <li class="mr-1"><button type="button" class="tab-link px-4 py-2 font-semibold" data-tab="tab-occlusion">Occlusion</button></li>
                    <li class="mr-1"><button type="button" class="tab-link px-4 py-2 font-semibold" data-tab="tab-hygiene">Oral Hygiene</button></li>
                </ul>
            </div>

            {{-- Tab Contents --}}
            <div>
                {{-- Soft Tissues --}}
                <div id="tab-soft" class="tab-content mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" id="patient_id" class="w-full border rounded px-2 py-1 mb-2" required>
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
                   <textarea id="soft_tissues" name="soft_tissues" rows="3" class="w-full border rounded px-2 py-1" placeholder="Specify any lesions, swelling, discoloration, etc."></textarea>
                </div>

                {{-- Gingiva --}}
                <div id="tab-gingiva" class="tab-content mb-4 hidden">
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
                <div id="tab-periodontium" class="tab-content mb-4 hidden">
                    <label>Probing Depths (Upload Periodontal Chart)</label>
                    <input type="file" name="probing_depths" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Mobility (Upload Odontogram)</label>
                    <input type="file" name="mobility" accept="image/*" class="w-full mt-1">

                    <label class="mt-2">Furcation Involvement (Upload Odontogram)</label>
                    <input type="file" name="furcation" accept="image/*" class="w-full mt-1">
                </div>

                {{-- Hard Tissues --}}
                <div id="tab-teeth" class="tab-content mb-4 hidden">
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
                <div id="tab-occlusion" class="tab-content mb-4 hidden">
                    <label>Occlusion Class</label>
                    <input type="text" name="occlusion_class" class="w-full border rounded px-2 py-1 mb-2" placeholder="Class I / Class II / Class III">
                    <input type="text" name="occlusion_other" class="w-full border rounded px-2 py-1 mb-2" placeholder="Open Bite / Deep Bite / Overjet / Overbite">
                    <input type="text" name="premature_contacts" class="w-full border rounded px-2 py-1" placeholder="Premature Contacts / Interferences">
                </div>

                {{-- Oral Hygiene --}}
                <div id="tab-hygiene" class="tab-content mb-4 hidden">
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
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('intraoralModal').classList.add('hidden');
}

// Tabs (unchanged)
const tabLinks = document.querySelectorAll('.tab-link');
const tabContents = document.querySelectorAll('.tab-content');

tabLinks.forEach(link => {
    link.addEventListener('click', () => {
        tabContents.forEach(tc => tc.classList.add('hidden'));
        tabLinks.forEach(l => l.classList.remove('border-b-2','border-blue-600'));
        document.getElementById(link.dataset.tab).classList.remove('hidden');
        link.classList.add('border-b-2','border-blue-600');
    });
});

function openIntraoralModal(mode, id=null){
    const modal = document.getElementById('intraoralModal');
    const form = document.getElementById('intraoralForm');

    // Reset form and hidden id
    form.reset();
    document.getElementById('exam_id').value='';

    // show first tab
    tabContents.forEach(tc => tc.classList.add('hidden'));
    tabLinks.forEach(l => l.classList.remove('border-b-2','border-blue-600'));
    document.getElementById('tab-soft').classList.remove('hidden');
    tabLinks[0].classList.add('border-b-2','border-blue-600');

    if(mode==='edit' && id){
        fetch(`/oral_examination/${id}/edit`)
            .then(res=>res.json())
            .then(data=>{
                document.getElementById('modalTitle').textContent='Edit Examination';
                document.getElementById('exam_id').value = data.id ?? '';

                // Set patient select
                if (data.patient_id) {
                    const patientSelect = document.getElementById('patient_id');
                    patientSelect.value = data.patient_id;
                    // if using a plugin like Select2, call trigger('change') here
                }

                // Soft tissues status (select)
                if (data.soft_tissues_status !== undefined && document.querySelector('[name="soft_tissues_status"]')) {
                    document.querySelector('[name="soft_tissues_status"]').value = data.soft_tissues_status;
                }

                // Soft tissues textarea
                if (data.soft_tissues !== undefined && document.getElementById('soft_tissues')) {
                    document.getElementById('soft_tissues').value = data.soft_tissues;
                }

                // Gingiva selects
                if (data.gingiva_color !== undefined) document.querySelector('[name="gingiva_color"]').value = data.gingiva_color || '';
                if (data.gingiva_texture !== undefined) document.querySelector('[name="gingiva_texture"]').value = data.gingiva_texture || '';

                // Radio fields (bleeding, recession)
                if (data.bleeding !== undefined) {
                    const b = document.querySelector('input[name="bleeding"][value="'+data.bleeding+'"]');
                    if (b) b.checked = true;
                }
                if (data.recession !== undefined) {
                    const r = document.querySelector('input[name="recession"][value="'+data.recession+'"]');
                    if (r) r.checked = true;
                }

                // Text inputs and selects for remaining fields
                if (data.bleeding_area !== undefined) document.querySelector('[name="bleeding_area"]').value = data.bleeding_area || '';
                if (data.recession_area !== undefined) document.querySelector('[name="recession_area"]').value = data.recession_area || '';
                if (data.teeth_condition !== undefined) document.querySelector('[name="teeth_condition"]').value = data.teeth_condition || '';
                if (data.occlusion_class !== undefined) document.querySelector('[name="occlusion_class"]').value = data.occlusion_class || '';
                if (data.occlusion_other !== undefined) document.querySelector('[name="occlusion_other"]').value = data.occlusion_other || '';
                if (data.premature_contacts !== undefined) document.querySelector('[name="premature_contacts"]').value = data.premature_contacts || '';
                if (data.hygiene_status !== undefined) document.querySelector('[name="hygiene_status"]').value = data.hygiene_status || '';
                if (data.plaque_index !== undefined) document.querySelector('[name="plaque_index"]').value = data.plaque_index || '';
                if (data.calculus !== undefined) document.querySelector('[name="calculus"]').value = data.calculus || '';

                // NOTE: file fields (probing_depths, mobility, etc.) cannot be set via JS for security reasons.
            })
            .catch(err => {
                console.error('Failed to load exam data', err);
                alert('Failed to load exam data for editing.');
            });
    } else {
        document.getElementById('modalTitle').textContent='New Examination';
    }

    modal.classList.remove('hidden');
}
</script>

</x-app-layout>
