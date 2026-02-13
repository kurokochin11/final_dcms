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

    // Initialize your table
    let table = $('#myTable').DataTable({
        responsive: true
    });

    // Populate Patient filter (column 0)
    let seen = new Set();

table.column(0).data().unique().sort().each(function (d) {
    if (!d) return;

    // Remove any HTML tags
    let text = d.replace(/<[^>]*>/g, '');

    // Remove "ID:" and any numbers/extra symbols
    text = text.replace(/\s*ID\s*:\s*\d+\s*>?\s*/i, '').trim();

    // Convert "Last, First" → "First Last"
    let parts = text.split(',').map(p => p.trim());
    let name = parts.length === 2 ? `${parts[1]} ${parts[0]}` : text;

    if (!seen.has(name)) {
        seen.add(name);
        $('#patientFilter').append(`<option value="${name}">${name}</option>`);
    }
});

$('#patientFilter').on('change', function () {
    table.column(0).search(this.value).draw();
});


    // Populate Created Date filter (column 1)
    $('#createdDateFilter').append('<option value="">All dates</option>');
    table.column(1).data().unique().sort().each(function(d) {
        $('#createdDateFilter').append(`<option value="${d}">${d}</option>`);
    });

    // Filter by Created Date
    $('#createdDateFilter').on('change', function() {
        table.column(1).search(this.value).draw();
    });

});
</script>


@section('title', 'Treatment Plans')
<x-app-layout>
    <x-slot name="header">
        <!-- {{-- header placeholder (wrapped by x-data root below) --}} -->
    </x-slot>

    <div x-data="treatmentPlanPage()" x-cloak>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Treatment Plans
                    </h2>

                    <div>
                        <button @click="openCreate = true; setActiveTab(0)" class="btn btn-primary btn-md"> <i class="fas fa-plus me-2 text-white"></i>New Treatment Plan</button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

<div class="d-flex gap-2 mb-2">
    <!-- Patient -->
    <select id="patientFilter"
            class="form-select form-select-sm"
            style="max-width:180px">
        <option value=""> All Patients</option>
    </select>

    <!-- Date -->
    <select id="createdDateFilter"
            class="form-select form-select-sm"
            style="max-width:120px">
        <option value=""> All Dates</option>
    </select>
