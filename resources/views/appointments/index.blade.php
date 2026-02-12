@section('title', 'Appointments')

<!-- ================= CSS ================= -->
 <link rel="stylesheet" href="{{ asset('assets/css/calendar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="../assets/css/plugins.min.css" />
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

<!-- ================= JS ================= -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Appointments
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div x-data="appointments()" x-cloak>

            <!-- Header -->
            <div class="d-flex justify-content-between mb-3">
                <h3 class="text-lg font-medium"></h3>
                <button class="btn btn-primary" @click="openModal('create')">
                    <i class="fas fa-plus"></i> New Appointment
                </button>
            </div>

<!-- calendar -->
 <link rel="stylesheet" href="{{ asset('assets/css/calendar.css') }}">

<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div x-data="calendar()" x-init="init()" class="calendar-container">

        <!-- PRIMARY HEADER -->
        <div class="calendar-header d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-light btn-sm" @click="prevMonth()">Prev</button>
            <h5 x-text="monthYear"></h5>
            <button class="btn btn-outline-light btn-sm" @click="nextMonth()">Next</button>
        </div>

        <!-- WEEK DAYS -->
        <div class="d-flex">
            <template x-for="day in days" :key="day">
                <div class="calendar-weekday flex-fill" x-text="day"></div>
            </template>
        </div>

        <!-- DAYS GRID -->
        <div class="d-flex flex-wrap">
            <!-- Empty slots -->
            <template x-for="blank in blankDays" :key="'b'+blank">
                <div class="calendar-day"></div>
            </template>

            <!-- Days -->
            <template x-for="day in daysInMonth" :key="'d'+day">
                <div
                    class="calendar-day"
                    :class="[
                        isToday(day) ? 'today' : '',
                        getAppointmentClass(day)
                    ]"
                    x-text="day">
                </div>
            </template>
        </div>

    </div>
