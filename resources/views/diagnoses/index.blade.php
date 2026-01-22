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

<script>
$(document).ready(function () {
    // Initialize DataTable
    var table = $('#myTable').DataTable({
        responsive: true
    });

    // Custom filter function for Patient and Date
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var patientFilter = $('#filterPatient').val().toLowerCase();
        var dateFilter = $('#filterDate').val();

        var patientName = data[1].toLowerCase(); // column index 1 = Patient
        var diagnosisDate = data[2]; // column index 2 = Date

        var patientMatch = patientFilter === "" || patientName.includes(patientFilter);
        var dateMatch = dateFilter === "" || diagnosisDate === dateFilter;

        return patientMatch && dateMatch;
    });

    // Trigger filter on change
    $('#filterPatient, #filterDate').on('change', function() {
        table.draw();
    });

    // Optional: Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterPatient').val('');
        $('#filterDate').val('');
        table.draw();
    });
});
</script>

@section('title', 'Diagnosis')
<x-app-layout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page header --}}
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Diagnosis</h1>
    </header>

    {{-- Card --}}
    <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-800">Records</h2>
        <button
          type="button"
          id="btnOpenModal"
           class="btn btn-primary btn-medium" >  <i class="fas fa-plus me-1"></i>
      New Diagnosis
        </button>
      </div>

      {{-- Table --}}
      <div class="bg-white rounded-md shadow border border-gray-100">
        <div class="table-responsive">
              <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
               <div class="mb-4 flex flex-wrap gap-4 items-end p-3 bg-gray-50 rounded shadow-sm">
  <!-- Patient Filter -->
  <div class="flex flex-col">
    <label for="filterPatient" class="text-xs font-semibold text-gray-600 mb-1">Patient Filter</label>
    <select id="filterPatient" class="form-control form-control-sm w-48">
      <option value="">All Patients</option>
      @foreach($patients as $patient)
        <option value="{{ $patient->first_name }} {{ $patient->last_name }}">
          {{ $patient->first_name }} {{ $patient->last_name }}
        </option>
      @endforeach
    </select>
  </div>

  <!-- Date Filter -->
  <div class="flex flex-col">
    <label for="filterDate" class="text-xs font-semibold text-gray-600 mb-1">Date Filter</label>
    <select id="filterDate" class="form-control form-control-sm w-48">
      <option value="">All Dates</option>
      @foreach($diagnoses as $diagnosis)
        @if($diagnosis->diagnosis_date)
          <option value="{{ \Carbon\Carbon::parse($diagnosis->diagnosis_date)->format('M d, Y') }}">
            {{ \Carbon\Carbon::parse($diagnosis->diagnosis_date)->format('M d, Y') }}
          </option>
        @endif
      @endforeach
    </select>
  </div>

  


            <thead class="bg-white">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient No.</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>

            <tbody class="bg-white">
              @forelse($diagnoses as $diagnosis)
                <tr class="border-b last:border-b-0">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $diagnosis->patient->id ?? '—' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $diagnosis->patient->first_name ?? '—' }} {{ $diagnosis->patient->last_name ?? '' }}
                  </td>
                   <td class="px-6 py-4 text-sm text-gray-700">{{ $diagnosis->diagnosis_date ? \Carbon\Carbon::parse($diagnosis->diagnosis_date)->format('M d, Y') : '—' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex gap-2 items-center">
 <!-- VIEW -->
    <button
      type="button"
      class="btn btn-primary btn-medium btn-view"
      title="View"
      data-patient="{{ $diagnosis->patient->first_name }} {{ $diagnosis->patient->last_name }}"
      data-diagnosis-date="{{ $diagnosis->diagnosis_date }}"
      data-dental_caries="{{ $diagnosis->dental_caries }}"
      data-periodontal_disease="{{ $diagnosis->periodontal_disease }}"
      data-pulpal_periapical="{{ $diagnosis->pulpal_periapical }}"
      data-occlusal_diagnosis="{{ $diagnosis->occlusal_diagnosis }}"
      data-other_oral_conditions="{{ $diagnosis->other_oral_conditions }}"
    >
      <i class="fas fa-eye"></i>
    </button>
                     
                 <!-- EDIT -->
                      <button
                        type="button"
                        data-id="{{ $diagnosis->id }}"
                        data-patient-id="{{ $diagnosis->patient_id }}"
                        data-diagnosis-date="{{ $diagnosis->diagnosis_date }}"
                        data-dental_caries="{{ $diagnosis->dental_caries }}"
                        data-periodontal_disease="{{ $diagnosis->periodontal_disease }}"
                        data-pulpal_periapical="{{ $diagnosis->pulpal_periapical }}"
                        data-occlusal_diagnosis="{{ $diagnosis->occlusal_diagnosis }}"
                        data-other_oral_conditions="{{ $diagnosis->other_oral_conditions }}"
                        class="btn-edit btn btn-warning btn-medium"title="Edit"><i class="fas fa-edit text-white"></i>

                     <!-- DELETE -->
              <button type="button" class="btn btn-danger btn-medium btn-delete" data-id="{{ $diagnosis->id }}"  title="Delete"><i class="fas fa-trash"></i>
            </button>
          </div>
            </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                    No diagnoses found.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!--ADD/EDIT MODAL-->
  <div id="modalBackdrop" class="fixed inset-0  hidden items-center justify-center z-40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4">
     <div class="p-5 flex items-center justify-between bg-blue-600 text-white rounded-t-lg">
  <h3 id="modalTitle" class="text-lg font-medium">Add Diagnosis</h3>
  <button type="button" id="modalClose" 
          class="text-white hover:text-gray-200 bg-transparent border-0 p-0 text-xl" 
          aria-label="Close">✕</button>
</div>



      <form id="modalForm" class="p-5" method="POST" action="{{ route('diagnoses.store') }}">
        @csrf
        <input type="hidden" name="_method" id="form_method" value="">
        <input type="hidden" name="edit_id" id="edit_id" value="">

       <div class="grid grid-cols-1  sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Patient</label>
            <select id="patient_id" name="patient_id" required class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
              <option value="">select patient </option>
              @foreach($patients as $patient)
                <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
              @endforeach
            </select>
          </div>

          <div>
    <label class="block text-sm font-medium text-gray-700"> Date</label>
    <input type="date" id="diagnosis_date" name="diagnosis_date"
           class="mt-1 block w-full rounded-md border-gray-200 shadow-sm"
           value="{{ now()->toDateString() }}"> 
</div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Dental Caries</label>
            <input type="text" id="dental_caries" name="dental_caries" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" placeholder="#14 Occlusal Caries">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Periodontal Disease</label>
            <input type="text" id="periodontal_disease" name="periodontal_disease" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" placeholder="Generalized Chronic Periodontitis">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Pulpal/Periapical Diagnosis</label>
            <input type="text" id="pulpal_periapical" name="pulpal_periapical" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" placeholder="#3 Reversible Pulpitis">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Occlusal Diagnosis</label>
            <input type="text" id="occlusal_diagnosis" name="occlusal_diagnosis" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" placeholder="Bruxism, Class I Malocclusion">
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Other Oral Conditions</label>
            <input type="text" id="other_oral_conditions" name="other_oral_conditions" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" placeholder="Oral Lesion, TMJ Dysfunction">
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button type="button" id="btnCancel" class="btn btn-dark btn-sm">Cancel</button>
          <button type="submit" id="btnSave" class="btn btn-primary btn-sm">Submit</button>
        </div>
      </form>
    </div>
  </div>

<!-- VIEW MODAL -->
<div class="modal" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewModalLabel">View Diagnosis</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="fw-semibold text-secondary">Patient</div>
            <div id="view_patient">—</div>
          </div>
          <div class="col-md-6">
            <div class="fw-semibold text-secondary">Date</div>
            <div id="view_date">—</div>
          </div>
          <div class="col-md-6">
            <div class="fw-semibold text-secondary">Dental Caries</div>
            <div id="view_dental_caries">—</div>
          </div>
          <div class="col-md-6">
            <div class="fw-semibold text-secondary">Periodontal Disease</div>
            <div id="view_periodontal_disease">—</div>
          </div>
          <div class="col-md-6">
            <div class="fw-semibold text-secondary">Pulpal/Periapical Diagnosis</div>
            <div id="view_pulpal_periapical">—</div>
          </div>
          <div class="col-md-6">
            <div class="fw-semibold text-secondary">Occlusal Diagnosis</div>
            <div id="view_occlusal_diagnosis">—</div>
          </div>
          <div class="col-12">
            <div class="fw-semibold text-secondary">Other Oral Conditions</div>
            <div id="view_other_oral_conditions">—</div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-black btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
      
<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Delete Diagnosis</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this diagnosis?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>


   

  {{-- JS --}}
  <script>
    
$(document).ready(function() {
    $('#myTable').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const deleteForm = $('#deleteForm');
        deleteForm.attr('action', '/diagnoses/' + id);

        // Show Bootstrap modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });
});


    document.addEventListener('click', function(e) {
  const viewBtn = e.target.closest('.btn-view');
  if (viewBtn) {
    // Fill modal content
    document.getElementById('view_patient').innerText = viewBtn.dataset.patient || '—';
    document.getElementById('view_date').innerText = viewBtn.dataset.diagnosisDate || '—';
    document.getElementById('view_dental_caries').innerText = viewBtn.dataset.dental_caries || '—';
    document.getElementById('view_periodontal_disease').innerText = viewBtn.dataset.periodontal_disease || '—';
    document.getElementById('view_pulpal_periapical').innerText = viewBtn.dataset.pulpal_periapical || '—';
    document.getElementById('view_occlusal_diagnosis').innerText = viewBtn.dataset.occlusal_diagnosis || '—';
    document.getElementById('view_other_oral_conditions').innerText = viewBtn.dataset.other_oral_conditions || '—';

    // Open Bootstrap modal
    var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
    viewModal.show();
  }
});


  (function(){
    const backdrop = document.getElementById('modalBackdrop');
    const openBtn = document.getElementById('btnOpenModal');
    const closeBtn = document.getElementById('modalClose');
    const cancelBtn = document.getElementById('btnCancel');
    const modalTitle = document.getElementById('modalTitle');
    const modalForm = document.getElementById('modalForm');
    const methodInput = document.getElementById('form_method');
    const editId = document.getElementById('edit_id');

    function openModal(){ backdrop.classList.remove('hidden'); backdrop.classList.add('flex'); }
    function closeModal(){ backdrop.classList.remove('flex'); backdrop.classList.add('hidden'); resetModal(); }

    function resetModal(){
      modalTitle.innerText = "Add Diagnosis";
      modalForm.action = "{{ route('diagnoses.store') }}";
      methodInput.value = '';
      editId.value = '';
      modalForm.reset();
    }

    openBtn && openBtn.addEventListener('click', function(){ resetModal(); openModal(); });
    closeBtn && closeBtn.addEventListener('click', closeModal);
    cancelBtn && cancelBtn.addEventListener('click', closeModal);

    document.addEventListener('click', function(e){
      const editBtn = e.target.closest('.btn-edit');
      if(editBtn){
        const id = editBtn.dataset.id;
        modalTitle.innerText = "Edit Diagnosis";
        modalForm.action = "/diagnoses/" + id;
        methodInput.value = 'PUT';
        editId.value = id;

        document.getElementById('patient_id').value = editBtn.dataset.patientId || '';
        document.getElementById('diagnosis_date').value = editBtn.dataset.diagnosisDate || '';
        document.getElementById('dental_caries').value = editBtn.dataset.dental_caries || '';
        document.getElementById('periodontal_disease').value = editBtn.dataset.periodontal_disease || '';
        document.getElementById('pulpal_periapical').value = editBtn.dataset.pulpal_periapical || '';
        document.getElementById('occlusal_diagnosis').value = editBtn.dataset.occlusal_diagnosis || '';
        document.getElementById('other_oral_conditions').value = editBtn.dataset.other_oral_conditions || '';

        openModal();
        return;
      }
    });
    
  })();
  </script>
</x-app-layout>
