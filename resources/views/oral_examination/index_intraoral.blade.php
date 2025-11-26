{{-- resources/views/intraoral_examinations/index.blade.php --}}
@section('title', 'Intraoral Examinations')
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Intraoral Examinations</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Records</h3>
        <div x-data>
          <button
            @click="$dispatch('open-intraoral-modal', { mode: 'create' })"
            type="button"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-500"
          >+ New Exam</button>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4">
          <table class="min-w-full text-left">
            <thead class="text-gray-600 dark:text-gray-300">
              <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Soft Tissues</th>
                <th class="px-4 py-2">Gingiva</th>
                <th class="px-4 py-2">Occlusion</th>
                <th class="px-4 py-2">Oral Hygiene</th>
                <th class="px-4 py-2">MIO</th>
                <th class="px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($examinations ?? [] as $exam)
                @php
                  // Build a JS-friendly record object. Include stored file paths (as saved in DB)
                  $record = [
                    'id' => $exam->id,
                    'patient_id' => $exam->patient_id,
                    'patient_first_name' => optional($exam->patient)->first_name ?? '',
                    'patient_last_name' => optional($exam->patient)->last_name ?? '',
                    'soft_tissues_status' => $exam->soft_tissues_status ?? '',
                    'soft_tissues_notes' => $exam->soft_tissues_notes ?? '',
                    'gingiva_color' => $exam->gingiva_color ?? '',
                    'gingiva_texture' => $exam->gingiva_texture ?? '',
                    'bleeding_on_probing' => $exam->bleeding_on_probing ? '1' : '0',
                    'bleeding_areas' => $exam->bleeding_areas ?? '',
                    'recession' => $exam->recession ? '1' : '0',
                    'recession_areas' => $exam->recession_areas ?? '',
                    // these three must match your DB column names (stored path like "intraoral/probing/xxx.png")
                    'probing_depths' => $exam->probing_depths ?? '',
                    'mobility' => $exam->mobility ?? '',
                    'furcation_file' => $exam->furcation_file ?? '',
                    'furcation_involvement' => $exam->furcation_involvement ?? '',
                    'hard_tissues_notes' => $exam->hard_tissues_notes ?? '',
                    'odontogram' => $exam->odontogram ?? '',
                    'occlusion_class' => $exam->occlusion_class ?? '',
                    'occlusion_details' => $exam->occlusion_details ?? '',
                    'premature_contacts' => $exam->premature_contacts ?? '',
                    'oral_hygiene_status' => $exam->oral_hygiene_status ?? '',
                    'plaque_index' => $exam->plaque_index ?? '',
                    'calculus' => $exam->calculus ?? '',
                    'mio' => $exam->mio ?? '',
                    'notes' => $exam->notes ?? '',
                  ];
                @endphp

                <tr class="border-t">
                  <td class="px-4 py-2">{{ $loop->iteration }}</td>
                  <td class="px-4 py-2">{{ $record['patient_first_name'] ?: '—' }} {{ $record['patient_last_name'] ?: '' }}</td>
                  <td class="px-4 py-2">{{ $record['soft_tissues_status'] ?: '—' }}</td>
                  <td class="px-4 py-2">{{ $record['gingiva_color'] ?: '—' }} / {{ $record['gingiva_texture'] ?: '—' }}</td>
                  <td class="px-4 py-2">{{ $record['occlusion_class'] ?: '—' }}</td>
                  <td class="px-4 py-2">{{ $record['oral_hygiene_status'] ?: '—' }}</td>
                  <td class="px-4 py-2">{{ $record['mio'] ?: '—' }}</td>

                  <td class="px-4 py-2">
                    <div class="flex items-center gap-2">
                      {{-- View --}}
                      <button
                        type="button"
                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-500"
                        data-record='@json($record)'
                        onclick="window.dispatchEvent(new CustomEvent('open-intraoral-view',{detail:{record: JSON.parse(this.dataset.record)}}))"
                      >View</button>

                      {{-- Edit --}}
                      <button
                        type="button"
                        class="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-400"
                        data-record='@json($record)'
                        onclick="window.dispatchEvent(new CustomEvent('open-intraoral-modal',{detail:{mode:'edit', record: JSON.parse(this.dataset.record)}}))"
                      >Edit</button>

                      {{-- Delete --}}
                      <form action="{{ route('intraoral_examinations.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-500">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">No records found.</td></tr>
              @endforelse
            </tbody>
          </table>

          <div class="mt-4">{{ $examinations->links() }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Alpine factory: used by the Create/Edit modal --}}
  <script>
    function intraoralModal() {
      return {
        open: false,
        mode: 'create', // 'create' | 'edit'
        step: 1,
        tabs: [
          { key: 'soft', label: 'Soft Tissues' },
          { key: 'gingiva', label: 'Gingiva' },
          { key: 'periodontium', label: 'Periodontium' },
          { key: 'occlusion', label: 'Occlusion' },
          { key: 'hygiene', label: 'Oral Hygiene' },
          { key: 'mio', label: 'MIO / Notes' },
        ],
        form: {
          id: null,
          patient_id: '{{ old("patient_id", isset($patient) && $patient ? $patient->id : "") }}',
          soft_tissues_status: '',
          soft_tissues_notes: '',
          gingiva_color: '',
          gingiva_texture: '',
          bleeding_on_probing: '0',
          bleeding_areas: '',
          recession: '0',
          recession_areas: '',
          probing_depths: '', // stores path string for existing record
          mobility: '',
          furcation_file: '',
          furcation_involvement: '',
          hard_tissues_notes: '',
          odontogram: '',
          occlusion_class: '',
          occlusion_details: '',
          premature_contacts: '',
          oral_hygiene_status: '',
          plaque_index: '',
          calculus: '',
          mio: '',
          notes: '',
        },
        errors: {},

        // route strings (adjust if your routes are named differently)
        get formAction() {
          if (this.mode === 'create') {
            return '{{ route("intraoral_examinations.store") }}';
          }
          return '{{ url("intraoral_examinations") }}/' + (this.form.id || '');
        },

        setForm(detail = {}) {
          this.errors = {};
          const d = detail || {};
          this.mode = d.mode || 'create';
          this.step = 1;

          if (this.mode === 'create') {
            this.form.id = null;
            @if(isset($patient) && $patient)
              this.form.patient_id = '{{ $patient->id }}';
            @else
              this.form.patient_id = '';
            @endif
            // clear fields except patient_id
            for (const k in this.form) {
              if (k !== 'patient_id') this.form[k] = '';
            }
            this.form.bleeding_on_probing = '0';
            this.form.recession = '0';
          } else {
            const r = d.record || {};
            this.form.id = r.id ?? null;
            this.form.patient_id = r.patient_id ?? this.form.patient_id ?? '';
            this.form.soft_tissues_status = r.soft_tissues_status ?? '';
            this.form.soft_tissues_notes = r.soft_tissues_notes ?? '';
            this.form.gingiva_color = r.gingiva_color ?? '';
            this.form.gingiva_texture = r.gingiva_texture ?? '';
            this.form.bleeding_on_probing = (typeof r.bleeding_on_probing !== 'undefined') ? String(r.bleeding_on_probing) : '0';
            this.form.bleeding_areas = r.bleeding_areas ?? '';
            this.form.recession = (typeof r.recession !== 'undefined') ? String(r.recession) : '0';
            this.form.recession_areas = r.recession_areas ?? '';
            // existing stored paths (we keep them so backend can retain if not replaced)
            this.form.probing_depths = r.probing_depths ?? '';
            this.form.mobility = r.mobility ?? '';
            this.form.furcation_file = r.furcation_file ?? '';
            this.form.furcation_involvement = r.furcation_involvement ?? '';
            this.form.hard_tissues_notes = r.hard_tissues_notes ?? '';
            this.form.odontogram = r.odontogram ?? '';
            this.form.occlusion_class = r.occlusion_class ?? '';
            this.form.occlusion_details = r.occlusion_details ?? '';
            this.form.premature_contacts = r.premature_contacts ?? '';
            this.form.oral_hygiene_status = r.oral_hygiene_status ?? '';
            this.form.plaque_index = r.plaque_index ?? '';
            this.form.calculus = r.calculus ?? '';
            this.form.mio = r.mio ?? '';
            this.form.notes = r.notes ?? '';
          }

          this.open = true;
          this.$nextTick(() => {
            const c = this.$refs.modalContent;
            if (c) c.scrollTop = 0;
          });
        },

        close() {
          this.open = false;
          this.errors = {};
        },

        setStep(n) {
          if (n < 1) n = 1;
          if (n > this.tabs.length) n = this.tabs.length;
          this.step = n;
        },

        next(){ this.setStep(this.step+1); },
        prev(){ this.setStep(this.step-1); }
      };
    }
  </script>

  {{-- CREATE / EDIT MODAL --}}
  <div
    x-data="intraoralModal()"
    x-on:open-intraoral-modal.window="setForm($event.detail)"
    x-show="open"
    x-cloak
    @keydown.escape.window="close()"
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="display:none;"
    aria-modal="true"
    role="dialog"
  >
    <div class="fixed inset-0 bg-black bg-opacity-40" @click="close"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl z-10 mx-4 overflow-auto max-h-[90vh] relative" x-ref="modalContent">
      <div class="px-6 py-4 flex items-center justify-between border-b dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="mode === 'create' ? 'Add Intraoral Examination' : 'Edit Intraoral Examination'"></h3>
        <button @click="close" class="text-gray-600 hover:text-gray-800 dark:text-gray-300">&times;</button>
      </div>

      {{-- NOTE: enctype required for file uploads --}}
      <form :action="formAction" method="POST" enctype="multipart/form-data" class="px-6 py-4 space-y-6" @submit.prevent="$el.submit()">
        @csrf
        <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PATCH"></template>

        {{-- patient selector (or hidden if page already for a patient) --}}
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

        {{-- Soft tissues (panel 1) --}}
        <div x-show="step === 1">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Soft Tissues</label>
          <div class="mt-2 flex gap-3">
            <label class="inline-flex items-center"><input type="radio" name="soft_tissues_status" value="Normal" x-model="form.soft_tissues_status"><span class="ml-2">Normal</span></label>
            <label class="inline-flex items-center"><input type="radio" name="soft_tissues_status" value="Abnormal" x-model="form.soft_tissues_status"><span class="ml-2">Abnormal</span></label>
          </div>
          <textarea name="soft_tissues_notes" x-model="form.soft_tissues_notes" rows="2" class="mt-2 block w-full rounded-md px-3 py-2"></textarea>
        </div>

        {{-- Gingiva (panel 2) --}}
        <div x-show="step === 2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gingiva</label>
          <div class="mt-2 grid grid-cols-3 gap-4">
            <select name="gingiva_color" x-model="form.gingiva_color" class="rounded-md px-3 py-2">
              <option value="">--</option><option>Pink</option><option>Red</option><option>Cyanotic</option>
            </select>
            <select name="gingiva_texture" x-model="form.gingiva_texture" class="rounded-md px-3 py-2">
              <option value="">--</option><option>Stippled</option><option>Edematous</option>
            </select>
            <div>
              <div class="text-xs">Bleeding on Probing</div>
              <div class="mt-2 flex gap-3">
                <label class="inline-flex items-center"><input type="radio" name="bleeding_on_probing" value="1" x-model="form.bleeding_on_probing"><span class="ml-2">Yes</span></label>
                <label class="inline-flex items-center"><input type="radio" name="bleeding_on_probing" value="0" x-model="form.bleeding_on_probing"><span class="ml-2">No</span></label>
              </div>
              <input name="bleeding_areas" x-model="form.bleeding_areas" class="mt-2 block w-full rounded-md px-3 py-2" placeholder="Specify areas">
            </div>
          </div>
        </div>

        {{-- Periodontium / Hard Tissues (panel 3) --}}
        <div x-show="step === 3">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periodontium / Hard Tissues</label>

          <div class="mt-2">
            <label class="block text-sm">Probing Depths (image/pdf)</label>
            {{-- when editing: existing path kept in form.probing_depths; upload input must be named probing_depths --}}
            <div class="flex gap-2 items-center">
              <input type="file" name="probing_depths" accept="image/*,.pdf" class="block rounded-md px-3 py-2 w-full">
              <template x-if="form.probing_depths">
                <a :href="'/storage/' + form.probing_depths" target="_blank" class="text-sm underline">Existing</a>
              </template>
            </div>
          </div>

          <div class="mt-2">
            <label class="block text-sm">Mobility (image/pdf)</label>
            <div class="flex gap-2 items-center">
              <input type="file" name="mobility" accept="image/*,.pdf" class="block rounded-md px-3 py-2 w-full">
              <template x-if="form.mobility">
                <a :href="'/storage/' + form.mobility" target="_blank" class="text-sm underline">Existing</a>
              </template>
            </div>
          </div>

          <div class="mt-2">
            <label class="block text-sm">Furcation (image/pdf)</label>
            <div class="flex gap-2 items-center">
              <input type="file" name="furcation_file" accept="image/*,.pdf" class="block rounded-md px-3 py-2 w-full">
              <template x-if="form.furcation_file">
                <a :href="'/storage/' + form.furcation_file" target="_blank" class="text-sm underline">Existing</a>
              </template>
            </div>
            <input name="furcation_involvement" x-model="form.furcation_involvement" placeholder="Optional text" class="mt-2 block w-full rounded-md px-3 py-2">
          </div>

          <textarea name="hard_tissues_notes" x-model="form.hard_tissues_notes" rows="2" class="mt-2 block w-full rounded-md px-3 py-2" placeholder="Notes"></textarea>
          <input name="odontogram" x-model="form.odontogram" class="mt-2 block w-full rounded-md px-3 py-2" placeholder="Odontogram">
        </div>

        {{-- Occlusion (panel 4) --}}
        <div x-show="step === 4">
          <label class="block text-sm font-medium">Occlusion</label>
          <div class="mt-2 grid grid-cols-3 gap-4">
            <select name="occlusion_class" x-model="form.occlusion_class" class="rounded-md px-3 py-2">
              <option value="">-- Class --</option><option>Class I</option><option>Class II</option><option>Class III</option>
            </select>
            <input name="occlusion_details" x-model="form.occlusion_details" placeholder="Details" class="rounded-md px-3 py-2">
            <input name="premature_contacts" x-model="form.premature_contacts" placeholder="Premature contacts" class="rounded-md px-3 py-2">
          </div>
        </div>

        {{-- Oral hygiene (panel 5) --}}
        <div x-show="step === 5">
          <label class="block text-sm font-medium">Oral Hygiene</label>
          <div class="mt-2 flex items-center gap-4">
            <label class="inline-flex items-center"><input type="radio" name="oral_hygiene_status" value="Good" x-model="form.oral_hygiene_status"><span class="ml-2">Good</span></label>
            <label class="inline-flex items-center"><input type="radio" name="oral_hygiene_status" value="Fair" x-model="form.oral_hygiene_status"><span class="ml-2">Fair</span></label>
            <label class="inline-flex items-center"><input type="radio" name="oral_hygiene_status" value="Poor" x-model="form.oral_hygiene_status"><span class="ml-2">Poor</span></label>
          </div>
          <div class="mt-2 grid grid-cols-2 gap-4">
            <input name="plaque_index" x-model="form.plaque_index" placeholder="Plaque Index" class="rounded-md px-3 py-2">
            <select name="calculus" x-model="form.calculus" class="rounded-md px-3 py-2">
              <option value="">Calculus</option><option>Light</option><option>Moderate</option><option>Heavy</option>
            </select>
          </div>
        </div>

        {{-- MIO / notes (panel 6) --}}
        <div x-show="step === 6">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm">MIO (mm)</label>
              <input type="number" name="mio" x-model="form.mio" min="0" max="100" class="rounded-md px-3 py-2 w-40">
            </div>
            <div>
              <label class="block text-sm">Notes</label>
              <input name="notes" x-model="form.notes" class="rounded-md px-3 py-2">
            </div>
          </div>
        </div>

        {{-- navigation + submit --}}
        <div class="flex items-center gap-3">
          <button type="button" @click="close" class="px-4 py-2 rounded-md border">Cancel</button>
          <div class="ml-auto flex gap-2">
            <button type="button" x-show="step>1" @click="prev()" class="px-3 py-2 rounded-md border">Back</button>
            <button type="button" x-show="step < tabs.length" @click="next()" class="px-3 py-2 rounded-md bg-gray-200">Next</button>
            <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white" x-text="mode === 'create' ? 'Save' : 'Update'"></button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- VIEW MODAL (read-only) --}}
  <div
    x-data="{ open: false, record: {} }"
    x-on:open-intraoral-view.window="record = $event.detail.record; 
      // normalize: if stored path present, prefix with /storage/
      if(record.probing_depths){ record.probing_depths_url = '/storage/' + record.probing_depths } 
      if(record.mobility){ record.mobility_url = '/storage/' + record.mobility }
      if(record.furcation_file){ record.furcation_url = '/storage/' + record.furcation_file }
      open = true;
    "
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="display:none;"
    aria-modal="true"
    role="dialog"
  >
    <div class="fixed inset-0 bg-black bg-opacity-40" @click="open = false"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl z-10 mx-4 overflow-auto max-h-[90vh] relative">
      <div class="px-6 py-4 flex items-center justify-between border-b dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Intraoral Examination</h3>
        <button @click="open = false" class="text-gray-600 hover:text-gray-800 dark:text-gray-300">&times;</button>
      </div>

      <div class="px-6 py-4 space-y-4">
        <div>
          <strong>Patient:</strong> <span x-text="(record.patient_first_name || '') + ' ' + (record.patient_last_name || '') || '—'"></span>
        </div>

        <hr class="my-2">

        <div>
          <strong>Soft Tissues:</strong>
          <div x-text="record.soft_tissues_status || '—'"></div>
          <div x-text="record.soft_tissues_notes || ''"></div>
        </div>

        <div>
          <strong>Gingiva:</strong>
          <div x-text="(record.gingiva_color || '—') + ' / ' + (record.gingiva_texture || '—')"></div>
          <div>Bleeding on probing: <span x-text="record.bleeding_on_probing == '1' ? 'Yes' : 'No'"></span></div>
          <div x-text="record.bleeding_areas || ''"></div>
          <div>Recession: <span x-text="record.recession == '1' ? 'Yes' : 'No'"></span></div>
          <div x-text="record.recession_areas || ''"></div>
        </div>

        <hr class="my-2">

        <div>
          <strong>Periodontium / Hard Tissues</strong>

          {{-- probing depths file --}}
          <template x-if="record.probing_depths_url">
            <div class="mt-2">
              <strong>Probing Depths:</strong>
              <template x-if="record.probing_depths_url.toLowerCase().endsWith('.pdf')">
                <div><a :href="record.probing_depths_url" target="_blank" class="text-indigo-600 hover:underline">Open PDF</a></div>
              </template>
              <template x-if="!record.probing_depths_url.toLowerCase().endsWith('.pdf')">
                <img :src="record.probing_depths_url" class="mt-1 max-h-48 border rounded" alt="Probing Depths">
              </template>
            </div>
          </template>

          {{-- mobility file --}}
          <template x-if="record.mobility_url">
            <div class="mt-2">
              <strong>Mobility:</strong>
              <template x-if="record.mobility_url.toLowerCase().endsWith('.pdf')">
                <div><a :href="record.mobility_url" target="_blank" class="text-indigo-600 hover:underline">Open PDF</a></div>
              </template>
              <template x-if="!record.mobility_url.toLowerCase().endsWith('.pdf')">
                <img :src="record.mobility_url" class="mt-1 max-h-48 border rounded" alt="Mobility">
              </template>
            </div>
          </template>

          {{-- furcation --}}
          <template x-if="record.furcation_url">
            <div class="mt-2">
              <strong>Furcation:</strong>
              <template x-if="record.furcation_url.toLowerCase().endsWith('.pdf')">
                <div><a :href="record.furcation_url" target="_blank" class="text-indigo-600 hover:underline">Open PDF</a></div>
              </template>
              <template x-if="!record.furcation_url.toLowerCase().endsWith('.pdf')">
                <img :src="record.furcation_url" class="mt-1 max-h-48 border rounded" alt="Furcation">
              </template>
            </div>
          </template>

          <div class="mt-2">
            <div><strong>Furcation / Hard Tissues Notes:</strong></div>
            <div x-text="record.hard_tissues_notes || '—'"></div>
            <div><strong>Odontogram:</strong></div>
            <div x-text="record.odontogram || '—'"></div>
          </div>
        </div>

        <hr class="my-2">

        <div>
          <strong>Occlusion:</strong>
          <div x-text="record.occlusion_class || '—'"></div>
          <div x-text="record.occlusion_details || ''"></div>
          <div x-text="record.premature_contacts || ''"></div>
        </div>

        <div>
          <strong>Oral Hygiene:</strong>
          <div x-text="record.oral_hygiene_status || '—'"></div>
          <div x-text="record.plaque_index || ''"></div>
          <div x-text="record.calculus || ''"></div>
        </div>

        <div>
          <strong>MIO:</strong> <span x-text="record.mio || '—'"></span>
          <div><strong>Notes:</strong> <span x-text="record.notes || ''"></span></div>
        </div>
      </div>

      <div class="px-6 py-4 border-t flex justify-end">
        <button @click="open = false" class="px-4 py-2 rounded-md border">Close</button>
      </div>
    </div>
  </div>
</x-app-layout>
