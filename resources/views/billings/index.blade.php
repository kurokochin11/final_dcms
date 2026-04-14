<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-indigo-600">
                    Billing Management
                </h2>
                <button onclick="openModal('add-modal')"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                    + Add Billing
                </button>
            </div>
        </x-slot>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        <div class="bg-white p-3 rounded-xl shadow-sm mb-4 border border-gray-100">
    <form method="GET" action="{{ route('billings.index') }}" class="flex flex-wrap items-center gap-3">
        
        <div class="w-64">
            <label class="block text-[10px] font-bold text-gray-400 uppercase ml-1">Patient</label>
            <select name="patient_id" class="w-full border-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs py-1.5">
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
            <select name="date" class="w-full border-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs py-1.5">
                <option value="">All Dates</option>
                @foreach($billings->pluck('date')->unique()->sortDesc() as $billingDate)
                    <option value="{{ $billingDate->format('Y-m-d') }}" {{ request('date') == $billingDate->format('Y-m-d') ? 'selected' : '' }}>
                        {{ $billingDate->format('M d, Y') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2 self-end pb-0.5">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-md shadow-sm transition font-medium text-xs">
                Filter
            </button>
            
            @if(request()->filled('patient_id') || request()->filled('date'))
                <a href="{{ route('billings.index') }}" class="text-gray-400 hover:text-red-500 transition text-xs font-medium px-2">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Patient</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Service Rendered</th>
                        <th class="px-4 py-3 text-right">Service Fee</th>
                        <th class="px-4 py-3 text-left">Paid</th>
                        <th class="px-4 py-3 text-left">Receipt No.</th>
                        <th class="px-4 py-3 text-right">Balance</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($billings as $billing)
                        @php
                            $patientName = $billing->patient 
                                ? $billing->patient->last_name . ', ' . $billing->patient->first_name . ($billing->patient->middle_name ? ' ' . $billing->patient->middle_name[0] . '.' : '') 
                                : 'N/A';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $patientName }}</td>
                            <td class="px-4 py-3">{{ $billing->date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">{{ $billing->service_rendered }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ number_format($billing->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $billing->payment_method ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $billing->receipt_no ?? '-' }}</td>
                            <td class="px-4 py-3 text-right text-red-600">{{ number_format($billing->outstanding_balance ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-center space-x-1 flex justify-center">
                                <button onclick="prepViewModal({{ $billing->toJson() }}, '{{ $patientName }}')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                    View
                                </button>
                                
                                <button onclick="prepEditModal({{ $billing->toJson() }})"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                    Edit
                                </button>
                                
                                <button onclick="prepDeleteModal({{ $billing->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">No billing records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ADD MODAL --}}
        <div id="add-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
                <h3 class="font-semibold text-lg text-indigo-600 mb-4">Add Billing</h3>
                <form method="POST" action="{{ route('billings.store') }}" class="space-y-3">
                    @csrf
                    <select name="patient_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Select Patient</option>
                        @if(isset($patients) && count($patients) > 0)
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->last_name }}, {{ $patient->first_name }} {{ $patient->middle_name ? $patient->middle_name[0] . '.' : '' }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>No patients found. Please add a patient first.</option>
                        @endif
                    </select>
                    <input type="date" name="date" class="w-full border rounded px-3 py-2" required>
                    <input type="text" name="service_rendered" placeholder="Service Rendered" class="w-full border rounded px-3 py-2" required>
                    <input type="number" step="0.01" name="amount" placeholder="Amount" class="w-full border rounded px-3 py-2" required>
                    <input type="text" name="payment_method" placeholder="Paid" class="w-full border rounded px-3 py-2">
                    <input type="text" name="receipt_no" placeholder="Receipt No." class="w-full border rounded px-3 py-2">
                    <input type="number" step="0.01" name="outstanding_balance" placeholder="Outstanding Balance" class="w-full border rounded px-3 py-2">
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
                    <h3 class="font-semibold text-lg text-blue-600">Billing Details</h3>
                    <button onclick="closeModal('view-modal')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                
                <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div class="col-span-2">
                        <label class="text-gray-500 font-semibold uppercase text-xs">Patient Name</label>
                        <p id="view-patient" class="text-base text-gray-800">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Date</label>
                        <p id="view-date" class="text-gray-800">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Receipt No.</label>
                        <p id="view-receipt" class="text-gray-800">-</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-gray-500 font-semibold uppercase text-xs">Service Rendered</label>
                        <p id="view-service" class="text-gray-800">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Service Fee</label>
                        <p id="view-amount" class="text-lg font-bold text-gray-900">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Balance Due</label>
                        <p id="view-outstanding" class="text-lg font-bold text-red-600">-</p>
                    </div>
                    <div>
                        <label class="text-gray-500 font-semibold uppercase text-xs">Payment Status</label>
                        <p id="view-payment" class="text-gray-800">-</p>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <a id="view-pdf-btn" href="#" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print PDF
            </a>
                    <button type="button" onclick="closeModal('view-modal')" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 shadow">Close</button>
                </div>
                
            </div>
        </div>

        {{-- EDIT MODAL --}}
        <div id="edit-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
                <h3 class="font-semibold text-lg text-yellow-600 mb-4">Edit Billing</h3>
                <form id="edit-form" method="POST" action="" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <input type="date" name="date" id="edit-date" class="w-full border rounded px-3 py-2">
                    <input type="text" name="service_rendered" id="edit-service" class="w-full border rounded px-3 py-2">
                    <input type="number" step="0.01" name="amount" id="edit-amount" class="w-full border rounded px-3 py-2">
                    <input type="text" name="payment_method" id="edit-payment" class="w-full border rounded px-3 py-2">
                    <input type="text" name="receipt_no" id="edit-receipt" class="w-full border rounded px-3 py-2">
                    <input type="number" step="0.01" name="outstanding_balance" id="edit-outstanding" class="w-full border rounded px-3 py-2">
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
                <p class="text-sm text-gray-500 mb-6">Are you sure you want to remove this record? This action cannot be undone.</p>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center gap-3">
                        <button type="button" onclick="closeModal('delete-modal')" class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // PREP VIEW MODAL
    function prepViewModal(billing, patientName) {
        // Set Patient Name
        document.getElementById('view-patient').innerText = patientName;
        
        // Simple date formatting
        const dateObj = new Date(billing.date);
        document.getElementById('view-date').innerText = dateObj.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });
        
        // Populate basic fields
        document.getElementById('view-service').innerText = billing.service_rendered;
        document.getElementById('view-payment').innerText = billing.payment_method || 'N/A';
        document.getElementById('view-receipt').innerText = billing.receipt_no || 'N/A';

        // Currency formatting
        const formatter = new Intl.NumberFormat('en-US', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        });
        document.getElementById('view-amount').innerText = formatter.format(billing.amount);
        document.getElementById('view-outstanding').innerText = formatter.format(billing.outstanding_balance || 0);

        // UPDATE PDF LINK (A4 Size)
        // Ensure this ID matches the <a> tag added to your View Modal
        const pdfBtn = document.getElementById('view-pdf-btn');
        if (pdfBtn) {
            pdfBtn.href = `/billings/${billing.id}/pdf`;
        }

        openModal('view-modal');
    }

    // PREP EDIT MODAL
    function prepEditModal(billing) {
        const form = document.getElementById('edit-form');
        form.action = `/billings/${billing.id}`;

        // Fill form fields
        document.getElementById('edit-date').value = billing.date ? billing.date.split('T')[0] : '';
        document.getElementById('edit-service').value = billing.service_rendered;
        document.getElementById('edit-amount').value = billing.amount;
        document.getElementById('edit-payment').value = billing.payment_method || '';
        document.getElementById('edit-receipt').value = billing.receipt_no || '';
        document.getElementById('edit-outstanding').value = billing.outstanding_balance || 0;

        openModal('edit-modal');
    }

    // PREP DELETE MODAL
    function prepDeleteModal(id) {
        const form = document.getElementById('delete-form');
        form.action = `/billings/${id}`;
        openModal('delete-modal');
    }

    // Close on background click
    window.onclick = function(event) {
        if (event.target.classList.contains('bg-black/50')) {
            // This logic will hide any open modal when the backdrop is clicked
            const modals = ['add-modal', 'view-modal', 'edit-modal', 'delete-modal'];
            modals.forEach(id => {
                const modal = document.getElementById(id);
                if (modal) modal.classList.add('hidden');
            });
        }
    }
</script>
</x-app-layout>