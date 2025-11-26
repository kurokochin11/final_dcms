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

            // Periodontium / Hard tissues
            'probing_depths'  => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:8192',
            'mobility'        => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:8192',
            'furcation_involvement'=> 'nullable|string',
            'furcation_file'       => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:8192',
            'hard_tissues_notes'   => 'nullable|string',
            'odontogram'           => 'nullable|string',

            // Occlusion
            'occlusion_class'   => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts'=> 'nullable|string',

            // Oral hygiene
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index'        => 'nullable|string|max:50',
            'calculus'            => 'nullable|string|max:50',

            // MIO / notes
            'mio'   => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // Normalize booleans
        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && $data['bleeding_on_probing'] == '1';
        $data['recession'] = isset($data['recession']) && $data['recession'] == '1';

        // File uploads
        if ($request->hasFile('probing_depths')) {
            $data['probing_depths'] = $request->file('probing_depths')->store('intraoral/probing', 'public');
        }

        if ($request->hasFile('mobility')) {
            $data['mobility'] = $request->file('mobility')->store('intraoral/mobility', 'public');
        }

        if ($request->hasFile('furcation_file')) {
            $data['furcation_file'] = $request->file('furcation_file')->store('intraoral/furcation', 'public');
        }

        IntraoralExamination::create($data);

        return redirect()->back()->with('success', 'Intraoral examination saved.');
    }

    public function show(IntraoralExamination $intraoral_examination)
    {
        $intraoral_examination->load('patient');

        return response()->json($intraoral_examination);
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

            // Periodontium / Hard tissues
            'probing_depths'  => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:8192',
            'mobility'        => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:8192',
            'furcation_involvement'=> 'nullable|string',
            'furcation_file'       => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:8192',
            'hard_tissues_notes'   => 'nullable|string',
            'odontogram'           => 'nullable|string',

            // Occlusion
            'occlusion_class'   => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts'=> 'nullable|string',

            // Oral hygiene
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index'        => 'nullable|string|max:50',
            'calculus'            => 'nullable|string|max:50',

            // MIO / notes
            'mio'   => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && $data['bleeding_on_probing'] == '1';
        $data['recession'] = isset($data['recession']) && $data['recession'] == '1';

        // Replace files if uploaded; delete old
        if ($request->hasFile('probing_depths')) {
            if ($intraoral_examination->probing_depths && Storage::disk('public')->exists($intraoral_examination->probing_depths)) {
                Storage::disk('public')->delete($intraoral_examination->probing_depths);
            }
            $data['probing_depths'] = $request->file('probing_depths')->store('intraoral/probing', 'public');
        }

        if ($request->hasFile('mobility')) {
            if ($intraoral_examination->mobility && Storage::disk('public')->exists($intraoral_examination->mobility)) {
                Storage::disk('public')->delete($intraoral_examination->mobility);
            }
            $data['mobility'] = $request->file('mobility')->store('intraoral/mobility', 'public');
        }

        if ($request->hasFile('furcation_file')) {
            if ($intraoral_examination->furcation_file && Storage::disk('public')->exists($intraoral_examination->furcation_file)) {
                Storage::disk('public')->delete($intraoral_examination->furcation_file);
            }
            $data['furcation_file'] = $request->file('furcation_file')->store('intraoral/furcation', 'public');
        }

        $intraoral_examination->update($data);

        return redirect()->back()->with('success', 'Intraoral examination updated.');
    }

    public function destroy(IntraoralExamination $intraoral_examination)
    {
        foreach (['probing_depths', 'mobility', 'furcation_file'] as $col) {
            if ($intraoral_examination->{$col} && Storage::disk('public')->exists($intraoral_examination->{$col})) {
                Storage::disk('public')->delete($intraoral_examination->{$col});
            }
        }

        $intraoral_examination->delete();

        return redirect()->back()->with('success', 'Record deleted.');
    }
}
