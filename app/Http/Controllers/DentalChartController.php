<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\DentalExamination;
use Illuminate\Http\Request;

class DentalChartController extends Controller {
    public function index($patientId) {
        $patient = Patient::findOrFail($patientId);
        $exam = DentalExamination::where('patient_id', $patientId)->latest()->first();
        
        return view('patients.dental-chart', compact('patient', 'exam'));
    }

    public function store(Request $request, $patientId) {
        DentalExamination::updateOrCreate(
            ['patient_id' => $patientId],
            $request->all()
        );

        return back()->with('success', 'Examination Record Updated.');
    }
}