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

<x-app-layout>
   
    
    

    <div x-data="treatmentPlanPage()" x-cloak>
        <div class="py-6">
           <x-slot name="header">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="page-header mb-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Treatment Plans
            </h2>
        </div>
    </div>
</x-slot>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 font-weight-bold text-gray-800">Records</h4>
            <button @click="openCreate = true; setActiveTab(0)" class="btn btn-primary btn-md">
                <i class="fas fa-plus me-2 text-white"></i>New Treatment Plan
            </button>
        </div>
<!-- Filter -->
<div class="sticky-top pt-1" style="top:0; z-index:10; background:#f8f9fa;">

    <div class="card shadow-sm border-0 mb-2" style="border-radius:6px;">
        <div class="card-body py-1 px-2">

            <div class="row g-2 align-items-end">

                <!-- Patient Filter -->
                <div class="col-md-3">
                    <label class="text-muted fw-semibold mb-1 d-block" style="font-size:0.7rem;">
                        Patient Filter
                    </label>

                    <select id="patientFilter" class="form-select form-select-sm py-1" style="font-size:0.8rem;">
                        <option value="">All Patients</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="col-md-3">
                    <label class="text-muted fw-semibold mb-1 d-block" style="font-size:0.7rem;">
                        Date Filter
                    </label>

                    <select id="createdDateFilter" class="form-select form-select-sm py-1" style="font-size:0.8rem;">
                        <option value="">All Dates</option>
                    </select>
                </div>

            </div>

        </div>
    </div>

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

