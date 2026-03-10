<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\DentalChart;
use Illuminate\Http\Request;

class DentalChartController extends Controller
{
    
    /**
     * Show the form for creating a new session for a specific patient.
     */
    public function create(Patient $patient)
    {
        // Load existing sessions to show in the sidebar history
        $patient->load(['dentalCharts' => function($query) {
            $query->latest();
        }]);

        return view('dental-chart.dental-chart', [
            'patient' => $patient,
            'currentSession' => null // Null because we are in "Create" mode
        ]);
    }

    /**
     * Store a newly created session in the database.
     */
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'tooth_data' => 'required|string', // JSON string from Alpine.js
            'occlusion' => 'nullable|string|max:255',
            'periodontal_condition' => 'nullable|string|max:255',
            'oral_hygiene' => 'nullable|string|max:255',
            'abnormalities' => 'nullable|string',
            'general_condition' => 'nullable|string',
            'nature_of_treatment' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'blood_pressure' => 'nullable|string|max:20',
            'drugs_taken' => 'nullable|string',
        ]);

        $patient->dentalCharts()->create([
            'tooth_data' => json_decode($validated['tooth_data'], true),
            'occlusion' => $validated['occlusion'],
            'periodontal_condition' => $validated['periodontal_condition'],
            'oral_hygiene' => $validated['oral_hygiene'],
            'abnormalities' => $validated['abnormalities'],
            'general_condition' => $validated['general_condition'],
            'nature_of_treatment' => $validated['nature_of_treatment'],
            'allergies' => $validated['allergies'],
            'blood_pressure' => $validated['blood_pressure'],
            'drugs_taken' => $validated['drugs_taken'],
        ]);

        return redirect()->route('dental-chart.dental-chart', $patient->id)
            ->with('success', 'New dental session recorded successfully.');
    }

    /**
     * Display a specific past session.
     */
    public function show(Patient $patient, DentalChart $dentalChart)
    {
        // Load history for the sidebar even when viewing one
        $patient->load(['dentalCharts' => function($query) {
            $query->latest();
        }]);

        return view('dental-chart.dental-chart', [
            'patient' => $patient,
            'currentSession' => $dentalChart
        ]);
    }

    /**
     * Update an existing session.
     */
    public function update(Request $request, DentalChart $dentalChart)
    {
        $validated = $request->validate([
            'tooth_data' => 'required|string',
            'occlusion' => 'nullable|string|max:255',
            'periodontal_condition' => 'nullable|string|max:255',
            'oral_hygiene' => 'nullable|string|max:255',
            'abnormalities' => 'nullable|string',
            'general_condition' => 'nullable|string',
            'nature_of_treatment' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'blood_pressure' => 'nullable|string|max:20',
            'drugs_taken' => 'nullable|string',
        ]);

        $dentalChart->update([
            'tooth_data' => json_decode($validated['tooth_data'], true),
            'occlusion' => $validated['occlusion'],
            'periodontal_condition' => $validated['periodontal_condition'],
            'oral_hygiene' => $validated['oral_hygiene'],
            'abnormalities' => $validated['abnormalities'],
            'general_condition' => $validated['general_condition'],
            'nature_of_treatment' => $validated['nature_of_treatment'],
            'allergies' => $validated['allergies'],
            'blood_pressure' => $validated['blood_pressure'],
            'drugs_taken' => $validated['drugs_taken'],
        ]);

        return back()->with('success', 'Session updated successfully.');
    }

    /**
     * Delete a specific session.
     */
    public function destroy(DentalChart $dentalChart)
    {
        $patientId = $dentalChart->patient_id;
        $dentalChart->delete();

        return redirect()->route('dental-chart.dental-chart', $patientId)
            ->with('success', 'Session deleted successfully.');
    }
}