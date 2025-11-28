{{-- resources/views/intraoral_examinations/index.blade.php (tabbed modal version) --}}
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
                    // file paths
                    'probing_depths_file' => $exam->probing_depths_file ?? '',
                    'mobility_file' => $exam->mobility_file ?? '',
                    'furcation_file' => $exam->furcation_file ?? '',
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

  {{-- Alpine factory: used by the Create/Edit modal (tabbed variant) --}}
  <script>
    function intraoralModal() {
      return {
        open: false,
        mode: 'create',
        activeTab: 'soft', // tab state: 'soft','gingiva','periodontium','occlusion','hygiene','mio'
        // store existing file paths separately so we can preview
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
          probing_depths_file: '',
          mobility_file: '',
          furcation_file: '',
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
            this.activeTab = 'soft';
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
            this.form.probing_depths_file = r.probing_depths_file ?? '';
            this.form.mobility_file = r.mobility_file ?? '';
            this.form.furcation_file = r.furcation_file ?? '';
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
            this.activeTab = 'soft';
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

        // switch tab helper
        switchTab(t) {
          this.activeTab = t;
          this.$nextTick(() => {
            const el = this.$refs[t + 'Tab'];
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
          });
        }
      };
    }
  </script>

  {{-- VIEW modal (simple preview) --}}
  <script>
    window.addEventListener('open-intraoral-view', function(e) {
      const r = e.detail.record || {};
      const view = document.getElementById('intraoral-view-modal');
      if (!view) return;

      // populate preview elements
      const setImg = (id, path) => {
        const el = view.querySelector('#' + id);
        if (!el) return;
        if (path) {
          el.src = '/storage/' + path;
          el.closest('.preview-wrap').classList.remove('hidden');
        } else {
          el.src = '';
          el.closest('.preview-wrap').classList.add('hidden');
        }
      }

      setImg('preview_probing', r.probing_depths_file);
      setImg('preview_mobility', r.mobility_file);
      setImg('preview_furcation', r.furcation_file);

      // show modal
      view.classList.remove('hidden');
    });

    function closeIntraoralView() {
      document.getElementById('intraoral-view-modal').classList.add('hidden');
    }
  </script>

  {{-- CREATE / EDIT MODAL (TAB-BY-TAB) --}}
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

    <!-- modal content -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl z-10 mx-4 overflow-auto max-h-[90vh] relative"
         x-ref="modalContent">

      <!-- header -->
      <div class="px-6 py-4 flex items-center justify-between border-b dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="mode === 'create' ? 'Add Intraoral Examination' : 'Edit Intraoral Examination'"></h3>
        <button @click="close" class="text-gray-600 hover:text-gray-800 dark:text-gray-300">&times;</button>
      </div>

      <!-- tabs -->
      <div class="px-6 py-3 border-b dark:border-gray-700 bg-white dark:bg-gray-800 sticky top-0 z-20">
        <div class="flex items-center gap-2 overflow-x-auto">
          <button :class="{'bg-indigo-600 text-white': activeTab==='soft', 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100': activeTab!=='soft'}" @click="switchTab('soft')" class="px-3 py-1 text-sm rounded">Soft Tissues</button>
          <button :class="{'bg-indigo-600 text-white': activeTab==='gingiva', 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100': activeTab!=='gingiva'}" @click="switchTab('gingiva')" class="px-3 py-1 text-sm rounded">Gingiva</button>
          <button :class="{'bg-indigo-600 text-white': activeTab==='periodontium', 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100': activeTab!=='periodontium'}" @click="switchTab('periodontium')" class="px-3 py-1 text-sm rounded">Periodontium</button>
          <button :class="{'bg-indigo-600 text-white': activeTab==='occlusion', 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100': activeTab!=='occlusion'}" @click="switchTab('occlusion')" class="px-3 py-1 text-sm rounded">Occlusion</button>
          <button :class="{'bg-indigo-600 text-white': activeTab==='hygiene', 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100': activeTab!=='hygiene'}" @click="switchTab('hygiene')" class="px-3 py-1 text-sm rounded">Oral Hygiene</button>
          <button :class="{'bg-indigo-600 text-white': activeTab==='mio', 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100': activeTab!=='mio'}" @click="switchTab('mio')" class="px-3 py-1 text-sm rounded">MIO / Notes</button>
        </div>
      </div>

      <!-- form -->
      <form :action="formAction" method="POST" enctype="multipart/form-data" class="px-6 py-4 space-y-6">
        @csrf
        <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PATCH"></template>

        {{-- Patient --}}
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

        <!-- Tab panels (each uses x-show and x-cloak) -->

        <!-- Soft tissues -->
        <div x-ref="softTab" x-show="activeTab==='soft'" x-cloak>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Soft Tissues (Lips, Cheeks, Tongue...)</label>
          <div class="mt-2 flex items-center gap-4">
            <label class="inline-flex items-center">
              <input type="radio" name="soft_tissues_status" value="Normal" x-model="form.soft_tissues_status" class="text-indigo-600">
              <span class="ml-2">Normal</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="soft_tissues_status" value="Abnormal" x-model="form.soft_tissues_status" class="text-indigo-600">
              <span class="ml-2">Abnormal</span>
            </label>
          </div>
          <textarea name="soft_tissues_notes" x-model="form.soft_tissues_notes" rows="2" placeholder="Specify lesions, swelling, discoloration..." class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2"></textarea>
        </div>

        <!-- Gingiva -->
        <div x-ref="gingivaTab" x-show="activeTab==='gingiva'" x-cloak>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gingiva (Gums)</label>
          <div class="mt-2 grid grid-cols-3 gap-4">
            <div>
              <div class="text-xs text-gray-600">Color</div>
              <select name="gingiva_color" x-model="form.gingiva_color" class="mt-1 block w-full rounded-md px-3 py-2">
                <option value="">--</option>
                <option>Pink</option>
                <option>Red</option>
                <option>Cyanotic</option>
              </select>
            </div>

            <div>
              <div class="text-xs text-gray-600">Texture</div>
              <select name="gingiva_texture" x-model="form.gingiva_texture" class="mt-1 block w-full rounded-md px-3 py-2">
                <option value="">--</option>
                <option>Stippled</option>
                <option>Edematous</option>
              </select>
            </div>

            <div>
              <div class="text-xs text-gray-600">Bleeding on Probing</div>
              <div class="mt-2 flex gap-3">
                <label class="inline-flex items-center"><input type="radio" name="bleeding_on_probing" value="1" x-model="form.bleeding_on_probing" class="text-indigo-600"><span class="ml-2">Yes</span></label>
                <label class="inline-flex items-center"><input type="radio" name="bleeding_on_probing" value="0" x-model="form.bleeding_on_probing" class="text-indigo-600"><span class="ml-2">No</span></label>
              </div>
              <input name="bleeding_areas" x-model="form.bleeding_areas" placeholder="Specify areas if localized" class="mt-2 block w-full rounded-md px-3 py-2">
            </div>
          </div>

          <div class="mt-3">
            <div class="text-xs">Recession</div>
            <div class="mt-2 flex gap-3">
              <label class="inline-flex items-center"><input type="radio" name="recession" value="1" x-model="form.recession" class="text-indigo-600"><span class="ml-2">Yes</span></label>
              <label class="inline-flex items-center"><input type="radio" name="recession" value="0" x-model="form.recession" class="text-indigo-600"><span class="ml-2">No</span></label>
            </div>
            <input name="recession_areas" x-model="form.recession_areas" placeholder="Specify teeth/areas" class="mt-2 block w-full rounded-md px-3 py-2">
          </div>
        </div>

        <!-- Periodontium / Hard tissues -->
        <div x-ref="periodontiumTab" x-show="activeTab==='periodontium'" x-cloak>
         <!-- Probing Depths Upload -->
