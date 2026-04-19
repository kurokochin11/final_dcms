<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-indigo-600">
                    Treatment Records
                </h2>
                <button onclick="openModal('add-modal')"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                    + Add Treatment
                </button>
            </div>
        </x-slot>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- FILTER SECTION --}}
        <div class="bg-white p-3 rounded-xl shadow-sm mb-4 border border-gray-100">
            <form method="GET" action="{{ route('treatments.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="w-64">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase ml-1">Patient</label>
                    <select name="patient_id" class="w-full border-gray-200 rounded-md shadow-sm focus:ring-indigo-500 text-xs py-1.5">
                        <option value="">All Patients</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->last_name }}, {{ $patient->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-48">
    <label class="block text-[10px] font-bold text-gray-400 uppercase ml-1">Date</label>
    <select name="date" class="w-full border-gray-200 rounded-md shadow-sm focus:ring-indigo-500 text-xs py-1.5">
        <option value="">All Dates</option>
        @foreach($availableDates as $date)
            <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>
                {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
            </option>
        @endforeach
    </select>
</div>

                <div class="flex items-center gap-2 self-end pb-0.5">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-md shadow-sm transition font-medium text-xs">
                        Filter
                    </button>
                    @if(request()->filled('patient_id') || request()->filled('date'))
                        <a href="{{ route('treatments.index') }}" class="text-gray-400 hover:text-red-500 transition text-xs font-medium px-2">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABLE SECTION --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Patient</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Treatment</th>
                        <th class="px-4 py-3 text-left">Tooth #</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($treatments as $treatment)
                        @php
                            $patientName = $treatment->patient 
                                ? $treatment->patient->last_name . ', ' . $treatment->patient->first_name 
                                : 'N/A';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $patientName }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($treatment->date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3">{{ $treatment->treatment }}</td>
                            <td class="px-4 py-3">{{ $treatment->tooth_number }}</td>
                            <td class="px-4 py-3 text-right font-semibold">₱{{ number_format($treatment->amount, 2) }}</td>
                            <td class="px-4 py-3 text-center space-x-1 flex justify-center">
                                <button onclick="prepViewModal({{ $treatment->toJson() }}, '{{ $patientName }}')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                    View
                                </button>
                                <button onclick="prepEditModal({{ $treatment->toJson() }})"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                    Edit
                                </button>
                                <button onclick="prepDeleteModal({{ $treatment->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No treatments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ADD MODAL --}}
        <div id="add-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
                <h3 class="font-semibold text-lg text-indigo-600 mb-4">Add Treatment</h3>
                <form method="POST" action="{{ route('treatments.store') }}" class="space-y-3">
                    @csrf
                    <select name="patient_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->last_name }}, {{ $patient->first_name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date" class="w-full border rounded px-3 py-2" required>
                    <input type="text" name="treatment" placeholder="Treatment Name" class="w-full border rounded px-3 py-2" required>
                    <input type="text" name="tooth_number" placeholder="Tooth Number" class="w-full border rounded px-3 py-2" required>
                    <input type="number" step="0.01" name="amount" placeholder="Amount" class="w-full border rounded px-3 py-2" required>
                    
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="closeModal('add-modal')" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- VIEW MODAL --}}
        <div id="view-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-semibold text-lg text-blue-600">Treatment Details</h3>
                    <button onclick="closeModal('view-modal')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <div class="grid grid-cols-2 gap-y-4 text-sm">
                    <div class="col-span-2">
                        <label class="text-gray-500 font-semibold uppercase text-xs">Patient</label>
                        <p id="view-patient" class="text-base text-gray-800">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Date</label>
                        <p id="view-date" class="text-gray-800">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Tooth #</label>
                        <p id="view-tooth" class="text-gray-800">-</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-gray-500 font-semibold uppercase text-xs">Treatment</label>
                        <p id="view-treatment-name" class="text-gray-800">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Amount</label>
                        <p id="view-amount" class="text-lg font-bold text-gray-900">-</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-6">
                    <a id="view-pdf-btn" href="#" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow text-sm flex items-center gap-2">
                        Print PDF
                    </a>
                    <button type="button" onclick="closeModal('view-modal')" class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow">Close</button>
                </div>
            </div>
        </div>

        {{-- EDIT MODAL --}}
        <div id="edit-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
                <h3 class="font-semibold text-lg text-yellow-600 mb-4">Edit Treatment</h3>
                <form id="edit-form" method="POST" action="" class="space-y-3">
                    @csrf @method('PUT')
                    <select name="patient_id" id="edit-patient-id" required class="w-full border rounded px-3 py-2">
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->last_name }}, {{ $p->first_name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date" id="edit-date" class="w-full border rounded px-3 py-2">
                    <input type="text" name="treatment" id="edit-treatment-name" class="w-full border rounded px-3 py-2">
                    <input type="text" name="tooth_number" id="edit-tooth" class="w-full border rounded px-3 py-2">
                    <input type="number" step="0.01" name="amount" id="edit-amount" class="w-full border rounded px-3 py-2">
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="closeModal('edit-modal')" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div id="delete-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Confirm Delete</h3>
                <form id="delete-form" method="POST" action="">
                    @csrf @method('DELETE')
                    <div class="flex justify-center gap-3">
                        <button type="button" onclick="closeModal('delete-modal')" class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function prepViewModal(treatment, patientName) {
            document.getElementById('view-patient').innerText = patientName;
            document.getElementById('view-date').innerText = treatment.date;
            document.getElementById('view-tooth').innerText = treatment.tooth_number;
            document.getElementById('view-treatment-name').innerText = treatment.treatment;
            document.getElementById('view-amount').innerText = "₱" + parseFloat(treatment.amount).toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('view-pdf-btn').href = `/treatments/${treatment.id}/pdf`;
            openModal('view-modal');
        }

        function prepEditModal(treatment) {
            const form = document.getElementById('edit-form');
            form.action = `/treatments/${treatment.id}`;
            document.getElementById('edit-patient-id').value = treatment.patient_id;
            document.getElementById('edit-date').value = treatment.date;
            document.getElementById('edit-treatment-name').value = treatment.treatment;
            document.getElementById('edit-tooth').value = treatment.tooth_number;
            document.getElementById('edit-amount').value = treatment.amount;
            openModal('edit-modal');
        }

        function prepDeleteModal(id) {
            document.getElementById('delete-form').action = `/treatments/${id}`;
            openModal('delete-modal');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('bg-black/50')) {
                ['add-modal', 'view-modal', 'edit-modal', 'delete-modal'].forEach(id => closeModal(id));
            }
        }
    </script>
</x-app-layout>