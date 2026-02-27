<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\MedicalFormController;
use App\Http\Controllers\CheckupFormController;
use App\Http\Controllers\CheckupAnswerController;
use App\Http\Controllers\ExtraoralExaminationController;
use App\Http\Controllers\IntraoralExaminationController;
use App\Http\Controllers\RadiographController;
use App\Http\Controllers\TreatmentPlanController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
  Route::get('/dashboard', function () {
        
        // 1. Gather the "ingredients" (Data)
        $totalPatients = \App\Models\Patient::count();
        $totalAppointments = \App\Models\Appointment::count();
        $todayAppointments = \App\Models\Appointment::whereDate('appointment_date', today())->count();
        $diagnoses = \App\Models\Diagnosis::count();
        $treatmentPlans = \App\Models\TreatmentPlan::count();
        $radiographs = \App\Models\Radiograph::count();
        $extraoralExaminations = \App\Models\ExtraoralExamination::count();
        $intraoralExaminations = \App\Models\IntraoralExamination::count();
        $medicalhistory = \App\Models\MedicalSession::count();
        $checkup = \App\Models\CheckupSession::count();
        $todayScheduledAppointments = \App\Models\Appointment::whereDate('appointment_date', today())->with('patient')->get();
        // 2. Pass them to the view
        return view('dashboard', compact(
            'totalPatients', 
            'totalAppointments', 
            'todayScheduledAppointments',
            'diagnoses', 
            'treatmentPlans',
            'radiographs',
            'extraoralExaminations',
            'intraoralExaminations',
            'medicalhistory',
            'checkup'
        ));
        
    })->name('dashboard');


    //patient routes
     Route::resource('patients', PatientController::class);

//medical history routes
    Route::get('/medical-history/{patient}', [MedicalFormController::class, 'index'])
    ->name('medical-history.index');

Route::post('/medical-history/{patient}', [MedicalHistoryController::class, 'store'])
    ->name('medical-history.store');

     // View all submitted answers for medical history
    Route::get('/answers', [MedicalHistoryController::class, 'answersIndex'])
        ->name('medical-history.answer_index');

        // Download medical history as PDF
 Route::get('/medical-history/{patient}/pdf', [MedicalHistoryController::class, 'downloadPdf'])->name('medical-history.download-pdf');

        // Edit existing medical history answers
      Route::put( '/medical-history/session/{session}',[MedicalHistoryController::class, 'updateSession'])->name('medical-history.update-session');

        
    //check-up form routes
    Route::get('/check-up/{patient}', [CheckupAnswerController::class, 'index'])
    ->name('check-up.checkup_index');

Route::get('/checkup/{patient}/pdf',
    [CheckupAnswerController::class, 'downloadPdf']
)->name('checkup.download-pdf');

Route::post('/check-up/{patient}', [CheckupAnswerController::class, 'store'])
    ->name('check-up.store');

Route::get('/check-up-answers', [CheckupAnswerController::class, 'checkup_answersIndex'])
    ->name('check-up.checkup_answer_index');

Route::get('/check-up/{patient}/edit', [CheckupAnswerController::class, 'edit'])
    ->name('check-up.edit');

Route::put('/check-up/session/{session}', [CheckupAnswerController::class, 'updateSession'])->name('check-up.session.update');

    //extraoral examination routes "A"
Route::get('/extraoral-examinations', [ExtraoralExaminationController::class, 'index'])
    ->name('oral_examination.index_extraoral');

Route::post('/extraoral-examinations', [ExtraoralExaminationController::class, 'store'])
    ->name('extraoral_examinations.store');

Route::put('/extraoral-examinations/{extraoral_examination}', [ExtraoralExaminationController::class, 'update'])
    ->name('extraoral_examinations.update');
Route::get('/extraoral-examinations/{extraoral_examination}', [ExtraoralExaminationController::class, 'show'])->name('extraoral-examinations.show');

