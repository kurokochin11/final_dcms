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

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Patient</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Service</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-left">Payment</th>
                        <th class="px-4 py-3 text-left">Receipt</th>
                        <th class="px-4 py-3 text-right">Outstanding</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($billings as $billing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $billing->patient ? $billing->patient->last_name . ', ' . $billing->patient->first_name . ($billing->patient->middle_name ? ' ' . $billing->patient->middle_name[0] . '.' : '')  : 'N/A'}}
                            </td>
                            <td class="px-4 py-3">{{ $billing->date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">{{ $billing->service_rendered }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ number_format($billing->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $billing->payment_method ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $billing->receipt_no ?? '-' }}</td>
                            <td class="px-4 py-3 text-right text-red-600">{{ number_format($billing->outstanding_balance ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-center space-x-1">
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
                        {{-- FIXED: Added check for patients data --}}
                        @if(isset($patients) && count($patients) > 0)
                            @foreach($patients as $patient)
                               <option value="{{ $patient->id }}">
    {{ $patient->last_name }}, 
    {{ $patient->first_name }}
    {{ $patient->middle_name ? $patient->middle_name[0] . '.' : '' }}
</option>

                            @endforeach
                        @else
                            <option value="" disabled>No patients found. Please add a patient first.</option>
                        @endif
                    </select>
                    <input type="date" name="date" class="w-full border rounded px-3 py-2" required>
                    <input type="text" name="service_rendered" placeholder="Service Rendered" class="w-full border rounded px-3 py-2" required>
                    <input type="number" step="0.01" name="amount" placeholder="Amount" class="w-full border rounded px-3 py-2" required>
                    <input type="text" name="payment_method" placeholder="Payment Method" class="w-full border rounded px-3 py-2">
                    <input type="text" name="receipt_no" placeholder="Receipt No." class="w-full border rounded px-3 py-2">
                    <input type="number" step="0.01" name="outstanding_balance" placeholder="Outstanding Balance" class="w-full border rounded px-3 py-2">
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="closeModal('add-modal')" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
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

        function prepEditModal(billing) {
            const form = document.getElementById('edit-form');
            form.action = `/billings/${billing.id}`;

            document.getElementById('edit-date').value = billing.date ? billing.date.split('T')[0] : '';
            document.getElementById('edit-service').value = billing.service_rendered;
            document.getElementById('edit-amount').value = billing.amount;
            document.getElementById('edit-payment').value = billing.payment_method || '';
            document.getElementById('edit-receipt').value = billing.receipt_no || '';
            document.getElementById('edit-outstanding').value = billing.outstanding_balance || 0;

            openModal('edit-modal');
        }

        function prepDeleteModal(id) {
            const form = document.getElementById('delete-form');
            form.action = `/billings/${id}`;
            openModal('delete-modal');
        }

        // Close on background click
        window.onclick = function(event) {
            if (event.target.classList.contains('bg-black/50')) {
                event.target.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>