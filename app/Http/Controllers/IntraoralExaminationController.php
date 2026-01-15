<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IntraoralExaminationController extends Controller
{
    public function index()
    {
        $examinations = IntraoralExamination::with('patient')->latest()->paginate(10);
        $patients = Patient::all();

        return view('oral_examination.index_intraoral', compact('examinations', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'soft_tissues' => 'nullable|string',
            'soft_tissues_status' => 'nullable|string',
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

    public function edit(IntraoralExamination $intraoral)
    {
        return response()->json([
            'id' => $intraoral->id,
            'patient_id' => $intraoral->patient_id,
            'soft_tissues' => $intraoral->soft_tissues,
            'soft_tissues_status' => $intraoral->soft_tissues_status,
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

  public function view(IntraoralExamination $intraoral)
{



    return response()->json([
        'id' => $intraoral->id,
        'patient_id' => $intraoral->patient_id,
        'patient_name' => $intraoral->patient->full_name ?? '-', // ✅ FIX

        'soft_tissues_status' => $intraoral->soft_tissues_status,
        'soft_tissues' => $intraoral->soft_tissues,
        'gingiva_color' => $intraoral->gingiva_color,
        'gingiva_texture' => $intraoral->gingiva_texture,
        'bleeding' => $intraoral->bleeding,
        'recession' => $intraoral->recession,
        'teeth_condition' => $intraoral->teeth_condition,
        'occlusion_class' => $intraoral->occlusion_class,
        'occlusion_other' => $intraoral->occlusion_other,
        'hygiene_status' => $intraoral->hygiene_status,
        'plaque_index' => $intraoral->plaque_index,
        'calculus' => $intraoral->calculus,
        'odontogram' => $intraoral->odontogram
            ? asset('storage/'.$intraoral->odontogram)
            : null,
    ]);
}


    public function update(Request $request, IntraoralExamination $intraoral)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'soft_tissues' => 'nullable|string',
            'soft_tissues_status' => 'nullable|string',
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

        foreach (['probing_depths','mobility','furcation','odontogram'] as $fileField) {
            if ($request->hasFile($fileField)) {
                if (!empty($intraoral->$fileField)) {
                    Storage::disk('public')->delete($intraoral->$fileField);
                }
                $validated[$fileField] = $request->file($fileField)->store('intraoral_files', 'public');
            }
        }

        $intraoral->update($validated);

        return redirect()->route('oral_examination.index_intraoral')->with('success', 'Examination updated successfully.');
    }

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
