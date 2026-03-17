<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Diagnosis;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the diagnoses.
     */
    public function index()
    {
        // 1. Fetch diagnoses for the table with pagination
        $diagnoses = Diagnosis::with('patient')->latest()->paginate(10);

        // 2. FILTER LIST: Only patients who HAVE at least one diagnosis record
        // Note: Ensure your Patient model has the 'diagnoses' relationship defined
        $patientsWithDiagnoses = Patient::whereHas('diagnoses')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // 3. FULL LIST: All patients for the "Add New Diagnosis" selection modal
        $patients = Patient::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('diagnoses.index', compact('diagnoses', 'patients', 'patientsWithDiagnoses'));
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
            'diagnosis_date' => $request->diagnosis_date ?? now()->toDateString(),
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

    /**
     * Generate and stream the PDF report.
     */
    public function downloadPdf(Diagnosis $diagnosis)
{
    $diagnosis->load('patient');

    $pdf = Pdf::loadView('diagnoses.diagnosis_pdf', [
        'diagnosis'     => $diagnosis,
        'patient'       => $diagnosis->patient, // Add this line to fix the error
        'physician'     => auth()->user()->name ?? '____________________',
        'formattedDate' => $diagnosis->diagnosis_date 
                            ? Carbon::parse($diagnosis->diagnosis_date)->format('F d, Y') 
                            : '—'
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('Diagnosis_Report_' . $diagnosis->id . '.pdf');
}
}