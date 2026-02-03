<x-app-layout>
<div class="container mt-5" x-data="{ openAdd:false, openEditId:null, openDeleteId:null }">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">Section 10: Billing and Payments</h3>
        <button class="btn btn-primary shadow" @click="openAdd = true">
            <i class="fas fa-plus me-1"></i> Add Billing
        </button>
    </div>

    <!-- TABLE -->
    <div class="table-responsive shadow rounded">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Date</th>
                    <th>Service Rendered</th>
                    <th>Amount (PHP)</th>
                    <th>Payment Method</th>
                    <th>Receipt No.</th>
                    <th>Outstanding (PHP)</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($billings as $billing)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($billing->date)->format('M d, Y') }}</td>
                    <td>{{ $billing->service_rendered }}</td>
                    <td class="text-end">{{ number_format($billing->amount,2) }}</td>
                    <td>{{ $billing->payment_method ?? '-' }}</td>
                    <td>{{ $billing->receipt_no ?? '-' }}</td>
                    <td class="text-end">{{ number_format($billing->outstanding_balance,2) }}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm me-1 shadow-sm"
                                @click="openEditId={{ $billing->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <button class="btn btn-danger btn-sm shadow-sm"
                                @click="openDeleteId={{ $billing->id }}">
                            <i class="fas fa-trash"></i> Del
                        </button>
                    </td>
                </tr>

                <!-- EDIT MODAL -->
                <div x-show="openEditId === {{ $billing->id }}" x-cloak
                     class="fixed inset-0 flex items-center justify-center z-50 bg-black/40">
                    <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-bold text-warning">Edit Billing</h2>
                            <button @click="openEditId = null" class="text-2xl font-bold">&times;</button>
                        </div>

                        <form method="POST" action="{{ route('billings.update', $billing->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="space-y-3">
                                <input type="date" name="date" value="{{ $billing->date }}" class="w-full border p-2 rounded shadow-sm" required>
                                <input type="text" name="service_rendered" value="{{ $billing->service_rendered }}" class="w-full border p-2 rounded shadow-sm" placeholder="Service Rendered" required>
                                <input type="number" name="amount" value="{{ $billing->amount }}" class="w-full border p-2 rounded shadow-sm" placeholder="Amount" required>
                                <input type="text" name="payment_method" value="{{ $billing->payment_method }}" class="w-full border p-2 rounded shadow-sm" placeholder="Payment Method">
                                <input type="text" name="receipt_no" value="{{ $billing->receipt_no }}" class="w-full border p-2 rounded shadow-sm" placeholder="Receipt No.">
                                <input type="number" name="outstanding_balance" value="{{ $billing->outstanding_balance }}" class="w-full border p-2 rounded shadow-sm" placeholder="Outstanding Balance">
                            </div>

                            <div class="flex justify-end mt-4 gap-2">
                                <button type="button" @click="openEditId = null" class="btn btn-dark">Cancel</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- DELETE MODAL -->
                <div x-show="openDeleteId === {{ $billing->id }}" x-cloak
                     class="fixed inset-0 flex items-center justify-center z-50 bg-black/40">
                    <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
                        <h2 class="text-lg font-bold text-danger mb-4">Delete Billing</h2>
                        <p class="mb-4">Are you sure you want to delete this billing record?</p>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="openDeleteId = null" class="btn btn-dark">Cancel</button>
                            <form method="POST" action="{{ route('billings.destroy', $billing->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ADD MODAL -->
    <div x-show="openAdd" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-black/40">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-primary">Add Billing</h2>
                <button @click="openAdd = false" class="text-2xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('billings.store') }}">
                @csrf
                <div class="space-y-3">
                    <input type="date" name="date" class="w-full border p-2 rounded shadow-sm" required>
                    <input type="text" name="service_rendered" class="w-full border p-2 rounded shadow-sm" placeholder="Service Rendered" required>
                    <input type="number" name="amount" class="w-full border p-2 rounded shadow-sm" placeholder="Amount" required>
                    <input type="text" name="payment_method" class="w-full border p-2 rounded shadow-sm" placeholder="Payment Method">
                    <input type="text" name="receipt_no" class="w-full border p-2 rounded shadow-sm" placeholder="Receipt No.">
                    <input type="number" name="outstanding_balance" class="w-full border p-2 rounded shadow-sm" placeholder="Outstanding Balance">
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <button type="button" @click="openAdd = false" class="btn btn-dark">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-app-layout>