<div class="mt-3">
  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
    Probing Depths (Upload Periodontal Chart)
  </label>
  <input type="file" name="probing_depths_file" accept="image/*,.pdf"
         class="mt-2 block w-full rounded-md px-3 py-2">
  <!-- keep existing path so backend can retain if not replaced -->
  <input type="hidden" name="existing_probing_depths_file" x-model="form.probing_depths_file">
</div>

<!-- Mobility Upload -->
<div class="mt-3">
  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
    Mobility (Upload Odontogram)
  </label>
  <input type="file" name="mobility_file" accept="image/*,.pdf"
         class="mt-2 block w-full rounded-md px-3 py-2">
  <input type="hidden" name="existing_mobility_file" x-model="form.mobility_file">
</div>

<!-- Furcation Involvement Upload -->
<div class="mt-3">
  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
    Furcation Involvement (Upload Odontogram)
  </label>
  <input type="file" name="furcation_file" accept="image/*,.pdf"
         class="mt-2 block w-full rounded-md px-3 py-2">
  <input type="hidden" name="existing_furcation_file" x-model="form.furcation_file">
</div>

          <textarea name="hard_tissues_notes" x-model="form.hard_tissues_notes" rows="2" placeholder="Missing teeth, caries, restorations, RCTs etc." class="mt-2 block w-full rounded-md px-3 py-2"></textarea>
          <input name="odontogram" x-model="form.odontogram" placeholder="Odontogram (json or shorthand)" class="mt-2 block w-full rounded-md px-3 py-2">
        </div>

        <!-- Occlusion -->
        <div x-ref="occlusionTab" x-show="activeTab==='occlusion'" x-cloak>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Occlusion</label>
          <div class="mt-2 grid grid-cols-3 gap-4">
            <select name="occlusion_class" x-model="form.occlusion_class" class="rounded-md px-3 py-2">
              <option value="">-- Class --</option>
              <option>Class I</option>
              <option>Class II</option>
              <option>Class III</option>
            </select>
            <input name="occlusion_details" x-model="form.occlusion_details" placeholder="Open bite / Deep bite / Overjet / Overbite" class="rounded-md px-3 py-2">
            <input name="premature_contacts" x-model="form.premature_contacts" placeholder="Premature contacts / interferences" class="rounded-md px-3 py-2">
          </div>
        </div>

        <!-- Oral hygiene -->
        <div x-ref="hygieneTab" x-show="activeTab==='hygiene'" x-cloak>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Oral Hygiene Status</label>
          <div class="mt-2 flex items-center gap-4">
            <label class="inline-flex items-center"><input type="radio" name="oral_hygiene_status" value="Good" x-model="form.oral_hygiene_status" class="text-indigo-600"><span class="ml-2">Good</span></label>
            <label class="inline-flex items-center"><input type="radio" name="oral_hygiene_status" value="Fair" x-model="form.oral_hygiene_status" class="text-indigo-600"><span class="ml-2">Fair</span></label>
            <label class="inline-flex items-center"><input type="radio" name="oral_hygiene_status" value="Poor" x-model="form.oral_hygiene_status" class="text-indigo-600"><span class="ml-2">Poor</span></label>
          </div>

          <div class="mt-2 grid grid-cols-2 gap-4">
            <input name="plaque_index" x-model="form.plaque_index" placeholder="Plaque Index" class="rounded-md px-3 py-2">
            <select name="calculus" x-model="form.calculus" class="rounded-md px-3 py-2">
              <option value="">Calculus</option>
              <option>Light</option>
              <option>Moderate</option>
              <option>Heavy</option>
            </select>
          </div>
        </div>

        <!-- MIO / Notes -->
        <div x-ref="mioTab" x-show="activeTab==='mio'" x-cloak class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm">Maximum Interincisal Opening (MIO) - mm</label>
            <input type="number" name="mio" x-model="form.mio" min="0" max="100" class="mt-1 rounded-md px-3 py-2 w-40">
          </div>
          <div>
            <label class="block text-sm">Notes</label>
            <input name="notes" x-model="form.notes" placeholder="General notes" class="mt-1 block w-full rounded-md px-3 py-2">
          </div>
        </div>

        <!-- footer buttons -->
        <div class="flex items-center justify-end gap-3">
          <button type="button" @click="close" class="px-4 py-2 rounded-md border text-gray-700 dark:text-gray-200">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" x-text="mode === 'create' ? 'Save' : 'Update'"></button>
        </div>
      </form>

    </div>
  </div>

  {{-- View modal markup (hidden by default) --}}
  <div id="intraoral-view-modal" class="hidden fixed inset-0 z-60 flex items-center justify-center">
    <div class="fixed inset-0 bg-black bg-opacity-40" onclick="closeIntraoralView()"></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl z-70 mx-4 overflow-auto max-h-[90vh] p-6">
      <div class="flex items-start justify-between mb-4">
        <h3 class="text-lg font-medium">Intraoral Exam - Preview</h3>
        <button onclick="closeIntraoralView()" class="text-gray-600">&times;</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="preview-wrap hidden">
          <div class="text-xs text-gray-500 mb-1">Probing Depths (chart)</div>
          <img id="preview_probing" class="w-full h-48 object-contain border rounded" src="" alt="Probing Depths">
        </div>

        <div class="preview-wrap hidden">
          <div class="text-xs text-gray-500 mb-1">Mobility (odontogram)</div>
          <img id="preview_mobility" class="w-full h-48 object-contain border rounded" src="" alt="Mobility">
        </div>

        <div class="preview-wrap hidden">
          <div class="text-xs text-gray-500 mb-1">Furcation (odontogram)</div>
          <img id="preview_furcation" class="w-full h-48 object-contain border rounded" src="" alt="Furcation">
        </div>
      </div>

      <div class="mt-6">
        <button onclick="closeIntraoralView()" class="px-4 py-2 rounded-md border">Close</button>
      </div>
    </div>
  </div>
</x-app-layout>