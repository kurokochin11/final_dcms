<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IntraoralExaminationController extends Controller
{
    /**
     * Display a paginated list of examinations.
     */
    public function index()
    {
        $examinations = IntraoralExamination::with('patient')->latest()->paginate(10);
        $patients = Patient::all();

        return view('oral_examination.index_intraoral', compact('examinations', 'patients'));
    }

    /**
     * Store a newly created or updated examination.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'soft_tissues' => 'nullable|string',
            'gingiva_color' => 'nullable|string',
            'gingiva_texture' => 'nullable|string',
            'bleeding' => 'nullable|string',
            'bleeding_area' => 'nullable|string',
            'recession' => 'nullable|string',
            'recession_area' => 'nullable|string',
            'probing_depths' => 'nullable|image|max:2048',
            'mobility' => 'nullable|image|max:2048',
            'furcation' => 'nullable|image|max:2048',
            'odontogram' => 'nullable|image|max:2048',
            'teeth_condition' => 'nullable|string',
            'occlusion_class' => 'nullable|string',
            'occlusion_other' => 'nullable|string',
            'premature_contacts' => 'nullable|string',
            'hygiene_status' => 'nullable|string',
            'plaque_index' => 'nullable|string',
            'calculus' => 'nullable|string',
        ]);

        // Handle file uploads
        foreach (['probing_depths','mobility','furcation','odontogram'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $validated[$fileField] = $request->file($fileField)->store('intraoral_files', 'public');
            }
        }

        if ($request->exam_id) {
            $exam = IntraoralExamination::findOrFail($request->exam_id);
            $exam->update($validated);
        } else {
            IntraoralExamination::create($validated);
        }

        return redirect()->route('oral_examination.index_intraoral')->with('success', 'Examination saved successfully.');
    }

    /**
     * Return JSON data for editing a single examination.
     */
    public function edit(IntraoralExamination $intraoral)
    {
        return response()->json([
            'id' => $intraoral->id,
            'patient_id' => $intraoral->patient_id,
            'soft_tissues' => $intraoral->soft_tissues,
            'gingiva_color' => $intraoral->gingiva_color,
            'gingiva_texture' => $intraoral->gingiva_texture,
            'bleeding' => $intraoral->bleeding,
            'bleeding_area' => $intraoral->bleeding_area,
            'recession' => $intraoral->recession,
            'recession_area' => $intraoral->recession_area,
            'teeth_condition' => $intraoral->teeth_condition,
            'occlusion_class' => $intraoral->occlusion_class,
            'occlusion_other' => $intraoral->occlusion_other,
            'premature_contacts' => $intraoral->premature_contacts,
            'hygiene_status' => $intraoral->hygiene_status,
            'plaque_index' => $intraoral->plaque_index,
            'calculus' => $intraoral->calculus,
            'probing_depths' => $intraoral->probing_depths ? asset('storage/'.$intraoral->probing_depths) : null,
            'mobility' => $intraoral->mobility ? asset('storage/'.$intraoral->mobility) : null,
            'furcation' => $intraoral->furcation ? asset('storage/'.$intraoral->furcation) : null,
            'odontogram' => $intraoral->odontogram ? asset('storage/'.$intraoral->odontogram) : null,
        ]);
    }

    /**
     * Delete an examination.
     */
    public function destroy(IntraoralExamination $intraoral)
    {
        foreach (['probing_depths','mobility','furcation','odontogram'] as $fileField) {
            if ($intraoral->$fileField) {
                Storage::disk('public')->delete($intraoral->$fileField);
            }
        }

        $intraoral->delete();

        return redirect()->route('oral_examination.index_intraoral')->with('success', 'Examination deleted successfully.');
    }
}
