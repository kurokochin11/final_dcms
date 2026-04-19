<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Radiograph;
use App\Models\ExtraoralExamination;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\TreatmentPlan;
use App\Models\IntraoralExamination;
use App\Models\MedicalSession;
use\App\Models\CheckupSession;


Route::get('/dashboard', function () {


    $totalPatients = Patient::count();
    $radiographs = Radiograph::count();
    $totalAppointments = Appointment::count();
   $todayAppointments = Appointment::whereDate('appointment_date', today())->count();

    $diagnoses = Diagnosis::count();
    $treatmentPlans = TreatmentPlan::count();
    $extraoralExaminations = ExtraoralExamination::count();
    $intraoralExaminations = IntraoralExamination::count();
    $medicalhistory = MedicalSession::count();
    $checkup = CheckupSession::count();
    $billings = Billing::count();
    $treatments = TreatmentRecord::count();



    return view('dashboard', compact(
        'totalPatients',
        'totalAppointments', 
        'todayAppointments',
        'diagnoses',
        'treatmentPlans',
        'radiographs',
        'extraoralExaminations',
        'intraoralExaminations',
        'medicalhistory',
        'checkup',
        'billings',
        'treatments'
    ));

})->middleware(['auth'])->name('dashboard');
