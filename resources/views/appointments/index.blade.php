<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.js"></script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Appointments
            </h2>

            <button onclick="openAddAppointmentModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                New Appointment
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ✅ CALENDAR --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded p-4 mb-6">
        <div id="appointmentCalendar"></div>
    </div>

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
                                    <td class="border px-4 py-2">{{ $appointment->patient?->first_name }} {{ $appointment->patient?->middle_name }} {{ $appointment->patient?->last_name }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->status }}</td>
                                    <td class="border px-4 py-2">{{ Str::limit($appointment->notes, 50) }}</td>
                                    <td class="border px-4 py-2 flex gap-2">
                                        <button onclick="openEditAppointmentModal({{ $appointment->id }})" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>

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

    {{-- ADD APPOINTMENT MODAL --}}
    <div id="addAppointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl p-6 relative">
            <button onclick="closeAddAppointmentModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✕</button>
            <h2 class="text-2xl font-semibold mb-4">New Appointment</h2>

            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" class="w-full border rounded px-2 py-1" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Appointment Date & Time</label>
                    <input type="datetime-local" name="appointment_date" class="w-full border rounded px-2 py-1" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAddAppointmentModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT APPOINTMENT MODAL --}}
    <div id="editAppointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl p-6 relative">
            <button onclick="closeEditAppointmentModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✕</button>
            <h2 class="text-2xl font-semibold mb-4">Edit Appointment</h2>

            <form id="editAppointmentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="appointment_id" id="edit_appointment_id">

                <div class="mb-4">
                    <label class="block mb-1">Patient</label>
                    <select name="patient_id" id="edit_patient_id" class="w-full border rounded px-2 py-1" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Appointment Date & Time</label>
                    <input type="datetime-local" name="appointment_date" id="edit_appointment_date" class="w-full border rounded px-2 py-1" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Status</label>
                    <select name="status" id="edit_status" class="w-full border rounded px-2 py-1">
                        <option value="Scheduled">Scheduled</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Notes</label>
                    <textarea name="notes" id="edit_notes" rows="3" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditAppointmentModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddAppointmentModal() {
            document.getElementById('addAppointmentModal').classList.remove('hidden');
        }

        function closeAddAppointmentModal() {
            document.getElementById('addAppointmentModal').classList.add('hidden');
        }

        function openEditAppointmentModal(id) {
            const modal = document.getElementById('editAppointmentModal');
            const form = document.getElementById('editAppointmentForm');

            fetch(`/appointments/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_appointment_id').value = data.id;
                    document.getElementById('edit_patient_id').value = data.patient_id;
                    document.getElementById('edit_appointment_date').value = data.appointment_date;
                    document.getElementById('edit_status').value = data.status ?? 'Scheduled';
                    document.getElementById('edit_notes').value = data.notes ?? '';
                    form.action = `/appointments/${id}`;
                    modal.classList.remove('hidden');
                })
                .catch(() => alert('Could not load appointment for editing.'));
        }

        function closeEditAppointmentModal() {
            document.getElementById('editAppointmentModal').classList.add('hidden');
        }

        //clendar js
        document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('appointmentCalendar');

    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: '/appointments/calendar',

        eventClick: function(info) {
            openEditAppointmentModal(info.event.id);
        }
    });

    calendar.render();
});
    </script>
</x-app-layout>