<!-- ADD MODAL -->
 
     <div x-show="openCreate" 
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-start justify-center pt-12 px-4" 
     x-cloak>
    
    <div class="fixed inset-0" @click="openCreate=false"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl z-50 overflow-hidden flex flex-col" @keydown.escape.window="openCreate=false">

        <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
            <div>
                <h3 class="card-title mb-0 text-white">New Treatment Plan</h3>
                <p class="text-[10px] text-white opacity-75 mb-0" x-text="'Step ' + (activeTab + 1) + ' of ' + tabs.length"></p>
            </div>
            <button @click="openCreate=false" class="text-white text-xl">&times;</button>
        </div>

        <div class="border-b bg-gray-50 dark:bg-gray-900">
            <nav class="flex divide-x divide-gray-200 dark:divide-gray-700" aria-label="Tabs">
                <template x-for="(t, i) in tabs" :key="i">
                    <button
                        @click="setActiveTab(i)"
                        :class="activeTab === i ? 'bg-white dark:bg-gray-800 text-primary font-bold' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 px-2 py-3 text-[11px] uppercase tracking-wider transition-all border-b-2"
                        :style="activeTab === i ? 'border-bottom-color: var(--primary)' : 'border-bottom-color: transparent'"
                    >
                        <span class="block md:inline" x-text="(i+1) + '. ' + t"></span>
                    </button>
                </template>
            </nav>
        </div>

        <div class="p-4 max-h-[70vh] overflow-auto bg-white dark:bg-gray-800">
            <form id="createForm" method="POST" action="{{ route('treatment-plans.store') }}">
                @csrf

                <div x-show="activeTab === 0" x-cloak class="space-y-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-md border border-blue-100 dark:border-blue-800">
                        <label class="block text-sm font-bold text-blue-900 dark:text-blue-200">Select Patient</label>
                        <select name="patient_id" required class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">-- Choose Patient --</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->last_name }}, {{ $p->first_name }} (ID: {{ $p->id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Phase I (Emergency/Pain Relief) — Date</label>
                        <input type="date" name="phase1_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600"/>
                        <label class="block text-sm font-medium mt-3">Procedures</label>
                        <textarea name="phase1_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" placeholder="#3 Extraction, #14 Temporary Filling"></textarea>
                    </div>
                </div>

                <div x-show="activeTab === 1" x-cloak class="space-y-4">
                    <label class="block text-sm font-medium">Phase II (Disease Control/Restorative) — Date</label>
                    <input type="date" name="phase2_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600"/>
                    <label class="block text-sm font-medium mt-2">Procedures</label>
                    <textarea name="phase2_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" placeholder="Scaling and Root Planing..."></textarea>
                </div>

                <div x-show="activeTab === 2" x-cloak class="space-y-4">
                    <label class="block text-sm font-medium">Phase III (Definitive/Rehabilitative) — Date</label>
                    <input type="date" name="phase3_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600"/>
                    <label class="block text-sm font-medium mt-2">Procedures</label>
                    <textarea name="phase3_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" placeholder="#19 PFM Crown..."></textarea>
                </div>

                <div x-show="activeTab === 3" x-cloak class="space-y-4">
                    <label class="block text-sm font-medium">Phase IV (Maintenance/Preventive) — Date</label>
                    <input type="date" name="phase4_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600"/>
                    <label class="block text-sm font-medium mt-2">Procedures</label>
                    <textarea name="phase4_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" placeholder="Regular Prophylaxis..."></textarea>
                </div>

                <div x-show="activeTab === 4" x-cloak class="space-y-3">
                    <label class="block text-sm font-medium">Discussion with Patient</label>
                    <textarea name="treatment_options" rows="2" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" placeholder="Treatment options discussed"></textarea>
                    <textarea name="risks_and_benefits" rows="2" class="block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" placeholder="Risks and benefits explained"></textarea>
                    <textarea name="alternatives" rows="2" class="block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" placeholder="Alternatives presented"></textarea>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                        <input name="estimated_costs" placeholder="Estimated costs (PHP)" class="block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600"/>
                        <input name="payment_options" placeholder="Payment options" class="block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600"/>
                    </div>
                </div>

                <div x-show="activeTab === 5" x-cloak class="space-y-4">
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 text-sm font-bold text-black dark:text-white">
                        I understand the proposed treatment plan, its benefits, risks, and alternatives, and I hereby give my consent for the procedures to be performed.
                    </div>
                    
                    <div class="flex items-center gap-3 py-2">
                        <input type="checkbox" name="consent_given" value="1" required id="consent_check" class="rounded h-5 w-5 text-primary border-gray-300">
                        <label for="consent_check" class="text-sm font-medium cursor-pointer">
                            Patient gives informed consent <span class="text-danger">*</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Consent Date</label>
                        <input type="date" name="consent_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600" required/>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-4 border-t bg-gray-50 dark:bg-gray-900 flex items-center justify-between">
            <div>
                <button type="button" 
                        x-show="!isFirstStep()" 
                        @click="prevStep()" 
                        class="px-4 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition">
                    ← Previous
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" @click="openCreate=false" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Cancel</button>
                
                <button type="button" 
                        x-show="!isLastStep()" 
                        @click="nextStep()" 
                        class="px-6 py-2 text-sm font-bold bg-primary text-white rounded hover:opacity-90 transition shadow-sm">
                    Next Step →
                </button>

                <button type="submit" 
                        form="createForm" 
                        x-show="isLastStep()" 
                        class="px-6 py-2 text-sm font-bold bg-green-600 text-white rounded hover:bg-green-700 transition shadow-sm">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>
       <div x-show="openEditModal" 
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-start justify-center pt-12 px-4" 
     x-cloak>
    
    <div class="fixed inset-0" @click="openEditModal=false"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl z-50 overflow-hidden flex flex-col" @keydown.escape.window="openEditModal=false">

        <div class="modal-header bg-blue-600 text-white d-flex justify-content-between align-items-center py-3 px-4">
            <div>
                <h3 class="card-title mb-0 text-white">Edit Treatment Plan</h3>
                <p class="text-[10px] text-white opacity-75 mb-0" x-text="'Step ' + (activeTab + 1) + ' of ' + tabs.length"></p>
            </div>
            <button @click="openEditModal=false" class="text-white text-xl">&times;</button>
        </div>

        <div class="border-b bg-gray-50 dark:bg-gray-900">
            <nav class="flex divide-x divide-gray-200 dark:divide-gray-700" aria-label="Tabs">
                <template x-for="(t, i) in tabs" :key="i">
                    <button
                        @click="setActiveTab(i)"
                        :class="activeTab === i ? 'bg-white dark:bg-gray-800 text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 px-2 py-3 text-[11px] uppercase tracking-wider transition-all border-b-2"
                        :style="activeTab === i ? 'border-bottom-color: #2563eb' : 'border-bottom-color: transparent'"
                    >
                        <span x-text="(i+1) + '. ' + t"></span>
                    </button>
                </template>
            </nav>
        </div>

        <div class="p-4 max-h-[70vh] overflow-auto bg-white dark:bg-gray-800">
            <form id="editForm" :action="editFormAction" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="patient_id" x-model="form.patient_id"/>

                <div x-show="activeTab === 0" x-cloak class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                        <label class="block text-xs font-bold uppercase text-gray-400">Patient</label>
                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-200" x-text="form.patient_name"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Phase I (Emergency/Pain Relief) — Date</label>
                        <input type="date" name="phase1_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase1.date"/>
                        <label class="block text-sm font-medium mt-3">Procedures</label>
                        <textarea name="phase1_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase1.procedures"></textarea>
                    </div>
                </div>

                <div x-show="activeTab === 1" x-cloak class="space-y-4">
                    <label class="block text-sm font-medium">Phase II (Disease Control/Restorative) — Date</label>
                    <input type="date" name="phase2_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase2.date"/>
                    <label class="block text-sm font-medium mt-3">Procedures</label>
                    <textarea name="phase2_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase2.procedures"></textarea>
                </div>

                <div x-show="activeTab === 2" x-cloak class="space-y-4">
                    <label class="block text-sm font-medium">Phase III (Definitive/Rehabilitative) — Date</label>
                    <input type="date" name="phase3_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase3.date"/>
                    <label class="block text-sm font-medium mt-3">Procedures</label>
                    <textarea name="phase3_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase3.procedures"></textarea>
                </div>

                <div x-show="activeTab === 3" x-cloak class="space-y-4">
                    <label class="block text-sm font-medium">Phase IV (Maintenance/Preventive) — Date</label>
                    <input type="date" name="phase4_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase4.date"/>
                    <label class="block text-sm font-medium mt-3">Procedures</label>
                    <textarea name="phase4_procedures" rows="4" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.phases.phase4.procedures"></textarea>
                </div>

                <div x-show="activeTab === 4" x-cloak class="space-y-4">
                    <label class="block text-sm font-medium">Discussion with Patient</label>
                    <textarea name="treatment_options" rows="2" class="mt-1 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.treatment_options" placeholder="Options"></textarea>
                    <textarea name="risks_and_benefits" rows="2" class="mt-2 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.risks_and_benefits" placeholder="Risks/Benefits"></textarea>
                    <textarea name="alternatives" rows="2" class="mt-2 block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.alternatives" placeholder="Alternatives"></textarea>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                        <input name="estimated_costs" class="block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.estimated_costs" placeholder="Costs"/>
                        <input name="payment_options" class="block w-full rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.payment_options" placeholder="Payment Options"/>
                    </div>
                </div>

                <div x-show="activeTab === 5" x-cloak class="space-y-4">
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 text-sm font-semibold text-gray-800 dark:text-gray-200">
                        Check the box below to confirm that the updated treatment plan has been discussed and patient consent is maintained.
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="consent_given" value="0">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="consent_given" value="1" class="rounded h-5 w-5 text-blue-600 border-gray-300" x-bind:checked="form.consent_given" x-on:change="form.consent_given = $event.target.checked"/>
                            <span class="ml-2 text-sm font-medium">Patient gives informed consent</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Consent Date</label>
                        <input type="date" name="consent_date" class="mt-1 block w-full md:w-1/2 rounded border-gray-200 dark:bg-gray-700 dark:border-gray-600 text-sm" x-model="form.consent_date"/>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-4 border-t bg-gray-50 dark:bg-gray-900 flex items-center justify-between">
            <div>
                <button type="button" 
                        x-show="!isFirstStep()" 
                        @click="prevStep()" 
                        class="px-4 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 transition shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    ← Previous
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" @click="openEditModal=false" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Cancel</button>
                
                <button type="button" 
                        x-show="!isLastStep()" 
                        @click="nextStep()" 
                        class="px-6 py-2 text-sm font-bold bg-blue-600 text-white rounded hover:bg-blue-700 transition shadow-sm">
                    Next Step →
                </button>

                <button type="submit" 
                        form="editForm" 
                        x-show="isLastStep()" 
                        class="px-6 py-2 text-sm font-bold bg-green-600 text-white rounded hover:bg-green-700 transition shadow-sm">
                    Update 
                </button>
            </div>
        </div>
    </div>
