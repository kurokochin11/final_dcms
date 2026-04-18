<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;

class TreatmentController extends Controller
{
    public function index() {
        $treatments = Treatment::with('patient')->latest()->get();
        $patients = Patient::all();
        return view('treatments.index', compact('treatments', 'patients'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'treatment_plan' => 'required',
            'tooth_number' => 'required',
            'amount' => 'required|numeric'
        ]);
        Treatment::create($data);
        return back()->with('success', 'Added successfully');
    }

    public function update(Request $request, Treatment $treatment) {
        $data = $request->validate([
            'treatment_plan' => 'required',
            'tooth_number' => 'required',
            'amount' => 'required|numeric'
        ]);
        $treatment->update($data);
        return back()->with('success', 'Updated successfully');
    }

    public function destroy(Treatment $treatment) {
        $treatment->delete();
        return back()->with('success', 'Deleted successfully');
    }

    public function pdf($id) {
        $treatment = Treatment::with('patient')->findOrFail($id);
        return Pdf::loadView('treatments.pdf', compact('treatment'))->download('treatment.pdf');
    }
}