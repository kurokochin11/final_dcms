{{-- resources/views/oral_examination/index_intraoral.blade.php --}}
@section('title', 'Intraoral Examinations')

<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Intraoral Examinations
        </h2>
        <button
            data-modal-target="createExamModal"
            data-modal-toggle="createExamModal"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            New Examination
        </button>
    </div>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Success message --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 overflow-x-auto">
            <table class="min-w-full border divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Patient</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-200">MIO</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($examinations as $exam)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-4 py-2">
                                {{ optional($exam->patient)->first_name }} {{ optional($exam->patient)->last_name }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $exam->patient_id }}</div>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ $exam->created_at ? $exam->created_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ $exam->mio ?? '—' }}
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                {{-- View button --}}
                                <button data-modal-target="viewExamModal-{{ $exam->id }}" data-modal-toggle="viewExamModal-{{ $exam->id }}"
                                    class="px-3 py-1 rounded bg-blue-600 text-white text-sm">View</button>

                                {{-- Edit button --}}
                                <button data-modal-target="editExamModal-{{ $exam->id }}" data-modal-toggle="editExamModal-{{ $exam->id }}"
                                    class="px-3 py-1 rounded bg-yellow-500 text-white text-sm">Edit</button>

                                {{-- Delete form --}}
                                <form action="{{ route('oral_examination.destroy', $exam) }}" method="POST" onsubmit="return confirm('Delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>

                        {{-- View Modal --}}
                        <div id="viewExamModal-{{ $exam->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50">
                            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-4xl p-6 relative">
                                <button data-modal-hide="viewExamModal-{{ $exam->id }}" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-white">&times;</button>
                                <h3 class="text-lg font-semibold mb-4">View Examination</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><strong>Patient:</strong> {{ optional($exam->patient)->first_name }} {{ optional($exam->patient)->last_name }}</div>
                                    <div><strong>MIO:</strong> {{ $exam->mio ?? '—' }}</div>
                                    <div><strong>Soft Tissues Status:</strong> {{ $exam->soft_tissues_status ?? '—' }}</div>
                                    <div><strong>Soft Tissues Notes:</strong> {{ $exam->soft_tissues_notes ?? '—' }}</div>
                                    <div><strong>Gingiva Color:</strong> {{ $exam->gingiva_color ?? '—' }}</div>
                                    <div><strong>Gingiva Texture:</strong> {{ $exam->gingiva_texture ?? '—' }}</div>
                                    <div><strong>Bleeding on Probing:</strong> {{ $exam->bleeding_on_probing ? 'Yes' : 'No' }}</div>
                                    <div><strong>Recession:</strong> {{ $exam->recession ? 'Yes' : 'No' }}</div>
                                    <div><strong>Bleeding Areas:</strong> {{ $exam->bleeding_areas ?? '—' }}</div>
                                    <div><strong>Recession Areas:</strong> {{ $exam->recession_areas ?? '—' }}</div>
                                    <div><strong>Hard Tissues Notes:</strong> {{ $exam->hard_tissues_notes ?? '—' }}</div>
                                    <div><strong>Odontogram:</strong> {{ $exam->odontogram ?? '—' }}</div>
                                    <div><strong>Occlusion Class:</strong> {{ $exam->occlusion_class ?? '—' }}</div>
                                    <div><strong>Occlusion Details:</strong> {{ $exam->occlusion_details ?? '—' }}</div>
                                    <div><strong>Premature Contacts:</strong> {{ $exam->premature_contacts ?? '—' }}</div>
                                    <div><strong>Oral Hygiene Status:</strong> {{ $exam->oral_hygiene_status ?? '—' }}</div>
                                    <div><strong>Plaque Index:</strong> {{ $exam->plaque_index ?? '—' }}</div>
                                    <div><strong>Calculus:</strong> {{ $exam->calculus ?? '—' }}</div>
                                    <div><strong>Notes:</strong> {{ $exam->notes ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Edit Modal --}}
                        <div id="editExamModal-{{ $exam->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50">
                            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-4xl p-6 relative">
                                <button data-modal-hide="editExamModal-{{ $exam->id }}" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-white">&times;</button>
                                <h3 class="text-lg font-semibold mb-4">Edit Examination</h3>

                                {{-- Tabs --}}
                                <div class="mb-4 border-b border-gray-200 dark:border-gray-600">
                                    <nav class="-mb-px flex space-x-4">
                                        <button class="edit-tab-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 border-b-2 border-blue-600" data-tab="soft-tissues-{{ $exam->id }}">Soft Tissues</button>
                                        <button class="edit-tab-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200" data-tab="gingiva-{{ $exam->id }}">Gingiva</button>
                                        <button class="edit-tab-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200" data-tab="periodontium-{{ $exam->id }}">Periodontium</button>
                                        <button class="edit-tab-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200" data-tab="occlusion-{{ $exam->id }}">Occlusion</button>
                                        <button class="edit-tab-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200" data-tab="mio-{{ $exam->id }}">MIO</button>
                                        <button class="edit-tab-link px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200" data-tab="hygiene-{{ $exam->id }}">Oral Hygiene</button>
                                    </nav>
                                </div>

                                <form action="{{ route('oral_examination.update', $exam) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    {{-- Patient --}}
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Patient</label>
                                        <select name="patient_id" class="w-full border rounded px-2 py-1">
                                            <option value="">Select patient</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}" {{ $exam->patient_id == $patient->id ? 'selected' : '' }}>
                                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Tab contents --}}
                                    <div id="edit-tab-contents-{{ $exam->id }}">
                                        {{-- Soft Tissues --}}
                                        <div class="edit-tab-content" id="soft-tissues-{{ $exam->id }}">
                                            <input type="text" name="soft_tissues_status" placeholder="Soft Tissues Status" value="{{ $exam->soft_tissues_status }}" class="w-full border rounded px-2 py-1">
                                            <textarea name="soft_tissues_notes" placeholder="Soft Tissues Notes" class="w-full border rounded px-2 py-1">{{ $exam->soft_tissues_notes }}</textarea>
                                        </div>

                                        {{-- Gingiva --}}
                                        <div class="edit-tab-content hidden" id="gingiva-{{ $exam->id }}">
                                            <input type="text" name="gingiva_color" placeholder="Gingiva Color" value="{{ $exam->gingiva_color }}" class="w-full border rounded px-2 py-1">
                                            <input type="text" name="gingiva_texture" placeholder="Gingiva Texture" value="{{ $exam->gingiva_texture }}" class="w-full border rounded px-2 py-1">
                                            <label><input type="checkbox" name="bleeding_on_probing" value="1" {{ $exam->bleeding_on_probing ? 'checked' : '' }}> Bleeding on Probing</label>
                                            <label><input type="checkbox" name="recession" value="1" {{ $exam->recession ? 'checked' : '' }}> Recession</label>
                                            <input type="text" name="bleeding_areas" placeholder="Bleeding Areas" value="{{ $exam->bleeding_areas }}" class="w-full border rounded px-2 py-1">
                                            <input type="text" name="recession_areas" placeholder="Recession Areas" value="{{ $exam->recession_areas }}" class="w-full border rounded px-2 py-1">
                                        </div>

                                        {{-- Periodontium --}}
                                        <div class="edit-tab-content hidden" id="periodontium-{{ $exam->id }}">
                                            <textarea name="hard_tissues_notes" placeholder="Hard Tissues Notes" class="w-full border rounded px-2 py-1">{{ $exam->hard_tissues_notes }}</textarea>
                                            <textarea name="odontogram" placeholder="Odontogram" class="w-full border rounded px-2 py-1">{{ $exam->odontogram }}</textarea>
                                        </div>

                                        {{-- Occlusion --}}
                                        <div class="edit-tab-content hidden" id="occlusion-{{ $exam->id }}">
                                            <input type="text" name="occlusion_class" placeholder="Occlusion Class" value="{{ $exam->occlusion_class }}" class="w-full border rounded px-2 py-1">
                                            <textarea name="occlusion_details" placeholder="Occlusion Details" class="w-full border rounded px-2 py-1">{{ $exam->occlusion_details }}</textarea>
                                            <input type="text" name="premature_contacts" placeholder="Premature Contacts" value="{{ $exam->premature_contacts }}" class="w-full border rounded px-2 py-1">
                                        </div>

                                        {{-- MIO --}}
                                        <div class="edit-tab-content hidden" id="mio-{{ $exam->id }}">
                                            <input type="number" name="mio" placeholder="MIO (mm)" value="{{ $exam->mio }}" class="w-full border rounded px-2 py-1">
                                        </div>

                                        {{-- Oral Hygiene --}}
                                        <div class="edit-tab-content hidden" id="hygiene-{{ $exam->id }}">
                                            <input type="text" name="oral_hygiene_status" placeholder="Oral Hygiene Status" value="{{ $exam->oral_hygiene_status }}" class="w-full border rounded px-2 py-1">
                                            <input type="text" name="plaque_index" placeholder="Plaque Index" value="{{ $exam->plaque_index }}" class="w-full border rounded px-2 py-1">
                                            <input type="text" name="calculus" placeholder="Calculus" value="{{ $exam->calculus }}" class="w-full border rounded px-2 py-1">
                                            <textarea name="notes" placeholder="Notes" class="w-full border rounded px-2 py-1">{{ $exam->notes }}</textarea>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="button" data-modal-hide="editExamModal-{{ $exam->id }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded hover:bg-gray-400 dark:hover:bg-gray-500">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                                No examinations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $examinations->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div id="createExamModal" class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50">
    <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-4xl p-6 relative">
        <button data-modal-hide="createExamModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-white">&times;</button>
        <h3 class="text-lg font-semibold mb-4">New Intraoral Examination</h3>
        <form action="{{ route('oral_examination.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Patient</label>
                <select name="patient_id" class="w-full border rounded px-2 py-1">
                    <option value="">Select patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Add other tab fields same as edit modal here if needed --}}
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" data-modal-hide="createExamModal" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded hover:bg-gray-400 dark:hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ---------------------------
    // MODAL FUNCTIONALITY
    // ---------------------------

    // Open modal buttons
    document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        });
    });

    // Close modal buttons
    document.querySelectorAll('[data-modal-hide]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-modal-hide');
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        });
    });

    // Close modal when clicking outside content
    document.querySelectorAll('.fixed.inset-0').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    // ---------------------------
    // EDIT MODAL TABS FUNCTIONALITY
    // ---------------------------

    document.querySelectorAll('.edit-tab-link').forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab'); // e.g., "soft-tissues-5"
            const examId = tabId.split('-').pop();

            // Hide all tab contents of this modal
            const tabContents = document.querySelectorAll(`#edit-tab-contents-${examId} .edit-tab-content`);
            tabContents.forEach(c => c.classList.add('hidden'));

            // Show selected tab content
            const activeTab = document.getElementById(tabId);
            if (activeTab) activeTab.classList.remove('hidden');

            // Update tab button styles
            const siblingTabs = tab.parentElement.querySelectorAll('.edit-tab-link');
            siblingTabs.forEach(t => t.classList.remove('border-b-2', 'border-blue-600'));
            tab.classList.add('border-b-2', 'border-blue-600');
        });
    });
</script>

</x-app-layout>