</div>
<!-- View Modal -->
       <div x-show="openViewModal" 
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-start justify-center pt-12 px-4" 
     x-cloak 
     style="display:none;">
    
    <div class="fixed inset-0 bg-black/20" @click="openViewModal=false"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl z-50 overflow-hidden flex flex-col transition-all">
        
        <div class="p-4 flex justify-between items-center bg-blue-600 text-white">
            <div>
                <h3 class="text-lg font-semibold text-white">Treatment Plan Details</h3>
                <p class="text-xs opacity-80" x-text="'Patient: ' + view.patient"></p>
            </div>
            <button @click="openViewModal=false" class="text-white hover:rotate-90 transition-transform text-2xl px-2">&times;</button>
        </div>

        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto bg-white dark:bg-gray-800">
            
            <div class="space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 border-b pb-1">Treatment Phases</h4>
                <div class="grid grid-cols-1 gap-4">
                    <template x-for="(phase, key) in view.phases" :key="key">
                        <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-bold text-blue-600 dark:text-blue-400" x-text="formatPhaseKey(key)"></span>
                                <span class="text-xs bg-white dark:bg-gray-700 px-2 py-1 rounded shadow-sm border dark:border-gray-600">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <span x-text="formatDate(phase.date)"></span>
                                </span>
                            </div>
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                <strong class="text-xs uppercase text-gray-400 block mb-1">Procedures:</strong>
                                <div class="pl-2 border-l-2 border-blue-200 dark:border-blue-800" x-html="nl2br(phase.procedures)"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-blue-800 dark:text-blue-300 mb-3">Patient Discussion</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <strong class="block text-xs text-gray-500">Options Discussed:</strong>
                        <span class="text-gray-800 dark:text-gray-200" x-text="view.treatment_options || 'None recorded'"></span>
                    </div>
                    <div>
                        <strong class="block text-xs text-gray-500">Risks & Benefits:</strong>
                        <span class="text-gray-800 dark:text-gray-200" x-text="view.risks_and_benefits || 'None recorded'"></span>
                    </div>
                    <div>
                        <strong class="block text-xs text-gray-500">Alternatives:</strong>
                        <span class="text-gray-800 dark:text-gray-200" x-text="view.alternatives || 'None recorded'"></span>
                    </div>
                    <div>
                        <strong class="block text-xs text-gray-500">Financials:</strong>
                        <span class="font-bold text-green-600 dark:text-green-400" x-text="view.estimated_costs ? 'Est: ' + view.estimated_costs : 'No cost provided'"></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border rounded-lg bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700">
                <div>
                    <h4 class="text-xs font-bold uppercase text-gray-400">Informed Consent</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <template x-if="view.consent_given">
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> YES
                            </span>
                        </template>
                        <template x-if="!view.consent_given">
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded flex items-center gap-1">
                                <i class="fas fa-times-circle"></i> NO
                            </span>
                        </template>
                        <span class="text-sm text-gray-600 dark:text-gray-400" x-text="'Signed on: ' + formatDate(view.consent_date)"></span>
                    </div>
                </div>
                <i class="fas fa-signature text-3xl text-gray-300 dark:text-gray-600"></i>
            </div>
        </div>

        <div class="p-4 border-t bg-gray-50 dark:bg-gray-900 flex items-center justify-end gap-3">
           <template x-if="view.id">
    <button type="button"
        @click="window.open('/treatment-plans/' + view.id + '/pdf', '_blank')"
        class="px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700 transition flex items-center gap-2 text-sm font-bold">
        <i class="fas fa-file-pdf"></i> Download PDF
    </button>
