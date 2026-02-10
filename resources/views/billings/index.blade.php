<x-app-layout>
<div class="max-w-7xl mx-auto px-6 py-8"
     x-data="{
        openAdd:false,
        openEdit:false,
        openDelete:false,
        selectedBilling:{}
     }">

    <!-- HEADER -->
     <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-indigo-600">
                Section 10: Billing and Payments
            </h2>

            <button
                @click="openAdd = true"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Add Billing
            </button>
        </div>
    </x-slot>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
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
                @foreach($billings as $billing)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($billing->date)->format('M d, Y') }}</td>
                    <td class="px-4 py-3">{{ $billing->service_rendered }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format($billing->amount,2) }}</td>
                    <td class="px-4 py-3">{{ $billing->payment_method ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $billing->receipt_no ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format($billing->outstanding_balance,2) }}</td>

                    <td class="px-4 py-3 text-center space-x-1">
                        <!-- EDIT -->
                        <button
                            @click="
                                openEdit=true;
                                selectedBilling={{ $billing }};
                            "
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- DELETE -->
                        <button
                            @click="
                                openDelete=true;
                                selectedBilling={{ $billing }};
                            "
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ADD MODAL -->
    <div x-show="openAdd" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg text-indigo-600">Add Billing</h3>
                <button @click="openAdd=false" class="text-2xl">&times;</button>
            </div>

            <form method="POST" action="{{ route('billings.store') }}" class="space-y-3">
                @csrf
                <input type="date" name="date" class="w-full border rounded px-3 py-2">
                <input type="text" name="service_rendered" placeholder="Service Rendered" class="w-full border rounded px-3 py-2">
                <input type="number" name="amount" placeholder="Amount" class="w-full border rounded px-3 py-2">
                <input type="text" name="payment_method" placeholder="Payment Method" class="w-full border rounded px-3 py-2">
                <input type="text" name="receipt_no" placeholder="Receipt No." class="w-full border rounded px-3 py-2">
                <input type="number" name="outstanding_balance" placeholder="Outstanding Balance" class="w-full border rounded px-3 py-2">

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="openAdd=false"
                        class="bg-gray-700 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-show="openEdit" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg text-yellow-600">Edit Billing</h3>
                <button @click="openEdit=false" class="text-2xl">&times;</button>
            </div>

            <form method="POST"
                  :action="`/billings/${selectedBilling.id}`"
                  class="space-y-3">
                @csrf
                @method('PUT')

                <input type="date" name="date" :value="selectedBilling.date" class="w-full border rounded px-3 py-2">
                <input type="text" name="service_rendered" :value="selectedBilling.service_rendered" class="w-full border rounded px-3 py-2">
                <input type="number" name="amount" :value="selectedBilling.amount" class="w-full border rounded px-3 py-2">
                <input type="text" name="payment_method" :value="selectedBilling.payment_method" class="w-full border rounded px-3 py-2">
                <input type="text" name="receipt_no" :value="selectedBilling.receipt_no" class="w-full border rounded px-3 py-2">
                <input type="number" name="outstanding_balance" :value="selectedBilling.outstanding_balance" class="w-full border rounded px-3 py-2">

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="openEdit=false"
                        class="bg-gray-700 text-white px-4 py-2 rounded">
                        Cancel
                    </button>
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div x-show="openDelete" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-red-600 mb-2">Delete Billing</h3>
            <p class="text-gray-600 mb-6">This action cannot be undone.</p>

            <div class="flex justify-end gap-2">
                <button @click="openDelete=false"
                    class="bg-gray-700 text-white px-4 py-2 rounded">
                    Cancel
                </button>

                <form method="POST" :action="`/billings/${selectedBilling.id}`">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-600 text-white px-4 py-2 rounded">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
</x-app-layout>
