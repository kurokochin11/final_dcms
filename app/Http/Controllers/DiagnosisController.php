<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Patient;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the diagnoses.
     */
    public function index()
    {
        // Load diagnoses with patient relationship
        $diagnoses = Diagnosis::with('patient')->latest()->paginate(10);
        $patients = Patient::whereHas('diagnoses')->orderBy('first_name')->get();


        return view('diagnoses.index', compact('diagnoses', 'patients'));
    }

    /**
     * Store a newly created diagnosis in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dental_caries' => 'nullable|string|max:255',
            'periodontal_disease' => 'nullable|string|max:255',
            'pulpal_periapical' => 'nullable|string|max:255',
            'occlusal_diagnosis' => 'nullable|string|max:255',
            'other_oral_conditions' => 'nullable|string|max:255',
             'diagnosis_date' => 'nullable|date',
        ]);

        Diagnosis::create([
            'patient_id' => $request->patient_id,
            'dental_caries' => $request->dental_caries,
            'periodontal_disease' => $request->periodontal_disease,
            'pulpal_periapical' => $request->pulpal_periapical,
            'occlusal_diagnosis' => $request->occlusal_diagnosis,
            'other_oral_conditions' => $request->other_oral_conditions,
             'diagnosis_date' => $request->diagnosis_date ?? now()->toDateString(), // <-- default to today if null
        ]);

        return redirect()->route('diagnoses.index')->with('success', 'Diagnosis added successfully.');
    }

    /**
     * Update the specified diagnosis in storage.
     */
    public function update(Request $request, $id)
    {
        $diagnosis = Diagnosis::findOrFail($id);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dental_caries' => 'nullable|string|max:255',
            'periodontal_disease' => 'nullable|string|max:255',
            'pulpal_periapical' => 'nullable|string|max:255',
            'occlusal_diagnosis' => 'nullable|string|max:255',
            'other_oral_conditions' => 'nullable|string|max:255',
               'diagnosis_date' => 'nullable|date', 
        ]);

        $diagnosis->update([
            'patient_id' => $request->patient_id,
            'dental_caries' => $request->dental_caries,
            'periodontal_disease' => $request->periodontal_disease,
            'pulpal_periapical' => $request->pulpal_periapical,
            'occlusal_diagnosis' => $request->occlusal_diagnosis,
            'other_oral_conditions' => $request->other_oral_conditions,
             'diagnosis_date' => $request->diagnosis_date ?? $diagnosis->diagnosis_date ?? now()->toDateString(),
        ]);

        return redirect()->route('diagnoses.index')->with('success', 'Diagnosis updated successfully.');
    }

    /**
     * Remove the specified diagnosis from storage.
     */
    public function destroy($id)
    {
        $diagnosis = Diagnosis::findOrFail($id);
        $diagnosis->delete();

        return redirect()->route('diagnoses.index')->with('success', 'Diagnosis deleted successfully.');
    }
}