</template>


            <button @click="openViewModal=false" class="px-6 py-2 bg-slate-800 text-white rounded shadow hover:bg-slate-900 transition text-sm font-bold">
                Close
            </button>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div x-cloak
    x-show="openDeleteModal"
    
    x-transition.opacity
 class="fixed inset-0 black/40 backdrop-blur-sm flex items-center justify-center z-50 bg-black/40">



    <!-- Overlay -->
    <div
        class="absolute inset-0 "
        @click="closeDelete()">
    </div>

    <!-- Modal Box -->
    <div
    x-transition.scale
    class="relative bg-white rounded-lg shadow-xl w-full max-w-md z-50 overflow-hidden"
>

    <!-- RED HEADER -->
    <div class="bg-red-600 text-white px-6 py-3">
        <h3 class="text-lg font-semibold">
            Confirm Delete
        </h3>
    </div>



        <p class="text-gray-700 mb-5">
            Are you sure you want to delete
            <strong x-text="deleteTargetName"></strong>?
        </p>

        <form :action="deleteFormAction" method="POST">
            @csrf
            @method('DELETE')

          <div class="flex justify-end gap-3 p-6 pt-0"> <button
        type="button"
        @click="closeDelete()"
        class="btn btn-black btn-md">
        Cancel
    </button>

    <button
        type="submit"
        class="btn btn-danger btn-md">
        Delete
    </button>
