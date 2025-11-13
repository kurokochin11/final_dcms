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
        $examinations = IntraoralExamination::with('patient')->latest()->paginate(12);
        $patients = Patient::orderBy('last_name')->get();

        return view('oral_examination.index_intraoral', compact('examinations', 'patients'));
    }

    public function create()
    {
        $patients = Patient::orderBy('last_name')->get();
        return view('oral_examination.create_intraoral', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',

            // Soft tissues
            'soft_tissues_status' => 'nullable|string|max:255',
            'soft_tissues_notes'  => 'nullable|string',

            // Gingiva
            'gingiva_color'   => 'nullable|string|max:100',
            'gingiva_texture' => 'nullable|string|max:100',
            'bleeding_on_probing' => 'nullable|in:1,0',
            'bleeding_areas'  => 'nullable|string',
            'recession'       => 'nullable|in:1,0',
            'recession_areas' => 'nullable|string',
            'probing_depths' => 'nullable|string',
            'mobility' => 'nullable|string',
            'furcation_involvement' => 'nullable|string',
            'hard_tissues_notes' => 'nullable|string',
            'odontogram' => 'nullable|string',
            'occlusion_class' => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts'=> 'nullable|string',

            // Oral hygiene
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index' => 'nullable|string|max:50',
            'calculus' => 'nullable|string|max:50',
            'notes' => 'nullable|string',

            // files
            'probing_depths' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:4096',
            'mobility' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:4096',
            'furcation_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:4096',
        ]);

        // Normalize booleans
        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && $data['bleeding_on_probing'] == '1';
        $data['recession'] = isset($data['recession']) && $data['recession'] == '1';

        IntraoralExamination::create($data);

        return redirect()->back()->with('success', 'Intraoral examination saved.');
    }

    public function update(Request $request, IntraoralExamination $intraoral_examination)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',

            // Soft tissues
            'soft_tissues_status' => 'nullable|string|max:255',
            'soft_tissues_notes'  => 'nullable|string',

            // Gingiva
            'gingiva_color'   => 'nullable|string|max:100',
            'gingiva_texture' => 'nullable|string|max:100',
            'bleeding_on_probing' => 'nullable|in:1,0',
            'bleeding_areas'  => 'nullable|string',
            'recession'       => 'nullable|in:1,0',
            'recession_areas' => 'nullable|string',
            'probing_depths' => 'nullable|string',
            'mobility' => 'nullable|string',
            'furcation_involvement' => 'nullable|string',
            'hard_tissues_notes' => 'nullable|string',
            'odontogram' => 'nullable|string',
            'occlusion_class' => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts'=> 'nullable|string',

            // Oral hygiene
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index' => 'nullable|string|max:50',
            'calculus' => 'nullable|string|max:50',
            'notes' => 'nullable|string',

            // files
            'probing_depths' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:4096',
            'mobility_' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:4096',
            'furcation_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:4096',

            // remove checkboxes
            'remove_probing_depths' => 'nullable',
            'remove_mobility' => 'nullable',
            'remove_furcation' => 'nullable',
        ]);

        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && $data['bleeding_on_probing'] == '1';
        $data['recession'] = isset($data['recession']) && $data['recession'] == '1';

        $intraoral_examination->update($data);

        return redirect()->back()->with('success', 'Intraoral examination updated.');
    }

    public function destroy(IntraoralExamination $intraoral_examination)
    {
        $intraoral_examination->delete();

        return redirect()->back()->with('success', 'Record deleted.');
    }
}
