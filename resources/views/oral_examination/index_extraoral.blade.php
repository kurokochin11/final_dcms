<x-app-layout>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Extraoral Examinations
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Records</h3>
                <div x-data>
                    <button @click="$dispatch('open-extraoral-modal', { mode: 'create' })" type="button"
                            class="btn btn-primary btn-round gap-2">
                        <i class="fas fa-plus"></i> New Exam
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                
                <div class="d-flex align-items-center mb-3 gap-2">
                    <select id="patientFilter" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">All Patients</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->first_name }} {{ $p->last_name }}">{{ $p->last_name }}, {{ $p->first_name }}</option>
                        @endforeach
                    </select>

                    <select id="dateFilter" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">All Dates</option>
                        @foreach($examinations->pluck('examination_date')->unique()->sortDesc() as $date)
                            <option value="{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}">
                                {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>

                    <button id="resetFilters" class="btn btn-outline-secondary btn-sm">Reset</button>
                </div>

                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>No.</th>
                                <th>Patient</th>
                                <th>Examination Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examinations as $exam)
                                @php
                                    $record = [
                                        'id' => $exam->id,
                                        'patient_id' => $exam->patient_id,
                                        'patient_name' => optional($exam->patient)->first_name . ' ' . optional($exam->patient)->last_name,
                                        'examination_date' => $exam->examination_date,
                                        'facial_symmetry' => $exam->facial_symmetry,
                                        'facial_symmetry_notes' => $exam->facial_symmetry_notes,
                                        'lymph_nodes' => $exam->lymph_nodes,
                                        'lymph_nodes_location' => $exam->lymph_nodes_location,
                                        'tmj_pain' => $exam->tmj_pain ? '1' : '0',
                                        'tmj_clicking' => $exam->tmj_clicking ? '1' : '0',
                                        'tmj_limited_opening' => $exam->tmj_limited_opening ? '1' : '0',
                                        'mio' => $exam->mio,
                                        'notes' => $exam->notes,
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $record['patient_name'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($exam->examination_date)->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="window.dispatchEvent(new CustomEvent('open-extraoral-view', { detail: {{ json_encode($record) }} }))">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm text-white"
                                                onclick="window.dispatchEvent(new CustomEvent('open-extraoral-modal', { detail: { mode: 'edit', record: {{ json_encode($record) }} } }))">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                data-url="{{ route('extraoral_examinations.destroy', $exam) }}" 
                                                onclick="openDeleteModal(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $examinations->links() }}</div>
            </div>
        </div>
    </div>

    <div x-data="{ open: false, record: {} }"
         x-on:open-extraoral-view.window="record = $event.detail; open = true"
         x-show="open" x-cloak
         class="modal fade" :class="{ 'show d-block': open }" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Examination Details</h5>
                    <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <span class="text-muted small uppercase">Patient Name</span>
                        <div class="fw-bold fs-5" x-text="record.patient_name"></div>
                        <span class="text-muted small">Date: </span>
                        <span class="fw-bold" x-text="record.examination_date"></span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Facial Symmetry</label>
                            <span class="badge" :class="record.facial_symmetry === 'Normal' ? 'bg-success' : 'bg-warning'" x-text="record.facial_symmetry"></span>
                            <p class="mt-1 small" x-text="record.facial_symmetry_notes"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Lymph Nodes</label>
                            <span class="badge" :class="record.lymph_nodes === 'Non-palpable' ? 'bg-success' : 'bg-info'" x-text="record.lymph_nodes"></span>
                            <p class="mt-1 small" x-text="record.lymph_nodes_location"></p>
                        </div>
                    </div>

                    <hr>

                    <div class="bg-light p-3 rounded">
                        <h6 class="fw-bold mb-3">TMJ Assessment</h6>
                        <div class="d-flex justify-content-between">
                            <span>Pain: <b x-text="record.tmj_pain == '1' ? 'Yes' : 'No'"></b></span>
                            <span>Clicking: <b x-text="record.tmj_clicking == '1' ? 'Yes' : 'No'"></b></span>
                            <span>Limited Opening: <b x-text="record.tmj_limited_opening == '1' ? 'Yes' : 'No'"></b></span>
                        </div>
                        <div class="mt-2">MIO: <b x-text="record.mio"></b> mm</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" @click="window.open(`/extraoral-examinations/${record.id}/pdf`, '_blank')">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button class="btn btn-secondary btn-sm" @click="open = false">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div x-data="extraoralModal()" 
         x-on:open-extraoral-modal.window="setForm($event.detail)" 
         x-show="open" x-cloak
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 bg-primary text-white flex justify-between items-center">
                <h3 class="text-lg font-medium" x-text="mode === 'create' ? 'Add Extraoral Exam' : 'Edit Extraoral Exam'"></h3>
                <button @click="close" class="text-white text-2xl">&times;</button>
            </div>
            
            <form :action="formAction" method="POST" class="px-6 py-4 space-y-4 overflow-y-auto">
                @csrf
                <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Patient</label>
                        <select name="patient_id" x-model="form.patient_id" required class="form-select">
                            <option value="">Select Patient</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->last_name }}, {{ $p->first_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Date</label>
                        <input type="date" name="examination_date" x-model="form.examination_date" required class="form-control">
                    </div>
                </div>

                
                    <div class="mb-4">
    <label class="form-label small fw-bold text-uppercase text-muted tracking-wide mb-2">
        Facial Symmetry
    </label>
  
       <div class="row g-2">
    <div class="col-6">
        <input type="radio" class="btn-check" name="facial_symmetry" value="Normal" id="fs_normal" x-model="form.facial_symmetry">
        <label 
            class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 transition-all" 
            for="fs_normal"
            :class="form.facial_symmetry === 'Normal' ? 'btn-primary text-white' : 'btn-outline-primary'"
        >
            <i class="fas fa-check-circle" x-show="form.facial_symmetry === 'Normal'"></i>
            Normal
        </label>
    </div>

    <div class="col-6">
        <input type="radio" class="btn-check" name="facial_symmetry" value="Asymmetrical" id="fs_asym" x-model="form.facial_symmetry">
        <label 
            class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 transition-all" 
            for="fs_asym"
            :class="form.facial_symmetry === 'Asymmetrical' ? 'btn-primary text-white' : 'btn-outline-primary'"
        >
            <i class="fas fa-exclamation-circle" x-show="form.facial_symmetry === 'Asymmetrical'"></i>
            Asymmetrical
        </label>
    </div>
</div>
    
    <div x-show="form.facial_symmetry === 'Asymmetrical'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         class="mt-3">
        <label class="small text-muted mb-1">Observation Details</label>
        <textarea name="facial_symmetry_notes" 
                  x-model="form.facial_symmetry_notes" 
                  class="form-control border-warning shadow-sm" 
                  rows="2"
                  placeholder="Describe location and nature of asymmetry..."></textarea>
    </div>
</div>

<div class="mb-4">
    <label class="form-label small fw-bold text-uppercase text-muted tracking-wide mb-2">
        Lymph Nodes
    </label>
    <div class="row g-2">
    <div class="col-6">
        <input type="radio" class="btn-check" name="lymph_nodes" value="Non-palpable" id="ln_non" x-model="form.lymph_nodes">
        <label 
            class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2" 
            for="ln_non"
            :class="form.lymph_nodes === 'Non-palpable' ? 'btn-primary text-white' : 'btn-outline-primary'"
            style="transition: all 0.2s ease;"
        >
            <i class="fas fa-shield-alt" x-show="form.lymph_nodes === 'Non-palpable'"></i>
            Non-palpable
        </label>
    </div>

    <div class="col-6">
        <input type="radio" class="btn-check" name="lymph_nodes" value="Palpable" id="ln_palp" x-model="form.lymph_nodes">
        <label 
            class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2" 
            for="ln_palp"
            :class="form.lymph_nodes === 'Palpable' ? 'btn-primary text-white' : 'btn-outline-primary'"
            style="transition: all 0.2s ease;"
        >
            <i class="fas fa-search" x-show="form.lymph_nodes === 'Palpable'"></i>
            Palpable
        </label>
    </div>
</div>
    <div x-show="form.lymph_nodes === 'Palpable'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         class="mt-3">
        <label class="small text-muted mb-1">Node Location/Size</label>
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-info"><i class="fas fa-map-marker-alt text-info"></i></span>
            <input type="text" 
                   name="lymph_nodes_location" 
                   x-model="form.lymph_nodes_location" 
                   class="form-control border-info" 
                   placeholder="e.g. Left Submandibular, 1cm, mobile">
        </div>
    </div>
</div>

                <div class="card bg-light p-3">
                    <h6 class="small fw-bold mb-3">TMJ Assessment</h6>
                    <div class="row text-center small">
                        <div class="col-4">
                            <span>Pain</span><br>
                            <input type="radio" name="tmj_pain" value="1" x-model="form.tmj_pain"> Y 
                            <input type="radio" name="tmj_pain" value="0" x-model="form.tmj_pain"> N
                        </div>
                        <div class="col-4">
                            <span>Clicking</span><br>
                            <input type="radio" name="tmj_clicking" value="1" x-model="form.tmj_clicking"> Y 
                            <input type="radio" name="tmj_clicking" value="0" x-model="form.tmj_clicking"> N
                        </div>
                        <div class="col-4">
                            <span>Limited</span><br>
                            <input type="radio" name="tmj_limited_opening" value="1" x-model="form.tmj_limited_opening"> Y 
                            <input type="radio" name="tmj_limited_opening" value="0" x-model="form.tmj_limited_opening"> N
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="small">MIO (mm)</label>
                        <input type="number" name="mio" x-model="form.mio" class="form-control w-50">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="close" class="btn btn-dark btn-sm">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" x-text="mode === 'create' ? 'Save' : 'Update'"></button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Delete Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">Are you sure you want to delete this record?</div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                responsive: true,
                dom: 'rtp', // Hide default search box to use custom filters
                pageLength: 10
            });

            // Live filters
            $('#patientFilter, #dateFilter').on('change', function() {
                const patient = $('#patientFilter').val();
                const date = $('#dateFilter').val();
                table.column(1).search(patient).column(2).search(date).draw();
            });

            $('#resetFilters').on('click', function () {
                $('#patientFilter, #dateFilter').val('');
                table.search('').columns().search('').draw();
            });
        });

        function openDeleteModal(button) {
            const url = button.getAttribute('data-url');
            document.getElementById('deleteForm').action = url;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function extraoralModal() {
            return {
                open: false,
                mode: 'create',
                baseUrl: '{{ url("extraoral-examinations") }}',
                storeUrl: '{{ route("extraoral_examinations.store") }}',
                form: {
                    id: null,
                    patient_id: '',
                    examination_date: new Date().toISOString().split('T')[0],
                    facial_symmetry: 'Normal',
                    facial_symmetry_notes: '',
                    lymph_nodes: 'Non-palpable',
                    lymph_nodes_location: '',
                    tmj_pain: '0',
                    tmj_clicking: '0',
                    tmj_limited_opening: '0',
                    mio: '',
                },
                get formAction() {
                    return this.mode === 'create' ? this.storeUrl : this.baseUrl + '/' + this.form.id;
                },
                setForm(detail) {
                    this.mode = detail.mode;
                    if (this.mode === 'edit') {
                        this.form = { ...detail.record };
                    } else {
                        this.resetForm();
                    }
                    this.open = true;
                },
                resetForm() {
                    this.form = { 
                        patient_id: '', 
                        examination_date: new Date().toISOString().split('T')[0], 
                        facial_symmetry: 'Normal', 
                        lymph_nodes: 'Non-palpable',
                        tmj_pain: '0', tmj_clicking: '0', tmj_limited_opening: '0' 
                    };
                },
                close() { this.open = false; }
            }
        }
    </script>
</x-app-layout>