<!-- ////////////////// CSS /////////////////////////////////////////////////// -->

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />


<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="card card-stats card-round">
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
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card card-stats card-round">
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
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-user-clock"></i></div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Today's Appointments</p>
                            <h4 class="card-title">18k</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body text-center">
                <p class="card-category mb-1 text-truncate">Radiographs</p>
                <h4 class="card-title"><i class="fas fa-x-ray text-primary"></i>{{ $radiographs }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body text-center">
                <p class="card-category mb-1 text-truncate">Extra Oral</p>
                <h4 class="card-title"><i class="fas fa-smile text-info"></i>{{ $extraoralExaminations }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body text-center">
                <p class="card-category mb-1 text-truncate">Intra Oral</p>
                <h4 class="card-title"><i class="fas fa-tooth text-success"></i>{{ $intraoralExaminations }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body text-center">
                <p class="card-category mb-1 text-truncate">Total Medical History</p>
                <h4 class="card-title"><i class="fas fa-file-medical text-danger"></i>{{ $medicalhistory }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body text-center">
                <p class="card-category mb-1 text-truncate">Total Checkups</p>
                <h4 class="card-title"><i class="fas fa-file-medical text-success"></i>{{ $checkup }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body text-center">
                <p class="card-category mb-1 text-truncate">Diagnosis</p>
                <h4 class="card-title"><i class="fas fa-notes-medical text-warning"></i> {{ $diagnoses }}</h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card card-stats card-round">
            <div class="card-body text-center">
                <p class="card-category mb-1 text-truncate">Tx Plans</p>
                <h4 class="card-title"><i class="fas fa-clipboard-list text-secondary"></i>{{ $treatmentPlans }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8"> 
        <div class="card card-round">
            <div class="card-header"><div class="card-title">Clinical Activity Trends</div></div>
            <div class="card-body">
                <div class="chart-container" style="min-height: 375px">
                    <canvas id="statisticsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-primary card-round">
            <div class="card-header">
                <div class="card-title">Daily Summary</div>
                <p class="card-category">Feb 24 - Mar 02</p>
            </div>
            <div class="card-body pb-0">
                <h2 class="mb-2">Activity +15%</h2>
                <div class="pull-in">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>