Route::delete('/extraoral-examinations/{extraoral_examination}', [ExtraoralExaminationController::class, 'destroy'])
    ->name('extraoral_examinations.destroy');

Route::get('/extraoral-examinations/{extraoral_examination}/pdf', [ExtraoralExaminationController::class, 'downloadPdf'])->name('extraoral_examinations.pdf');

    
//intraoral examination routes "B"
 Route::prefix('oral_examination')->name('oral_examination.')->group(function () {
    Route::get('/index_intraoral', [IntraoralExaminationController::class, 'index'])->name('index_intraoral');
    Route::post('/store', [IntraoralExaminationController::class, 'store'])->name('store');
    Route::get('/{intraoral}/edit', [IntraoralExaminationController::class, 'edit'])->name('edit');
    Route::put('/{intraoral}', [IntraoralExaminationController::class, 'update'])->name('update');
    Route::delete('/{intraoral}', [IntraoralExaminationController::class, 'destroy'])->name('destroy');
Route::get('/{intraoral}/view', [IntraoralExaminationController::class, 'view'])->name('intraoral.view');
  Route::get('/{intraoral}/pdf', [IntraoralExaminationController::class, 'downloadPdf'])->name('download-pdf');

});
 //radiograph routes
 Route::get('/radiographs', [RadiographController::class, 'index'])->name('radiographs.index');
Route::post('/radiographs', [RadiographController::class, 'store'])->name('radiographs.store');
Route::put('/radiographs/{radiograph}', [RadiographController::class, 'update'])->name('radiographs.update');
Route::delete('/radiographs/{radiograph}', [RadiographController::class, 'destroy'])->name('radiographs.destroy');
Route::get('/radiographs/{radiograph}/download-pdf', [RadiographController::class, 'downloadPdf'])->name('radiographs.download-pdf');


// Treatment Plan routes

    Route::get('/treatment-plans', [TreatmentPlanController::class, 'index'])->name('treatment-plans.index');
    Route::post('/treatment-plans', [TreatmentPlanController::class, 'store'])->name('treatment-plans.store');
    Route::get('/treatment-plans/{treatmentPlan}/edit', [TreatmentPlanController::class, 'edit'])->name('treatment-plans.edit');
    Route::put('/treatment-plans/{treatmentPlan}', [TreatmentPlanController::class, 'update'])->name('treatment-plans.update');
    Route::delete('/treatment-plans/{treatmentPlan}', [TreatmentPlanController::class, 'destroy'])->name('treatment-plans.destroy');
Route::get('/treatment-plans/{treatmentPlan}/pdf',[TreatmentPlanController::class, 'downloadPdf'])->name('treatment-plans.download-pdf');

    //Appointment route
  Route::prefix('appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/sampleCalendar', [AppointmentController::class, 'sampleCalendar'])->name('appointments.sampleCalendar');
});

// Diagnosis routes
    Route::get('/diagnoses', [DiagnosisController::class, 'index'])->name('diagnoses.index');
    Route::post('/diagnoses', [DiagnosisController::class, 'store'])->name('diagnoses.store');
    Route::get('/diagnoses/{diagnosis}', [DiagnosisController::class, 'show'])->name('diagnoses.show'); // Optional, for AJAX
    Route::put('/diagnoses/{diagnosis}', [DiagnosisController::class, 'update'])->name('diagnoses.update');
    Route::delete('/diagnoses/{diagnosis}', [DiagnosisController::class, 'destroy'])->name('diagnoses.destroy');
    Route::get('/diagnoses/{diagnosis}/download-pdf', [DiagnosisController::class, 'downloadPdf']);


    // Billing routes
Route::get('/billings', [BillingController::class, 'index'])->name('billings.index');
Route::post('/billings', [BillingController::class, 'store'])->name('billings.store');
Route::put('/billings/{billing}', [BillingController::class, 'update'])->name('billings.update');
Route::delete('/billings/{billing}', [BillingController::class, 'destroy'])->name('billings.destroy');


});


