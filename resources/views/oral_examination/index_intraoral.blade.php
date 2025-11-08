@section('title', 'Intraoral Examinations')
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Intraoral Examinations
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Records</h3>
        <div x-data>
          <button @click="$dispatch('open-intraoral-modal', { mode: 'create' })" type="button"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-500">
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
                <th class="px-4 py-2">Soft Tissues</th>
                <th class="px-4 py-2">Gingiva</th>
                <th class="px-4 py-2">Periodontium</th>
                <th class="px-4 py-2">Occlusion</th>
                <th class="px-4 py-2">Oral Hygiene</th>
                <th class="px-4 py-2">MIO</th>
                <th class="px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($examinations ?? [] as $exam)
                <tr class="border-t">
                  <td class="px-4 py-2">{{ $loop->iteration }}</td>
                  <td class="px-4 py-2">{{ optional($exam->patient)->first_name ?? '—' }} {{ optional($exam->patient)->last_name ?? '' }}</td>
                  <td class="px-4 py-2">{{ $exam->soft_tissues_status ?? '—' }}</td>
                  <td class="px-4 py-2">{{ $exam->gingiva_color ?? '—' }} / {{ $exam->gingiva_texture ?? '—' }}</td>
                  <td class="px-4 py-2">
                    PDepths: {{ Str::limit($exam->probing_depths, 40) }}<br>
                    Mobility: {{ Str::limit($exam->mobility, 30) }}
                  </td>
                  <td class="px-4 py-2">{{ $exam->occlusion_class ?? '—' }}</td>
                  <td class="px-4 py-2">{{ $exam->oral_hygiene_status ?? '—' }}</td>
                  <td class="px-4 py-2">{{ $exam->mio ?? '—' }}</td>
                  <td class="px-4 py-2">
                    <div class="flex items-center gap-2">
                      @php
                        $record = [
                          'id'=>$exam->id,
                          'patient_id'=>$exam->patient_id,
                          'soft_tissues_status'=>$exam->soft_tissues_status,
                          'soft_tissues_notes'=>$exam->soft_tissues_notes,
                          'gingiva_color'=>$exam->gingiva_color,
                          'gingiva_texture'=>$exam->gingiva_texture,
                          'bleeding_on_probing'=>$exam->bleeding_on_probing ? '1' : '0',
                          'bleeding_areas'=>$exam->bleeding_areas,
                          'recession'=>$exam->recession ? '1' : '0',
                          'recession_areas'=>$exam->recession_areas,
                          'probing_depths'=>$exam->probing_depths,
                          'mobility'=>$exam->mobility,
                          'furcation_involvement'=>$exam->furcation_involvement,
                          'hard_tissues_notes'=>$exam->hard_tissues_notes,
                          'odontogram'=>$exam->odontogram,
                          'occlusion_class'=>$exam->occlusion_class,
                          'occlusion_details'=>$exam->occlusion_details,
                          'premature_contacts'=>$exam->premature_contacts,
                          'oral_hygiene_status'=>$exam->oral_hygiene_status,
                          'plaque_index'=>$exam->plaque_index,
                          'calculus'=>$exam->calculus,
                          'mio'=>$exam->mio,
                          'notes'=>$exam->notes,
                        ];
                      @endphp

                      <button
                        type="button"
                        class="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-400"
                        data-record='{{ json_encode($record, JSON_HEX_APOS|JSON_HEX_QUOT) }}'
                        onclick="window.dispatchEvent(new CustomEvent('open-intraoral-modal',{detail:{mode:'edit', record: JSON.parse(this.dataset.record)}}))"
                      >
                        Edit
                      </button>

                      <form action="{{ route('intraoral_examinations.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?');">
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

  {{-- Alpine factory (define before modal markup) --}}
  <script>
    function intraoralModal() {
      return {
        open: false,
        mode: 'create',
        form: {
          id: null,
          patient_id: '{{ old("patient_id", isset($patient) && $patient ? $patient->id : '') }}',
          soft_tissues_status: '',
          soft_tissues_notes: '',
          gingiva_color: '',
          gingiva_texture: '',
          bleeding_on_probing: '0',
          bleeding_areas: '',
          recession: '0',
          recession_areas: '',
          probing_depths: '',
          mobility: '',
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
        get formAction() {
          if (this.mode === 'create') {
            return '{{ route("intraoral_examinations.store") }}';
          }
          return '{{ url("intraoral_examinations") }}/' + (this.form.id || '')
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
            // reset all fields
            for (const k in this.form) { if (k !== 'patient_id') this.form[k] = ''; }
            this.form.bleeding_on_probing = '0';
            this.form.recession = '0';
          } else {
            const r = d.record || {};
            this.form.id = r.id ?? null;
            this.form.patient_id = r.patient_id ?? this.form.patient_id;
            this.form.soft_tissues_status = r.soft_tissues_status ?? '';
            this.form.soft_tissues_notes = r.soft_tissues_notes ?? '';
            this.form.gingiva_color = r.gingiva_color ?? '';
            this.form.gingiva_texture = r.gingiva_texture ?? '';
            this.form.bleeding_on_probing = (typeof r.bleeding_on_probing !== 'undefined') ? String(r.bleeding_on_probing) : '0';
            this.form.bleeding_areas = r.bleeding_areas ?? '';
            this.form.recession = (typeof r.recession !== 'undefined') ? String(r.recession) : '0';
            this.form.recession_areas = r.recession_areas ?? '';
            this.form.probing_depths = r.probing_depths ?? '';
            this.form.mobility = r.mobility ?? '';
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
        },
        close() { this.open = false; this.errors = {}; }
      }
    }
  </script>

  {{-- Modal markup --}}
  <div x-data="intraoralModal()"
       x-on:open-intraoral-modal.window="setForm($event.detail)"
       x-show="open"
       class="fixed inset-0 z-50 flex items-center justify-center"
       style="display:none;"
       aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black bg-opacity-40" @click="close"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl z-10 mx-4 overflow-auto max-h-[90vh]">
      <div class="px-6 py-4 flex items-center justify-between border-b dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="mode==='create' ? 'Add Intraoral Examination' : 'Edit Intraoral Examination'"></h3>
        <button @click="close" class="text-gray-600 hover:text-gray-800 dark:text-gray-300">&times;</button>
      </div>

      <form :action="formAction" method="POST" class="px-6 py-4 space-y-4">
        @csrf
        <input type="hidden" name="_method" x-bind:value="mode === 'edit' ? 'PATCH' : ''">

        {{-- Patient --}}
        @if(isset($patient) && $patient)
          <input type="hidden" name="patient_id" x-model="form.patient_id">
          <div class="text-sm text-gray-600 dark:text-gray-300">Patient: <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></div>
        @else
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient</label>
            <select name="patient_id" x-model="form.patient_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 px-3 py-2">
              <option value="">-- Select patient --</option>
              @foreach($patients as $p)
                <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
              @endforeach
            </select>
          </div>
        @endif

        {{-- Soft tissues --}}
        <div>
          <label class="block text-sm font-medium">Soft Tissues (Lips, Cheeks, Tongue...)</label>
          <div class="mt-2 flex items-center gap-4">
            <label><input type="radio" name="soft_tissues_status" value="Normal" x-model="form.soft_tissues_status"> <span class="ml-2">Normal</span></label>
            <label><input type="radio" name="soft_tissues_status" value="Abnormal" x-model="form.soft_tissues_status"> <span class="ml-2">Abnormal</span></label>
          </div>
          <textarea name="soft_tissues_notes" x-model="form.soft_tissues_notes" rows="2" placeholder="Specify lesions, swelling, discoloration..." class="mt-2 block w-full rounded-md border-gray-300 px-3 py-2"></textarea>
        </div>

        {{-- Gingiva --}}
        <div>
          <label class="block text-sm font-medium">Gingiva (Gums)</label>
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
                <label><input type="radio" name="bleeding_on_probing" value="1" x-model="form.bleeding_on_probing"> Yes</label>
                <label><input type="radio" name="bleeding_on_probing" value="0" x-model="form.bleeding_on_probing"> No</label>
              </div>
              <input name="bleeding_areas" x-model="form.bleeding_areas" placeholder="Specify areas if localized" class="mt-2 block w-full rounded-md px-3 py-2">
            </div>
          </div>

          <div class="mt-3">
            <div class="text-xs">Recession</div>
            <div class="mt-2 flex gap-3">
              <label><input type="radio" name="recession" value="1" x-model="form.recession"> Yes</label>
              <label><input type="radio" name="recession" value="0" x-model="form.recession"> No</label>
            </div>
            <input name="recession_areas" x-model="form.recession_areas" placeholder="Specify teeth/areas" class="mt-2 block w-full rounded-md px-3 py-2">
          </div>
        </div>

        {{-- Periodontium / Hard tissues --}}
        <div>
          <label class="block text-sm font-medium">Periodontium / Hard Tissues</label>
          <textarea name="probing_depths" x-model="form.probing_depths" rows="2" placeholder="Probing depths (e.g., sextant chart or JSON)" class="mt-2 block w-full rounded-md px-3 py-2"></textarea>
          <div class="mt-2 grid grid-cols-2 gap-4">
            <input name="mobility" x-model="form.mobility" placeholder="Mobility (odontogram reference)" class="block w-full rounded-md px-3 py-2">
            <input name="furcation_involvement" x-model="form.furcation_involvement" placeholder="Furcation involvement" class="block w-full rounded-md px-3 py-2">
          </div>
          <textarea name="hard_tissues_notes" x-model="form.hard_tissues_notes" rows="2" placeholder="Missing teeth, caries, restorations, RCTs etc." class="mt-2 block w-full rounded-md px-3 py-2"></textarea>
          <input name="odontogram" x-model="form.odontogram" placeholder="Odontogram (json or shorthand)" class="mt-2 block w-full rounded-md px-3 py-2">
        </div>

        {{-- Occlusion --}}
        <div>
          <label class="block text-sm font-medium">Occlusion</label>
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

        {{-- Oral hygiene --}}
        <div>
          <label class="block text-sm font-medium">Oral Hygiene Status</label>
          <div class="mt-2 flex items-center gap-4">
            <label><input type="radio" name="oral_hygiene_status" value="Good" x-model="form.oral_hygiene_status"> <span class="ml-2">Good</span></label>
            <label><input type="radio" name="oral_hygiene_status" value="Fair" x-model="form.oral_hygiene_status"> <span class="ml-2">Fair</span></label>
            <label><input type="radio" name="oral_hygiene_status" value="Poor" x-model="form.oral_hygiene_status"> <span class="ml-2">Poor</span></label>
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

        {{-- MIO and notes --}}
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm">Maximum Interincisal Opening (MIO) - mm</label>
            <input type="number" name="mio" x-model="form.mio" min="0" max="100" class="mt-1 rounded-md px-3 py-2 w-40">
          </div>
          <div>
            <label class="block text-sm">Notes</label>
            <input name="notes" x-model="form.notes" placeholder="General notes" class="mt-1 block w-full rounded-md px-3 py-2">
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3">
          <button type="button" @click="close" class="px-4 py-2 rounded-md border">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" x-text="mode === 'create' ? 'Save' : 'Update'"></button>
        </div>
      </form>
    </div>
  </div>

</x-app-layout>
