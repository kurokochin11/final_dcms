<?php

namespace App\Http\Controllers;

use App\Models\ExtraoralExamination;
use App\Models\Patient;
use Illuminate\Http\Request;

class ExtraoralExaminationController extends Controller
{
    public function index()
    {
        $examinations = ExtraoralExamination::with('patient')->latest()->paginate(12);
        $patients = Patient::orderBy('last_name')->get();

        return view('oral_examination.index_extraoral', compact('examinations', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
              'examination_date' => 'required|date',
            'facial_symmetry' => 'nullable|string|max:255',
            'facial_symmetry_notes' => 'nullable|string',
            'lymph_nodes' => 'nullable|string|max:255',
            'lymph_nodes_location' => 'nullable|string',
            'tmj_pain' => 'nullable|boolean',
            'tmj_clicking' => 'nullable|boolean',
            'tmj_limited_opening' => 'nullable|boolean',
            'mio' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // normalize checkbox-style inputs: if absent, ensure false
        $validated['tmj_pain'] = $request->has('tmj_pain') ? (bool)$request->input('tmj_pain') : false;
        $validated['tmj_clicking'] = $request->has('tmj_clicking') ? (bool)$request->input('tmj_clicking') : false;
        $validated['tmj_limited_opening'] = $request->has('tmj_limited_opening') ? (bool)$request->input('tmj_limited_opening') : false;

        ExtraoralExamination::create($validated);

        return redirect()->back()->with('success', 'Extraoral examination added.');
    }
public function show(ExtraoralExamination $extraoral_examination)
{
    return response()->json(
        $extraoral_examination->load('patient')
    );
}

    public function update(Request $request, ExtraoralExamination $extraoral_examination)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
              'examination_date' => 'required|date',
            'facial_symmetry' => 'nullable|string|max:255',
            'facial_symmetry_notes' => 'nullable|string',
            'lymph_nodes' => 'nullable|string|max:255',
            'lymph_nodes_location' => 'nullable|string',
            'tmj_pain' => 'nullable|boolean',
            'tmj_clicking' => 'nullable|boolean',
            'tmj_limited_opening' => 'nullable|boolean',
            'mio' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['tmj_pain'] = $request->has('tmj_pain') ? (bool)$request->input('tmj_pain') : false;
        $validated['tmj_clicking'] = $request->has('tmj_clicking') ? (bool)$request->input('tmj_clicking') : false;
        $validated['tmj_limited_opening'] = $request->has('tmj_limited_opening') ? (bool)$request->input('tmj_limited_opening') : false;

        $extraoral_examination->update($validated);

        return redirect()->back()->with('success', 'Extraoral examination updated.');
    }

    public function destroy(ExtraoralExamination $extraoral_examination)
    {
        $extraoral_examination->delete();
        return redirect()->back()->with('success', 'Record deleted.');
    }
}