</div>


                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="table-responsive">
                    <table id="myTable"  class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="text-left text-sm text-gray-600">
                                    <th class="px-4 py-2">Patient</th>
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Consent</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($plans as $plan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            {{ $plan->patient->last_name }}, {{ $plan->patient->first_name }}
                                            <div class="text-xs text-gray-500">ID: {{ $plan->patient->id }}</div>
                                        </td>
                                       <td class="px-4 py-3 text-sm text-gray-600">{{ $plan->consent_date ? \Carbon\Carbon::parse($plan->consent_date)->format('M d, Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($plan->consent_given)
                                                <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Yes</span>
                                            @else
                                                <span class="inline-block px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">No</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <button @click="openView({{ $plan->id }})"  class="btn btn-md btn-primary mr-2"> <i class="fas fa-eye text-white"></i></button>
                                            <button @click="openEdit({{ $plan->id }}); setActiveTab(0)" class="btn btn-md btn-warning mr-2"><i class="fas fa-edit text-white"></i></button>
                                <button type="button" class="btn btn-danger btn-md"
        @click="openDelete({{ $plan->id }}, @js($plan->patient->last_name . ', ' . $plan->patient->first_name))">
    <i class="fas fa-trash"></i>
</button>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center text-gray-500">No treatment plans yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $plans->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- {{-- ADD MODAL (tabbed) --}} -->

        <div x-show="openCreate" class="fixed inset-0  z-50 bg-black/40 backdrop-blur-sm flex items-start justify-center pt-12 px-4" style="display:none;">
            <div class="fixed inset-0 bg-blue" @click="openCreate=false"></div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl z-50 overflow-hidden" @keydown.escape.window="openCreate=false">

                <!-- {{-- modal header --}} -->
             <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
                    <h3 class="card-title mb-0 text-white">New Treatment Plan</h3>
                    <button @click="openCreate=false" class="text-white">✕</button>
                </div>

                <!-- {{-- tabs --}} -->
                <div class="border-b">
                    <nav class="flex gap-1 p-2 px-4 text-sm" aria-label="Tabs">
                        <template x-for="(t, i) in tabs" :key="i">
                            <button
                                :class="{'bg-gray-100 dark:bg-gray-700 rounded-md': activeTab === i, 'text-blue-600': activeTab !== i}"
                                class="px-3 py-2"
                                @click="setActiveTab(i)"
                            ><span x-text="t"></span></button>
                        </template>
                    </nav>
                </div>

                <!-- {{-- body: scrollable --}} -->
                <div class="p-4 max-h-[70vh] overflow-auto">
                    <form id="createForm" method="POST" action="{{ route('treatment-plans.store') }}">
                        @csrf

                        <div x-show="activeTab === 0" x-cloak class="space-y-4">

                            <!-- {{-- Patient selection --}} -->
                            <div>
                                <label class="block text-sm">Patient</label>
                                <select name="patient_id" required class="mt-1 block w-full rounded border-gray-200">
                                    <option value="">select patient </option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}">{{ $p->last_name }}, {{ $p->first_name }} (ID: {{ $p->id }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- {{-- Phase I --}} -->
                            <div>
                                <label class="block text-sm font-medium">Phase I (Emergency/Pain Relief) — Date</label>
                                <input type="date" name="phase1_date" class="mt-1 block w-1/2 rounded border-gray-200"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase1_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" placeholder="#3 Extraction, #14 Temporary Filling"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 1" x-cloak class="space-y-4">
                            <!-- {{-- Phase II --}} -->
                            <div>
                                <label class="block text-sm font-medium">Phase II (Disease Control/Restorative) — Date</label>
                                <input type="date" name="phase2_date" class="mt-1 block w-1/2 rounded border-gray-200"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase2_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" placeholder="Scaling and Root Planing, #5 Composite Filling"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 2" x-cloak class="space-y-4">
                            <!-- {{-- Phase III --}} -->
                            <div>
                                <label class="block text-sm font-medium">Phase III (Definitive/Rehabilitative) — Date</label>
                                <input type="date" name="phase3_date" class="mt-1 block w-1/2 rounded border-gray-200"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase3_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" placeholder="#19 PFM Crown, #7-9 Bridge"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 3" x-cloak class="space-y-4">
                            <!-- {{-- Phase IV --}} -->
                            <div>
                                <label class="block text-sm font-medium">Phase IV (Maintenance/Preventive) — Date</label>
                                <input type="date" name="phase4_date" class="mt-1 block w-1/2 rounded border-gray-200"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase4_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" placeholder="Oral Hygiene Instructions, Regular Prophylaxis, Fluoride Application"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 4" x-cloak class="space-y-4">
                            <!-- {{-- Discussion --}} -->
                            <div>
                                <label class="block text-sm font-medium">Discussion with Patient</label>
                                <textarea name="treatment_options" rows="2" class="mt-1 block w-full rounded border-gray-200" placeholder="Treatment options discussed"></textarea>
                                <textarea name="risks_and_benefits" rows="2" class="mt-1 block w-full rounded border-gray-200 mt-2" placeholder="Risks and benefits explained"></textarea>
                                <textarea name="alternatives" rows="2" class="mt-1 block w-full rounded border-gray-200 mt-2" placeholder="Alternatives presented"></textarea>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                    <input name="estimated_costs" placeholder="Estimated costs" class="mt-1 block w-full rounded border-gray-200"/>
                                    <input name="payment_options" placeholder="Payment options" class="mt-1 block w-full rounded border-gray-200"/>
                                </div>
                            </div>
                        </div>

                        <div x-show="activeTab === 5" x-cloak class="space-y-4">
                            <!-- {{-- Consent --}} -->
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="consent_given" value="0">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="consent_given" value="1" class="rounded"/>
                                    <span class="ml-2 text-sm">Patient gives informed consent</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <input type="text" name="patient_signature" placeholder="Patient's Signature (text)" class="mt-1 block w-full rounded border-gray-200"/>
                                <input type="text" name="dentist_signature" placeholder="Dentist's Signature (text)" class="mt-1 block w-full rounded border-gray-200"/>
                            </div>

                            <div>
                                <label class="block text-sm">Consent Date</label>
                                <input type="date" name="consent_date" class="mt-1 block w-1/2 rounded border-gray-200"/>
                            </div>

                            <div class="text-xs text-gray-500">By signing, the patient acknowledges they understand the treatment, risks, benefits, and alternatives.</div>
                        </div>
                    </form>
                </div>

                <!-- {{-- sticky footer with actions --}} -->
                <div class="p-4 border-t bg-white dark:bg-gray-800 flex items-center justify-end gap-2">
                    <button type="button" @click="openCreate=false"class="px-3 py-1.5 text-sm bg-black text-white rounded hover:bg-gray-800">Cancel</button>
                    <button type="submit" form="createForm" class="px-3 py-1.5 text-sm bg-primary text-white rounded hover:bg-gray-800">Submit</button>
                </div>
            </div>
        </div>

        <!-- {{-- EDIT MODAL --}} -->
        <div x-show="openEditModal" class="fixed inset-0  z-50 bg-black/40 backdrop-blur-sm flex items-start justify-center pt-12 px-4" style="display:none;">
            <div class="fixed inset-0 " @click="openEditModal=false"></div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl z-50 overflow-hidden" @keydown.escape.window="openEditModal=false">
                <div class="flex items-center justify-between p-4 bg-blue-600 text-white">
                    <h3 class="text-lg font-semibold text-white">Edit Treatment Plan</h3>
                    <button @click="openEditModal=false" class="text-white hover:text-gray-200">✕</button>
                </div>

                <div class="border-b">
                    <nav class="flex gap-1 p-2 px-4 text-sm" aria-label="Tabs">
                        <template x-for="(t, i) in tabs" :key="i">
                            <button
                                :class="{'bg-gray-100 dark:bg-gray-700 rounded-md': activeTab === i, 'text-blue-600': activeTab !== i}"
                                class="px-3 py-2"
                                @click="setActiveTab(i)"
                            ><span x-text="t"></span></button>
                        </template>
                    </nav>
                </div>

                <div class="p-4 max-h-[70vh] overflow-auto">
                    <form id="editForm" :action="editFormAction" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="patient_id" x-model="form.patient_id"/>
                        <div x-show="activeTab === 0" x-cloak class="space-y-4">
                            <div>
                                <label class="block text-sm">Patient</label>
                                <div class="mt-1 text-sm" x-text="form.patient_name"></div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Phase I (Emergency/Pain Relief) — Date</label>
                                <input type="date" name="phase1_date" class="mt-1 block w-1/2 rounded border-gray-200" x-model="form.phases.phase1.date"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase1_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase1.procedures"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 1" x-cloak class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Phase II (Disease Control/Restorative) — Date</label>
                                <input type="date" name="phase2_date" class="mt-1 block w-1/2 rounded border-gray-200" x-model="form.phases.phase2.date"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase2_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase2.procedures"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 2" x-cloak class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Phase III (Definitive/Rehabilitative) — Date</label>
                                <input type="date" name="phase3_date" class="mt-1 block w-1/2 rounded border-gray-200" x-model="form.phases.phase3.date"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase3_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase3.procedures"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 3" x-cloak class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Phase IV (Maintenance/Preventive) — Date</label>
                                <input type="date" name="phase4_date" class="mt-1 block w-1/2 rounded border-gray-200" x-model="form.phases.phase4.date"/>
                                <label class="block text-sm font-medium mt-2">Procedures</label>
                                <textarea name="phase4_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase4.procedures"></textarea>
                            </div>
                        </div>

                        <div x-show="activeTab === 4" x-cloak class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Discussion with Patient</label>
                                <textarea name="treatment_options" rows="2" class="mt-1 block w-full rounded border-gray-200" x-model="form.treatment_options"></textarea>
                                <textarea name="risks_and_benefits" rows="2" class="mt-1 block w-full rounded border-gray-200 mt-2" x-model="form.risks_and_benefits"></textarea>
                                <textarea name="alternatives" rows="2" class="mt-1 block w-full rounded border-gray-200 mt-2" x-model="form.alternatives"></textarea>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                    <input name="estimated_costs" placeholder="Estimated costs" class="mt-1 block w-full rounded border-gray-200" x-model="form.estimated_costs"/>
                                    <input name="payment_options" placeholder="Payment options" class="mt-1 block w-full rounded border-gray-200" x-model="form.payment_options"/>
                                </div>
                            </div>
                        </div>

                        <div x-show="activeTab === 5" x-cloak class="space-y-4">
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="consent_given" value="0">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="consent_given" value="1" class="rounded" x-bind:checked="form.consent_given" x-on:change="form.consent_given = $event.target.checked"/>
                                    <span class="ml-2 text-sm">Patient gives informed consent</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <input type="text" name="patient_signature" placeholder="Patient's Signature" class="mt-1 block w-full rounded border-gray-200" x-model="form.patient_signature"/>
                                <input type="text" name="dentist_signature" placeholder="Dentist's Signature" class="mt-1 block w-full rounded border-gray-200" x-model="form.dentist_signature"/>
                            </div>

                            <div>
                                <label class="block text-sm">Consent Date</label>
                                <input type="date" name="consent_date" class="mt-1 block w-1/2 rounded border-gray-200" x-model="form.consent_date"/>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-4 border-t bg-white dark:bg-gray-800 flex items-center justify-end gap-2">
                    <button type="button" @click="openEditModal=false" class="px-3 py-1.5 text-sm bg-black text-white rounded hover:bg-gray-800">Cancel</button>
                    <button type="submit" form="editForm" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Submit</button>
                </div>
            </div>
        </div>

        <!-- {{-- VIEW modal --}} -->
        <div x-show="openViewModal" class="fixed inset-0 z-50 black/40 backdrop-blur-sm flex items-start justify-center pt-12 px-4" style="display:none;">
            <div class="fixed inset-0 bg-black/40" @click="openViewModal=false"></div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl z-50 overflow-hidden">
                <div class="p-4 flex justify-between items-center bg-blue-600 text-white">
                    <h3 class="text-lg font-semibold text-white">Treatment Plan Details</h3>
                    <button @click="openViewModal=false" class="text-white hover:text-gray-200 text-xl ">✕</button>
                </div>

                <div class="p-4 space-y-3 max-h-[70vh] overflow-y-auto">
                    <div class="text-sm text-gray-600">
                        <strong>Patient:</strong> <span x-text="view.patient"></span>
                    </div>

                    <template x-for="(phase, key) in view.phases" :key="key">
                        <div class="border rounded p-3">
                            <div class="font-semibold" x-text="formatPhaseKey(key)"></div>
                            <div class="text-sm mt-1"><strong>Date:</strong> <span x-text="formatDate(phase.date)"></span></div>
                            <div class="text-sm mt-1"><strong>Procedures:</strong> <div x-html="nl2br(phase.procedures)"></div></div>
                        </div>
                    </template>

                    <div>
                        <h4 class="font-semibold">Discussion</h4>
                        <div class="text-sm"><strong>Options:</strong> <span x-text="view.treatment_options"></span></div>
                        <div class="text-sm"><strong>Risks/Benefits:</strong> <span x-text="view.risks_and_benefits"></span></div>
                        <div class="text-sm"><strong>Alternatives:</strong> <span x-text="view.alternatives"></span></div>
                        <div class="text-sm"><strong>Estimated Costs:</strong> <span x-text="view.estimated_costs"></span></div>
                    </div>

                    <div>
                        <h4 class="font-semibold">Consent</h4>
                        <div class="text-sm"><strong>Given:</strong> <span x-text="view.consent_given ? 'Yes' : 'No'"></span></div>
                        <div class="text-sm"><strong>Patient signature:</strong> <span x-text="view.patient_signature"></span></div>
                        <div class="text-sm"><strong>Dentist signature:</strong> <span x-text="view.dentist_signature"></span></div>
                        <div class="text-sm"><strong>Consent date:</strong><span x-text="formatDate(view.consent_date)"></span></div>
                    </div>

                    <div class="flex justify-end">
                        <button @click="openViewModal=false" class="px-3 py-1.5 text-sm bg-black text-white rounded hover:bg-gray-800">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- DELETE MODAL -->
<div x-data="treatmentPlanPage()" x-cloak

    x-show="openDeleteModal"
    
    x-transition.opacity
    class="fixed inset-0 black/40 backdrop-blur-smz-50 flex items-center justify-center"
>

    <!-- Overlay -->
    <div
        class="absolute inset-0 bg-black bg-opacity-50"
        @click="closeDelete()">
    </div>

    <!-- Modal Box -->
    <div
        x-transition.scale
        class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6 z-50"
    >
        <h3 class="text-lg font-semibold text-red-600 mb-3">
            Confirm Delete
        </h3>

        <p class="text-gray-700 mb-5">
            Are you sure you want to delete
            <strong x-text="deleteTargetName"></strong>?
        </p>

        <form :action="deleteFormAction" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="closeDelete()"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>


    <!-- {{-- Alpine logic --}} -->
    <script>
        function treatmentPlanPage(){
            return {
                tabs: ['Phase I','Phase II','Phase III','Phase IV','Discussion','Consent'],
                activeTab: 0,
                openCreate: false,
                openEditModal: false,
                openViewModal: false,
                editFormAction: '',
                form: {
                    patient_id: '',
                    patient_name: '',
                    phases: {
                        phase1: { date: '', procedures: '' },
                        phase2: { date: '', procedures: '' },
                        phase3: { date: '', procedures: '' },
                        phase4: { date: '', procedures: '' },
                    },
                    treatment_options: '',
                    risks_and_benefits: '',
                    alternatives: '',
                    estimated_costs: '',
                    payment_options: '',
                    consent_given: false,
                    patient_signature: '',
                    dentist_signature: '',
                    consent_date: '',
                },
                view: {
                    patient: '',
                    phases: {},
                    treatment_options: '',
                    risks_and_benefits: '',
                    alternatives: '',
                    estimated_costs: '',
                    payment_options: '',
                    consent_given: false,
                    patient_signature: '',
                    dentist_signature: '',
                    consent_date: ''
                },

                setActiveTab(i){
                    this.activeTab = i;
                    // small UX enhancement: scroll top of modal body when switching tabs
                    // find the first scrollable container near the clicked tab and scroll to top
                    this.$nextTick(() => {
                        const body = document.querySelector('[x-cloak]') // no-op; just delay
                        // find the scrollable parent and reset scroll
                        const scrollable = document.querySelector('.max-h-\\[70vh\\]');
                        if(scrollable) scrollable.scrollTop = 0;
                    });
                },

                openEdit(id){
                    fetch(`/treatment-plans/${id}/edit`)
                        .then(r => {
                            if(!r.ok) throw new Error('Failed to load.');
                            return r.json();
                        })
                        .then(data => {
                            this.form.patient_id = data.patient_id ?? '';
                            this.form.patient_name = (data.patient?.last_name ?? '') + ', ' + (data.patient?.first_name ?? '') + (data.patient ? ' (ID: ' + data.patient.id + ')' : '');
                            this.form.phases = data.phases ?? {
                                phase1: { date: '', procedures: '' },
                                phase2: { date: '', procedures: '' },
                                phase3: { date: '', procedures: '' },
                                phase4: { date: '', procedures: '' },
                            };
                            this.form.treatment_options = data.treatment_options ?? '';
                            this.form.risks_and_benefits = data.risks_and_benefits ?? '';
                            this.form.alternatives = data.alternatives ?? '';
                            this.form.estimated_costs = data.estimated_costs ?? '';
                            this.form.payment_options = data.payment_options ?? '';
                            this.form.consent_given = !!data.consent_given;
                            this.form.patient_signature = data.patient_signature ?? '';
                            this.form.dentist_signature = data.dentist_signature ?? '';
                            this.form.consent_date = data.consent_date
                                  ? data.consent_date.substring(0, 10): '';

                            this.editFormAction = `/treatment-plans/${id}`;
                            this.openEditModal = true;
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Unable to load treatment plan for editing.');
                        });
                },

                openView(id){
                    fetch(`/treatment-plans/${id}/edit`)
                        .then(r => {
                            if(!r.ok) throw new Error('Failed to load.');
                            return r.json();
                        })
                        .then(data => {
                            this.view.patient = (data.patient?.last_name ?? '') + ', ' + (data.patient?.first_name ?? '') + (data.patient ? ' (ID: ' + data.patient.id + ')' : '');
                            this.view.phases = data.phases ?? {};
                            this.view.treatment_options = data.treatment_options ?? '';
                            this.view.risks_and_benefits = data.risks_and_benefits ?? '';
                            this.view.alternatives = data.alternatives ?? '';
                            this.view.estimated_costs = data.estimated_costs ?? '';
                            this.view.payment_options = data.payment_options ?? '';
                            this.view.consent_given = !!data.consent_given;
                            this.view.patient_signature = data.patient_signature ?? '';
                            this.view.dentist_signature = data.dentist_signature ?? '';
                            this.view.consent_date = data.consent_date ?? '';
                            this.openViewModal = true;
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Unable to load treatment plan details.');
                        });
                },

                nl2br(txt){
                    if(!txt) return '-';
                    return txt.replace(/\n/g, '<br>');
                },

                formatPhaseKey(key){
                    const map = { phase1: 'Phase I (Emergency/Pain Relief)', phase2: 'Phase II (Disease Control/Restorative)', phase3: 'Phase III (Definitive/Rehabilitative)', phase4: 'Phase IV (Maintenance/Preventive)' };
                    return map[key] ?? key;
                },
                
formatDate(date) {
    if (!date) return '-';

    const d = new Date(date);
    return d.toLocaleDateString('en-US', {
        month: 'long',
        day: '2-digit',
        year: 'numeric'
    });
},


        /* =====================
           DELETE MODAL 
        ===================== */
      openDeleteModal: false,
deleteFormAction: '',
deleteTargetName: '',

openDelete(id, patientName) {
    console.log('Delete ID:', id, 'Patient:', patientName); // optional debug
    this.deleteFormAction = `/treatment-plans/${id}`;
    this.deleteTargetName = patientName;
    this.openDeleteModal = true;
},

closeDelete() {
    this.openDeleteModal = false;
    this.deleteFormAction = '';
    this.deleteTargetName = '';
}


            }
        }
    </script>
</x-app-layout>  