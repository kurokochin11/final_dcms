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
$(document).ready(function() {
    $('#myTable').DataTable({
        responsive: true
    });
});
</script>

@section('title', 'Radiograph Records')
<x-sidebar />
<x-app-layout>
    <div class="container-fluid py-4">

        <!-- Header -->
           <x-slot name="header">
        <header class="h4">
            <h1 >Radiograph Section</h1>
        </header>
    </x-slot>

        
        <!-- Main Content -->
        <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-800">Records</h2>
                <button type="button" id="btnOpenModal" class="btn btn-primary"> <i class="fa fa-plus me-1"></i> New
                    Radiograph
                </button>
            </div>

            <!-- Filters for Radiographs-->

            <div class="mb-4">
                <form method="GET" class="flex flex-wrap items-center gap-2 text-sm">

                    <!-- Patient -->
                    <select name="patient_id"
                        class="w-48 rounded-md border-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">All Patients</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}"
                            {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Type -->
                    <select name="type"
                        class="w-40 rounded-md border-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Year Range -->

                    <select name="year_range"
                        class="rounded-md border-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">All Years</option>
                        @for($y = now()->year - 5; $y <= now()->year + 5; $y++)
                            @php
                            $value = "{$y}-" . ($y + 1);
                            @endphp
                            <option value="{{ $value }}" {{ request('year_range') === $value ? 'selected' : '' }}>
                                {{ $y }} to {{ $y + 1 }}
                            </option>
                            @endfor
                    </select>

                    <!-- Apply Button -->

                    <button type="submit" class="btn btn-secondary btn-sm">
                        Apply
                    </button>

                    <!-- Reset Button -->
                    @if(request()->hasAny(['patient_id','type','year_range']))
                    <a href="{{ route('radiographs.index') }}" class="btn btn-black btn-link">
                        Reset
                    </a>
                    @endif

                </form>
            </div>

            <!-- Radiographs Table -->

            <div class="card-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
                        <thead class="bg-white">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Image</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Patient</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Findings</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
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
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs border">
                                        No Image
                                    </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($rg->patient)
                                    {{ $rg->patient->first_name }} {{ $rg->patient->last_name }}
                                    @else
                                    —
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ optional($rg->date_taken) ? \Carbon\Carbon::parse($rg->date_taken)->format('M d, Y') : '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">{{ $rg->type ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $rg->findings ?? '—' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2 items-center">

                                        <!-- View button -->
                                        <button type="button" data-id="{{ $rg->id }}"
                                            data-patient-id="{{ $rg->patient_id ?? '' }}"
                                            data-patient="{{ e(optional($rg->patient)->first_name . ' ' . optional($rg->patient)->last_name) }}"
                                            data-date="{{ optional($rg->date_taken) ? \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') : '' }}"
                                            data-type="{{ e($rg->type) }}" data-findings="{{ e($rg->findings) }}"
                                            data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                                            class="btn btn-primary btn-medium btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Edit button -->
                                        <button type="button" data-id="{{ $rg->id }}"
                                            data-patient-id="{{ $rg->patient_id ?? '' }}"
                                            data-date="{{ optional($rg->date_taken) ? \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') : '' }}"
                                            data-type="{{ e($rg->type) }}" data-findings="{{ e($rg->findings) }}"
                                            data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                                            class="btn btn-medium btn-warning text-white btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                       <form method="POST" class="d-flex align-items-center m-0 delete-form">
                                      @csrf
                                  @method('DELETE')
                                   <button type="button"
        class="btn btn-medium btn-danger btn-delete"
        data-action="{{ route('radiographs.destroy', $rg->id) }}"
        data-patient="{{ optional($rg->patient)->first_name }} {{ optional($rg->patient)->last_name }}">
</button>
                                     </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    <div class="space-y-3">
                                        <div class="text-sm">No records found.</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $radiographs->links() }}
            </div>
        </div>
    </div>

   {{-- Add/Edit Modal --}}
