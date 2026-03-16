<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="../assets/css/plugins.min.css" />
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
<script src="assets/js/kaiadmin.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
</script>


<x-app-layout>
    <div class="container-fluid py-4">

        <x-slot name="header">
            <header class="h4">
                <h1>Radiograph Section</h1>
            </header>
        </x-slot>

        <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-800">Records</h2>
                <button type="button" id="btnOpenModal" class="btn btn-primary"> 
                    <i class="fa fa-plus me-1"></i> New Radiograph
                </button>
            </div>

            <div class="mb-4">
    <form method="GET" class="flex flex-wrap items-center gap-3 text-sm">
        <select name="patient_id" class="w-48 rounded-md border-gray-200 shadow-sm focus:ring-indigo-400">
            <option value="">All Patients</option>
            @foreach($filterPatients as $patient)
                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </option>
            @endforeach
        </select>

        <select name="type" class="w-40 rounded-md border-gray-200 shadow-sm focus:ring-indigo-400">
            <option value="">All Types</option>
            @foreach($allTypes as $t)
                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>

        <select name="exact_date" class="w-52 rounded-md border-gray-200 shadow-sm focus:ring-indigo-400">
            <option value="">All Dates</option>
            @foreach($availableDates as $date)
                <option value="{{ $date->date_val }}" {{ request('exact_date') == $date->date_val ? 'selected' : '' }}>
                    {{ $date->date_label }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary btn-sm px-4">Apply</button>

        @if(request()->hasAny(['patient_id', 'type', 'exact_date']))
            <a href="{{ route('radiographs.index') }}" class="btn btn-link text-gray-500">Reset</a>
        @endif
    </form>
</div>
         
            <div class="card-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Findings</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($radiographs as $rg)
                            <tr class="border-b last:border-b-0">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($rg->image_path && Storage::disk('public')->exists($rg->image_path))
                                    <img src="{{ asset('storage/'.$rg->image_path) }}" alt="thumb"
                                        class="w-16 h-16 object-cover rounded-md border">
                                    @else
                                    <div class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs border">
                                        No Image
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $rg->patient ? $rg->patient->first_name . ' ' . $rg->patient->last_name : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $rg->date_taken ? \Carbon\Carbon::parse($rg->date_taken)->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $rg->type ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $rg->findings ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2 items-center">
                                        <button type="button" data-id="{{ $rg->id }}"
                                            data-patient-id="{{ $rg->patient_id ?? '' }}"
                                            data-patient="{{ e(optional($rg->patient)->first_name . ' ' . optional($rg->patient)->last_name) }}"
                                            data-date="{{ optional($rg->date_taken) ? \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') : '' }}"
                                            data-type="{{ e($rg->type) }}" data-findings="{{ e($rg->findings) }}"
                                            data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                                            data-pdf="{{ route('radiographs.download-pdf', $rg->id) }}"
                                            class="btn btn-primary btn-medium btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button type="button" data-id="{{ $rg->id }}"
                                            data-patient-id="{{ $rg->patient_id ?? '' }}"
                                            data-date="{{ optional($rg->date_taken) ? \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') : '' }}"
                                            data-type="{{ e($rg->type) }}" data-findings="{{ e($rg->findings) }}"
                                            data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                                            class="btn btn-medium btn-warning text-white btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button type="button"
                                            class="btn btn-medium btn-danger btn-delete"
                                            data-action="{{ route('radiographs.destroy', $rg->id) }}"
                                            data-patient="{{ optional($rg->patient)->first_name }} {{ optional($rg->patient)->last_name }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">No records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $radiographs->links() }}
            </div>
        </div>
    </div>

