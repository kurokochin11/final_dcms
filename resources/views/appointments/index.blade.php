<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Appointments</h2>
    </x-slot>

    <div class="p-6" x-data="{ openAdd: false, openView: null, openEdit: null, openDelete: null }">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Appointment Button -->
        <button @click="openAdd = true"
                class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            + New Appointment
        </button>

        <!-- Appointment Table -->
        <table class="min-w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-2 py-1">Patient</th>
                    <th class="border px-2 py-1">Date</th>
                    <th class="border px-2 py-1">Status</th>
                    <th class="border px-2 py-1">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $a)
                <tr class="hover:bg-gray-50">
                    <td class="border px-2 py-1">{{ $a->patient->full_name }}</td>
                    <td class="border px-2 py-1">{{ $a->appointment_date }}</td>
                    <td class="border px-2 py-1">{{ $a->status }}</td>
                    <td class="border px-2 py-1 space-x-1">
                        <button @click="openView = {{ $a->id }}"
                            class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                        <button @click="openEdit = {{ $a->id }}"
                            class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button @click="openDelete = {{ $a->id }}"
                            class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>
                    </td>
                </tr>

                <!-- View Modal -->
                <div x-show="openView === {{ $a->id }}" x-cloak x-transition
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">Appointment Details</h2>
                        <div class="space-y-2">
                            <p><strong>Patient:</strong> {{ $a->patient->full_name }}</p>
                            <p><strong>Date:</strong> {{ $a->appointment_date }}</p>
                            <p><strong>Status:</strong> {{ $a->status }}</p>
                            <p><strong>Notes:</strong> {{ $a->notes }}</p>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button @click="openView = null" 
                                class="px-4 py-2 bg-gray-500 text-white rounded">Close</button>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div x-show="openEdit === {{ $a->id }}" x-cloak x-transition
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">Edit Appointment</h2>
                        <form action="{{ route('appointments.update',$a) }}" method="POST" class="space-y-4">
                            @csrf @method('PUT')

                            <label class="block">Date & Time</label>
                            <input type="datetime-local" name="appointment_date" 
                                value="{{ $a->appointment_date }}" class="w-full border p-2 rounded">

                            <label class="block">Status</label>
                            <select name="status" class="w-full border p-2 rounded">
                                <option {{ $a->status=='Scheduled'?'selected':'' }}>Scheduled</option>
                                <option {{ $a->status=='Completed'?'selected':'' }}>Completed</option>
                                <option {{ $a->status=='Cancelled'?'selected':'' }}>Cancelled</option>
                            </select>

                            <label class="block">Notes</label>
                            <textarea name="notes" class="w-full border p-2 rounded">{{ $a->notes }}</textarea>

                            <div class="flex justify-end space-x-2 mt-4">
                                <button type="button" @click="openEdit = null" 
                                    class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                                <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div x-show="openDelete === {{ $a->id }}" x-cloak x-transition
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 text-center">
                        <h2 class="text-lg font-semibold mb-4">Confirm Deletion</h2>
                        <p class="mb-4">Delete appointment for <strong>{{ $a->patient->full_name }}</strong>?</p>
                        <form action="{{ route('appointments.destroy',$a) }}" method="POST" class="flex justify-center space-x-2">
                            @csrf @method('DELETE')
                            <button type="button" @click="openDelete = null" 
                                class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                            <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">{{ $appointments->links() }}</div>

        <!-- Add Modal -->
        <div x-show="openAdd" x-cloak x-transition
             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Add New Appointment</h2>
                <form action="{{ route('appointments.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <label class="block">Patient</label>
                    <select name="patient_id" class="w-full border p-2 rounded">
                        @foreach(App\Models\Patient::all() as $p)
                            <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                        @endforeach
                    </select>

                    <label class="block">Date & Time</label>
                    <input type="datetime-local" name="appointment_date" class="w-full border p-2 rounded">

                    <label class="block">Notes</label>
                    <textarea name="notes" class="w-full border p-2 rounded"></textarea>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="openAdd = false" 
                            class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Add Modal -->
    </div>
</x-app-layout>