</div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        @php
                            $record = [
                                'id' => $appointment->id,
                                'patient_id' => $appointment->patient_id,
                                'patient_name' => $appointment->patient->full_name,
                                'appointment_date' => \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d'),
                                'appointment_time' => \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i'),
                                'purpose' => $appointment->purpose,
                                'status' => $appointment->status,
                            ];
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $appointment->patient->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            <td>{{ $appointment->purpose ?? '-' }}</td>
                            <td>{{ $appointment->status }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-primary btn-medium"
                                            @click="openModal('view', @js($record))">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button class="btn btn-warning btn-medium text-white"
                                            @click="openModal('edit', @js($record))">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-danger btn-medium"
                                            @click="openModal('delete', @js($record))">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

         <!-- CREATE / EDIT MODAL -->
<div x-show="showModal && (mode==='create' || mode==='edit')"
     class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-transparent"
     @click.stop>
  <div class="modal-dialog modal-dialog-centered modal-lg shadow-lg">
    <div class="modal-content bg-white border-0">

      <!-- Header -->
      <div class="modal-header px-4 py-3"
           :class="(mode==='create' || mode==='edit') ? 'bg-primary text-white' : ''">
        <h5 class="modal-title fs-5 fw-bold"
            x-text="mode==='create' ? 'New Appointment' : 'Edit Appointment'"></h5>
        <button type="button" class="btn-close text-white" @click="close"></button>
      </div>

      <!-- Body -->
      <form :action="formAction" method="POST" class="px-4 py-4">
        @csrf
        <template x-if="mode==='edit'">
          <input type="hidden" name="_method" value="PUT">
        </template>

        <div class="row g-3">
          <!-- Patient -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Patient</label>
            <select name="patient_id" class="form-select" x-model="form.patient_id" required>
              <option value="">Select Patient</option>
              @foreach($patients as $patient)
                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Date -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Date</label>
            <input type="date" name="appointment_date" class="form-control" x-model="form.appointment_date" required>
          </div>

          <!-- Time -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Time</label>
            <input type="time" name="appointment_time" class="form-control" x-model="form.appointment_time" required>
          </div>

          <!-- Purpose -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Purpose</label>
            <input type="text" name="purpose" class="form-control" x-model="form.purpose" placeholder="e.g., Checkup">
          </div>

          <!-- Status -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select" x-model="form.status">
              <option>Scheduled</option>
              <option>Completed</option>
              <option>Cancelled</option>
            </select>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer justify-content-between px-0 py-3 border-top-0">
          <button type="button" class="btn btn-black btn-sm" @click="close">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm" x-text="mode==='create' ? 'Save' : 'Update'"></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- VIEW MODAL -->
<div x-show="showModal && mode==='view'"
     class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-transparent"
     @click.stop>
  <div class="modal-dialog modal-dialog-centered modal-md shadow-lg">
    <div class="modal-content bg-white border-0">

      <!-- Header -->
      <div class="modal-header bg-primary text-white px-4 py-3">
        <h5 class="modal-title fs-5 fw-bold">Appointment Details</h5>
        <button class="btn-close text-white" @click="close"></button>
      </div>

      <div class="modal-body p-4 fs-6">
        <p><strong>Patient:</strong> <span x-text="form.patient_name"></span></p>
        <p><strong>Date:</strong> <span x-text="form.appointment_date"></span></p>
        <p><strong>Time:</strong> <span x-text="form.appointment_time"></span></p>
        <p><strong>Purpose:</strong> <span x-text="form.purpose || '-'"></span></p>
        <p><strong>Status:</strong> <span x-text="form.status"></span></p>
      </div>

      <div class="modal-footer justify-content-center border-top-0 px-0 py-3">
        <button class="btn btn-dark btn-sm" @click="close">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div x-show="showModal && mode==='delete'"
     class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-transparent"
     @click.stop>
  <div class="modal-dialog modal-dialog-centered modal-md shadow-lg">
    <div class="modal-content bg-white border-0">

      <!-- Header -->
      <div class="modal-header bg-danger text-white px-4 py-3">
        <h5 class="modal-title fs-5 fw-bold">Delete Appointment</h5>
        <button class="btn-close text-white" @click="close"></button>
      </div>

      <div class="modal-body text-center p-4 fs-6">
        Are you sure you want to delete appointment for
        <strong x-text="form.patient_name"></strong>?
      </div>

      <div class="modal-footer justify-content-center border-top-0 px-0 py-3">
        <button class="btn btn-black btn-sm me-2" @click="close">Cancel</button>
        <form :action="formAction" method="POST">
          @csrf
          <input type="hidden" name="_method" value="DELETE">
          <button class="btn btn-danger btn-sm" type="submit">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

    <!-- Alpine -->
     
    <script>
document.addEventListener('alpine:init', () => {
    
    Alpine.data('calendar', () => ({
        month: new Date().getMonth(),
        year: new Date().getFullYear(),

        days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        daysInMonth: [],
        blankDays: [],

     
    // $appointments->map(fn($a) => [
    //     'date' => \Carbon\Carbon::parse($a->appointment_date)->format('Y-m-d'),
    //     'patient_name' => $a->patient->full_name,
    //     'time' => \Carbon\Carbon::parse($a->appointment_time)->format('h:i A'),
    //     'purpose' => $a->purpose,
    //     'status' => $a->status,
    data: "sample",
 appointments: @json(
            $appointments->map(fn($a) => [
                'date' => $a->appointment_date->format('Y-m-d'),
                'patient_name' => $a->patient->first_name,
                'status' => $a->status
            
    ]),
   
),

        get monthYear() {
            return new Date(this.year, this.month)
                .toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },

        init() {
            this.calculateDays();
        },

        calculateDays() {
            const firstDay = new Date(this.year, this.month, 1).getDay();
            const totalDays = new Date(this.year, this.month + 1, 0).getDate();

            this.blankDays = Array.from({ length: firstDay }, (_, i) => i);
            this.daysInMonth = Array.from({ length: totalDays }, (_, i) => i + 1);
        },

        prevMonth() {
            if (this.month === 0) {
                this.month = 11;
                this.year--;
            } else {
                this.month--;
            }
            this.calculateDays();
        },

        nextMonth() {
            if (this.month === 11) {
                this.month = 0;
                this.year++;
            } else {
                this.month++;
            }
            this.calculateDays();
        },

        isToday(day) {
            const today = new Date();
            return (
                day === today.getDate() &&
                this.month === today.getMonth() &&
                this.year === today.getFullYear()
            );
        },

        getAppointmentClass(day) {
            const dateStr = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const appt = this.appointments.find(a => a.date === dateStr);

            if (!appt) return '';

            switch (appt.status) {
                case 'Completed': return 'appointment success';
                case 'Cancelled': return 'appointment danger';
                default: return 'appointment';
            }
        }
    }));


    Alpine.data('appointments', () => ({
        showModal: false,
        mode: 'create',
        form: {
            id: '',
            patient_id: '',
            patient_name: '',
            appointment_date: '',
            appointment_time: '',
            purpose: '',
            status: 'Scheduled'
        },

        get formAction() {
            return this.mode === 'create'
                ? "{{ route('appointments.store') }}"
                : "{{ url('appointments') }}/" + this.form.id;
        },

        openModal(mode, data = null) {
            this.mode = mode;
            this.showModal = true;
            if (data) this.form = { ...this.form, ...data };
        },

        close() {
            this.showModal = false;
            this.mode = 'create';
            this.form = {
                id: '',
                patient_id: '',
                patient_name: '',
                appointment_date: '',
                appointment_time: '',
                purpose: '',
                status: 'Scheduled'
            };
        }
    }));

});



        function appointments() {
            return {
                showModal: false,
                mode: 'create',
                form: {
                    id: '',
                    patient_id: '',
                    patient_name: '',
                    appointment_date: '',
                    appointment_time: '',
                    purpose: '',
                    status: 'Scheduled'
                },

                get formAction() {
                    if (this.mode === 'create') {
                        return "{{ route('appointments.store') }}";
                    }
                    return "{{ url('appointments') }}/" + this.form.id;
                },

                openModal(mode, data = null) {
                    this.mode = mode;
                    this.showModal = true;
                    if (data) this.form = { ...this.form, ...data };
                },

                close() {
                    this.showModal = false;
                    this.mode = 'create';
                    this.form = {
                        id: '',
                        patient_id: '',
                        patient_name: '',
                        appointment_date: '',
                        appointment_time: '',
                        purpose: '',
                        status: 'Scheduled'
                    };
                }
            }
        }
//  ADD THIS       getAppointmentsForDay(day) {
//     const dateStr = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
//     return this.appointments.filter(a => a.date === dateStr);
// },

    </script>
</x-app-layout>