<!-- ADD/EDIT MODAL -->
 
    <div id="modalBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-5 border-b flex items-center justify-between bg-blue-600 text-white rounded-t-lg">
                <h3 id="modalTitle" class="text-lg font-medium">Add Radiograph</h3>
                <button type="button" id="modalClose" class="text-white hover:text-gray-200">✕</button>
            </div>

            <form id="modalForm" class="p-5 overflow-y-auto" method="POST" action="{{ route('radiographs.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="form_method" value="">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <select id="modal_patient_id" name="patient_id" required class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
                            <option value="">Select Patient</option>
                            @foreach($allPatients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Taken</label>
                        <input id="date_taken" name="date_taken" type="date" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Type of Radiograph</label>
                        <input type="text" id="type_id" name="type" required class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" placeholder="Enter radiograph type">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Findings</label>
                        <textarea id="findings" name="findings" rows="4" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm"></textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Radiograph Image</label>
                        <input id="image" name="image" type="file" class="mt-1 block w-full" accept="image/*">
                        <div id="currentPreview" class="mt-3 hidden">
                            <img id="previewImg" src="" class="mt-2 w-28 h-28 object-cover rounded-md border">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" id="btnCancel" class="btn btn-black btn-sm">Cancel</button>
                    <button type="submit" id="btnSave" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>

<!-- VIEW MODAL -->

    <div id="viewBackdrop" class="fixed inset-0 hidden items-center justify-center z-50 bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
            <div class="px-5 py-4 border-b flex items-center justify-between bg-blue-600 text-white rounded-t-lg">
                <h3 class="text-lg font-medium flex items-center gap-2">
                    <span class="text-xs uppercase tracking-wide opacity-80">Patient Name:</span>
                    <span id="viewPatientName" class="font-semibold">—</span>
                </h3>
                <button type="button" id="viewClose" class="text-white hover:text-gray-200">✕</button>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="bg-gray-50 rounded-md p-4 flex items-center justify-center border">
                        <img id="viewImageLarge" src="" alt="Radiograph" class="max-h-96 object-contain">
                    </div>
                </div>
                <div class="md:col-span-1 space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Date</h4>
                        <p id="viewDate" class="mt-1 text-sm text-gray-700">—</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Type</h4>
                        <p id="viewType" class="mt-1 text-sm text-gray-700">—</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Findings</h4>
                        <p id="viewFindings" class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">—</p>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        <a id="downloadLink" href="#" target="_blank" class="btn btn-primary btn-md flex items-center justify-center gap-2">
                            <i class="fas fa-images"></i> Full Image
                        </a>
                        <button type="button" id="pdfDownloadBtn" class="btn btn-danger btn-md flex items-center justify-center gap-2">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- DELETE MODAL -->

    <div id="deleteBackdrop" class="fixed inset-0 hidden items-center justify-center z-[9999] bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="p-4 bg-red-600 text-white">
                <h3 class="text-lg font-bold">Confirm Delete</h3>
            </div>
            <div class="p-6 text-sm text-gray-700">
                Are you sure you want to delete this record for <span class="font-bold" id="deletePatientName"></span>?
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="p-4 flex justify-end gap-3 bg-gray-50 border-t">
                    <button type="button" id="btnDeleteCancel" class="btn btn-black btn-sm">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const backdrop = document.getElementById('modalBackdrop');
        const viewBackdrop = document.getElementById('viewBackdrop');
        const deleteBackdrop = document.getElementById('deleteBackdrop');
        const modalForm = document.getElementById('modalForm');
        const previewBlock = document.getElementById('currentPreview');
        const previewImg = document.getElementById('previewImg');

        // Modal Controls
        function openModal() { backdrop.classList.replace('hidden', 'flex'); }
        function closeModal() { 
            backdrop.classList.replace('flex', 'hidden'); 
            modalForm.reset();
            document.getElementById('form_method').value = '';
            previewBlock.classList.add('hidden');
        }

        document.getElementById('btnOpenModal').addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = "Add Radiograph";
            modalForm.action = "{{ route('radiographs.store') }}";
            openModal();
        });

        document.getElementById('modalClose').onclick = closeModal;
        document.getElementById('btnCancel').onclick = closeModal;

        // View/Edit/Delete Delegation
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.btn-edit');
            const viewBtn = e.target.closest('.btn-view');
            const delBtn = e.target.closest('.btn-delete');

            if (editBtn) {
                const d = editBtn.dataset;
                document.getElementById('modalTitle').innerText = "Edit Radiograph";
                modalForm.action = "/radiographs/" + d.id;
                document.getElementById('form_method').value = 'PUT';
                document.getElementById('modal_patient_id').value = d.patientId;
                document.getElementById('date_taken').value = d.date;
                document.getElementById('type_id').value = d.type;
                document.getElementById('findings').value = d.findings;
                if (d.imagepath) {
                    previewImg.src = d.imagepath;
                    previewBlock.classList.remove('hidden');
                }
                openModal();
            }

            if (viewBtn) {
                const d = viewBtn.dataset;
                document.getElementById('viewPatientName').innerText = d.patient;
                document.getElementById('viewDate').innerText = d.date;
                document.getElementById('viewType').innerText = d.type;
                document.getElementById('viewFindings').innerText = d.findings || 'No findings.';
                document.getElementById('viewImageLarge').src = d.imagepath;
                document.getElementById('downloadLink').href = d.imagepath;
                document.getElementById('pdfDownloadBtn').onclick = () => window.open(d.pdf, '_blank');
                viewBackdrop.classList.replace('hidden', 'flex');
            }

            if (delBtn) {
                document.getElementById('deleteForm').action = delBtn.dataset.action;
                document.getElementById('deletePatientName').innerText = delBtn.dataset.patient;
                deleteBackdrop.classList.replace('hidden', 'flex');
            }
        });

        document.getElementById('viewClose').onclick = () => viewBackdrop.classList.replace('flex', 'hidden');
        document.getElementById('btnDeleteCancel').onclick = () => deleteBackdrop.classList.replace('flex', 'hidden');

        // Image Preview on upload
        document.getElementById('image').addEventListener('change', function(ev) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewBlock.classList.remove('hidden');
            };
            if(ev.target.files[0]) reader.readAsDataURL(ev.target.files[0]);
        });
    </script>
</x-app-layout>