{{-- resources/views/treatment_plans/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Treatment Plans
            </h2>
            <div>
                <button
                    @click="openCreate = true"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md"
                >New Treatment Plan</button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifications --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div x-data="treatmentPlanPage()" x-cloak>
                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="text-left text-sm text-gray-600">
                                    <th class="px-4 py-2">Patient</th>
                                    <th class="px-4 py-2">Created</th>
                                    <th class="px-4 py-2">Consent</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($plans as $plan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            {{ $plan->patient->last_name }}, {{ $plan->patient->first_name }}
                                            <div class="text-xs text-gray-500">Patient ID: {{ $plan->patient->id }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $plan->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($plan->consent_given)
                                                <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Yes</span>
                                            @else
                                                <span class="inline-block px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">No</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <button @click="openView({{ $plan->id }})" class="px-2 py-1 border rounded text-sm mr-2">View</button>
                                            <button @click="openEdit({{ $plan->id }})" class="px-2 py-1 border rounded text-sm mr-2">Edit</button>

                                            <form action="{{ route('treatment-plans.destroy', $plan) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this treatment plan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 border rounded text-sm text-red-600">Delete</button>
                                            </form>
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

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $plans->links() }}
                    </div>

                    {{-- Create Modal --}}
                    <div x-show="openCreate" class="fixed inset-0 z-40 flex items-start justify-center pt-16 px-4">
                        <div class="fixed inset-0 bg-black bg-opacity-40" @click="openCreate=false"></div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl z-50 overflow-auto" @keydown.escape.window="openCreate = false">
                            <div class="p-4 border-b flex justify-between items-center">
                                <h3 class="text-lg font-semibold">New Treatment Plan</h3>
                                <button @click="openCreate=false" class="text-gray-500">✕</button>
                            </div>

                            <form method="POST" action="{{ route('treatment-plans.store') }}" class="p-4 space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm">Patient</label>
                                    <select name="patient_id" required class="mt-1 block w-full rounded border-gray-200">
                                        <option value="">— select patient —</option>
                                        @foreach($patients as $p)
                                            <option value="{{ $p->id }}">{{ $p->last_name }}, {{ $p->first_name }} (ID: {{ $p->id }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Phases 1-4 --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium">Phase I (Emergency/Pain Relief) — Date</label>
                                        <input type="date" name="phase1_date" class="mt-1 block w-full rounded border-gray-200"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase1_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" placeholder="#3 Extraction, #14 Temporary Filling"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Phase II (Disease Control/Restorative) — Date</label>
                                        <input type="date" name="phase2_date" class="mt-1 block w-full rounded border-gray-200"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase2_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" placeholder="Scaling and Root Planing, #5 Composite Filling"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Phase III (Definitive/Rehabilitative) — Date</label>
                                        <input type="date" name="phase3_date" class="mt-1 block w-full rounded border-gray-200"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase3_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" placeholder="#19 PFM Crown, #7-9 Bridge"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Phase IV (Maintenance/Preventive) — Date</label>
                                        <input type="date" name="phase4_date" class="mt-1 block w-full rounded border-gray-200"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase4_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" placeholder="Oral Hygiene Instructions, Regular Prophylaxis, Fluoride Application"></textarea>
                                    </div>
                                </div>

                                {{-- Discussion --}}
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

                                {{-- Consent --}}
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="consent_given" class="rounded"/>
                                        <span class="ml-2 text-sm">Patient gives informed consent</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <input type="text" name="patient_signature" placeholder="Patient's Signature (text)" class="mt-1 block w-full rounded border-gray-200"/>
                                    <input type="text" name="dentist_signature" placeholder="Dentist's Signature (text)" class="mt-1 block w-full rounded border-gray-200"/>
                                </div>
                                <div>
                                    <label class="block text-sm">Consent Date</label>
                                    <input type="date" name="consent_date" class="mt-1 block w-full rounded border-gray-200"/>
                                </div>

                                <div class="flex justify-end gap-2 mt-3">
                                    <button type="button" @click="openCreate=false" class="px-4 py-2 border rounded">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Modal (same form as create but will be filled via fetch) --}}
                    <div x-show="openEditModal" class="fixed inset-0 z-40 flex items-start justify-center pt-16 px-4">
                        <div class="fixed inset-0 bg-black bg-opacity-40" @click="openEditModal=false"></div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl z-50 overflow-auto" @keydown.escape.window="openEditModal = false">
                            <div class="p-4 border-b flex justify-between items-center">
                                <h3 class="text-lg font-semibold">Edit Treatment Plan</h3>
                                <button @click="openEditModal=false" class="text-gray-500">✕</button>
                            </div>

                            <form :action="editFormAction" method="POST" class="p-4 space-y-4">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="patient_id" x-model="form.patient_id"/>

                                <div>
                                    <label class="block text-sm">Patient</label>
                                    <div class="mt-1 text-sm" x-text="form.patient_name"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium">Phase I (Date)</label>
                                        <input type="date" name="phase1_date" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase1.date"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase1_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" x-text="form.phases.phase1.procedures"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Phase II (Date)</label>
                                        <input type="date" name="phase2_date" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase2.date"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase2_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" x-text="form.phases.phase2.procedures"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Phase III (Date)</label>
                                        <input type="date" name="phase3_date" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase3.date"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase3_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" x-text="form.phases.phase3.procedures"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Phase IV (Date)</label>
                                        <input type="date" name="phase4_date" class="mt-1 block w-full rounded border-gray-200" x-model="form.phases.phase4.date"/>
                                        <label class="block text-sm font-medium mt-2">Procedures</label>
                                        <textarea name="phase4_procedures" rows="3" class="mt-1 block w-full rounded border-gray-200" x-text="form.phases.phase4.procedures"></textarea>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Discussion with Patient</label>
                                    <textarea name="treatment_options" rows="2" class="mt-1 block w-full rounded border-gray-200" x-text="form.treatment_options"></textarea>
                                    <textarea name="risks_and_benefits" rows="2" class="mt-1 block w-full rounded border-gray-200 mt-2" x-text="form.risks_and_benefits"></textarea>
                                    <textarea name="alternatives" rows="2" class="mt-1 block w-full rounded border-gray-200 mt-2" x-text="form.alternatives"></textarea>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                        <input name="estimated_costs" placeholder="Estimated costs" class="mt-1 block w-full rounded border-gray-200" x-model="form.estimated_costs"/>
                                        <input name="payment_options" placeholder="Payment options" class="mt-1 block w-full rounded border-gray-200" x-model="form.payment_options"/>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="consent_given" class="rounded" :checked="form.consent_given"/>
                                        <span class="ml-2 text-sm">Patient gives informed consent</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <input type="text" name="patient_signature" placeholder="Patient's Signature" class="mt-1 block w-full rounded border-gray-200" x-model="form.patient_signature"/>
                                    <input type="text" name="dentist_signature" placeholder="Dentist's Signature" class="mt-1 block w-full rounded border-gray-200" x-model="form.dentist_signature"/>
                                </div>
                                <div>
                                    <label class="block text-sm">Consent Date</label>
                                    <input type="date" name="consent_date" class="mt-1 block w-full rounded border-gray-200" x-model="form.consent_date"/>
                                </div>

                                <div class="flex justify-end gap-2 mt-3">
                                    <button type="button" @click="openEditModal=false" class="px-4 py-2 border rounded">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- View Modal --}}
                    <div x-show="openViewModal" class="fixed inset-0 z-40 flex items-start justify-center pt-16 px-4">
                        <div class="fixed inset-0 bg-black bg-opacity-40" @click="openViewModal=false"></div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl z-50 overflow-auto">
                            <div class="p-4 border-b flex justify-between items-center">
                                <h3 class="text-lg font-semibold">Treatment Plan Details</h3>
                                <button @click="openViewModal=false" class="text-gray-500">✕</button>
                            </div>

                            <div class="p-4 space-y-3">
                                <div class="text-sm text-gray-600">
                                    <strong>Patient:</strong> <span x-text="view.patient"></span>
                                </div>

                                <template x-for="(phase, key) in view.phases">
                                    <div class="border rounded p-3">
                                        <div class="font-semibold" x-text="key.replace('phase','Phase ')"></div>
                                        <div class="text-sm mt-1"><strong>Date:</strong> <span x-text="phase.date ?? '-'"></span></div>
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
                                    <div class="text-sm"><strong>Consent date:</strong> <span x-text="view.consent_date"></span></div>
                                </div>

                                <div class="flex justify-end">
                                    <button @click="openViewModal=false" class="px-4 py-2 border rounded">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- x-data --}}
            </div>
        </div>
    </div>

    {{-- Alpine + small JS --}}
    <script>
        function treatmentPlanPage(){
            return {
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

                openEdit(id){
                    // fetch data from edit endpoint
                    fetch(`/treatment-plans/${id}/edit`)
                        .then(r => r.json())
                        .then(data => {
                            this.form.patient_id = data.patient_id;
                            this.form.patient_name = data.patient.last_name + ', ' + data.patient.first_name + ' (ID: ' + data.patient.id + ')';
                            this.form.phases = data.phases || {
                                phase1: { date: '', procedures: '' },
                                phase2: { date: '', procedures: '' },
                                phase3: { date: '', procedures: '' },
                                phase4: { date: '', procedures: '' },
                            };
                            this.form.treatment_options = data.treatment_options || '';
                            this.form.risks_and_benefits = data.risks_and_benefits || '';
                            this.form.alternatives = data.alternatives || '';
                            this.form.estimated_costs = data.estimated_costs || '';
                            this.form.payment_options = data.payment_options || '';
                            this.form.consent_given = data.consent_given;
                            this.form.patient_signature = data.patient_signature || '';
                            this.form.dentist_signature = data.dentist_signature || '';
                            this.form.consent_date = data.consent_date || '';

                            this.editFormAction = `/treatment-plans/${id}`;
                            this.openEditModal = true;
                        });
                },

                openView(id){
                    fetch(`/treatment-plans/${id}/edit`)
                        .then(r => r.json())
                        .then(data => {
                            this.view.patient = data.patient.last_name + ', ' + data.patient.first_name + ' (ID: ' + data.patient.id + ')';
                            this.view.phases = data.phases || {};
                            this.view.treatment_options = data.treatment_options || '';
                            this.view.risks_and_benefits = data.risks_and_benefits || '';
                            this.view.alternatives = data.alternatives || '';
                            this.view.estimated_costs = data.estimated_costs || '';
                            this.view.payment_options = data.payment_options || '';
                            this.view.consent_given = data.consent_given;
                            this.view.patient_signature = data.patient_signature || '';
                            this.view.dentist_signature = data.dentist_signature || '';
                            this.view.consent_date = data.consent_date || '';
                            this.openViewModal = true;
                        });
                },

                // small helper to convert newlines to <br>
                nl2br(txt){
                    if(!txt) return '-';
                    return txt.replace(/\n/g, '<br>');
                }
            }
        }
    </script>

</x-app-layout>