</div>
        </form>
    </div>
</div>
</div>

   <script>
    function treatmentPlanPage() {
        return {
            // UI State
            tabs: ['Phase I', 'Phase II', 'Phase III', 'Phase IV', 'Discussion', 'Consent'],
            activeTab: 0,
            openCreate: false,
            openEditModal: false,
            openViewModal: false,
            openDeleteModal: false,

            // Action URLs
            editFormAction: '',
            deleteFormAction: '',
            deleteTargetName: '',

            // Form Data (for Create/Edit)
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

            // View Data (for Read-only)
            view: {
                id: null,
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

            /* ==========================================
               STEP-BY-STEP NAVIGATION LOGIC
            ========================================== */
            
            // Call this to open "Add New" Modal
            openCreateModal() {
                this.resetForm();
                this.activeTab = 0;
                this.openCreate = true;
            },

            setActiveTab(i) {
                if (i >= 0 && i < this.tabs.length) {
                    this.activeTab = i;
                    // Auto-scroll modal body to top on step change
                    this.$nextTick(() => {
                        const scrollable = document.querySelector('.max-h-\\[70vh\\]');
                        if (scrollable) scrollable.scrollTop = 0;
                    });
                }
            },

            nextStep() { this.setActiveTab(this.activeTab + 1); },
            prevStep() { this.setActiveTab(this.activeTab - 1); },

            isFirstStep() { return this.activeTab === 0; },
            isLastStep() { return this.activeTab === this.tabs.length - 1; },

            resetForm() {
                this.form = {
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
                };
            },

            /* ==========================================
               AJAX ACTIONS (EDIT / VIEW)
            ========================================== */

            openEdit(id) {
                // Reset tab to start when editing
                this.activeTab = 0; 
                fetch(`/treatment-plans/${id}/edit`)
                    .then(r => {
                        if (!r.ok) throw new Error('Failed to load.');
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
                        this.form.consent_date = data.consent_date ? data.consent_date.substring(0, 10) : '';

                        this.editFormAction = `/treatment-plans/${id}`;
                        this.openEditModal = true;
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Unable to load treatment plan for editing.');
                    });
            },

            openView(id) {
                fetch(`/treatment-plans/${id}/edit`)
                    .then(r => {
                        if (!r.ok) throw new Error('Failed to load.');
                        return r.json();
                    })
                    .then(data => {
                        this.view.id = id;
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

            /* ==========================================
               DELETE MODAL LOGIC
            ========================================== */

            openDelete(id, patientName) {
                this.deleteFormAction = `/treatment-plans/${id}`;
                this.deleteTargetName = patientName;
                this.openDeleteModal = true;
            },

            closeDelete() {
                this.openDeleteModal = false;
                this.deleteFormAction = '';
                this.deleteTargetName = '';
            },

            /* ==========================================
               FORMATTERS / HELPERS
            ========================================== */

            nl2br(txt) {
                if (!txt) return '-';
                return txt.replace(/\n/g, '<br>');
            },

            formatPhaseKey(key) {
                const map = { 
                    phase1: 'Phase I (Emergency/Pain Relief)', 
                    phase2: 'Phase II (Disease Control/Restorative)', 
                    phase3: 'Phase III (Definitive/Rehabilitative)', 
                    phase4: 'Phase IV (Maintenance/Preventive)' 
                };
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
            }
        }
    }
</script>
</x-app-layout>  