<div id="modalBackdrop"
     class="fixed inset-0 hidden items-center justify-center z-40 bg-black/40">


    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4
            max-h-[90vh] overflow-hidden flex flex-col">


        <!-- HEADER -->
        <div class="p-5 border-b flex items-center justify-between bg-blue-600 text-white rounded-t-lg">
            <h3 id="modalTitle" class="text-lg font-medium">Add Radiograph</h3>
            <button type="button" id="modalClose" class="text-white hover:text-gray-200"
                aria-label="Close">✕</button>
        </div>

        <!-- FORM -->
       <form id="modalForm"class="p-5 overflow-y-auto"style="max-height: calc(90vh - 80px);"method="POST" action="{{ route('radiographs.store') }}"

            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="form_method" value="">
            <input type="hidden" name="edit_id" id="edit_id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                    <select id="patient_id" name="patient_id" required
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date Taken</label>
                    <input id="date_taken" name="date_taken" type="date"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Type of Radiograph</label>
                    <input type="text" id="type_id" name="type" required
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm"
                        placeholder="Enter radiograph type" list="typesList">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Findings</label>
                    <textarea id="findings" name="findings" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Radiograph Image</label>
                    <input id="image" name="image" type="file" class="mt-1 block w-full" accept="image/*,.pdf">
                    <div id="currentPreview" class="mt-3 hidden">
                        <div class="text-sm text-gray-500">Current image preview</div>
                        <img id="previewImg" src="" class="mt-2 w-28 h-28 object-cover rounded-md border">
                    </div>
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="btnCancel"
                    class="btn btn-black btn-sm">Cancel</button>
               <button type="submit" id="btnSave" class="btn btn-primary btn-sm">Submit</button>

            </div>
        </form>
    </div>
</div>

    {{-- View Modal --}}
<div id="viewBackdrop"
     class="fixed inset-0 hidden items-center justify-center z-50 bg-black/40">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
        <!-- HEADER -->
        <div class="px-5 py-4 border-b flex items-center justify-between bg-blue-600 text-white rounded-t-lg">

            <div>
               <h3 class="text-lg font-medium flex items-center gap-2">
    <span class="text-xs uppercase tracking-wide opacity-80">
        Patient Name:
    </span>
    <span id="viewPatientName" class="font-semibold">
        —
    </span>
</h3>
                <div id="viewSubtitle" class="text-sm opacity-80"></div>
            </div>
            <button type="button" id="viewClose" class="text-white hover:text-gray-200" aria-label="Close">✕</button>
        </div>

        <!-- CONTENT -->
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
                    <h4 class="text-sm font-medium text-gray-700">Type of Radiograph</h4>
                    <p id="viewType" class="mt-1 text-sm text-gray-700">—</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Findings</h4>
                    <p id="viewFindings" class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">—</p>
                </div>
                <div class="mt-4">
                    <a id="downloadLink" href="#" target="_blank"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 inline-flex items-center gap-2">
                        <i class="fas fa-images"></i> See the full Image
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="deleteBackdrop"
     class="fixed inset-0 hidden items-center justify-center z-[9999] bg-black/40">

  <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 relative">
    <!-- Header -->
    <div class="p-4 border-b bg-red-600 text-white rounded-t-lg">
      <h3 class="text-lg font-medium">Confirm Delete</h3>
    </div>

    <!-- Body -->
    <div class="p-5 text-sm text-gray-700">
      Are you sure you want to delete this radiograph record of
      <span class="font-semibold" id="deletePatientName"></span>?
    </div>

    <!-- Footer -->
    <div class="p-4 flex justify-end gap-2 border-t">
      <button type="button" id="btnDeleteCancel" class="btn btn-dark btn-sm">
        Cancel
      </button>

      <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
          Delete
        </button>
      </form>
    </div>
  </div>
</div>


    {{-- JS --}}
    <script>
        const deleteBackdrop = document.getElementById('deleteBackdrop');
const deleteForm = document.getElementById('deleteForm');
const deletePatientName = document.getElementById('deletePatientName');
const btnDeleteCancel = document.getElementById('btnDeleteCancel');

function openDeleteModal(action, patient) {
    deleteForm.action = action;
    deletePatientName.innerText = patient || '';
    deleteBackdrop.classList.remove('hidden');
    deleteBackdrop.classList.add('flex');
}

function closeDeleteModal() {
    deleteBackdrop.classList.remove('flex');
    deleteBackdrop.classList.add('hidden');
    deleteForm.action = '';
    deletePatientName.innerText = '';
}

btnDeleteCancel.addEventListener('click', closeDeleteModal);

