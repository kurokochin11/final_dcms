<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;

class TreatmentController extends Controller
{
    // 📄 INDEX
    public function index()
    {
        $treatments = Treatment::with('patient')->latest()->get();
        $patients = Patient::all();

        return view('treatments.index', compact('treatments', 'patients'));
    }

    // 💾 STORE
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'treatment_plan' => 'required',
            'tooth_number' => 'required',
            'amount' => 'required|numeric'
        ]);

        Treatment::create([
            'patient_id' => $request->patient_id,
            'treatment_plan' => $request->treatment_plan,
            'tooth_number' => $request->tooth_number,
            'amount' => $request->amount,
        ]);

        return back()->with('success', 'Treatment added successfully');
    }

    // ✏️ UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'treatment_plan' => 'required',
            'tooth_number' => 'required',
            'amount' => 'required|numeric'
        ]);

        $treatment = Treatment::findOrFail($id);

        $treatment->update([
            'treatment_plan' => $request->treatment_plan,
            'tooth_number' => $request->tooth_number,
            'amount' => $request->amount,
        ]);

        return back()->with('success', 'Treatment updated successfully');
    }

    // ❌ DELETE
    public function destroy($id)
    {
        Treatment::destroy($id);

        return back()->with('success', 'Treatment deleted');
    }

    // 📄 PDF (single treatment)
    public function pdf($id)
    {
        $treatment = Treatment::with('patient')->findOrFail($id);

        $pdf = Pdf::loadView('treatments.pdf', compact('treatment'));

        return $pdf->download('treatment_'.$id.'.pdf');
    }
}
