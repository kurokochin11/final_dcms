<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\DentalChart;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DentalChartController extends Controller
{
    /**
     * INDEX: Display a list of all past sessions for a specific patient.
     * This acts as the "Landing Page" before entering a specific session.
     */
    public function index(Patient $patient)
    {
        // Fetch all sessions for this patient, latest first
        $sessions = $patient->dentalCharts()->latest()->get();

        return view('dental-chart.index', [
            'patient' => $patient,
            'sessions' => $sessions
        ]);
    }

    /**
     * CREATE: Show the blank charting form for a new session.
     * Triggered when the user clicks "+ NEW SESSION" from the index.
     */
    public function create(Patient $patient)
    {
        return view('dental-chart.chart-form', [
            'patient' => $patient,
            'currentSession' => null // Null indicates "New Session" mode
        ]);
    }

    /**
     * STORE: Save the new session data to the database.
     */
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'tooth_data' => 'required|json', 
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

        // Redirect back to the session history list
        return redirect()->route('dental-chart.index', $patient->id)
            ->with('success', 'New dental session recorded successfully.');
    }

    /**
     * SHOW: Display and allow editing of a specific past session.
     */
    public function show(Patient $patient, DentalChart $dentalChart)
    {
        return view('dental-chart.chart-form', [
            'patient' => $patient,
            'currentSession' => $dentalChart
        ]);
    }

    /**
     * UPDATE: Save changes to an existing session.
     */
    public function update(Request $request, DentalChart $dentalChart)
    {
        $validated = $request->validate([
            'tooth_data' => 'required|json',
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
     * DESTROY: Remove a session record.
     */
    public function destroy(DentalChart $dentalChart)
    {
        $patientId = $dentalChart->patient_id;
        $dentalChart->delete();

        return redirect()->route('dental-chart.index', $patientId)
            ->with('success', 'Session deleted successfully.');
    }
}