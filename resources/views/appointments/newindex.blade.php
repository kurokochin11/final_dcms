<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Appointments Calendar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                
                <!-- Calendar -->
                <div id="calendar"></div>

                <!-- Modal -->
                <div id="appointmentModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-4 rounded w-96">
                        <h2 class="text-lg font-bold mb-4" id="modalTitle"></h2>

                        <form id="appointmentForm">
                            <input type="hidden" name="id" id="apptId">

                            <div class="mb-2">
                                <label>Patient Name</label>
                                <input type="text" id="patientName" name="patient_name" class="w-full border p-1">
                            </div>

                            <div class="mb-2">
                                <label>Date</label>
                                <input type="date" id="apptDate" name="appointment_date" class="w-full border p-1">
                            </div>

                            <div class="mb-2">
                                <label>Time</label>
                                <input type="time" id="apptTime" name="appointment_time" class="w-full border p-1">
                            </div>

                            <div class="mb-2">
                                <label>Status</label>
                                <select id="apptStatus" name="status" class="w-full border p-1">
                                    <option>Scheduled</option>
                                    <option>Completed</option>
                                    <option>Cancelled</option>
                                </select>
                            </div>

                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" id="closeModal" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        import { Calendar } from '@fullcalendar/core';
        import dayGridPlugin from '@fullcalendar/daygrid';
        import timeGridPlugin from '@fullcalendar/timegrid';
        import interactionPlugin from '@fullcalendar/interaction';

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            // Modal elements
            const modal = document.getElementById('appointmentModal');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('appointmentForm');
            const apptId = document.getElementById('apptId');
            const patientName = document.getElementById('patientName');
            const apptDate = document.getElementById('apptDate');
            const apptTime = document.getElementById('apptTime');
            const apptStatus = document.getElementById('apptStatus');
            const closeModal = document.getElementById('closeModal');

            const calendar = new Calendar(calendarEl, {
                plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '/appointments/sampleCalendar',

                dateClick: function(info) {
                    openModal('create', { appointment_date: info.dateStr });
                },

                eventClick: function(info) {
                    const event = info.event;
                    openModal('edit', {
                        id: event.id,
                        patient_name: event.title.split(' (')[0],
                        appointment_date: event.startStr.split('T')[0],
                        appointment_time: event.startStr.split('T')[1] || '12:00',
                        status: event.title.match(/\((.*?)\)/)[1]
                    });
                }
            });

            calendar.render();

            // Open modal function
            function openModal(mode, data) {
                modalTitle.textContent = mode === 'create' ? 'Create Appointment' : 'Edit Appointment';
                apptId.value = data.id || '';
                patientName.value = data.patient_name || '';
                apptDate.value = data.appointment_date || '';
                apptTime.value = data.appointment_time || '';
                apptStatus.value = data.status || 'Scheduled';

                modal.classList.remove('hidden');
            }

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // Form submit
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const id = apptId.value;
                const url = id ? `/appointments/${id}` : '/appointments';
                const method = id ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        patient_name: patientName.value,
                        appointment_date: apptDate.value,
                        appointment_time: apptTime.value,
                        status: apptStatus.value
                    })
                })
                .then(res => res.json())
                .then(() => {
                    modal.classList.add('hidden');
                    calendar.refetchEvents();
                });
            });
        });
    </script>
    @endpush

</x-app-layout>
