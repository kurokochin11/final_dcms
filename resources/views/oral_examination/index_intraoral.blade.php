{{-- resources/views/intraoral_examinations/show.blade.php --}}
@section('title', 'Intraoral Examination View')

<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Intraoral Examination 
      </h2>

      <div class="flex items-center gap-2">
        {{-- Back to index (make sure this route name exists; see note below) --}}
        <a href="{{ route('oral_examination.index_intraoral') }}" class="px-3 py-1 rounded border text-sm">Back</a>
  <a href="{{ route('oral_examination.show', $exam) }}" class="px-3 py-1 rounded bg-blue-600 text-white text-sm">View</a>

        {{-- Delete form: pass model or id; using $exam->id is explicit and safe --}}
      <form action="{{ route('oral_examination.destroy', $exam) }}" method="POST" onsubmit="return confirm('Delete this record?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-sm">Delete</button>
</form>

      </div>
    </div>
  </x-slot>

  <div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        {{-- Patient --}}
        <div class="mb-4">
          <div class="text-sm text-gray-600 dark:text-gray-300">Patient</div>
          <div class="text-lg font-medium">
            {{ optional($exam->patient)->first_name ?? '—' }} {{ optional($exam->patient)->last_name ?? '' }}
            <span class="ml-2 text-xs text-gray-500">ID: {{ $exam->patient_id }}</span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          {{-- Soft tissues --}}
          <div class="space-y-2">
            <h3 class="font-semibold">Soft tissues</h3>
            <div><strong>Status:</strong> {{ $exam->soft_tissues_status ?: '—' }}</div>
            <div><strong>Notes:</strong> {{ $exam->soft_tissues_notes ?: '—' }}</div>
          </div>

          {{-- Gingiva --}}
          <div class="space-y-2">
            <h3 class="font-semibold">Gingiva</h3>
            <div><strong>Color:</strong> {{ $exam->gingiva_color ?: '—' }}</div>
            <div><strong>Texture:</strong> {{ $exam->gingiva_texture ?: '—' }}</div>
            <div><strong>Bleeding on probing:</strong> {{ $exam->bleeding_on_probing ? 'Yes' : 'No' }}</div>
            <div><strong>Bleeding areas:</strong> {{ $exam->bleeding_areas ?: '—' }}</div>
            <div><strong>Recession:</strong> {{ $exam->recession ? 'Yes' : 'No' }}</div>
            <div><strong>Recession areas:</strong> {{ $exam->recession_areas ?: '—' }}</div>
          </div>

          {{-- Periodontium / Hard tissues --}}
          <div class="space-y-2">
            <h3 class="font-semibold">Periodontium / Hard tissues</h3>
            <div><strong>Hard tissues notes:</strong> {{ $exam->hard_tissues_notes ?: '—' }}</div>
            <div>
              <strong>Odontogram:</strong>
              <pre class="whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-200">{{ $exam->odontogram ?: '—' }}</pre>
            </div>
          </div>

          {{-- Occlusion --}}
          <div class="space-y-2">
            <h3 class="font-semibold">Occlusion</h3>
            <div><strong>Class:</strong> {{ $exam->occlusion_class ?: '—' }}</div>
            <div><strong>Details:</strong> {{ $exam->occlusion_details ?: '—' }}</div>
            <div><strong>Premature contacts:</strong> {{ $exam->premature_contacts ?: '—' }}</div>
          </div>

          {{-- Oral hygiene --}}
          <div class="space-y-2">
            <h3 class="font-semibold">Oral hygiene</h3>
            <div><strong>Status:</strong> {{ $exam->oral_hygiene_status ?: '—' }}</div>
            <div><strong>Plaque index:</strong> {{ $exam->plaque_index ?: '—' }}</div>
            <div><strong>Calculus:</strong> {{ $exam->calculus ?: '—' }}</div>
          </div>

          {{-- MIO & Notes --}}
          <div class="space-y-2">
            <h3 class="font-semibold">MIO & Notes</h3>
            <div><strong>MIO (mm):</strong> {{ $exam->mio ?? '—' }}</div>
            <div><strong>Notes:</strong> {{ $exam->notes ?: '—' }}</div>
          </div>
        </div>

        {{-- Images --}}
        <div class="mt-6">
          <h3 class="font-semibold mb-3">Images</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Probing --}}
            <div class="border rounded p-3 text-center">
              <div class="text-xs text-gray-500 mb-2">Probing Depths</div>
              @if($exam->probing_depths_file)
                <a href="{{ asset('storage/'.$exam->probing_depths_file) }}" target="_blank" class="block mb-2">
                  <img src="{{ asset('storage/'.$exam->probing_depths_file) }}" alt="Probing Depths" class="w-full h-48 object-contain">
                </a>
                <div class="text-sm"><a href="{{ asset('storage/'.$exam->probing_depths_file) }}" target="_blank" class="text-indigo-600">Open full image</a></div>
              @else
                <div class="text-sm text-gray-500">No image uploaded</div>
              @endif
            </div>

            {{-- Mobility --}}
            <div class="border rounded p-3 text-center">
              <div class="text-xs text-gray-500 mb-2">Mobility (Odontogram)</div>
              @if($exam->mobility_file)
                <a href="{{ asset('storage/'.$exam->mobility_file) }}" target="_blank" class="block mb-2">
                  <img src="{{ asset('storage/'.$exam->mobility_file) }}" alt="Mobility" class="w-full h-48 object-contain">
                </a>
                <div class="text-sm"><a href="{{ asset('storage/'.$exam->mobility_file) }}" target="_blank" class="text-indigo-600">Open full image</a></div>
              @else
                <div class="text-sm text-gray-500">No image uploaded</div>
              @endif
            </div>

            {{-- Furcation --}}
            <div class="border rounded p-3 text-center">
              <div class="text-xs text-gray-500 mb-2">Furcation (Odontogram)</div>
              @if($exam->furcation_file)
                <a href="{{ asset('storage/'.$exam->furcation_file) }}" target="_blank" class="block mb-2">
                  <img src="{{ asset('storage/'.$exam->furcation_file) }}" alt="Furcation" class="w-full h-48 object-contain">
                </a>
                <div class="text-sm"><a href="{{ asset('storage/'.$exam->furcation_file) }}" target="_blank" class="text-indigo-600">Open full image</a></div>
              @else
                <div class="text-sm text-gray-500">No image uploaded</div>
              @endif
            </div>
          </div>
        </div>

        {{-- Timestamps --}}
        <div class="mt-6 text-sm text-gray-500">
          <div>Created: {{ $exam->created_at ? $exam->created_at->format('M d, Y H:i') : '—' }}</div>
          <div>Last updated: {{ $exam->updated_at ? $exam->updated_at->format('M d, Y H:i') : '—' }}</div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
