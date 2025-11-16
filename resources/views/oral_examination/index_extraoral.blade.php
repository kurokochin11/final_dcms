@section('title', 'Extraoral Examinations')
<x-app-layout>
  
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
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            + New Exam
          </button>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4">
          <table class="min-w-full text-left">
            <thead class="text-gray-600 dark:text-gray-300">
              <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Facial Symmetry</th>
                <th class="px-4 py-2">Lymph Nodes</th>
                <th class="px-4 py-2">Pain</th>
                <th class="px-4 py-2">Clicking/Popping</th>
                <th class="px-4 py-2">Limited Opening</th>
                <th class="px-4 py-2">MIO (mm)</th>
                <th class="px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($examinations ?? [] as $exam)
                @php
                  // Prepare safe JSON for the edit button
                  $record = [
                    'id' => $exam->id,
                    'patient_id' => $exam->patient_id,
                    'facial_symmetry' => $exam->facial_symmetry,
                    'facial_symmetry_notes' => $exam->facial_symmetry_notes,
                    'lymph_nodes' => $exam->lymph_nodes,
                    'lymph_nodes_location' => $exam->lymph_nodes_location,
                    'tmj_pain' => $exam->tmj_pain ? '1' : '0',
                    'tmj_clicking' => $exam->tmj_clicking ? '1' : '0',
                    'tmj_limited_opening' => $exam->tmj_limited_opening ? '1' : '0',
                    'mio' => $exam->mio,
                    'notes' => $exam->notes ?? null,
                  ];
                @endphp

                <tr class="border-t">
                  <td class="px-4 py-2">{{ $loop->iteration }}</td>
                  <td class="px-4 py-2">{{ optional($exam->patient)->first_name ?? '—' }} {{ optional($exam->patient)->last_name ?? '' }}</td>
                  <td class="px-4 py-2">{{ $exam->facial_symmetry ?? '—' }}</td>
                  <td class="px-4 py-2">{{ $exam->lymph_nodes ?? '—' }}</td>
                  <td class="px-4 py-2">{{ $exam->tmj_pain ? 'Yes' : 'No' }}</td>
                  <td class="px-4 py-2">{{ $exam->tmj_clicking ? 'Yes' : 'No' }}</td>
                  <td class="px-4 py-2">{{ $exam->tmj_limited_opening ? 'Yes' : 'No' }}</td>
                  <td class="px-4 py-2">{{ $exam->mio ?? '—' }}</td>

                  <td class="px-4 py-2">
                    <div class="flex items-center gap-2">
                      {{-- Put JSON safely in data-record and dispatch using onclick to avoid Blade/JS quoting issues --}}
                      <button
                        type="button"
                        class="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-400"
                        data-record='{{ json_encode($record, JSON_HEX_APOS | JSON_HEX_QUOT) }}'
                        onclick="window.dispatchEvent(new CustomEvent('open-extraoral-modal',{detail:{mode:'edit', record: JSON.parse(this.dataset.record)}}))"
                      >
                        Edit
                      </button>

                      <form action="{{ route('extraoral_examinations.destroy', $exam) }}" method="POST" onsubmit="return confirm('Delete this record?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-500">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="9" class="px-4 py-6 text-center text-gray-500">No records found.</td></tr>
              @endforelse
            </tbody>
          </table>

          <div class="mt-4">
            {{ $examinations->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Define Alpine factory BEFORE Alpine initializes the component --}}
  <script>
    function extraoralModal() {
      return {
        open: false,
        mode: 'create',
        form: {
          id: null,
          patient_id: '{{ isset($patient) && $patient ? $patient->id : '' }}',
          facial_symmetry: '',
          facial_symmetry_notes: '',
          lymph_nodes: '',
          lymph_nodes_location: '',
          tmj_pain: '0',
          tmj_clicking: '0',
          tmj_limited_opening: '0',
          mio: '',
          notes: '',
        },
        errors: {},
        get formAction() {
          if (this.mode === 'create') {
            return '{{ route("extraoral_examinations.store") }}';
          }
          return '{{ url("extraoral_examinations") }}/' + (this.form.id || '');
        },
        setForm(detail) {
          this.errors = {};
          const d = detail || {};
          this.mode = d.mode || 'create';

          if (this.mode === 'create') {
            this.form.id = null;
            @if(isset($patient) && $patient)
              this.form.patient_id = '{{ $patient->id }}';
            @else
              this.form.patient_id = '';
            @endif
            this.form.facial_symmetry = '';
            this.form.facial_symmetry_notes = '';
            this.form.lymph_nodes = '';
            this.form.lymph_nodes_location = '';
            this.form.tmj_pain = '0';
            this.form.tmj_clicking = '0';
            this.form.tmj_limited_opening = '0';
            this.form.mio = '';
            this.form.notes = '';
          } else {
            const r = d.record || {};
            this.form.id = r.id ?? null;
            this.form.patient_id = r.patient_id ?? (this.form.patient_id || '');
            this.form.facial_symmetry = r.facial_symmetry ?? '';
            this.form.facial_symmetry_notes = r.facial_symmetry_notes ?? '';
            this.form.lymph_nodes = r.lymph_nodes ?? '';
            this.form.lymph_nodes_location = r.lymph_nodes_location ?? '';
            this.form.tmj_pain = (typeof r.tmj_pain !== 'undefined') ? String(r.tmj_pain) : '0';
            this.form.tmj_clicking = (typeof r.tmj_clicking !== 'undefined') ? String(r.tmj_clicking) : '0';
            this.form.tmj_limited_opening = (typeof r.tmj_limited_opening !== 'undefined') ? String(r.tmj_limited_opening) : '0';
            this.form.mio = (typeof r.mio !== 'undefined' && r.mio !== null) ? r.mio : '';
            this.form.notes = r.notes ?? '';
          }

          this.open = true;
        },
        close() {
          this.open = false;
          this.errors = {};
        }
      }
    }
  </script>

  <!-- Modal + Alpine -->
  <div
    x-data="extraoralModal()"
    x-on:open-extraoral-modal.window="setForm($event.detail)"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="display:none;"
    aria-modal="true"
    role="dialog"
  >
    <div class="fixed inset-0 bg-black bg-opacity-40" @click="close"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl z-10 mx-4 overflow-hidden">
      <div class="px-6 py-4 flex items-center justify-between border-b dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="mode === 'create' ? 'Add Extraoral Examination' : 'Edit Extraoral Examination'"></h3>
        <button @click="close" class="text-gray-600 hover:text-gray-800 dark:text-gray-300">&times;</button>
      </div>

      <form :action="formAction" method="POST" class="px-6 py-4 space-y-4">
        @csrf
        <input type="hidden" name="_method" x-bind:value="mode === 'edit' ? 'PATCH' : ''" />

        @if(isset($patient) && $patient)
          <input type="hidden" name="patient_id" x-model="form.patient_id">
          <div class="text-sm text-gray-600 dark:text-gray-300">Patient: <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></div>
        @else
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient</label>
            <select name="patient_id" x-model="form.patient_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2">
              <option value="">-- Select patient --</option>
              @foreach($patients ?? [] as $p)
                <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
              @endforeach
            </select>
          </div>
        @endif

        <!-- Facial Symmetry -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Facial Symmetry</label>
          <div class="mt-2 flex items-center gap-4">
            <label class="inline-flex items-center">
              <input type="radio" name="facial_symmetry" value="Normal" x-model="form.facial_symmetry" class="text-indigo-600">
              <span class="ml-2">Normal</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="facial_symmetry" value="Asymmetrical" x-model="form.facial_symmetry" class="text-indigo-600">
              <span class="ml-2">Asymmetrical</span>
            </label>
          </div>
          <input name="facial_symmetry_notes" x-model="form.facial_symmetry_notes" placeholder="Notes (if asymmetrical)" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2">
        </div>

        <!-- Lymph Nodes -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lymph Nodes</label>
          <div class="mt-2 flex items-center gap-4">
            <label class="inline-flex items-center">
              <input type="radio" name="lymph_nodes" value="Palpable" x-model="form.lymph_nodes" class="text-indigo-600">
              <span class="ml-2">Palpable</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="lymph_nodes" value="Non-palpable" x-model="form.lymph_nodes" class="text-indigo-600">
              <span class="ml-2">Non-palpable</span>
            </label>
          </div>
          <input name="lymph_nodes_location" x-model="form.lymph_nodes_location" placeholder="Specify location if palpable" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2">
        </div>

        <!-- TMJ -->
        <fieldset>
          <legend class="font-medium text-gray-700 dark:text-gray-300">Temporomandibular Joint (TMJ)</legend>

          <div class="mt-2 grid grid-cols-3 gap-4">
            <div>
              <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Pain</label>
              <div class="flex items-center gap-3">
                <label class="inline-flex items-center">
                  <input type="radio" name="tmj_pain" value="1" x-model="form.tmj_pain" class="text-indigo-600"><span class="ml-2">Yes</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="tmj_pain" value="0" x-model="form.tmj_pain" class="text-indigo-600"><span class="ml-2">No</span>
                </label>
              </div>
            </div>

            <div>
              <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Clicking / Popping</label>
              <div class="flex items-center gap-3">
                <label class="inline-flex items-center">
                  <input type="radio" name="tmj_clicking" value="1" x-model="form.tmj_clicking" class="text-indigo-600"><span class="ml-2">Yes</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="tmj_clicking" value="0" x-model="form.tmj_clicking" class="text-indigo-600"><span class="ml-2">No</span>
                </label>
              </div>
            </div>

            <div>
              <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Limited Opening</label>
              <div class="flex items-center gap-3">
                <label class="inline-flex items-center">
                  <input type="radio" name="tmj_limited_opening" value="1" x-model="form.tmj_limited_opening" class="text-indigo-600"><span class="ml-2">Yes</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="tmj_limited_opening" value="0" x-model="form.tmj_limited_opening" class="text-indigo-600"><span class="ml-2">No</span>
                </label>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <label class="block text-sm text-gray-700 dark:text-gray-300">Maximum Interincisal Opening (MIO) - mm</label>
            <input type="number" name="mio" x-model="form.mio" min="0" max="100" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 w-40">
          </div>
        </fieldset>

        <div class="flex items-center justify-end gap-3 pt-3">
          <button type="button" @click="close" class="px-4 py-2 rounded-md border text-gray-700 dark:text-gray-200">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" x-text="mode === 'create' ? 'Save' : 'Update'"></button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
