<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\DentalChart;
use Illuminate\Http\Request;

class DentalChartController extends Controller
{
    public function index(Patient $patient)
    {
        $sessions = $patient->dentalCharts()->latest()->get();
        return view('dental-chart.index', compact('patient', 'sessions'));
    }

    public function create(Patient $patient)
    {
        return view('dental-chart.chart-form', [
            'patient' => $patient,
            'currentSession' => null 
        ]);
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'tooth_data' => 'required|json', 
            'tooth_notes' => 'nullable|json',
            'occlusion' => 'nullable|string|max:255',
            'periodontal_condition' => 'nullable|string|max:255',
            'oral_hygiene' => 'nullable|string|max:255',
            'abnormalities' => 'nullable|string',
            'general_condition' => 'nullable|string',
            'nature_of_treatment' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'blood_pressure' => 'nullable|string|max:20',
            'drugs_taken' => 'nullable|string',
            'denture_upper_since' => 'nullable|string|max:255',
            'denture_lower_since' => 'nullable|string|max:255',
            'physician' => 'nullable|string|max:255',  
            'chronic_ailments' => 'nullable|string',
           'spo2' => 'nullable|string|max:20',
        ]);

        $patient->dentalCharts()->create([
            'tooth_data' => json_decode($validated['tooth_data'], true),
            'tooth_notes' => json_decode($validated['tooth_notes'] ?? '{}', true),
            'occlusion' => $validated['occlusion'],
            'periodontal_condition' => $validated['periodontal_condition'],
            'oral_hygiene' => $validated['oral_hygiene'],
            'abnormalities' => $validated['abnormalities'],
            'general_condition' => $validated['general_condition'],
            'nature_of_treatment' => $validated['nature_of_treatment'],
            'allergies' => $validated['allergies'],
            'blood_pressure' => $validated['blood_pressure'],
            'drugs_taken' => $validated['drugs_taken'],
            'denture_upper_since' => $validated['denture_upper_since'],
            'denture_lower_since' => $validated['denture_lower_since'],
            'physician' => $validated['physician'],
            'chronic_ailments' => $validated['chronic_ailments'],
            'spo2' => $validated['spo2'],
        ]);

        return redirect()->route('dental-chart.index', $patient->id)
            ->with('success', 'New dental session recorded successfully.');
    }

    public function show(Patient $patient, DentalChart $dentalChart)
    {
        return view('dental-chart.chart-form', [
            'patient' => $patient,
            'currentSession' => $dentalChart
        ]);
    }

    public function update(Request $request, DentalChart $dentalChart)
    {
        $validated = $request->validate([
            'tooth_data' => 'required|json',
            'tooth_notes' => 'nullable|json',
            'occlusion' => 'nullable|string|max:255',
            'periodontal_condition' => 'nullable|string|max:255',
            'oral_hygiene' => 'nullable|string|max:255',
            'abnormalities' => 'nullable|string',
            'general_condition' => 'nullable|string',
            'nature_of_treatment' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'blood_pressure' => 'nullable|string|max:20',
            'drugs_taken' => 'nullable|string',
            'denture_upper_since' => 'nullable|string|max:255',
            'denture_lower_since' => 'nullable|string|max:255',
            'physician' => 'nullable|string|max:255',  
            'chronic_ailments' => 'nullable|string',
            'spo2' => 'nullable|string|max:20',
        ]);

        $dentalChart->update([
            'tooth_data' => json_decode($validated['tooth_data'], true),
            'tooth_notes' => json_decode($validated['tooth_notes'] ?? '{}', true),
            'occlusion' => $validated['occlusion'],
            'periodontal_condition' => $validated['periodontal_condition'],
            'oral_hygiene' => $validated['oral_hygiene'],
            'abnormalities' => $validated['abnormalities'],
            'general_condition' => $validated['general_condition'],
            'nature_of_treatment' => $validated['nature_of_treatment'],
            'allergies' => $validated['allergies'],
            'blood_pressure' => $validated['blood_pressure'],
            'drugs_taken' => $validated['drugs_taken'],
            'denture_upper_since' => $validated['denture_upper_since'],
            'denture_lower_since' => $validated['denture_lower_since'],
            'physician' => $validated['physician'],
            'chronic_ailments' => $validated['chronic_ailments'],
            'spo2' => $validated['spo2'],
        ]);

        return back()->with('success', 'Session updated successfully.');
    }

    public function destroy(DentalChart $dentalChart)
    {
        $patientId = $dentalChart->patient_id;
        $dentalChart->delete();

        return redirect()->route('dental-chart.index', $patientId)
            ->with('success', 'Session deleted successfully.');
    }
}