@section('title', 'Appointments')

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="../assets/css/plugins.min.css" />
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

<style>
    [x-cloak] { display: none !important; }

    /* Calendar Grid Styling */
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background-color: #dee2e6; /* Border color */
        gap: 1px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .calendar-day {
        min-height: 120px;
        background-color: #fff;
        padding: 5px;
        transition: background-color 0.2s;
    }

    .calendar-weekday {
        background-color: #f8f9fa;
        font-weight: bold;
        text-align: center;
        padding: 10px 0;
        text-transform: uppercase;
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Appointment Pill Styles */
    .event-pill {
        font-size: 11px;
        padding: 2px 6px;
        margin-bottom: 3px;
        border-radius: 4px;
        color: white;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        display: block;
        line-height: 1.4;
        border: none;
        width: 100%;
        text-align: left;
    }

    /* Status Colors */
    .status-scheduled { background-color: #0d6efd; } /* Blue */
    .status-completed { background-color: #198754; } /* Green */
    .status-cancelled { background-color: #dc3545; } /* Red */
    .status-default { background-color: #6c757d; }   /* Gray */

    .today { background-color: #fff9db !important; }
    .today-label { 
        background: #e67e22; 
        color: white; 
        border-radius: 50%; 
        width: 22px; 
        height: 22px; 
        display: inline-block; 
        text-align: center;
        line-height: 22px;
        font-weight: bold;
    }

    .calendar-header {
        background: #1a2035;
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Appointments Management
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="appointmentManager()" x-cloak>
        
        <div class="card shadow-sm mb-4">
            <div class="calendar-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <h4 class="mb-0 fw-bold" x-text="monthYear"></h4>
                    <div class="btn-group">
                        <button class="btn btn-outline-light btn-sm" @click="prevMonth()"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn btn-outline-light btn-sm" @click="nextMonth()"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <button class="btn btn-primary" @click="openModal('create')">
                    <i class="fas fa-plus"></i> New Appointment
                </button>
            </div>

            <div class="calendar-grid">
                <template x-for="dayName in daysOfWeek" :key="dayName">
                    <div class="calendar-weekday" x-text="dayName"></div>
                </template>

                <template x-for="blank in blankDays" :key="'b'+blank">
                    <div class="calendar-day bg-light"></div>
                </template>

                <template x-for="day in daysInMonth" :key="'d'+day">
                    <div class="calendar-day" :class="isToday(day) ? 'today' : ''">
                        <div class="text-end mb-1">
                            <span :class="isToday(day) ? 'today-label' : ''" x-text="day" style="font-size: 12px;"></span>
                        </div>

                        <div class="event-container">
                            <template x-for="appt in getAppointmentsForDay(day)" :key="appt.id">
                                <button class="event-pill" 
                                     :class="getStatusClass(appt.status)"
                                     @click="openModal('view', appt.full_data)"
                                     :title="appt.patient_name + ' - ' + appt.purpose">
                                    <span class="fw-bold" x-text="appt.time"></span>
                                    <span x-text="appt.patient_name"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="card shadow-sm p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
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
                            <td class="fw-bold">{{ $appointment->patient->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            <td>{{ $appointment->purpose ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $appointment->status === 'Completed' ? 'bg-success' : ($appointment->status === 'Cancelled' ? 'bg-danger' : 'bg-primary') }}">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-icon btn-link btn-primary" @click="openModal('view', @js($record))"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-icon btn-link btn-warning" @click="openModal('edit', @js($record))"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-icon btn-link btn-danger" @click="openModal('delete', @js($record))"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
<!-- ADD/EDIT MODAL -->
        <div x-show="showModal && (mode==='create' || mode==='edit')" class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black/50" style="background: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg w-full">
                <div class="modal-content shadow-2xl">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" x-text="mode==='create' ? 'New Appointment' : 'Edit Appointment'"></h5>
                        <button type="button" class="btn-close btn-close-white" @click="closeModal()"></button>
                    </div>
                    <form :action="formAction" method="POST" class="p-4 bg-white">
                        @csrf
                        <template x-if="mode==='edit'"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Patient</label>
                                <select name="patient_id" class="form-select" x-model="form.patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="appointment_date" class="form-control" x-model="form.appointment_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Time</label>
                                <input type="time" name="appointment_time" class="form-control" x-model="form.appointment_time" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purpose</label>
                                <input type="text" name="purpose" class="form-control" x-model="form.purpose">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" x-model="form.status">
                                    <option>Scheduled</option>
                                    <option>Completed</option>
                                    <option>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer mt-3 px-0 pb-0">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary" x-text="mode==='create' ? 'Save Appointment' : 'Update Appointment'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- VIEW MODAL -->
        <div x-show="showModal && mode==='view'" class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black/50" style="background: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-md w-full">
                <div class="modal-content bg-white shadow-2xl">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Appointment Details</h5>
                        <button class="btn-close btn-close-white" @click="closeModal()"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-2"><strong>Patient:</strong> <span x-text="form.patient_name"></span></div>
                        <div class="mb-2"><strong>Date:</strong> <span x-text="form.appointment_date"></span></div>
                        <div class="mb-2"><strong>Time:</strong> <span x-text="form.appointment_time"></span></div>
                        <div class="mb-2"><strong>Purpose:</strong> <span x-text="form.purpose || 'N/A'"></span></div>
                        <div><strong>Status:</strong> <span class="badge bg-primary" x-text="form.status"></span></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" @click="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>

    
     <!-- DELETE CONFIRMATION MODAL -->

<div x-show="showModal && mode==='delete'" class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black/50" style="background: rgba(0,0,0,0.5)">
    <div class="modal-dialog modal-md w-full">
        <div class="modal-content bg-white shadow-2xl">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button class="btn-close btn-close-white" @click="closeModal()"></button>
            </div>
            <form :action="formAction" method="POST" class="p-4">
                @csrf
                @method('DELETE')
                <p>Are you sure you want to delete the appointment for <strong x-text="form.patient_name"></strong>?</p>
                <p class="text-muted small">This action cannot be undone.</p>
                
                <div class="modal-footer mt-3 px-0 pb-0">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
   <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('appointmentManager', () => ({
            // ==========================================
            // 1. CALENDAR & UI STATE
            // ==========================================
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            daysInMonth: [],
            blankDays: [],
            
            showModal: false,
            mode: 'create', // Modes: 'create', 'edit', 'view', 'delete'
            
            // This object holds the data for whatever appointment is being interacted with
            form: {
                id: '',
                patient_id: '',
                patient_name: '',
                appointment_date: '',
                appointment_time: '',
                purpose: '',
                status: 'Scheduled'
            },

            // ==========================================
            // 2. DATA MAPPING (LARAVEL TO JS)
            // ==========================================
            // We map the Eloquent collection to a clean JS array
            appointments: @js($appointments->map(fn($a) => [
                'id' => $a->id,
                'date' => \Carbon\Carbon::parse($a->appointment_date)->format('Y-m-d'),
                'time' => \Carbon\Carbon::parse($a->appointment_time)->format('h:i A'),
                'patient_name' => $a->patient->first_name, 
                'status' => $a->status,
                'purpose' => $a->purpose,
                'full_record' => [
                    'id' => $a->id,
                    'patient_id' => $a->patient_id,
                    'patient_name' => $a->patient->full_name,
                    'appointment_date' => \Carbon\Carbon::parse($a->appointment_date)->format('Y-m-d'),
                    'appointment_time' => \Carbon\Carbon::parse($a->appointment_time)->format('H:i'),
                    'purpose' => $a->purpose,
                    'status' => $a->status
                ]
            ])),

            // ==========================================
            // 3. INITIALIZATION
            // ==========================================
            init() {
                this.calculateDays();
            },

            // ==========================================
            // 4. CALENDAR ENGINE
            // ==========================================
            calculateDays() {
                const firstDay = new Date(this.year, this.month, 1).getDay();
                const totalDays = new Date(this.year, this.month + 1, 0).getDate();
                
                // Slots for the previous month's trailing days
                this.blankDays = Array.from({ length: firstDay }, (_, i) => i);
                // Days for the current month
                this.daysInMonth = Array.from({ length: totalDays }, (_, i) => i + 1);
            },

            get monthYear() {
                return new Date(this.year, this.month).toLocaleDateString('en-US', { 
                    month: 'long', 
                    year: 'numeric' 
                });
            },

            getAppointmentsForDay(day) {
                // Matches grid day (e.g. 5) to DB format (e.g. 2024-05-05)
                const dateStr = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                return this.appointments.filter(a => a.date === dateStr);
            },

            getStatusClass(status) {
                const s = status ? status.toLowerCase() : 'scheduled';
                return `status-${s}`; // Returns status-scheduled, status-completed, etc.
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
                return day === today.getDate() && 
                       this.month === today.getMonth() && 
                       this.year === today.getFullYear();
            },

            // ==========================================
            // 5. MODAL & FORM LOGIC
            // ==========================================
            
            // This computed property handles the dynamic URL for Create, Update, and Delete
           get formAction() {
    if (this.mode === 'create') {
        return "{{ route('appointments.store') }}";
    }
    // Ensure we have an ID before trying to build the URL for Edit/Delete
    return this.form.id ? "{{ url('appointments') }}/" + this.form.id : '#';
},

            openModal(mode, data = null) {
                this.mode = mode;
                if (data) {
                    // Populate the form object with passed appointment data
                    // If the data came from a pill, it uses 'full_record'. If from table, it uses '$record'
                    const appointmentData = data.full_record ? data.full_record : data;
                    this.form = { ...this.form, ...appointmentData };
                } else {
                    this.resetForm();
                }
                this.showModal = true;
            },

            closeModal() {
                this.showModal = false;
                this.resetForm();
            },

            resetForm() {
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
</script>
</x-app-layout>