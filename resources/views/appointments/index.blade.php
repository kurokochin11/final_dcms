{{-- resources/views/appointments/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Appointments
            </h2>

            <button onclick="openAppointmentModal('create')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                New Appointment
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-200 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border px-4 py-2">#</th>
                                <th class="border px-4 py-2">Patient</th>
                                <th class="border px-4 py-2">Date &amp; Time</th>
                                <th class="border px-4 py-2">Status</th>
                                <th class="border px-4 py-2">Notes</th>
                                <th class="border px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                                <tr class="text-gray-800 dark:text-gray-200">
                                    <td class="border px-4 py-2">{{ $loop->iteration + ($appointments->currentPage()-1) * $appointments->perPage() }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->patient->name ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->status }}</td>
                                    <td class="border px-4 py-2">{{ Str::limit($appointment->notes, 50) }}</td>
                                    <td class="border px-4 py-2 flex gap-2">
                                        <button onclick="openAppointmentModal('edit', {{ $appointment->id }})" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>

                                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Delete this appointment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border px-4 py-2 text-center text-gray-500">No appointments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $appointments->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl p-6 relative">
            <button onclick="closeAppointmentModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✕</button>
            <h2 id="modalTitle" class="text-2xl font-semibold mb-4">New Appointment</h2>

            <form id="appointmentForm" method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="appointment_id" id="appointment_id">

                <div class="mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" id="patient_id" class="w-full border rounded px-2 py-1" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Appointment Date &amp; Time</label>
                    {{-- datetime-local expects format YYYY-MM-DDTHH:MM --}}
                    <input type="datetime-local" name="appointment_date" id="appointment_date" class="w-full border rounded px-2 py-1" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Status</label>
                    <select name="status" id="status" class="w-full border rounded px-2 py-1">
                        <option value="Scheduled">Scheduled</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAppointmentModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // open modal for create or edit
    function openAppointmentModal(mode, id = null) {
        const modal = document.getElementById('appointmentModal');
        const form = document.getElementById('appointmentForm');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');

        // reset form
        form.reset();
        document.getElementById('appointment_id').value = '';

        // default action = store (create)
        form.action = "{{ route('appointments.store') }}";
        // remove potential method spoof input if present
        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        // default status visible
        document.getElementById('status').value = 'Scheduled';

        if (mode === 'edit' && id) {
            title.textContent = 'Edit Appointment';
            // fetch data for appointment (controller edit endpoint must exist)
            fetch(`/appointments/${id}/edit`)
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch appointment');
                    return res.json();
                })
                .then(data => {
                    document.getElementById('appointment_id').value = data.id;
                    document.getElementById('patient_id').value = data.patient_id;
                    // fill datetime-local value expects YYYY-MM-DDTHH:MM
                    document.getElementById('appointment_date').value = data.appointment_date;
                    document.getElementById('status').value = data.status ?? 'Scheduled';
                    document.getElementById('notes').value = data.notes ?? '';

                    // change form action to update route and add _method PUT
                    form.action = `/appointments/${id}`;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                })
                .catch(err => {
                    alert('Could not load appointment for editing.');
                    console.error(err);
                });
        } else {
            title.textContent = 'New Appointment';
        }

        modal.classList.remove('hidden');
    }

    function closeAppointmentModal() {
        document.getElementById('appointmentModal').classList.add('hidden');
    }
    </script>
</x-app-layout>