// Listen for delete button clicks
document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.btn-delete');
    if (!deleteBtn) return;

    const action = deleteBtn.dataset.action;
    const patient = deleteBtn.dataset.patient;
    openDeleteModal(action, patient);
});

    (function() {
        const backdrop = document.getElementById('modalBackdrop');
        const openBtn = document.getElementById('btnOpenModal');
        const closeBtn = document.getElementById('modalClose');
        const cancelBtn = document.getElementById('btnCancel');
        const modalTitle = document.getElementById('modalTitle');
        const modalForm = document.getElementById('modalForm');
        const methodInput = document.getElementById('form_method');
        const editId = document.getElementById('edit_id');
        const previewBlock = document.getElementById('currentPreview');
        const previewImg = document.getElementById('previewImg');

        const viewBackdrop = document.getElementById('viewBackdrop');
        const viewClose = document.getElementById('viewClose');
        const viewPatientName = document.getElementById('viewPatientName');
        const viewDate = document.getElementById('viewDate');
        const viewType = document.getElementById('viewType');
        const viewImageLarge = document.getElementById('viewImageLarge');
        const viewFindings = document.getElementById('viewFindings');
        const downloadLink = document.getElementById('downloadLink');

        function openModal() {
            backdrop.classList.remove('hidden');
            backdrop.classList.add('flex');
        }

        function closeModal() {
            backdrop.classList.remove('flex');
            backdrop.classList.add('hidden');
            resetModal();
        }

        function resetModal() {
            modalTitle.innerText = "Add Radiograph";
            modalForm.action = "{{ route('radiographs.store') }}";
            methodInput.value = '';
            editId.value = '';
            modalForm.reset();
            previewBlock.classList.add('hidden');
            previewImg.src = '';
        }
        openBtn && openBtn.addEventListener('click', function() {
            resetModal();
            openModal();
        });
        closeBtn && closeBtn.addEventListener('click', closeModal);
        cancelBtn && cancelBtn.addEventListener('click', closeModal);

        function openViewModal() {
            viewBackdrop.classList.remove('hidden');
            viewBackdrop.classList.add('flex');
        }

        function closeViewModal() {
            viewBackdrop.classList.remove('flex');
            viewBackdrop.classList.add('hidden');
            viewImageLarge.src = '';
            viewPatientName.innerText = '';
            viewDate.innerText = '';
            viewType.innerText = '';
            viewFindings.innerText = '';
            downloadLink.href = '#';
            downloadLink.classList.add('pointer-events-none', 'opacity-50');
        }
        viewClose && viewClose.addEventListener('click', closeViewModal);

        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.btn-edit');
            const viewBtn = e.target.closest('.btn-view');

            if (editBtn) {
                const id = editBtn.dataset.id;
                const patientId = editBtn.dataset.patientId || '';
                const date = editBtn.dataset.date || '';
                const type = editBtn.dataset.type || '';
                const findings = editBtn.dataset.findings || '';
                const imagepath = editBtn.dataset.imagepath || '';

                modalTitle.innerText = "Edit Radiograph Records";
                modalForm.action = "/radiographs/" + id;
                methodInput.value = 'PUT';
                editId.value = id;

                document.getElementById('patient_id').value = patientId;
                document.getElementById('date_taken').value = date;
                document.getElementById('type_id').value = type;
                document.getElementById('findings').value = findings;

                if (imagepath && !imagepath.toLowerCase().endsWith('.pdf')) {
                    previewImg.src = imagepath;
                    previewBlock.classList.remove('hidden');
                } else {
                    previewBlock.classList.add('hidden');
                    previewImg.src = '';
                }

                openModal();
                return;
            }

            if (viewBtn) {
                const patient = viewBtn.dataset.patient || '';
                const date = viewBtn.dataset.date || '';
                const type = viewBtn.dataset.type || '';
                const findings = viewBtn.dataset.findings || '';
                const imagepath = viewBtn.dataset.imagepath || '';

                viewPatientName.innerText = patient || '—';
                if (date) {
    const d = new Date(date);
    viewDate.innerText = d.toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric'
    });
} else {
    viewDate.innerText = '—';
}

                viewType.innerText = type || '—';
                viewFindings.innerText = findings || 'No findings recorded.';

                if (imagepath) {
                    viewImageLarge.src = imagepath.toLowerCase().endsWith('.pdf') ? '' : imagepath;
                    downloadLink.href = imagepath;
                    downloadLink.classList.remove('pointer-events-none', 'opacity-50');
                } else {
                    viewImageLarge.src = '';
                    downloadLink.href = '#';
                    downloadLink.classList.add('pointer-events-none', 'opacity-50');
                }

                openViewModal();
                return;
            }
        });

        const fileInput = document.getElementById('image');
        fileInput && fileInput.addEventListener('change', function(ev) {
            const file = ev.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                previewBlock.classList.add('hidden');
                previewImg.src = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBlock.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        });
    })();
    </script>
</x-app-layout>