@section('title', 'Appointments')

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="../assets/css/plugins.min.css" />
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

<!-- JS -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
     <script src="assets/js/kaiadmin.min.js"></script>
    <script>
$(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true
    });
});
</script>

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
<div class="bg-white border-bottom px-3 py-2 d-flex gap-4">
        <div class="d-flex align-items-center">
            <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #0d6efd;"></span>
            <span class="small fw-bold text-muted text-uppercase" style="font-size: 10px;">Scheduled</span>
        </div>
        <div class="d-flex align-items-center">
            <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #198754;"></span>
            <span class="small fw-bold text-muted text-uppercase" style="font-size: 10px;">Completed</span>
        </div>
        <div class="d-flex align-items-center">
            <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #dc3545;"></span>
            <span class="small fw-bold text-muted text-uppercase" style="font-size: 10px;">Cancelled</span>
        </div>
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
                                     @click="openModal('view', appt.full_record)"
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
               <table id="myTable"  class="table table-striped table-bordered table-hover">
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
                               
                                <button class="btn btn-icon btn-link btn-danger" @click="openModal('delete', @js($record))"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
<div x-show="showModal && (mode==='create' || mode==='edit')" 
     class="fixed inset-0 flex items-center justify-center z-50 p-4"
     :class="isSubmitting ? 'invisible' : 'bg-black/50'"
     style="background: rgba(0,0,0,0.5)"
     x-cloak>
    
    <div class="modal-dialog modal-lg w-full" x-show="!isSubmitting">
        <div class="modal-content shadow-2xl border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    <span x-text="mode==='create' ? 'New Appointment' : 'Edit Appointment'"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" @click="closeModal()"></button>
            </div>
            
            <form :action="formAction" method="POST" x-ref="editForm" class="p-4 bg-white">
                @csrf
                <template x-if="mode==='edit' || isSubmitting">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Patient</label>
                        <select name="patient_id" class="form-select shadow-none" x-model="form.patient_id" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Date</label>
                        <input type="date" name="appointment_date" class="form-control shadow-none" x-model="form.appointment_date" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Time</label>
                        <input type="time" name="appointment_time" class="form-control shadow-none" x-model="form.appointment_time" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Purpose</label>
                        <input type="text" name="purpose" class="form-control shadow-none" x-model="form.purpose" placeholder="e.g., Cleaning">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold small">Status</label>
                        <select name="status" class="form-select shadow-none" x-model="form.status">
                            <option value="Scheduled">Scheduled</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                            
                        </select>
                    </div>
                </div>

                <div class="modal-footer mt-4 px-0 pb-0 pt-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-4" @click="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <span x-text="mode==='create' ? 'Save Appointment' : 'Update Appointment'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="isSubmitting" class="fixed inset-0 z-[60] flex items-center justify-center bg-white/50 backdrop-blur-sm">
    <div class="text-center">
        <div class="spinner-border text-primary mb-2" role="status"></div>
        <p class="fw-bold text-primary">Updating Appointment...</p>
    </div>
</div>
<!-- VIEW MODAL -->
  <div x-show="showModal && mode==='view'" class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black/50" style="background: rgba(0,0,0,0.5)">
    <div class="modal-dialog modal-md w-full">
        <div class="modal-content bg-white shadow-2xl border-0">
            <div class="modal-header bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center">
                <h6 class="modal-title mb-0"><i class="fas fa-calendar-check me-2"></i>Appointment Details</h6>
                <button type="button" class="btn-close btn-close-white shadow-none" @click="closeModal()"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted small mb-1 uppercase fw-bold">Patient Name</label>
                        <p class="fs-5 fw-bold mb-0 text-dark" x-text="form.patient_name"></p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small mb-1 uppercase fw-bold">Current Status</label>
                        <div>
                            <span class="badge px-3 py-2" :class="getStatusClass(form.status)" x-text="form.status"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small mb-1 uppercase fw-bold">Purpose of Visit</label>
                        <p class="mb-0 text-dark fw-semibold" x-text="form.purpose || 'General Checkup'"></p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small mb-1 uppercase fw-bold">Appointment Date</label>
                        <p class="mb-0 text-dark"><i class="far fa-calendar me-2 text-info"></i><span x-text="form.appointment_date"></span></p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small mb-1 uppercase fw-bold">Scheduled Time</label>
                        <p class="mb-0 text-dark"><i class="far fa-clock me-2 text-info"></i><span x-text="form.appointment_time"></span></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-sm btn-secondary px-3" @click="closeModal()">
                    Close
                </button>
                
                <div class="d-flex gap-2">
                    <template x-if="form.status === 'Scheduled'">
                        <div class="d-flex gap-2 me-2 border-end pe-2">
                            <button type="button" class="btn btn-sm btn-success px-3" @click="directUpdateStatus('Completed')">
                                <i class="fa fa-check me-1"></i> Complete
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger px-3" @click="directUpdateStatus('Cancelled')">
                                <i class="fa fa-times me-1"></i> Cancel
                            </button>
                        </div>
                    </template>

<button class="btn btn-sm btn-warning text-white px-3" @click="openModal('edit', form)">
    <i class="fa fa-calendar-alt me-1"></i> Edit Details
</button>
                </div>
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
            isSubmitting: false, // ADD THIS LINE
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
            },
           directUpdateStatus(newStatus) {
    // 1. Prepare data
    this.form.status = newStatus;
    this.mode = 'edit';
    
    // 2. Set submitting to TRUE (this triggers the UI changes)
    this.isSubmitting = true;

    // 3. Submit the form
    this.$nextTick(() => {
        if (this.$refs.editForm) {
            this.$refs.editForm.submit();
        } else {
            // Fallback if ref is still missing
            const fallbackForm = document.querySelector('form[x-ref="editForm"]');
            if(fallbackForm) fallbackForm.submit();
        }
    });
}
        }));
    });
       
</script>
</x-app-layout>