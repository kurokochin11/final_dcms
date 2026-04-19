<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TreatmentRecord; // Using the new model name
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TreatmentRecordController extends Controller
{
    /**
     * Display a listing of the treatment records.
     */
    public function index(Request $request): View
    {
        $query = TreatmentRecord::with('patient');

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $treatments = $query->latest()->get();
        $patients = Patient::orderBy('last_name')->get();
        $availableDates = TreatmentRecord::select('date')
        ->distinct()
        ->orderBy('date', 'desc')
        ->pluck('date');

        return view('treatments.index', compact('treatments', 'patients', 'availableDates'));
    }

    /**
     * Store a newly created treatment record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'date'         => 'required|date',
            'tooth_number' => 'required|string|max:255',
            'treatment'    => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
        ]);

        TreatmentRecord::create($validated);

        return back()->with('success', 'Treatment record added successfully.');
    }

    /**
     * Display the specified treatment record (JSON for Modals).
     */
    public function show($id): JsonResponse
    {
        $treatmentRecord = TreatmentRecord::with('patient')->findOrFail($id);
        return response()->json($treatmentRecord);
    }

    /**
     * Update the specified treatment record.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $treatmentRecord = TreatmentRecord::findOrFail($id);

        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'date'         => 'required|date',
            'tooth_number' => 'required|string|max:255',
            'treatment'    => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
        ]);

        $treatmentRecord->update($validated);

        return back()->with('success', 'Treatment record updated successfully.');
    }

    /**
     * Generate PDF for a single treatment record.
     */
    public function streamSinglePDF($id)
    {
        $treatment = TreatmentRecord::with('patient')->findOrFail($id);
        
        $treatments = collect([$treatment]);
        
        $pdf = Pdf::loadView('treatments.pdf', compact('treatments'));
        return $pdf->stream("Treatment_Record_{$treatment->id}.pdf");
    }

    /**
     * Remove the specified treatment record.
     */
    public function destroy($id): RedirectResponse
    {
        $treatmentRecord = TreatmentRecord::findOrFail($id);
        $treatmentRecord->delete();

        return back()->with('success', 'Treatment record deleted successfully.');
    }
}