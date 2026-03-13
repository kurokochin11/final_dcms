<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

<style>
    .card-clickable {
        transition: all 0.3s ease;
        text-decoration: none !important;
        display: block;
    }
    .card-clickable:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .card-category { font-weight: 600; }
</style>

<div class="page-inner">
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <a href="{{ route('patients.index') }}" class="card card-stats card-round card-clickable">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-users"></i></div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Patients</p>
                                <h4 class="card-title">{{ number_format($totalPatients) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-6">
            <a href="{{ route('appointments.index') }}" class="card card-stats card-round card-clickable">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small"><i class="fas fa-calendar-check"></i></div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Appointments</p>
                                <h4 class="card-title">{{ number_format($totalAppointments) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-6 col-md-2">
            <a href="{{ route('radiographs.index') }}" class="card card-stats card-round card-clickable text-center">
                <div class="card-body">
                    <p class="card-category mb-1 text-truncate">Radiographs</p>
                    <h4 class="card-title"><i class="fas fa-x-ray text-primary"></i> {{ $radiographs }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{ route('oral_examination.index_extraoral') }}" class="card card-stats card-round card-clickable text-center">
                <div class="card-body">
                    <p class="card-category mb-1 text-truncate">Extra Oral</p>
                    <h4 class="card-title"><i class="fas fa-smile text-info"></i> {{ $extraoralExaminations }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{ route('oral_examination.index_intraoral') }}" class="card card-stats card-round card-clickable text-center">
                <div class="card-body">
                    <p class="card-category mb-1 text-truncate">Intra Oral</p>
                    <h4 class="card-title"><i class="fas fa-tooth text-success"></i> {{ $intraoralExaminations }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{ route('medical-history.answer_index') }}" class="card card-stats card-round card-clickable text-center">
                <div class="card-body">
                    <p class="card-category mb-1 text-truncate">Med History</p>
                    <h4 class="card-title"><i class="fas fa-file-medical text-danger"></i> {{ $medicalhistory }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{ route('check-up.checkup_answer_index') }}" class="card card-stats card-round card-clickable text-center">
                <div class="card-body">
                    <p class="card-category mb-1 text-truncate">Checkups</p>
                    <h4 class="card-title"><i class="fas fa-stethoscope text-success"></i> {{ $checkup }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{ route('diagnoses.index') }}" class="card card-stats card-round card-clickable text-center">
                <div class="card-body">
                    <p class="card-category mb-1 text-truncate">Diagnosis</p>
                    <h4 class="card-title"><i class="fas fa-notes-medical text-warning"></i> {{ $diagnoses }}</h4>
                </div>
            </a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-6 col-md-2">
            <a href="{{ route('treatment-plans.index') }}" class="card card-stats card-round card-clickable text-center">
                <div class="card-body">
                    <p class="card-category mb-1 text-truncate">Tx Plans</p>
                    <h4 class="card-title"><i class="fas fa-clipboard-list text-secondary"></i> {{ $treatmentPlans }}</h4>
                </div>
            </a>
        </div>
    </div>
</div>