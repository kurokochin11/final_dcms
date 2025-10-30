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
    
       // Visitor city lookup (uses IPinfo token from .env)
    // Returns JSON: { ip, city, region, country, loc }
    Route::get('/visitor-city', function (Request $request) {
        $token = env('IPINFO_TOKEN'); // add to .env: IPINFO_TOKEN=pk_xxx

        // optional: allow passing specific IP via ?ip=8.8.8.8
        $ip = $request->query('ip');
        $url = $ip ? "https://ipinfo.io/{$ip}/json" : "https://ipinfo.io/json";

        $resp = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get($url);

        if ($resp->failed()) {
            return response()->json(['error' => 'lookup failed'], 500);
        }

        $data = $resp->json();

        return response()->json([
            'ip' => $data['ip'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'country' => $data['country'] ?? null,
            'loc' => $data['loc'] ?? null,
        ]);
    })->name('visitor.city');
// return patient data as JSON (used by AJAX edit)
Route::get('patients/{patient}/json', [PatientController::class, 'showJson'])
    ->name('patients.show.json');

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
});

 



