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
    $('#myTable').DataTable({
        responsive: true
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
        <x-button
          type="button"
          id="btnOpenModal"
           class="btn btn-primary btn-xs" >  <i class="fas fa-plus me-1"></i>
      New Diagnosis
        </x-button>
      </div>

      {{-- Table --}}
      <div class="bg-white rounded-md shadow border border-gray-100">
        <div class="table-responsive">
              <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
            <thead class="bg-white">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dental Caries</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periodontal Disease</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pulpal/Periapical</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Occlusal Diagnosis</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Other Oral Conditions</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>

            <tbody class="bg-white">
              @forelse($diagnoses as $diagnosis)
                <tr class="border-b last:border-b-0">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $diagnosis->patient->first_name ?? '—' }} {{ $diagnosis->patient->last_name ?? '' }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-700">{{ $diagnosis->dental_caries ?? '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-700">{{ $diagnosis->periodontal_disease ?? '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-700">{{ $diagnosis->pulpal_periapical ?? '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-700">{{ $diagnosis->occlusal_diagnosis ?? '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-700">{{ $diagnosis->other_oral_conditions ?? '—' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex gap-2 items-center">
 <!-- VIEW -->
    <button
      type="button"
      class="btn btn-primary btn-xs btn-view"
      title="View"
      data-patient="{{ $diagnosis->patient->first_name }} {{ $diagnosis->patient->last_name }}"
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
                        data-dental_caries="{{ $diagnosis->dental_caries }}"
                        data-periodontal_disease="{{ $diagnosis->periodontal_disease }}"
                        data-pulpal_periapical="{{ $diagnosis->pulpal_periapical }}"
                        data-occlusal_diagnosis="{{ $diagnosis->occlusal_diagnosis }}"
                        data-other_oral_conditions="{{ $diagnosis->other_oral_conditions }}"
                        class="btn-edit btn btn-warning btn-xs"title="Edit"><i class="fas fa-edit text-white"></i>

                     <!-- DELETE -->
                      <form action="{{ route('diagnoses.destroy', $diagnosis->id) }}" method="POST" onsubmit="return confirm('Delete this diagnosis?');" class="inline">

                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-500 text-xs text-white rounded-md"> <i class="fas fa-trash me-1"></i> </button>
                      </form>
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

  {{-- Add/Edit Modal --}}
  <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4">
      <div class="p-5 border-b flex items-center justify-between">
        <h3 id="modalTitle" class="text-lg font-medium text-gray-800">Add Diagnosis</h3>
        <button type="button" id="modalClose" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
      </div>

      <form id="modalForm" class="p-5" method="POST" action="{{ route('diagnoses.store') }}">
        @csrf
        <input type="hidden" name="_method" id="form_method" value="">
        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

  {{-- JS --}}
  <script>
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
