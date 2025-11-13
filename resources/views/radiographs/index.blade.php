
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

                      <!-- View button (opens view modal) -->
                      <button
                        data-id="{{ $rg->id }}"
                        data-patient="{{ e($rg->patient_name) }}"
                        data-date="{{ \Carbon\Carbon::parse($rg->date_taken)->format('Y-m-d') }}"
                        data-type="{{ e($rg->type) }}"
                        data-findings="{{ e($rg->findings) }}"
                        data-imagepath="{{ $rg->image_path ? asset('storage/'.$rg->image_path) : '' }}"
                        class="btn-view inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-xs text-white rounded-md"
                        title="View"
                      >View</button>

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

  {{-- Add / Edit Modal  --}}
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
<!-- VIEW Modal (labeled Date / Type rows, findings, image, download) -->
<div id="viewBackdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
    <!-- Header -->
    <div class="p-5 border-b flex items-start justify-between gap-4">
      <div>
        <h3 id="viewPatientName" class="text-lg font-medium text-gray-800">Patient Name</h3>
        <!-- kept small subtitle if you want; left blank intentionally -->
        <div id="viewSubtitle" class="text-sm text-gray-500"></div>
      </div>
      <button id="viewClose" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
    </div>

    <!-- Body -->
    <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Large image area -->
      <div class="md:col-span-2">
        <div class="bg-gray-50 rounded-md p-4 flex items-center justify-center border">
          <img id="viewImageLarge" src="" alt="Radiograph" class="max-h-96 object-contain">
        </div>
      </div>

      <!-- Right column: labeled fields -->
      <div class="md:col-span-1 space-y-4">
        <!-- Date -->
        <div>
          <h4 class="text-sm font-medium text-gray-700">Date</h4>
          <p id="viewDate" class="mt-1 text-sm text-gray-700">—</p>
        </div>

        <!-- Type of Radiograph -->
        <div>
          <h4 class="text-sm font-medium text-gray-700">Type of Radiograph</h4>
          <p id="viewType" class="mt-1 text-sm text-gray-700">—</p>
        </div>

        <!-- Findings -->
        <div>
          <h4 class="text-sm font-medium text-gray-700">Findings</h4>
          <p id="viewFindings" class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">—</p>
        </div>

        <!-- Open / Download -->
        <div class="mt-4">
          <a id="downloadLink" href="#" target="_blank" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-500 text-white text-xs rounded-md">Open / Download</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS: populate labeled fields when .btn-view clicked -->
<script>
  (function () {
    const viewBackdrop = document.getElementById('viewBackdrop');
    const viewClose = document.getElementById('viewClose');
    const viewPatientName = document.getElementById('viewPatientName');
    const viewSubtitle = document.getElementById('viewSubtitle'); // optional
    const viewDate = document.getElementById('viewDate');
    const viewType = document.getElementById('viewType');
    const viewImageLarge = document.getElementById('viewImageLarge');
    const viewFindings = document.getElementById('viewFindings');
    const downloadLink = document.getElementById('downloadLink');

    function openViewModal() {
      viewBackdrop.classList.remove('hidden');
      viewBackdrop.classList.add('flex');
    }
    function closeViewModal() {
      viewBackdrop.classList.remove('flex');
      viewBackdrop.classList.add('hidden');
      // clear content
      viewImageLarge.src = '';
      viewPatientName.innerText = '';
      viewSubtitle.innerText = '';
      viewDate.innerText = '';
      viewType.innerText = '';
      viewFindings.innerText = '';
      downloadLink.href = '#';
    }
    viewClose && viewClose.addEventListener('click', closeViewModal);

    // Delegate: listen for view button clicks
    document.addEventListener('click', function (e) {
      const viewBtn = e.target.closest('.btn-view');
      if (!viewBtn) return;

      const patient = viewBtn.dataset.patient || '';
      const date = viewBtn.dataset.date || '';
      const type = viewBtn.dataset.type || '';
      const findings = viewBtn.dataset.findings || '';
      const imagepath = viewBtn.dataset.imagepath || '';

      // Populate header / subtitle if you want
      viewPatientName.innerText = patient || '—';
      viewSubtitle.innerText = ''; // not used; keep empty or set extra info

      // Date: show formatted date (fallback to raw string if parsing fails)
      if (date) {
        const d = new Date(date);
        // if valid date
        if (!isNaN(d.getTime())) {
          // localized human readable format (browser locale)
          viewDate.innerText = d.toLocaleDateString();
        } else {
          viewDate.innerText = date;
        }
      } else {
        viewDate.innerText = '—';
      }

      // Type of radiograph
      viewType.innerText = type || '—';

      // Findings
      viewFindings.innerText = findings || 'No findings recorded.';

      // Image & download
      if (imagepath) {
        viewImageLarge.src = imagepath;
        downloadLink.href = imagepath;
        downloadLink.classList.remove('pointer-events-none', 'opacity-50');
      } else {
        viewImageLarge.src = '';
        downloadLink.href = '#';
        downloadLink.classList.add('pointer-events-none', 'opacity-50');
      }

      openViewModal();
    });

    // Close on ESC
    window.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !viewBackdrop.classList.contains('hidden')) closeViewModal();
    });
  })();
</script>

 
</x-app-layout>
