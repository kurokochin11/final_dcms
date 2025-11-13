<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\MedicalFormController;
use App\Http\Controllers\CheckupFormController;
use App\Http\Controllers\CheckupAnswerController;
use App\Http\Controllers\ExtraoralExaminationController;
use App\Http\Controllers\IntraoralExaminationController;
use App\Http\Controllers\RadiographController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
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

    //check-up form routes
    Route::post('/check-up/{patient}', [CheckupAnswerController::class, 'store'])
    ->name('check-up.store');
    Route::get('/check-up/{patient}', [CheckupFormController::class, 'index'])
    ->name('check-up.checkup_index');

    //   View all submitted answers for check-up
    Route::get('/check-up-answers', [CheckupAnswerController::class, 'checkup_answersIndex'])
        ->name('check-up.checkup_answer_index');

    //extraoral examination routes "A"
Route::resource('extraoral_examinations', ExtraoralExaminationController::class);
//intraoral examination routes "B"
Route::resource('intraoral_examinations', IntraoralExaminationController::class);
Route::resource('intraoral_examinations', IntraoralExaminationController::class);
});

 //radiograph routes
 Route::get('/radiographs', [RadiographController::class, 'index'])->name('radiographs.index');
Route::post('/radiographs', [RadiographController::class, 'store'])->name('radiographs.store');
Route::put('/radiographs/{radiograph}', [RadiographController::class, 'update'])->name('radiographs.update');
Route::delete('/radiographs/{radiograph}', [RadiographController::class, 'destroy'])->name('radiographs.destroy');



