<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlan;
use App\Models\Patient;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    public function index(Request $request)
    {
        // eager load patient, paginate
        $plans = TreatmentPlan::with('patient')->latest()->paginate(10);
        $patients = Patient::orderBy('last_name')->get(); // for create form
        return view('treatment_plans.index', compact('plans', 'patients'));
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        $plan = TreatmentPlan::create($data);

        return redirect()->route('treatment-plans.index')
            ->with('success', 'Treatment Plan created.');
    }

    public function edit(TreatmentPlan $treatmentPlan)
    {
        // we return JSON if requested via AJAX (modal will request via fetch)
        return response()->json($treatmentPlan->load('patient'));
    }

    public function update(Request $request, TreatmentPlan $treatmentPlan)
    {
        $data = $this->validateRequest($request);

        $treatmentPlan->update($data);

        return redirect()->route('treatment-plans.index')
            ->with('success', 'Treatment Plan updated.');
    }
     protected function validateRequest(Request $request)
    {
        $rules = [
            'patient_id' => ['required','exists:patients,id'],
            // phase fields
            'phase1_date' => ['nullable','date'],
            'phase1_procedures' => ['nullable','string'],
            'phase2_date' => ['nullable','date'],
            'phase2_procedures' => ['nullable','string'],
            'phase3_date' => ['nullable','date'],
            'phase3_procedures' => ['nullable','string'],
            'phase4_date' => ['nullable','date'],
            'phase4_procedures' => ['nullable','string'],

            'treatment_options' => ['nullable','string'],
            'risks_and_benefits' => ['nullable','string'],
            'alternatives' => ['nullable','string'],
            'estimated_costs' => ['nullable','string'],
            'payment_options' => ['nullable','string'],

            'consent_given' => ['nullable','in:on,1,true'],
            'patient_signature' => ['nullable','string'],
            'dentist_signature' => ['nullable','string'],
            'consent_date' => ['nullable','date'],
        ];

        $validated = $request->validate($rules);

        // build phases array
        $phases = [
            'phase1' => [
                'date' => $request->input('phase1_date'),
                'procedures' => $request->input('phase1_procedures'),
            ],
            'phase2' => [
                'date' => $request->input('phase2_date'),
                'procedures' => $request->input('phase2_procedures'),
            ],
            'phase3' => [
                'date' => $request->input('phase3_date'),
                'procedures' => $request->input('phase3_procedures'),
            ],
            'phase4' => [
                'date' => $request->input('phase4_date'),
                'procedures' => $request->input('phase4_procedures'),
            ],
        ];

        return [
            'patient_id' => $validated['patient_id'],
            'phases' => $phases,
            'treatment_options' => $validated['treatment_options'] ?? null,
            'risks_and_benefits' => $validated['risks_and_benefits'] ?? null,
            'alternatives' => $validated['alternatives'] ?? null,
            'estimated_costs' => $validated['estimated_costs'] ?? null,
            'payment_options' => $validated['payment_options'] ?? null,
            'consent_given' => $request->has('consent_given'),
            'patient_signature' => $validated['patient_signature'] ?? null,
            'dentist_signature' => $validated['dentist_signature'] ?? null,
            'consent_date' => $validated['consent_date'] ?? null,
        ];
    }

   public function destroy($id)
{
    $plan = TreatmentPlan::findOrFail($id);
    $plan->delete();

    return redirect()->route('treatment-plans.index')
                     ->with('success', 'Treatment plan deleted successfully.');
}

   
}
