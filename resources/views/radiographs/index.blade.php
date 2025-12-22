
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
    $('#myTable').DataTable({
        responsive: true
    });
});
</script>

  @section('title', 'Radiograph Records')
<x-sidebar/>   
<x-app-layout>
  <div class="container-fluid py-4">

 <!-- Header -->
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Radiograph Records</h1>
    </header>

    ,<!-- Main Content -->
    <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-800">Records</h2>
        <button
          type="button"
          id="btnOpenModal"
          class="btn btn-primary"
        > <i class="fa fa-plus me-1"></i> New Radiograph
        </button>
      </div>

  <!-- Filters for Radiographs-->

<div class="mb-4">
  <form method="GET" class="flex flex-wrap items-center gap-2 text-sm">

    <!-- Patient -->
    <select
      name="patient_id"
      class="w-48 rounded-md border-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400"
    >
      <option value="">All Patients</option>
      @foreach($patients as $patient)
        <option
          value="{{ $patient->id }}"
          {{ request('patient_id') == $patient->id ? 'selected' : '' }}
        >
          {{ $patient->first_name }} {{ $patient->last_name }}
        </option>
      @endforeach
    </select>

    <!-- Type -->
    <select
      name="type"
      class="w-40 rounded-md border-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400"
    >
      <option value="">All Types</option>
      @foreach($types as $type)
        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
          {{ $type }}
        </option>
      @endforeach
    </select>

   <!-- Year Range -->

    <select
      name="year_range"
      class="rounded-md border-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400"
    >
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

    <button
      type="submit"
      class="btn btn-secondary btn-sm">
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
          <table id="myTable" class="table table-striped table-bordered table-hover">
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
                      <img src="{{ asset('storage/'.$rg->image_path) }}" alt="thumb" class="w-16 h-16 object-cover rounded-md border">
                    @else
                      <div class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs border">
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
                  <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $rg->findings ?? '—' }}</td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex gap-2 items-center">

                      <!-- View button -->
                      <button
                        type="button"
                        data-id="{{ $rg->id }}"
                        data-patient-id="{{ $rg->patient_id ?? '' }}"
                        data-patient="{{ e(optional($rg->patient)->first_name . ' ' . optional($rg->patient)->last_name) }}"
                        data-date="{{ optional($rg->date_taken) ? \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') : '' }}"
                        data-type="{{ e($rg->type) }}"
                        data-findings="{{ e($rg->findings) }}"
                        data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                        class="btn btn-normal btn-primary"
                        title="View"
                      >View</button>

                      <!-- Edit button -->
                      <x-button
                        type="button"
                        data-id="{{ $rg->id }}"
                        data-patient-id="{{ $rg->patient_id ?? '' }}"
                        data-date="{{ optional($rg->date_taken) ? \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') : '' }}"
                        data-type="{{ e($rg->type) }}"
                        data-findings="{{ e($rg->findings) }}"
                        data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                        class="btn btn-normal btn-warning"
                      >Edit</x-button>

                      <form action="{{ route('radiographs.destroy', $rg->id) }}" method="POST" onsubmit="return confirm('Delete this record?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-normal btn-danger">Delete</button>
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
  <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4">
      <div class="p-5 border-b flex items-center justify-between">
        <h3 id="modalTitle" class="text-lg font-medium text-gray-800">Add Radiograph</h3>
        <button type="button" id="modalClose" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
      </div>

      <form id="modalForm" class="p-5" method="POST" action="{{ route('radiographs.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="form_method" value="">
        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Patient</label>
            <select id="patient_id" name="patient_id" required class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
              <option value="">— select patient —</option>
              @foreach($patients as $patient)
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
    <input 
        type="text" 
        id="type_id" 
        name="type" 
        required 
        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" 
        placeholder="Enter radiograph type"
        list="typesList"
    >
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Findings</label>
            <textarea id="findings" name="findings" rows="4" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm"></textarea>
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
          <button type="button" id="btnCancel" class="px-4 py-2 rounded-md bg-gray-100 text-gray-700">Cancel</button>
          <button type="submit" id="btnSave" class="px-4 py-2 rounded-md bg-indigo-600 text-white">Save</button>
        </div>
      </form>
    </div>
  </div>

  {{-- View Modal --}}
  <div id="viewBackdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
      <div class="p-5 border-b flex items-start justify-between gap-4">
        <div>
          <h3 id="viewPatientName" class="text-lg font-medium text-gray-800">Patient Name</h3>
          <div id="viewSubtitle" class="text-sm text-gray-500"></div>
        </div>
        <button type="button" id="viewClose" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
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
            <h4 class="text-sm font-medium text-gray-700">Type of Radiograph</h4>
            <p id="viewType" class="mt-1 text-sm text-gray-700">—</p>
          </div>
          <div>
            <h4 class="text-sm font-medium text-gray-700">Findings</h4>
            <p id="viewFindings" class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">—</p>
          </div>
          <div class="mt-4">
            <a id="downloadLink" href="#" target="_blank" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-500 text-white text-xs rounded-md pointer-events-none opacity-50">Open / Download</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- JS --}}
  <script>
  (function () {
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

    function openModal() { backdrop.classList.remove('hidden'); backdrop.classList.add('flex'); }
    function closeModal() { backdrop.classList.remove('flex'); backdrop.classList.add('hidden'); resetModal(); }
    function resetModal() {
      modalTitle.innerText = "Add Radiograph";
      modalForm.action = "{{ route('radiographs.store') }}";
      methodInput.value = '';
      editId.value = '';
      modalForm.reset();
      previewBlock.classList.add('hidden');
      previewImg.src = '';
    }
    openBtn && openBtn.addEventListener('click', function(){ resetModal(); openModal(); });
    closeBtn && closeBtn.addEventListener('click', closeModal);
    cancelBtn && cancelBtn.addEventListener('click', closeModal);

    function openViewModal() { viewBackdrop.classList.remove('hidden'); viewBackdrop.classList.add('flex'); }
    function closeViewModal() {
      viewBackdrop.classList.remove('flex'); viewBackdrop.classList.add('hidden');
      viewImageLarge.src = '';
      viewPatientName.innerText = '';
      viewDate.innerText = '';
      viewType.innerText = '';
      viewFindings.innerText = '';
      downloadLink.href = '#';
      downloadLink.classList.add('pointer-events-none','opacity-50');
    }
    viewClose && viewClose.addEventListener('click', closeViewModal);

    document.addEventListener('click', function(e){
      const editBtn = e.target.closest('.btn-edit');
      const viewBtn = e.target.closest('.btn-view');

      if(editBtn){
        const id = editBtn.dataset.id;
        const patientId = editBtn.dataset.patientId || '';
        const date = editBtn.dataset.date || '';
        const type = editBtn.dataset.type || '';
        const findings = editBtn.dataset.findings || '';
        const imagepath = editBtn.dataset.imagepath || '';

        modalTitle.innerText = "Edit Radiograph";
        modalForm.action = "/radiographs/" + id;
        methodInput.value = 'PUT';
        editId.value = id;

        document.getElementById('patient_id').value = patientId;
        document.getElementById('date_taken').value = date;
        document.getElementById('type_id').value = type;
        document.getElementById('findings').value = findings;

        if(imagepath && !imagepath.toLowerCase().endsWith('.pdf')){
          previewImg.src = imagepath;
          previewBlock.classList.remove('hidden');
        } else { previewBlock.classList.add('hidden'); previewImg.src=''; }

        openModal();
        return;
      }

      if(viewBtn){
        const patient = viewBtn.dataset.patient || '';
        const date = viewBtn.dataset.date || '';
        const type = viewBtn.dataset.type || '';
        const findings = viewBtn.dataset.findings || '';
        const imagepath = viewBtn.dataset.imagepath || '';

        viewPatientName.innerText = patient || '—';
        viewDate.innerText = date ? new Date(date).toLocaleDateString() : '—';
        viewType.innerText = type || '—';
        viewFindings.innerText = findings || 'No findings recorded.';

        if(imagepath){
          viewImageLarge.src = imagepath.toLowerCase().endsWith('.pdf') ? '' : imagepath;
          downloadLink.href = imagepath;
          downloadLink.classList.remove('pointer-events-none','opacity-50');
        } else {
          viewImageLarge.src='';
          downloadLink.href='#';
          downloadLink.classList.add('pointer-events-none','opacity-50');
        }

        openViewModal();
        return;
      }
    });

    const fileInput = document.getElementById('image');
    fileInput && fileInput.addEventListener('change', function (ev) {
      const file = ev.target.files[0];
      if (!file) return;
      if (!file.type.startsWith('image/')) {
        previewBlock.classList.add('hidden');
        previewImg.src = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e){ previewImg.src = e.target.result; previewBlock.classList.remove('hidden'); }
      reader.readAsDataURL(file);
    });
  })();
  </script>
</x-app-layout>
