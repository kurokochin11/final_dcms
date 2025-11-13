{{-- resources/views/radiographs/index.blade.php --}}
<x-app-layout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page header like your screenshot --}}
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Radiograph Records</h1>
    </header>

    {{-- Card --}}
    <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-800">Records</h2>
        <button
          id="btnOpenModal"
          class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300"
        >
          + New Radiograph
        </button>
      </div>

      {{-- Table container --}}
      <div class="bg-white rounded-md shadow border border-gray-100">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
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

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $rg->patient_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($rg->date_taken)->format('M d, Y') }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $rg->type }}</td>
                  <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $rg->findings }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex gap-2">
                      <!-- Edit: use data- attributes and JS to populate modal -->
                      <button
                        data-id="{{ $rg->id }}"
                        data-patient="{{ e($rg->patient_name) }}"
                        data-date="{{ \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') }}"
                        data-type="{{ e($rg->type) }}"
                        data-findings="{{ e($rg->findings) }}"
                        data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                        class="btn-edit inline-flex items-center px-3 py-1.5 bg-yellow-400 hover:bg-yellow-300 text-xs text-gray-800 rounded-md"
                      >Edit</button>

                      <form action="{{ route('radiographs.destroy', $rg->id) }}" method="POST" onsubmit="return confirm('Delete this record?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-500 text-xs text-white rounded-md">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                    <div class="space-y-3">
                      <div class="text-sm">No records found.</div>

                      <!-- {{-- Example preview --}}
<div class="mx-auto mt-2">
    <img src="{{ asset('storage/radiographs/no_image.png') }}" 
         alt="Default Image" 
         class="w-24 h-24 object-cover rounded-md border">
</div> -->

                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Pagination (if used) --}}
      <div class="mt-4">
        {{ $radiographs->links() ?? '' }}
      </div>
    </div>
  </div>

  {{-- Simple Tailwind modal (hidden/shown via JS) --}}
  <div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4">
      <div class="p-5 border-b flex items-center justify-between">
        <h3 id="modalTitle" class="text-lg font-medium text-gray-800">Add Radiograph</h3>
        <button id="modalClose" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
      </div>

      <form id="modalForm" class="p-5" method="POST" action="{{ route('radiographs.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="form_method" value="">
        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Patient Name</label>
            <input id="patient_name" name="patient_name" type="text" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Date Taken</label>
            <input id="date_taken" name="date_taken" type="date" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Type of Radiograph</label>
            <input id="type" name="type" type="text" placeholder="Periapical, Bitewing etc" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
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

  {{-- Minimal JS to toggle modal and populate edit values --}}
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
      const removeImage = document.getElementById('remove_image');

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
        if (removeImage) removeImage.checked = false;
      }

      openBtn && openBtn.addEventListener('click', function (ev) {
        // open add modal
        resetModal();
        openModal();
      });

      closeBtn && closeBtn.addEventListener('click', closeModal);
      cancelBtn && cancelBtn.addEventListener('click', closeModal);

      // delegate edit buttons
      document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit');
        if (!btn) return;
        const id = btn.dataset.id;
        const patient = btn.dataset.patient || '';
        const date = btn.dataset.date || '';
        const type = btn.dataset.type || '';
        const findings = btn.dataset.findings || '';
        const imagepath = btn.dataset.imagepath || '';

        modalTitle.innerText = "Edit Radiograph";
        modalForm.action = "/radiographs/" + id;
        methodInput.value = 'PUT';
        editId.value = id;
        document.getElementById('patient_name').value = patient;
        document.getElementById('date_taken').value = date;
        document.getElementById('type').value = type;
        document.getElementById('findings').value = findings;

        if (imagepath) {
          previewImg.src = imagepath;
          previewBlock.classList.remove('hidden');
        } else {
          previewBlock.classList.add('hidden');
          previewImg.src = '';
        }

        openModal();
      });

      // preview file selected
      const fileInput = document.getElementById('image');
      fileInput && fileInput.addEventListener('change', function (ev) {
        const file = ev.target.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function (e) {
          previewImg.src = e.target.result;
          previewBlock.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      });

      // close modal on ESC
      window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !backdrop.classList.contains('hidden')) closeModal();
      });
    })();
  </script>
</x-app-layout>
